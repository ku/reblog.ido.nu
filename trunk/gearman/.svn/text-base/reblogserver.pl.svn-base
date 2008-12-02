#!/usr/bin/perl

use strict;
use warnings;

use Magic::Schema;
use DateTime::Format::ISO8601;

use Encode;

use utf8;

#use encoding 'utf8';
#use utf8;
#use utf8;

use Gearman::Worker;
use Storable qw(thaw);
use JSON;
use HTTP::Cookies;
use WWW::Mechanize;

use List::Util qw(sum);

use Data::Dumper;

use LWP::Debug;

my $DURATION = 3;


my $dsn = join ":", 'DBI', "mysql", "tumblr", "localhost";

my $schema = Magic::Schema->connect( $dsn,
		"root",
		"passwd"
	);

my $worker = Gearman::Worker->new;
$worker->job_servers(qw|localhost|);

$worker->register_function(
    reblog => sub {
        my $job = shift;
        my $args = from_json( $job->arg );
    	print "accepted.\n";

        return reblog($args);
    }
);
$worker->register_function(
	sum => sub {
		my $job = shift;
		print "accepted\n";
        my $args = from_json( $job->arg );
		my $r = sum @$args;
		sleep $DURATION;
		print "$r\n";
		return $r;
	}
);

#while ( 1 ) {
	$worker->work;
#}

sub reblog {
	my $args = shift;

	my $postid;
	my $uniqkey;
	my $sessionkey;
	my $permalink;

	my $cookie_jar;
	my $mech;

	my $ticket;

	my $isAPI = $args->{email} and $args->{password};

	if ( $isAPI ) {
		$mech = WWW::Mechanize->new;
		print "open login page\n";
		$mech->get( 'http://www.tumblr.com/login' );
		sleep $DURATION;
		print "login\n";
		$postid = $args->{postid};
		my $email    = $args->{email};
		my $password    = $args->{password};
		$mech->submit_form(
				fields      => {
					email    => $email,
					password    => $password
				}
		);
	
		my $headers = $mech->response->headers;
		if (
			( $headers and $headers->{'refresh'} ne '0;url=/dashboard' ) and
			( $mech->content !~ m|<meta http-equiv="Refresh" content="0;url=/dashboard">|s )
		) {
			print $mech->response->code;
			warn "header ng. $email $password ";
			print Dumper $headers;
			print $mech->content;
			return 1;
		}
	} else {
		$postid = $args->{postid};
		$uniqkey = $args->{uniqkey};
		$sessionkey = $args->{sessionkey};
		$permalink = $args->{permalink};
		my $cookies = $args->{cookies};
		$cookies or return 0;
		my $ticket = $schema->resultset('Reblogs')->new( {
			uniqkey => $uniqkey,
			postid => $postid,
			permalink => $permalink,
			sessionkey => $sessionkey,
			created_at => "" . DateTime->now
		} );

		my $r = $ticket->insert;

		my $cookie_jar = HTTP::Cookies->new;
		foreach ( @$cookies ) {
			$cookie_jar->set_cookie(
				0, $_->{name}, $_->{value}, $_->{path}, 'www.tumblr.com'
			);
			#kie_jar->set_cookie($version, $key, $val, $path, $domain, $port, $path_spec, $secure, $maxage, $discard, \%rest)

		}
		$mech = WWW::Mechanize->new( cookie_jar => $cookie_jar);
		$mech->agent_alias( 'Windows IE 6' );
	}

	
	print "reblog page\n";
	$mech->get( "http://www.tumblr.com/reblog/$postid" );
	sleep $DURATION;

#open F, ">log/reblog.html";
#print F $mech->content;
#close F;

	print "reblog\n";
	my($code, $msg);
	$code = $mech->res->code;
	$msg = $mech->res->message;
	if ( $code != 200 ) {
		print "reblogpage code: " . $mech->res->status_line . "\n";

		if ( $isAPI ) {
			$schema->resultset('Reblogs')->update_or_create( {
					id => $ticket->id,
					status =>  'errorAtOpen',
					code => $code
				} );
		}

		return 1;
	}
	#print Dumper $mech->current_form;

	my $f = $mech->current_form;

	my @forms = $mech->forms;
	foreach (@forms) {
		foreach ( @{$_->{inputs}} ) {
			my $v = $_->{value};
			$v or next;

			eval {
				decode('UTF-8', $v);
			};
			$_->{value} = $v;
		}
	}

#	my $req = $mech->current_form->make_request->as_string;
#	$req =~ s/&/\n/g;
#	print $req;

print "reblogging...\n";
	sleep $DURATION;
	$mech->submit_form;
	$code = $mech->res->code;
	$msg = $mech->res->message;
#	print $mech->res->{_request}->{_content};

	if ( not $isAPI ) {
		my $hash = { id => $ticket->id };
		if ( $code != 200 ) {
			$hash->{status} = 'errorAtOpen';
		} else {
			$hash->{status} = 'success';
		}
		$hash->{code} = $code;
		$hash->{complete_at} = "" . DateTime->now;

		$schema->resultset('Reblogs')->update_or_create( $hash );
	}
	
	print "end " . $mech->res->status_line . "\n";
	0;
}


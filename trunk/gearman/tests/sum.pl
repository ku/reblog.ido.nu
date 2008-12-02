#!/usr/bin/perl

use strict;
use warnings;

use Data::Dump qw(dump);
use Storable qw(freeze thaw);
use Gearman::Client;
use Gearman::Task;

use JSON;

my $client = Gearman::Client->new;

$client->job_servers(qw|localhost:37003|);


for ( my $i = 0; $i < 4; $i ++ ) {
	@_ = map { ($i + 1 ) * $_ } 1..9;
	my $args = to_json \@_;

	$client->dispatch_background("sum", \$args, {
	});
}


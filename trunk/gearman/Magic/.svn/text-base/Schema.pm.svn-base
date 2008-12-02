package Magic::Schema;

#use DBIx::Class::Schema::Loader;

use strict;
use base qw/DBIx::Class::Schema::Loader/;

$ENV{DBIC_TRACE} = 1;

__PACKAGE__->loader_options(
	debug => 0,
	constraint => qr/^(reblogs)$/
);

1;

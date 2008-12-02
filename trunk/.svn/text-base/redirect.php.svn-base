<?php
	$url = $_REQUEST['url'];

	if ( preg_match( '/^https?:\/\//', $url) ) {
		$url = preg_replace('/[<>]/', '', $url);
		print "<html><body>";
		print "click and access to <a href=\"$url\">$url</a>";
		print "<hr />";
		print $_SERVER['HTTP_HOST'];
		print "</body></html>";
	} else {
		header( 'Location: /' );
	}
?>

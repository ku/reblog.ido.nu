<?php

require_once 'APIRenderer.php';

$host = $_SERVER["HTTP_HOST"];

if ( ! preg_match( '/^([^.]+)\.reblog\.ido.nu$/i', $host, $m ) ) {
	print "x_x";
	exit;
}

$page = (int) @$_REQUEST['page'];
if ( $page < 0 ) {
	$page = 0;
}

print $page;

$username = $m[1];

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=shift_jis" />
	<!--link rel="stylesheet" type="text/css" href="./style.css" media="all" /-->
	<title><?php echo $username ?> - on reblog.ido.nu</title>
	<link rel="stylesheet" type="text/css" media="all" href="/style.css" />
</head>
<body><?php
$me = new APIRenderer($username, $page);
$me->index();

?>


</body>
</html>

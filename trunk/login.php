<?php

extract($_REQUEST);

$err = '';

require_once 'HTTP/Request.php';

//require_once 'DB.php';
require_once 'lib/adodb/adodb.inc.php';
require_once 'lib/adodb/adodb-pear.inc.php';
require_once 'lib/db.php';

require_once 'Cookie.php';


if ( $email != '' and $password != '' ) {
	$hash = null;
	list($err) = update_cookie_info($email, $password, $persistent, $hash);
	if ( $err == '' ) {
		$host = ($_SERVER['SERVER_NAME']);
		$u = "Location: http://$host/dashboard/$hash?.rand=" . rand();
		header($u);
	} else {
	}
		
}



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=SHIFT_JIS" />
	<link rel="stylesheet" type="text/css" href="/style.css" media="all" />
	<title></title>
</head>
<body>
<?php
	include 'tmpl/login.php';
	include 'tmpl/foot.php';
?>
</body>
</html>

<?php

require_once 'HTML/Safe.php';
require_once "Dashboard_Class.php";

#$content = file_get_contents("log/raw.html");

function from_tumblr() {
    global $sessionkey;
    $db = get_db_connectiuon();

    list($sessionkey, $cookies, $u) = get_login_cookie($db);

	$page = getPage();
	//$postid = getPostId();

	$url = 'http://www.tumblr.com/dashboard/';

	if ( $page > 1 ) {
		$url .= $page;
	}
	if ( $postid > 1 ) {
		$url .= "/$postid";
	}

	#print "<!--$url-->";

	$retry = 2;

	while ( $retry-- ) {
		
		$req =& new HTTP_Request($url);
		$req->setMethod(HTTP_REQUEST_METHOD_GET);
		
		foreach ( $cookies as $v ) {
			$req->addCookie($v['name'], $v['value']);
		}

		if ( PEAR::isError($req->sendRequest()) ) {
			$err = 1;
		}

		$code = $req->getResponseCode();

		if ( $code == 302 ) {
			$err = true;
			if ( $u['email'] ) {
				list($err, $cookies) = update_cookie_info( $u['email'], $u['password'], true, $u['hash'] );
			}

			if ( $err ) {
				$retry = 0;
				break;
			}
		} else if ( $code == 200 ) {
			return $req->getResponseBody();
		} else {
			print '<html><body><pre>x_x';
			print " $code ";
			print '</body></html>';
			exit;
		}
	}

	if ( $retry == 0 ) {
		header('Location: /login');
	} else {
		print '<html><body><pre>x_x';
		print '</body></html>';
	}
	exit;
}

function from_file ($filename) {
	global $sessionkey;

	$path = split( '/', $_SERVER["SCRIPT_URL"] );
	$sessionkey = $path[2];

	$content = file_get_contents($filename);
	return $content;
}

require_once 'HTTP/Request.php';
require_once 'lib/adodb/adodb.inc.php';
require_once 'lib/adodb/adodb-pear.inc.php';
require_once 'lib/db.php';
#require_once 'Dashboard_Class.php';


require_once 'Cookie.php';
require_once 'mobileicon.php';
require_once 'HTML/Safe.php';

global $sessionkey;


$host = $_SERVER['HTTP_HOST'];
define('DEVEL', $host == 'reblog-dev.ido.nu' );


	if ( DEVEL && USE_CACHE ) {
		#$content =  from_file('log/audio.html');
		#$content =  from_file('log/video_regular.html');
		#$content =  from_file('log/sample.html');
		$content =  from_file('log/raw.html');
	} else {
		$content = from_tumblr();
	#	savecontent("log/raw.html", $content);
	}


$d = new Dashboard($content);

$d->parse();

$d->render();


?>

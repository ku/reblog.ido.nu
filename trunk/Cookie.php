<?php

	function update_cookie_info ($email, $password, $persistent, &$hash = null) {
		$url = 'http://www.tumblr.com/login';

		$err = false;

		$req =& new HTTP_Request($url);
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		$req->addPostData("email", $email);
		$req->addPostData("password", $password);
		if ( PEAR::isError($req->sendRequest()) ) {
			$err = 1;
		} else {
			$res = $req->getResponseBody();
			if (strstr($res, '<meta http-equiv="Refresh" content="0;url=/dashboard">' ) === false) {
				$err = 1;
			} else {

                $cookies = $req->getResponseCookies();
                $cookie_content = serialize($cookies);
                
                $k = calc_sha1($email, $password);
                if ( $persistent == '' ) {
                    $email = '';
                    $password = '';
                }

                $db = get_db_connectiuon();

                if ( $hash ) {
                    $db->query('UPDATE auth SET cookie = ? WHERE hash = ?',
                        array($cookie_content, $hash));
                } else {
                    $hash = $k;
                    $db->query('INSERT auth (hash, cookie, email, password) VALUES (?,?,?,?)',
                        array($k, $cookie_content, $email, $password));
                }

			}
		}
		
		if ($err)
			$err = 'login failed.';

		return array($err, $cookies);
	}

	function get_login_cookie ($db) {
		if ( $_SERVER['PATH_INFO'] ) {
			$path = split( '/', $_SERVER["PATH_INFO"] );
		} else {
			$path = split( '/', $_SERVER["SCRIPT_URL"] );
		}

		$sessionkey = array_pop($path);

		$sql = 'SELECT * FROM auth WHERE hash = ?';

		$u = $db->getRow($sql, array($sessionkey));

		if ( count($u) == 0 ) {
			header("Location: /login");
			exit;
		}

		$cookies = unserialize($u['cookie']);
		
		return array($sessionkey, $cookies, $u);
	}

function calc_sha1($email, $password) {
	$k = md5(sha1( time() . "*" . $email. $password ) );
	return $k;
}
?>

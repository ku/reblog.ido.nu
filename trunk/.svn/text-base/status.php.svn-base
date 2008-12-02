<?php

require_once 'HTTP/Request.php';

require_once 'lib/adodb/adodb.inc.php';
require_once 'lib/adodb/adodb-pear.inc.php';
require_once 'lib/db.php';

require_once 'Cookie.php';
require_once 'Page.php';
require_once 'Net/Gearman/Client.php';

class ReblogPage extends Page {

	function __construct() {
		$post_id = $_REQUEST['id'];

		$this->db = $db = get_db_connectiuon();

		list($sessionkey, $cookies) = get_login_cookie($db);

		$this->sessionkey = $sessionkey;

		$this->page = $page = getPage();
	}

	function html_header() {
		$postid = $this->postid;
		print <<<__HTML__
<html>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis"/>
<title>reblogging $postid...</title>
<style>
div.odd {
	background-color: rgb(245, 245, 245);
}
</style><body>
<h1>Your reblog request status</h1>
__HTML__;
	}
	function html_footer() {
		$nextpage = $this->page + 1;

		$u = $_SERVER['SCRIPT_URI'];
		$u .= "?page=$nextpage";
		$u .= "&.rand=" . rand();

		print "<hr />";
		print $_SERVER['HTTP_HOST'];

		print "</body></html>";
	}

};


$pageclass = new ReblogPage();
$pageclass->show();

exit;

function getPage() {
	$page = (int)( @$_REQUEST['page'] );
	if ($page < 1 ) {
		$page = 1;
	}
	return $page;
}


function add_reblog_task () {


}
?>

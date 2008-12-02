<?php

require_once 'HTTP/Request.php';

require_once 'lib/adodb/adodb.inc.php';
require_once 'lib/adodb/adodb-pear.inc.php';
require_once 'lib/db.php';

require_once 'Cookie.php';
require_once 'Net/Gearman/Client.php';

class ReblogPage {

	function __construct() {
		$post_id = $_REQUEST['id'];

		$this->db = $db = get_db_connectiuon();

		list($sessionkey, $cookies) = get_login_cookie($db);

		$this->sessionkey = $sessionkey;

		$this->page = $page = getPage();

		$pid = getmypid();
		$this->postid = $postid = (int) $_REQUEST['postid'];

		if ( preg_match( '%^http://([a-z\-_.]+\.)+\w{2,4}(/mobile)?/post/\d+$%i' , $link = (string) $_REQUEST['permalink'] ) ) {
			$this->permalink = $link;
		} else {
			$link = ''; 
		};

		$uniqkey = "$pid-$postid-$sessionkey";

		$set = new Net_Gearman_Set();
		$task = new Net_Gearman_Task(
			'reblog', array(
				cookies => $cookies,
				postid => $postid,
				sessionkey => $sessionkey,
				permalink => $this->permalink,
				token =>  $_REQUEST['token'],
				uniqkey => $uniqkey
			),
			$uniqkey,  Net_Gearman_Task::JOB_BACKGROUND
		);

		//   $task->attachCallback('result');
		$set->addTask($task);

		$client = new Net_Gearman_Client(array('localhost:37003'));
		$client->runSet($set);
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
<h1> reblogging post $postid...</h1>
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

	function show () {
		$this->html_header();
		$tasks = $this->db->getAll('SELECT permalink, postid, UNIX_TIMESTAMP(created_at) AS created_at, status FROM reblogs WHERE sessionkey = ? ORDER BY created_at DESC LIMIT 10' ,
			array($this->sessionkey)
		);

		$link = $this->permalink;

		print "<div>";
		print "<a href=\"$link\">post" . $this->postid ."</a> is going to be reblogged.";
		print "</div>";
		print "<br />";

		if (  count( $tasks ) > 0 ) {
			print "<div>";
			print "your reblogging requests status :";
			print "</div>";
			foreach ( $tasks as $k => $task ) {
				$link = $task['permalink'];
				$classname = (($k+1) % 2) ? 'odd': 'even';
				print "<div class=$classname>";
				print "<a href=\"$link\">post" . $task["postid"] . "</a>\n";
				print $task["status"] . "\n";
				print date("c",$task["created_at"]);
				print "</div>";
			}
		}

		#$anchor = @$_REQUEST['anchor'];
		#header(
		#print (
		#	sprintf( "Location: /dashboard/%s?reblog=1&page=%s&anchor=%s&.rand=" . rand() . "#%s",
		#		$this->sessionkey,
		#		$this->page,
		#		$anchor,
		#		$anchor
		#	)
		#);
		$this->html_footer();
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

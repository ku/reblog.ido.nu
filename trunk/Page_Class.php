<?php
class Page {
	protected $sessionkey = null;
	protected $db = null;
	
	function show () {
		$this->html_header();
		$tasks = $this->db->getAll('SELECT permalink, postid, UNIX_TIMESTAMP(created_at) AS created_at, status FROM reblogs WHERE sessionkey = ? ORDER BY created_at DESC LIMIT 10' ,
			array($this->sessionkey)
		);

		$link = $this->permalink;

		if (  count( $tasks ) > 0 ) {
			foreach ( $tasks as $k => $task ) {
				$link = $task['permalink'];
				$classname = (($k+1) % 2) ? 'odd': 'even';
				print "<div class=$classname>";
				print "<a href=\"$link\">post" . $task["postid"] . "</a>\n";
				print $task["status"] . "\n";

				$t = localtime($task["created_at"] + 9 * 3600, true);
				printf(
					"%d.%d.%d %02d:%02d",
					$t['tm_year'] + 1900,
					$t['tm_mon'],
					$t['tm_mday'],
					$t['tm_hour'],
					$t['tm_min']
				
				);
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
}

?>

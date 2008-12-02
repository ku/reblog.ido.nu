<?php

class APIRenderer {

	private $id = null;
	private $endpoint = null;
	private $xmlstring = null;

	private $page_unit = 20;

	function __construct ($id, $page) {
		$this->id = $id;
		$this->page = $page;
		$this->endpoint = 'http://' . $id . '.tumblr.com' . '/api/';
	}

	function index ($index = 0) {
		if ( ! $this->read($index) ) {
			return;
		}
		$this->render();

	}

	function render () {
		$logs = array();
		foreach( $this->xml->posts->post as $post ) {
			$t = $post['type'];

			$method = "render_$t";

			$content = $this->$method($post);
			$k = (string)$post['id'];
			$logs[$k] = $content;
		}

		$res ='';
		#$res = "<ul>";
		foreach ( $logs as $k => $v ) {
			$c = ($k % 2) ? 'odd' : 'even';
			$res .= <<<__LI__
	<div class="$c">
		<span class="left">$v</span>
		<!--<span class="right"><a href="/reblog/"><img src="/reblog.png" /></a></span>
		-->
		<span style="clear: both;"><!-- --></span>
	</div>
__LI__;

		}
		#$res .= "</ul>";

		print '<span class="ownerid">' . $this->id . '</span> ';
		print (int)$this->page;
		print "/";
		print (int)$this->pages;

		#$res = self::convert($res);
		
		print mb_convert_encoding($res, 'SHIFT_JIS', 'UTF-8');

		$older_page = $this->page + 1;

		print "<br />";
		$k = '#';
		print "[$k]<a href=\"/pages/$older_page\" rel=\"next\" accesskey=\"$k\" directkey=\"$k\">older</a>";

		print "<hr />";
		print $_SERVER['HTTP_HOST'];
	}
	function convert ($s) {
		return preg_replace_callback('/(http:\/\/([\w\-]+).tumblr.com\/)(.{7})/', array('APIRenderer', 'replace_callback'), $s);
	}
	static function replace_callback ($m) {
		return ( $m[2] == 'data' or preg_match('/^mobile\//', $m[3]) ) ? $m[0] : $m[1] . 'mobile/' . $m[3];
	}

	private function read($index) {
		$u = $this->endpoint . 'read' . '?start=' . $this->page * $this->page_unit; 

		$xml = file_get_contents($u);
		if ($xml == '') {
			print 'user ' . $this->id . ' not found.';
			exit;
		}
		#$this->xmlstring = mb_convert_encoding($xml, 'SHIFT_JIS', 'UTF-');
		$this->xmlstring = $xml;

		return $this->parse();
	}

	private function parse() {
		$this->xml = simplexml_load_string($this->xmlstring);

		$total = ( $this->xml->posts['total'] );

		$pages = ($total / $this->page_unit);
		if ($total % $this->page_unit) {
			$pages++;
		}
		$this->pages = $pages;

		return true;
	}
	function render_quote($post) {
		#extract($post);
		#$content .= "<a href=\"" . $post['url'] . "\">#" . $post['id'] ."</a>";
		$content = "<q>";
		$content .= $post->{'quote-text'};
		$content .= "</q>";
		$content .= "<cite>";
		$content .= $post->{"quote-source"};
		$content .= "</cite>";

		return $content;
	}

	function render_link($post) {
		$u = $post->{'link-url'} ;

		$content = '<a href="' . $u . '">' . $post->{'link-text'}. '</a>';
		return $content;
	}

	function render_regular ($post) {
		$content = '<span class="title">' . $post->{'regular-title'}. '</span>';
		$content .= '<span class="body">' . $post->{'regular-body'}. '</span>';
		return $content;
	}
	function render_photo ($post) {

//  [photo-url] => Array
//        (
//            [0] => http://data.tumblr.com/7424068_500.jpg
//            [1] => http://data.tumblr.com/7424068_400.jpg
//            [2] => http://data.tumblr.com/7424068_250.jpg
//            [3] => http://data.tumblr.com/7424068_100.jpg
//            [4] => http://data.tumblr.com/7424068_75sq.jpg
//        )
		$urls = $post->{'photo-url'};
		$content = '<a href="' . $urls[2] . '">';
		$content .= '<img class="photo" width="100" src="' . $urls[3]. '" />';
		$content .= '</a>';
		$content .= '<span class="phototitle">' . $post->{'photo-caption'}. '</span>';
		return $content;
	}

	function render_conversation($post) {
		$content = "conversation is not supported now...";
		return $content;
	}

	function render_video($post) {
		// print "<pre>";
		// print_r($post);
		// print "</pre>";
		
		$content = '<span class="title">' . $post->{'video-caption'} . '</span>';
		$content .= 'video not available on reblog.ido.nu yet.';
		return $content;
	}
}

function a2a($node) {
	$attr = array();
	$attrs = $node->attributes();
	foreach ( $attrs as $k => $v ) {
		$attr[$k] = (string)$v;
	}
	return $attr;
}


?>

<?php
	function get_db_connectiuon() {
		
			$type = "mysql";
			$user = "root";
			$pass = "passwd";
			$host = "localhost";
			$dbname = "tumblr";

			$dsn    = "$type://$user:$pass@$host/$dbname";
			$db = ADONewConnection('mysql'); # eg 'mysql' or 'postgres'
				//$db->debug = true;
				if ( ! $db->connect($host, $user, $pass, $dbname) ) {
					die ($this->db->getMessage());
				}
			$db->setFetchMode(DB_FETCHMODE_ASSOC);
			return $db;
	}
?>

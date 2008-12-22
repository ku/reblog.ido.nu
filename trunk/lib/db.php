<?php
require_once('./config.php');

function get_db_connectiuon() {
    
        $type = DB_TYPE;
        $user = DB_USER;
        $pass = DB_PASS;
        $host = DB_HOST;
        $dbname = DB_NAME;

        $dsn    = "$type://$user:$pass@$host/$dbname";
        $db = ADONewConnection($type); # eg 'mysql' or 'postgres'
            //$db->debug = true;
            if ( ! $db->connect($host, $user, $pass, $dbname) ) {
                die ($this->db->getMessage());
            }
        $db->setFetchMode(DB_FETCHMODE_ASSOC);
        return $db;
}
?>

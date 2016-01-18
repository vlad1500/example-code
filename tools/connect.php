<?php
$host = '127.0.0.1';
$user = 'stash';
$pass = '3ME+QaA~_n@X';
$dbname = 'hardcover';

$link = mysql_connect($host, $user, $pass);
if (!$link) {
    die('Not connected : ' . mysql_error());
}

$db_selected = mysql_select_db($dbname, $link);
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysql_error());
}


?>
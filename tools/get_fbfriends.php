<?php

error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);
require_once('connect.php');
require_once('config.php');

$fbid =$_COOKIE['hardcover_fbid'];
$token = $_COOKIE['hardcover_token'];
$book_info_id = $_GET['book_info_id'];


//FB graph api to get friends
$graph_url = trim("https://graph.facebook.com/$fbid/friends?access_token=$token");
$friends = @json_decode(file_get_contents($graph_url));
logme($graph_url);

$friends_fbid = '';
$friends_list = (array) $friends->data;
foreach ($friends_list as $friend ){
	$friends_fbid .= ';'.$friend->id;
}
$friends_fbid = substr($friends_fbid,1);
$created_date = date('m-j-Y h:i:s a');
$sql = sprintf("INSERT INTO book_cover(book_info_id,friends_fbid,created_date) VALUES('%s','%s','%s')"
		,mysql_real_escape_string($book_info_id)
		,mysql_real_escape_string($friends_fbid)
		,$created_date);
$result = mysql_query($sql);

// This shows the actual query sent to MySQL, and the error. Useful for debugging.
if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $sql;
	logme($message);
    die($message);
}else
	echo 'done with no errors';
	
function logme($data){
	global $config;
	$file = $config['tools'] . "/logs/get_fbfriends.log"; 
	$cdate = date('n/j/Y h:i:s a');
	$handle = fopen($file, 'ab');	
	fwrite($handle, "$data => $cdate \n"); 
	fclose($handle); 
}	
?>
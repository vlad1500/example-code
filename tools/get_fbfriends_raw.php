<?php

error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);
require_once('connect.php');
require_once('config.php');

$fbid = empty($_GET['fbid'])?$_COOKIE['hardcover_fbid']:$_GET['fbid'];
$token = empty($_GET['token'])?$_COOKIE['hardcover_token']:$_GET['token'];

if (empty($fbid)){
	die('no fb id to process..');
}

//FB graph api to get friends
$graph_url = trim("https://graph.facebook.com/$fbid/friends?access_token=$token");
$fb = @json_decode(file_get_contents($graph_url));
$fbdata = $fb->data;

//delete first if there is a previous data
$sql_del = sprintf("DELETE FROM friends_raw_data WHERE facebook_id='%s'",mysql_real_escape_string($fbid));
mysql_query($sql_del);

$sql_insert = "INSERT INTO friends_raw_data(facebook_id,friends_fbid,friends_name,created_date) VALUES ";
$sql = '';
foreach($fbdata as $data){
	$sql .= sprintf(", ('%s','%s','%s','%s')",
						mysql_real_escape_string($fbid),
						mysql_real_escape_string($data->id),
						mysql_real_escape_string($data->name),
						$cdate
						);
}
$sql = substr($sql,1);
$query = $sql_insert . $sql;
mysql_query($query);		
if (mysql_errno()) {
	die(mysql_errno().': '.mysql_error().'; '.$query."\n");
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
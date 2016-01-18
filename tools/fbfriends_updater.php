<?php

error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);
require_once('connect.php');
require_once('config_file.php');
include_once('common_functions.php');

$fbid = empty($argv[1])?$_GET['fbid']:$argv[1];
$token = empty($argv[2])?$_GET['token']:$argv[2];

if (empty($fbid) or empty($token)){
	logme('no fbid or token in cookie');
	die('no fbid or token in cookie');
}
$graph_url = "https://graph.facebook.com";

$execution_time['totalstart'] = get_time();
$execution_time['start'] = get_time();

$param->fbid = $fbid;
$param->token = $token;
$param->graph_url = $graph_url;


echo 'processing fb friends...';
$param->connection = 'friends';
get_fb_friends($param);
echo "done<br/>";


$execution_time['end'] = get_time();
$totaltime = ($execution_time['end'] - $execution_time['start']); 	
echo "<br/>totaltime : $totaltime";
logme("Facebook ID:$fbid");
logme("Total Execution Time: $totaltime");
logme("============================================================================");


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_graphapi_data($graph_url){
 	$ch = curl_init();
 	curl_setopt($ch, CURLOPT_URL, $graph_url);
 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 	$data = @json_decode(curl_exec($ch));
	return $data;
}

function get_fb_friends($param){
	$graph_url = $param->graph_url.'/'.$param->fbid.'/'. $param->connection.'?access_token='.$param->token;	
	$fb = get_graphapi_data($graph_url);
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');	
	echo "inside get_fb_friends";
	if ($fbdata) logme('updating friends if any');
	
	$sql_insert = "INSERT INTO friends_raw_data(facebook_id,friends_fbid,friends_name,friend_location_name,fbdata,created_date) VALUES ";
	$sql = '';
	foreach($fbdata as $data){			
		$sql_select = sprintf("SELECT friends_fbid FROM friends_raw_data WHERE facebook_id='%s' AND friends_fbid='%s'",
						mysql_real_escape_string($param->fbid),
						mysql_real_escape_string($data->id));
		$result = mysql_query($sql_select);

		if (mysql_num_rows($result)==0){			
			$graph_url_of_friend = $param->graph_url.'/'.$data->id.'?access_token='.$param->token;
			$fb = get_graphapi_data($graph_url_of_friend);
			
			$data_temp = serialize($data);	
			$sql .= sprintf(", ('%s','%s','%s','%s','%s','%s')",
								mysql_real_escape_string($param->fbid),								
								mysql_real_escape_string($data->id),
								mysql_real_escape_string($data->name),
								mysql_real_escape_string(@$fb->location->name),
								mysql_real_escape_string(($data_temp)),
								$cdate
								);
		}
		echo "\n\nplease wait....{$data->id}\n\n";
	}

	if ($sql){
		$sql = substr($sql,1);
		$query = $sql_insert . $sql;
		mysql_query($query);		
	}
	//echo '<br/><br/>'.$query;
	if (mysql_errno()) {
		echo "error encoutered...";
		logme(mysql_error().'=='.$query);
		die(mysql_errno().': '.mysql_error().'; '.$query."\n");
	}
}


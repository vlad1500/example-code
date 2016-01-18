<?php
/*
This script will retrive FB data for first time users
*/


error_reporting(E_ERROR||E_PARSE);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);
require_once('connect.php');
require_once('config_file.php');
include_once('common_functions.php');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function get_photo($param, $updater_file){
	logme('get photos', $updater_file);
	
	try{
		$since_dt = get_date_range($param);
		$offset = $param->offset;
		$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token
		. "&since={$since_dt->since}&limit={$param->limit}&offset=$offset";
		
		logme("graph url: $graph_url", $updater_file);
		$fb = get_graphapi_data($graph_url);
		$fbdata = $fb->data;
		$cdate = date('Y-n-j H:i:s');
		
		if ($fbdata) {echo "starting updating photos...\n";logme('start updating photos....', $updater_file);logme('fbdata=>'. count($fbdata), $updater_file);}
		
		$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,fb_dataid,fbdata,width,height,hd,medium,small,fbdata_postedtime,created_date) VALUES ";
		while ($fbdata){
				$sql = '';
				$counter = 0;
		
				foreach($fbdata as $data){
					$counter++;
					//logme("\ncounter: " . $counter++, $updater_file);
					logme("data id:" . $data->id, $updater_file);
					//check for duplicate, just in case
					$sql_select = "SELECT facebook_id FROM {$param->table_name} WHERE facebook_id='{$param->fbid}' and fb_dataid='{$data->id}'";
					$result = mysql_query($sql_select);
						
					if (mysql_errno()) {
						logme(mysql_error().'=='.$query, $updater_file);
						die(mysql_errno().': '.mysql_error().'; '.$query."\n");
						echo mysql_error().'=='.$query;
						logme(mysql_error().'=='.$query, $updater_file);
					}
			
					if (mysql_num_rows($result)==0){
						$data->original_image = $data->source;
						$data_temp = serialize($data);
			
						list($d, $t) = explode("+", $date->updated_time);
						$fbdata_postedtime = str_replace("T", " ", $d);
			
						$hd = $data->images[0]->source;
						$medium = $data->images[3]->source;
						$small = $data->images[7]->source;
						$sql .= sprintf(", ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
											mysql_real_escape_string($param->fbid),
											mysql_real_escape_string($param->connection),
											mysql_real_escape_string($data->id),
											mysql_real_escape_string(($data_temp)),
											$data->width,
											$data->height,
											$hd,
											$medium,
											$small,
											$fbdata_postedtime,
											$cdate
						);
						logme("image save: $medium", $updater_file );
					}
					mysql_free_result($result);
				}
		
				if ($sql){
					$sql = substr($sql,1);
					$query = $sql_insert . $sql;
					mysql_query($query);
		
					if (mysql_errno()) {
						logme(mysql_error().'=='.$query, $updater_file);
						die(mysql_errno().': '.mysql_error().'; '.$query."\n");
						echo mysql_errno().': '.mysql_error().'; '.$query."\n";
					}
				}
		
				$fbdata = '';
				if ($counter >= $param->limit){
					//navigate to next page of the graph explorer
					$offset+=25;
					$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token . "&since={$since_dt->since}&limit={$param->limit}&offset=$offset";
					$fb = get_graphapi_data($graph_url);
					$fbdata = $fb->data;
					logme("\nprocessing next batch...", $updater_file);
				}
		}
	} catch (Exception $e) {
    	echo 'Caught exception: ',  $e->getMessage(), "\n";
    	logme('Caught exception: ',  $e->getMessage(), "\n", $updater_file);
	}
	//commented as it is not use for now
	//save_comments_likes($param);
	
}



function save_comments_likes($param){
	$sql_insert_raw_comment = 'INSERT INTO book_raw_comment(facebook_id,connection,fb_dataid,comment_id,comment_obj,fbdata_postedtime,status) VALUES ';
	$sql_insert_comment = 'INSERT INTO book_comment(book_info_id,connection,fb_dataid,comment_id,comment_obj,page_num,fbdata_postedtime,status) VALUES ';

	$sql_select = "SELECT fb_dataid FROM {$param->table_name} WHERE facebook_id='$param->fbid'";
	$result = mysql_query($sql_select);

	if ($fbdata) {echo "\nupdating comments and likes if any";logme('updating comments and likes if any', $updater_file);}
	while ($row=mysql_fetch_object($result)){
		$sql_raw_comment = '';
		$sql_comment = '';
		$friends_that_commented = '';
		$fb_dataid = $row->fb_dataid;
		
		//get the comments for the object id
		$graph_url = 'https://graph.facebook.com/fql?access_token='.$param->token
							.'&q='.urlencode('select id,text,fromid,time from comment where object_id=').$fb_dataid;
				$fb = get_graphapi_data($graph_url);
				echo "\n$graph_url";
				foreach($fb->data as $comment_data){
				if ($param->fbid!=$comment_data->fromid) $friends_that_commented .= $comment_data->fromid.';';
				$comment_postedtime = date('Y-n-j H:i:s',$comment_data->time);
		
				//lets modify the comment object so it will  be the same as the fb tools explorer format
		$from = create_from_obj($param,$comment_data->fromid);
		$comment_data->from = $from;
		$comment_data->created_time = $comment_postedtime;
		$comment_data->message = $comment_data->text;
		$comment = serialize($comment_data);
		echo "\n";
		print_r($comment_data);
		//end of modifying comment object
	
		//check for duplicate first
		$sql_select = "SELECT fb_dataid FROM book_raw_comment WHERE comment_id='".trim($comment_data->id)."'";
		$result_comments = mysql_query($sql_select);
					if (mysql_errno()) {
						logme(mysql_error().'=='.$query, $updater_file);
						die(mysql_errno().': '.mysql_error().'; '.$query_comment."\n");
	}
	
	if (mysql_num_rows($result_comments)==0){
	$sql_raw_comment .= sprintf(", ('%s','%s','%s','%s','%s','%s','%s')",
	$param->fbid,
	$param->connection,
	$fb_dataid,
		$comment_data->id,
		mysql_real_escape_string($comment),
								$comment_postedtime,
								'active'
	);
}
mysql_free_result($result_comments);
	
	
//we will try to add comment to all the book with the fb_dataid
$sql_select = "SELECT page_num,book_info_id FROM book_comment WHERE fb_dataid='".trim($fb_dataid)."'";
$result_comments = mysql_query($sql_select);
while ($row=mysql_fetch_object($result_comments)){
				$book_info_id = $row->book_info_id;

				//check first if there is already inserted comment for the said book
				$sql_select = "SELECT fb_dataid FROM book_comment WHERE book_info_id=$book_info_id AND comment_id='".trim($comment_data->id)."'";
$result_ = mysql_query($sql_select);
if (mysql_num_rows($result_)==0){
$sql_comment .= sprintf(", ('%s','%s','%s','%s','%s','%s','%s','%s')",
$book_info_id,
$param->connection,
$fb_dataid,
$comment_data->id,
mysql_real_escape_string($comment),
$row->page_num,
$comment_postedtime,
'new'
);
}
mysql_free_result($result_);
			}
			mysql_free_result($result_comments);
			unset($from);
			unset($comment_data);
			unset($comment);
}//end for

//insert into raw book comment in case there is a new comment on one of the objects in the book
if ($sql_raw_comment){
$sql_raw_comment = substr($sql_raw_comment,1);
$query_comment = $sql_insert_raw_comment . $sql_raw_comment;
mysql_query($query_comment);

if (mysql_errno()) {
logme(mysql_error().'=='.$query, $updater_file);
die(mysql_errno().': '.mysql_error().'; '.$query_comment."\n");
}
}

//insert into the book_comment
if ($sql_comment){
$sql_comment = substr($sql_comment,1);
$query_comment = $sql_insert_comment . $sql_comment;
mysql_query($query_comment);
echo "<br/>$query_comment";
if (mysql_errno()) {
logme(mysql_error().'=='.$query, $updater_file);
die(mysql_errno().': '.mysql_error().'; '.$query_comment."\n");
}
}
//end of gettings comments

//get the likes so we can make an update in one query
$graph_url = 'https://graph.facebook.com/fql??access_token='.$param->token
.'&q='.urlencode('select user_id,object_type from like where object_id=').trim($fb_dataid);
$fb = get_graphapi_data($graph_url);
$friends_that_like = format_friend_that_like($fb->data,$param);

//update the friends that liked and commented
$sql_update = "UPDATE {$param->table_name}
SET friends_that_commented='$friends_that_commented',friends_that_like='$friends_that_like'
WHERE facebook_id='{$param->fbid}' AND fb_dataid='$fb_dataid'";
mysql_query($sql_update);
}
}


function format_friend_that_like($likes,$param){
	$friends_that_like = '';
	foreach ($likes as $friend){
		if ($param->fbid!=$friend->id) $friends_that_like .= $friend->id.';';
	}
	return $friends_that_like;
}


function get_date_range($param){
	$sql_select = "SELECT fbdata_postedtime FROM {$param->table_name} WHERE  `facebook_id` =  '{$param->fbid}' ORDER BY  `fbdata_postedtime` DESC LIMIT 1";
	$result = mysql_query($sql_select);
	if ($row = mysql_fetch_object($result))
		$since_dt->since = strtotime($row->fbdata_postedtime) + 1;
	else
		$since_dt->since = strtotime("2004-02-01") + 1;

	mysql_free_result($result);

	return $since_dt;
}

function create_from_obj($param,$fromid){
	$graph_url = 'https://graph.facebook.com/fql?access_token='.$param->token
	.'&q='.urlencode('select first_name, last_name from user where uid='). $fromid;
	$fb = get_graphapi_data($graph_url);
	$user = $fb->data;
	$from = new stdclass();
	$from->name = $user[0]->first_name . ' ' . $user[0]->last_name;
	$from->id = $fromid;

	return $from;
}


/////////////////////////////////////////////////////////////////////////////////////////////
$updater_file = get_filename($_SERVER["PHP_SELF"]);
$fbid = empty($argv[1])?$_GET['fbid']:$argv[1];
$token = empty($argv[2])?$_GET['token']:$argv[2];

//$fbid = '1216568374';
//$token = 'CAAEtGOhTURQBAMSC2vYBAvOwjdR5nZCeOI1w3V6pMpwA6YeUBXE7Keli9vsd0eqz0r82IZA76o4a7xaOqTumI3rVCSKCJVyHLkQNiIZC5mAwSrP2cx5ceOIiZAUhyogfHzrTTYWZBbuZBccjk8ZC1F566lo5bG91jpKD0PMcIa1tyC2MNQYQsfJs5aDgZABZAO64ZD';


if (empty($fbid) or empty($token)){
	logme('no fbid or token in cookie');
	die('no fbid or token in cookie');
}
$graph_url = "https://graph.facebook.com/$fbid/";

$execution_time['totalstart'] = get_time();
$execution_time['start'] = get_time();

$param->fbid = $fbid;
$param->token = $token;
$param->graph_url = $graph_url;
$param->limit = 25;
$param->offset = 0;

//me/photos
logme('processing fb photos...', $updater_file);
$param->connection = 'photos';
$param->table_name = 'photos_raw_data';
get_photo($param, $updater_file);
logme('done', $updater_file);


$execution_time['end'] = get_time();
$totaltime = ($execution_time['end'] - $execution_time['start']); 	
echo "<br/>totaltime : $totaltime";
logme("Facebook ID:$fbid", $updater_file);
logme("Total Execution Time: $totaltime", $updater_file);
logme("============================================================================", $updater_file);




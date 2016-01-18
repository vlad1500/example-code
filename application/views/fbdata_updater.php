<?php
/*
This script will retrive FB data for first time users
Probably there will be another script for just updating fb data for a specific user
*/

error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);
require_once('connect.php');
require_once('config.php');

$fbid = empty($argv[1])?$_GET['fbid']:$argv[1];
$token = empty($argv[2])?$_GET['token']:$argv[2];

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


//me/friends
echo 'processing fb friends...';
$param->connection = 'friends';
$param->table_name = 'friends_raw_data';
get_fb_friends($param);
echo "done<br/>";

//me/statuses
echo 'processing fb statuses...';
$param->connection = 'statuses';
$param->table_name = 'statuses_raw_data';
get_statuses($param);
echo "done<br/>";
die;

//me/feed
echo 'processing fb feeds...';
$param->connection = 'feed';
$param->table_name = 'feed_raw_data';
get_feed($param);
echo "done<br/>";

//me/photos
echo 'processing fb photos...';
$param->connection = 'photos';
$param->table_name = 'photos_raw_data';
get_photo($param);
echo "done<br/>";

//me/albums
echo 'processing fb albums...';
$param->connection = 'albums';
$param->table_name = 'albums_raw_data';
$album_ids = get_albums($param);
echo "done<br/>";


//album_id/photos
echo 'processing fb photos inside albums...';
$param->connection = 'photos';
$param->table_name = 'album_photos_raw_data';
$param->graph_url = 'https://graph.facebook.com/';
get_album_photos($param,$album_ids);
echo "done<br/>";


$execution_time['end'] = get_time();
$totaltime = ($execution_time['end'] - $execution_time['start']); 	
echo "<br/>totaltime : $totaltime";
logme("Facebook ID:$fbid");
logme("Total Execution Time: $totaltime");
logme("============================================================================");

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//MY STATUS UPDATES
function get_statuses($param){
	$since_dt = get_date_range($param);	
	$offset = $param->offset;
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token 
				. "&since={$since_t->since}&limit={$param->limit}&offset=$offset";
	//logme($graph_url);
	$fb = @json_decode(file_get_contents($graph_url));
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');
	
	if ($fbdata) logme('updating status');
	
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,fb_dataid,fbdata,fbdata_postedtime,created_date) VALUES ";
	while ($fbdata){		
		$sql = '';
		$counter = 0;
		foreach($fbdata as $data){		
			$counter++;	

			//check for duplicate, just in case
			$sql_select = "SELECT facebook_id FROM {$param->table_name} WHERE facebook_id='{$param->fbid}' and fb_dataid='{$data->id}'";
			$result = mysql_query($result);
			if (mysql_num_rows($result)==0){
				$data_temp = serialize($data);	
				$dt = new DateTime($data->updated_time);
				$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
				
				$sql .= sprintf(", ('%s','%s','%s','%s','%s','%s')",
								mysql_real_escape_string($param->fbid),
								$param->connection,
								$data->id,
								mysql_real_escape_string(($data_temp)),
								$fbdata_postedtime,
								$cdate
								);	
			}
		}
		if ($sql){
			$sql = substr($sql,1);
			$query = $sql_insert . $sql;
			mysql_query($query);		
		
			if (mysql_errno()) {
				logme(mysql_error().'=='.$query);
				die(mysql_errno().': '.mysql_error().'; '.$query."\n");
			}
		}
		$fbdata = '';
		if ($counter >= $param->limit){
			//navigate to next page of the graph explorer	
			$offset+=25;	
			$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token . "&since={$since_dt->since}&limit={$param->limit}&offset=$offset";
			$fb = @json_decode(file_get_contents($graph_url));
			$fbdata = $fb->data;
		}
	}
	mysql_free_result($result);
	save_comments_likes($param);
}

function get_feed($param){
	$since_dt = get_date_range($param);	
	$offset = $param->offset;
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token 
				. "&since={$since_dt->since}&limit={$param->limit}&offset=$offset";
	$fb = @json_decode(file_get_contents($graph_url));
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');
	
	if ($fbdata) logme('updating feeds');
	
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,fb_dataid,fbdata,feed_type,fbdata_postedtime,created_date) VALUES ";
	while ($fbdata){
		$sql = '';
		$counter = 0;
		foreach($fbdata as $data){		
			$counter++;	

			//check for duplicate, just in case
			$sql_select = "SELECT facebook_id FROM {$param->table_name} WHERE facebook_id='{$param->fbid}' and fb_dataid='{$data->id}'";
			$result = mysql_query($result);
			if (mysql_num_rows($result)==0){
				$data->original_image = $data->picture;
				$data_temp = serialize($data);	
				$dt = new DateTime($data->updated_time);
				$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
				
				$sql .= sprintf(", ('%s','%s','%s','%s','%s','%s','%s')",
								mysql_real_escape_string($param->fbid),
								mysql_real_escape_string($param->connection),
								mysql_real_escape_string($data->id),
								mysql_real_escape_string(($data_temp)),
								mysql_real_escape_string($data->type),
								$fbdata_postedtime,
								$cdate
								);	
			}
		}
		if ($sql){
			$sql = substr($sql,1);
			$query = $sql_insert . $sql;
			mysql_query($query);		
		
			if (mysql_errno()) {
				logme(mysql_error().'=='.$query);
				die(mysql_errno().': '.mysql_error().'; '.$query."\n");
			}
		}
		$fbdata = '';
		if ($counter >= $param->limit){
			//navigate to next page of the graph explorer	
			$offset+=25;	
			$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token . "&since={$since_dt->since}&limit={$param->limit}&offset=$offset";
			$fb = @json_decode(file_get_contents($graph_url));
			$fbdata = $fb->data;
		}
	}
	mysql_free_result($result);
	save_comments_likes($param);
}

function get_photo($param){
	$since_dt = get_date_range($param);
	$offset = $param->offset;	
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token 
				. "&since={$since_dt->since}&limit={$param->limit}&offset=$offset";
	$fb = @json_decode(file_get_contents($graph_url));
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');
	
	if ($fbdata) logme('updating photos');
	
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,fb_dataid,fbdata,width,height,hd,medium,small,fbdata_postedtime,created_date) VALUES ";
	while ($fbdata){		
		$sql = '';
		$counter = 0;
		foreach($fbdata as $data){		
			$counter++;	

			//check for duplicate, just in case
			$sql_select = "SELECT facebook_id FROM {$param->table_name} WHERE facebook_id='{$param->fbid}' and fb_dataid='{$data->id}'";
			$result = mysql_query($sql_select);
			if (mysql_errno()) {
				logme(mysql_error().'=='.$query);
				die(mysql_errno().': '.mysql_error().'; '.$query."\n");
			}

			if (mysql_num_rows($result)==0){
				$data->original_image = $data->source;
				$data_temp = serialize($data);	
				$dt = new DateTime($data->updated_time);
				$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
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
			}
			mysql_free_result($result);
		}

		if ($sql){
			$sql = substr($sql,1);
			$query = $sql_insert . $sql;
			mysql_query($query);		
		
			if (mysql_errno()) {
				logme(mysql_error().'=='.$query);
				die(mysql_errno().': '.mysql_error().'; '.$query."\n");
			}
		}
		$fbdata = '';
		if ($counter >= $param->limit){
			//navigate to next page of the graph explorer	
			$offset+=25;	
			$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token . "&since={$since_dt->since}&limit={$param->limit}&offset=$offset";
			$fb = @json_decode(file_get_contents($graph_url));
			$fbdata = $fb->data;
		}
	}	
	save_comments_likes($param);	
}


function get_albums($param){
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token;
	$fb = @json_decode(file_get_contents($graph_url));
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');
	
	if ($fbdata) logme('updating album list');
	
	$album_ids = '';
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,album_id,fbdata,fbdata_postedtime,created_date) VALUES ";
	while ($fbdata){
		$sql = '';
		foreach($fbdata as $data){	
			$album_ids .= ';'.$data->id;
			
			//check first if there is a previous data
			$sql_select = sprintf("SELECT facebook_id FROM {$param->table_name} WHERE facebook_id='%s'",mysql_real_escape_string($param->fbid));
			$result = mysql_query($sql_select);
			if (mysql_num_rows($result)==0){				
				$data_temp = serialize($data);	
				$dt = new DateTime($data->updated_time);
				$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
				
				$sql .= sprintf(", ('%s','%s','%s','%s','%s','%s')",
								mysql_real_escape_string($param->fbid),
								mysql_real_escape_string($param->connection),
								mysql_real_escape_string($data->id),
								mysql_real_escape_string(($data_temp)),
								$fbdata_postedtime,
								$cdate
								);	
			}
			mysql_free_result($result);
		}
		if ($sql){
			$sql = substr($sql,1);
			$query = $sql_insert . $sql;
			mysql_query($query);
			
			if (mysql_errno()) {
				logme(mysql_error().'=='.$query);
				die(mysql_errno().': '.mysql_error().'; '.$query."\n");
			}
		}
		
		//navigate to next page of the graph explorer
		$fbpaging = $fb->paging;
		$fb = @json_decode(file_get_contents($fbpaging->next));
		$fbdata = $fb->data;
	}	
	return substr($album_ids,1) ;
}

function get_album_photos($param,$album_ids){
	$arr_albums_ids = explode(';',$album_ids);
	foreach ($arr_albums_ids as $id){		
		$graph_url = $param->graph_url . $id .'/'.$param->connection.'?access_token='.$param->token;
		$fb = @json_decode(file_get_contents($graph_url));
		$fbdata = $fb->data;
		$cdate = date('Y-n-j H:i:s');

		$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,album_id,fb_dataid,fbdata,width,height,hd,medium,small,fbdata_postedtime,created_date) VALUES ";
		while ($fbdata){
			$sql = '';
			foreach($fbdata as $data){	
				//check first if there is a previous data
				$sql_select = sprintf("SELECT album_id FROM {$param->table_name} WHERE facebook_id='%s' AND fb_dataid='%s'",
							mysql_real_escape_string($param->fbid),
							mysql_real_escape_string($data->id));
				$result = mysql_query($sql_select);
				
				if (mysql_num_rows($result)==0){
					$data->original_image = $data->source;
					$data_temp = serialize($data);	
					$dt = new DateTime($data->updated_time);
					$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
					
					$hd = $data->images[0]->source;
					$medium = $data->images[3]->source;
					$small = $data->images[7]->source;
	
					$sql .= sprintf(", ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
									mysql_real_escape_string($param->fbid),
									'album_photos',
									$id,
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
				}
				mysql_free_result($result);
			}
			
			if ($sql){
				$sql = substr($sql,1);
				$query = $sql_insert . $sql;
				mysql_query($query);		
				if (mysql_errno()) {
					logme(mysql_error().'=='.$query);
					die(mysql_errno().': '.mysql_error().'; '.$query."\n");
				}
			}
			
			//navigate to next page of the graph explorer
			$fbpaging = $fb->paging;
			$fb = @json_decode(file_get_contents($fbpaging->next));
			$fbdata = $fb->data;
		}
	}
	save_comments_likes($param);
}

function get_fb_friends($param){
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token;	
	$fb = @json_decode(file_get_contents($graph_url));	
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');	
	
	if ($fbdata) logme('updating friends if any');
	
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,friends_fbid,friends_name,created_date) VALUES ";
	$sql = '';
	foreach($fbdata as $data){			
		$sql_select = sprintf("SELECT friends_fbid FROM {$param->table_name} WHERE facebook_id='%s' AND friends_fbid='%s'",
						mysql_real_escape_string($param->fbid),
						mysql_real_escape_string($data->id));
		$result = mysql_query($sql_select);
		if (mysql_num_rows($result)==0){
			$sql .= sprintf(", ('%s','%s','%s','%s')",
								mysql_real_escape_string($param->fbid),								
								mysql_real_escape_string($data->id),
								mysql_real_escape_string($data->name),
								$cdate
								);
		}
	}
	if ($sql){
		$sql = substr($sql,1);
		$query = $sql_insert . $sql;
		mysql_query($query);		
	}
	if (mysql_errno()) {
		logme(mysql_error().'=='.$query);
		die(mysql_errno().': '.mysql_error().'; '.$query."\n");
	}
}


function save_comments_likes($param){	
	$sql_insert_raw_comment = 'INSERT INTO book_raw_comment(facebook_id,connection,fb_dataid,comment_id,comment_obj,fbdata_postedtime,status) VALUES ';
	$sql_insert_comment = 'INSERT INTO book_comment(book_info_id,connection,fb_dataid,comment_id,comment_obj,page_num,fbdata_postedtime,status) VALUES ';

	$sql_select = "SELECT fb_dataid FROM {$param->table_name} WHERE facebook_id='$param->fbid'";
	$result = mysql_query($sql_select);

	if ($fbdata) logme('updating comments and likes if any');
	while ($row=mysql_fetch_object($result)){		
		$sql_raw_comment = '';
		$sql_comment = '';
		$friends_that_commented = '';	
		$fb_dataid = $row->fb_dataid;
		
		//get the comments for the object id
		$graph_url = 'https://graph.facebook.com/fql?access_token='.$param->token
					.'&q='.urlencode('select id,text,fromid,time from comment where object_id=').$fb_dataid;
		$fb = json_decode(file_get_contents($graph_url));			
		foreach($fb->data as $comment_data){
			if ($param->fbid!=$comment_data->fromid) $friends_that_commented .= $comment_data->fromid.';';
			$comment_postedtime = date('Y-n-j H:i:s',$comment_data->time);
				
			//lets modify the comment object so it will  be the same as the fb tools explorer format
			$from = create_from_obj($param,$comment_data->fromid);
			$comment_data->from = $from;
			$comment_data->created_time = $comment_postedtime;							
			$comment_data->message = $comment_data->text;
			$comment = serialize($comment_data);
			//end of modifying comment object
			
			//check for duplicate first
			$sql_select = "SELECT fb_dataid FROM book_raw_comment WHERE comment_id='".trim($comment_data->id)."'";
			$result_comments = mysql_query($sql_select);
			if (mysql_errno()) {
				logme(mysql_error().'=='.$query);
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
		}
		
		//insert into raw book comment in case there is a new comment on one of the objects in the book
		if ($sql_raw_comment){
			$sql_raw_comment = substr($sql_raw_comment,1);
			$query_comment = $sql_insert_raw_comment . $sql_raw_comment;
			mysql_query($query_comment);

			if (mysql_errno()) {
				logme(mysql_error().'=='.$query);
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
				logme(mysql_error().'=='.$query);
				die(mysql_errno().': '.mysql_error().'; '.$query_comment."\n");
			}
		}
		//end of gettings comments
		
		//get the likes so we can make an update in one query
		$graph_url = 'https://graph.facebook.com/fql??access_token='.$param->token
					.'&q='.urlencode('select user_id,object_type from like where object_id=').trim($fb_dataid);
		$fb = @json_decode(file_get_contents($graph_url));
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
	$fb = @json_decode(file_get_contents($graph_url));
	$user = $fb->data;
	$from = new stdclass();
	$from->name = $user[0]->first_name . ' ' . $user[0]->last_name;
	$from->id = $fromid;
	
	return $from;
}

function get_time(){
	$mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   return $mtime; 
}


function logme($data){
	global $config;
	$file = $config['tools'] . '/logs/fbupdater.log'; 
	$cdate = date('n/j/Y h:i:s a');
	$handle = fopen($file, 'ab');	
	fwrite($handle, "$data => $cdate \n"); 
	fclose($handle); 
}
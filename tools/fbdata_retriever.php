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

//$fbid = empty($fbid)?$_GET['fbid']:$fbid;
//$token = empty($token)?$_GET['token']:$token;

if (empty($fbid)){
	logme('no fbid pass or in cookie');
	die('no fbid pass or in cookie');
}
$graph_url = "https://graph.facebook.com/$fbid/";
logme($graph_url);

$execution_time['totalstart'] = get_time();
$execution_time['start'] = get_time();

$param->fbid = $fbid;
$param->token = $token;
$param->graph_url = $graph_url;


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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//MY STATUS UPDATES
function get_statuses($param){
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token;
	$fb = @json_decode(file_get_contents($graph_url));
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');	

	//delete first if there is a previous data
	$sql_del = sprintf("DELETE FROM {$param->table_name} WHERE facebook_id='%s'",mysql_real_escape_string($param->fbid));
	mysql_query($sql_del);
	
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,fb_dataid,fbdata,fbdata_postedtime,friends_that_like,friends_that_commented,created_date) VALUES ";
	$sql_insert_comment = 'INSERT INTO book_comment(connection,fb_dataid,comment_obj,fbdata_postedtime) VALUES ';
	while ($fbdata){
		$sql = '';
		foreach($fbdata as $data){			
			$data_temp = serialize($data);	
			$dt = new DateTime($data->updated_time);
			$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
			
			$likes = $data->likes->data;			
			$friends_that_like = '';
			foreach ($likes as $friend){
				if ($param->fbid!=$friend->id) $friends_that_like .= $friend->id.';';
			}			
			
			//this will get the friends that commented as well as make insert in the book-comment table for all comments made	
			$param->fbdata_id = $data->id;		
			$friends_that_commented = save_comments($data->comments->data,$param);
			
			$sql .= sprintf(",('%s','%s','%s','%s','%s','%s','%s','%s')",
							mysql_real_escape_string($param->fbid),
							$param->connection,
							$data->id,
							mysql_real_escape_string(($data_temp)),
							$fbdata_postedtime,
							$friends_that_like,
							$friends_that_commented,
							$cdate
							);	
		}
		$sql = substr($sql,1);
		$query = $sql_insert . $sql;
		mysql_query($query);		
		if (mysql_errno()) {
 			die(mysql_errno().': '.mysql_error().'; '.$query."\n");
		}
		
		//navigate to next page of the graph explorer
		$fbpaging = $fb->paging;
		$fb = @json_decode(file_get_contents($fbpaging->next));
		$fbdata = $fb->data;
	}
}


function get_feed($param){
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token;
	$fb = @json_decode(file_get_contents($graph_url));
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');	

	//delete first if there is a previous data
	$sql_del = sprintf("DELETE FROM {$param->table_name} WHERE facebook_id='%s'",mysql_real_escape_string($param->fbid));
	mysql_query($sql_del);
	
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,fb_dataid,fbdata,feed_type,fbdata_postedtime,friends_that_like,friends_that_commented,created_date) VALUES ";
	while ($fbdata){
		$sql = '';
		foreach($fbdata as $data){	
			$data->original_image = $data->picture;		
			$data_temp = serialize($data);	
			$dt = new DateTime($data->updated_time);
			$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
			
			$likes = $data->likes->data;			
			$friends_that_like = '';
			foreach ($likes as $friend){
				if ($param->fbid!=$friend->id) $friends_that_like .= $friend->id.';';
			}			
			
			$param->fbdata_id = $data->id;
			$friends_that_commented = save_comments($data->comments->data,$param);
			
			$sql .= sprintf(",('%s','%s','%s','%s','%s','%s','%s','%s','%s')",
							mysql_real_escape_string($param->fbid),
							mysql_real_escape_string($param->connection),
							mysql_real_escape_string($data->id),
							mysql_real_escape_string(($data_temp)),
							mysql_real_escape_string($data->type),
							$fbdata_postedtime,
							$friends_that_like,
							$friends_that_commented,
							$cdate
							);	
		}
		$sql = substr($sql,1);
		$query = $sql_insert . $sql;
		mysql_query($query);		
		if (mysql_errno()) {
 			die(mysql_errno().': '.mysql_error().'; '.$query."\n");
		}
		
		//navigate to next page of the graph explorer
		$fbpaging = $fb->paging;
		$fb = @json_decode(file_get_contents($fbpaging->next));
		$fbdata = $fb->data;
	}
}

function get_photo($param){
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token;
	$fb = @json_decode(file_get_contents($graph_url));
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');	

	//delete first if there is a previous data
	$sql_del = sprintf("DELETE FROM {$param->table_name} WHERE facebook_id='%s'",mysql_real_escape_string($param->fbid));
	mysql_query($sql_del);
	
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,fb_dataid,fbdata,width,height,hd,medium,small,fbdata_postedtime,friends_that_like,friends_that_commented,created_date) VALUES ";
	while ($fbdata){
		$sql = '';
		foreach($fbdata as $data){
			$data->original_image = $data->source;
			$data_temp = serialize($data);	
			$dt = new DateTime($data->updated_time);
			$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
			
			$likes = $data->likes->data;			
			$friends_that_like = '';
			foreach ($likes as $friend){				
				if ($param->fbid!=$friend->id) $friends_that_like .= $friend->id.';';
			}			
			
			$param->fbdata_id = $data->id;
			$friends_that_commented = save_comments($data->comments->data,$param);
			
			$hd = $data->images[0]->source;
			$medium = $data->images[3]->source;
			$small = $data->images[7]->source;
			$sql .= sprintf(",('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
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
							$friends_that_like,
							$friends_that_commented,
							$cdate
							);	
		}
		$sql = substr($sql,1);
		$query = $sql_insert . $sql;
		mysql_query($query);		
		if (mysql_errno()) {
 			die(mysql_errno().': '.mysql_error().'; '.$query."\n");
		}
		
		//navigate to next page of the graph explorer
		$fbpaging = $fb->paging;
		$fb = @json_decode(file_get_contents($fbpaging->next));
		$fbdata = $fb->data;
	}
}

function get_albums($param){
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token;
	$fb = @json_decode(file_get_contents($graph_url));
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');
	
	//delete first if there is a previous data
	$sql_del = sprintf("DELETE FROM {$param->table_name} WHERE facebook_id='%s'",mysql_real_escape_string($param->fbid));
	mysql_query($sql_del);
	
	$album_ids = '';
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,album_id,fbdata,fbdata_postedtime,created_date) VALUES ";
	while ($fbdata){
		$sql = '';
		foreach($fbdata as $data){			
			$data_temp = serialize($data);	
			$dt = new DateTime($data->updated_time);
			$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
			
			$album_ids .= ';'.$data->id;
			$sql .= sprintf(",('%s','%s','%s','%s','%s','%s')",
							mysql_real_escape_string($param->fbid),
							mysql_real_escape_string($param->connection),
							mysql_real_escape_string($data->id),
							mysql_real_escape_string(($data_temp)),
							$fbdata_postedtime,
							$cdate
							);	
		}
		$sql = substr($sql,1);
		$query = $sql_insert . $sql;
		mysql_query($query);
		
		if (mysql_errno()) {
 			die(mysql_errno().': '.mysql_error().'; '.$query."\n");
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
		
		//delete first if there is a previous data
		$sql_del = sprintf("DELETE FROM {$param->table_name} WHERE facebook_id='%s'",mysql_real_escape_string($param->fbid));
		mysql_query($sql_del);
		
		$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,connection,fb_dataid,fbdata,width,height,hd,medium,small,fbdata_postedtime,friends_that_like,friends_that_commented,created_date) VALUES ";
		while ($fbdata){
			$sql = '';
			foreach($fbdata as $data){	
				$data->original_image = $data->source;		
				$data_temp = serialize($data);	
				$dt = new DateTime($data->updated_time);
				$fbdata_postedtime = $dt->format('Y-n-j H:i:s');
				
				$likes = $data->likes->data;			
				$friends_that_like = '';
				foreach ($likes as $friend){
					if ($param->fbid!=$friend->id) $friends_that_like .= $friend->id.';';
				}			
				
				$param->fbdata_id = $data->id;
				$friends_that_commented = save_comments($data->comments->data,$param);
				
				$hd = $data->images[0]->source;
				$medium = $data->images[3]->source;
				$small = $data->images[7]->source;

				$sql .= sprintf(",('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
								mysql_real_escape_string($param->fbid),
								'album_photos',
								mysql_real_escape_string($data->id),
								mysql_real_escape_string(($data_temp)),
								$data->width,
								$data->height,
								$hd,
								$medium,
								$small,
								$fbdata_postedtime,
								$friends_that_like,
								$friends_that_commented,
								$cdate
								);	
			}
			$sql = substr($sql,1);
			$query = $sql_insert . $sql;
			mysql_query($query);		
			if (mysql_errno()) {
				die(mysql_errno().': '.mysql_error().'; '.$query."\n");
			}
			//navigate to next page of the graph explorer
			$fbpaging = $fb->paging;
			$fb = @json_decode(file_get_contents($fbpaging->next));
			$fbdata = $fb->data;
		}	
	}
}


function get_fb_friends($param){
	$graph_url = $param->graph_url . $param->connection.'?access_token='.$param->token;	
	$fb = @json_decode(file_get_contents($graph_url));	
	$fbdata = $fb->data;
	$cdate = date('Y-n-j H:i:s');	

	//delete first if there is a previous data
	$sql_del = sprintf("DELETE FROM {$param->table_name} WHERE facebook_id='%s'",mysql_real_escape_string($param->fbid));
	mysql_query($sql_del);
	
	$sql_insert = "INSERT INTO {$param->table_name}(facebook_id,friends_fbid,friends_name,created_date) VALUES ";
	$sql = '';
	foreach($fbdata as $data){			
		$sql .= sprintf(", ('%s','%s','%s','%s')",
							mysql_real_escape_string($param->fbid),
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
	}
}


function save_comments($comments,$param){
	$sql_del = sprintf("DELETE FROM book_raw_comment WHERE facebook_id='%s'",mysql_real_escape_string($param->fbid));
	mysql_query($sql_del);
	
	$sql_insert_comment = 'INSERT INTO book_raw_comment(facebook_id,connection,fb_dataid,comment_id,comment_obj,fbdata_postedtime) VALUES ';
	$friends_that_commented = '';
	$sql_comment = '';
	foreach($comments as $comment_data){
		if ($param->fbid!=$comment_data->from->id) $friends_that_commented .= $comment_data->from->id.';';
		$comment = serialize($comment_data);
		$dt = new DateTime($comment_data->updated_time);
		$comment_postedtime = $dt->format('Y-n-j H:i:s');
		$sql_comment .= sprintf(", ('%s','%s','%s','%s','%s','%s')",
					$param->fbid,
					$param->connection,
					$param->fbdata_id,
					$comment_data->id,
					mysql_real_escape_string($comment),
					$comment_postedtime
					);	
	}
	if ($sql_comment){
		$sql_comment = substr($sql_comment,1);
		$query_comment = $sql_insert_comment . $sql_comment;
		mysql_query($query_comment);		
		if (mysql_errno()) {
			die(mysql_errno().': '.mysql_error().'; '.$query_comment."\n");
		}
	}
	
	return $friends_that_commented;
}


function get_time(){
	$mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   return $mtime; 
}


function logme($data){
	global $config;
	$file = $config['tools'] . '/logs/fbretriever.log'; 
	$cdate = date('n/j/Y h:i:s a');
	$handle = fopen($file, 'ab');	
	fwrite($handle, "$data => $cdate \n"); 
	fclose($handle); 
}
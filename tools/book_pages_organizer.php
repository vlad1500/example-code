<?php
/*
This script will organize the book pages

*/

error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);
require_once('connect.php');
require_once('config.php');

$book_info_id = $_GET['book_info_id'];
$sql = sprintf("SELECT bc.* FROM  book_pages bp INNER JOIN book_comment bc ON bp.fb_dataid=bc.fb_dataid 
						WHERE bp.book_info_id=%d AND bc.status!='deleted'",mysql_real_escape_string($book_info_id));
$result = mysql_query($sql) or die(mysql_error());  

while($row = mysql_fetch_object($result)) {
	$comment = unserialize($row->comment_obj);
	//comment objecti fields
	//id  = contains comment id
	//from = an object  access using $comment->from->name
	//message = contains the message of the comment
	//created_time = contains the time this comment was save into the fb server
	print_r($comment);
	echo "<br/><br/>";

}
mysql_free_result($result);
?>

<?php
/*
This script will organize the book pages

*/

/*
define("BOOK_HEIGHT", "700");				//in pixel
define("BOOK_WIDTH", "900");				//in pixel
define("COMMENT_HEIGHT", "500");			//in pixel
define("COMMENT_WIDTH", "200");				//in pixel
define("FONT_SIZE", "12");					//in pixel
define("HEIGHT_PER_LINE", "16");			//in pixel
define("CHAR_PER_LINE", "24");				
define("PROFILE_PICT_HEIGHT", "30");		//in pixel
define("DATE_HEIGHT", "16");				//in pixel
define("COMMENT_BORDER_HEIGHT", "16");		//in pixel
*/

//char_per_line = this is the number of characters that can accomodate in one line for the comment area
//date_height = the height in pixel of the date section of the comment
//comment_border_height - the height in pixel of the comment border area
//max_page_height = the max height in pixel of the comment container  in one page

//Mychele please define the values according to what is the height
$arr_page_layout[1] = array('char_per_line'=>24,'height_per_line'=>16,'profile_pict_height'=>30,'date_height'=>16,'comment_border_height'=>16,'max_container_height_col_l'=>500,'max_container_height_col_r'=>500);
$arr_page_layout[2] = array('char_per_line'=>24,'height_per_line'=>16,'profile_pict_height'=>30,'date_height'=>16,'comment_border_height'=>16,'max_container_height_col_l'=>500,'max_container_height_col_r'=>500);
$arr_page_layout[3] = array('char_per_line'=>24,'height_per_line'=>16,'profile_pict_height'=>30,'date_height'=>16,'comment_border_height'=>16,'max_container_height_col_l'=>250,'max_container_height_col_r'=>500);
$arr_page_layout[4] = array('char_per_line'=>55,'height_per_line'=>16,'profile_pict_height'=>30,'date_height'=>16,'comment_border_height'=>16,'max_container_height_col_l'=>90,'max_container_height_col_r'=>500);
$arr_page_layout[5] = array('char_per_line'=>24,'height_per_line'=>16,'profile_pict_height'=>30,'date_height'=>16,'comment_border_height'=>16,'max_container_height_col_l'=>500,'max_container_height_col_r'=>500);
//initialize php settings run-time
error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);


require_once('connect.php');
require_once('config.php');

$execution_time['totalstart'] = get_time();
$execution_time['start'] = get_time();


//this is the only parameter to get pass in this script
$book_info_id = empty($argv[1])?$_GET['book_info_id']:$argv[1];
if (empty($book_info_id)) {
	logme('empty book info id');
	die;
}else
	logme("updating book page number for book info id = $book_info_id");
 
//comment this when running on LIVE and uncomment the above lines
//$book_info_id = 43;

$sql = sprintf("SELECT * from book_details_vw WHERE book_info_id = %d",mysql_real_escape_string($book_info_id));		
$result = mysql_query($sql) or die(mysql_error());  

$sql_book_pages = '';
$comment = '';
$page_num = 1;

$row = mysql_fetch_object($result);
while ($row) {
	switch ($row->page_layout) {
		case 1: $page_col = 2; 
			$what_col = 'max_container_height_col_r';
			break;
		case 2: $page_col = 1; 
			$what_col = 'max_container_height_col_l';
			break;				
		case 3: $page_col = 1; 
			$what_col = 'max_container_height_col_l';
			break;
		case 4: $page_col = 2; 
			$what_col = 'max_container_height_col_r';
			break;		
	}
	//update the page number
	//$sql_book_pages = "INSERT INTO temp_page_table (book_info_id, book_pages_id, bp_page_num, bc_page_num) VALUES (".$row->book_info_id.", ".$row->book_pages_id.", ".$page_num.", 0);";
	$sql_book_pages = "UPDATE book_pages SET page_num = $page_num, page_col =  $page_col WHERE book_pages_id='{$row->book_pages_id}';";
	mysql_query($sql_book_pages) or die( mysql_error());
	echo "<br/> 1: $sql_book_pages 1:<br/>";
	
	if (!is_null($row->book_comment_id)){
		if (empty($comment)) $comment = unserialize($row->comment_obj);
		//comment objecti fields
		//id  = contains comment id
		//from = an object  access using $comment->from->name
		//message = contains the message of the comment
		//created_time = contains the time this comment was save into the fb server		
		
		$sql_book_comment = '';	
		$fb_dataid = $row->fb_dataid;
		$layout = $arr_page_layout[$row->page_layout];
		//$layout = $arr_page_layout[3];
		

		$container_ht = $layout[$what_col];
		echo "layout " . $layout['max_container_height_col_l'] . "<br>";
		while ($fb_dataid == $row->fb_dataid){
			$fb_dataid = $row->fb_dataid;		
			$comment_ht = getCommentHeight($comment->message,$layout);
			$tmp_page = 0;
			if (isCurrPage ($container_ht, $comment_ht) == 0) {
				//code to update DB. Data on current page				
				//$sql_book_comment = "UPDATE book_comment SET page_num = $page_num, page_col = $page_col WHERE book_info_id=$book_info_id AND fb_dataid='{$row->fb_dataid}';";
				echo "curr_page col $page_col :$page_num: $fb_dataid = $container_ht <br><div style='border: 1px solid black; width: 200; height: " . $comment_ht . "'>"  . $comment->message . "</div><br><br>";
				
				//$what_col = 'max_container_height_col_l';
			}
			else if ($page_col == 1) {
								
				$page_col = 2; 
				//$sql_book_comment = "UPDATE book_comment SET page_num = $page_num, page_col = $page_col WHERE book_info_id=$book_info_id AND fb_dataid='{$row->fb_dataid}';";
				echo "curr_page col $page_col :$page_num: $fb_dataid = $container_ht <br><div style='border: 1px solid black; width: 200; height: " . $comment_ht . "'>"  . $comment->message . "</div><br><br>";
				$what_col = 'max_container_height_col_2';
				$container_ht = $layout[$what_col];
			}
			else {
				$page_num++;
				$what_col = 'max_container_height_col_l';
				$container_ht = $layout[$what_col];
				$page_col = 1;
				// code to update DB. Data goes to next page			
				echo "next_page col $page_col :$page_num: $fb_dataid = $container_ht <br><div style='border: 1px solid black; width: 200; height: " . $comment_ht . "'>"  . $comment->message . "</div><br><br>";
			}
			
			
			$container_ht = $container_ht - $comment_ht;
			$max_chars = getMaxCountOfChars($comment->message,$layout,$page_col);		
			// add here the update comment
			
			$tmp_page = $page_num;		
			//$sql_book_comment = "INSERT INTO temp_page_table (book_info_id, book_pages_id, bp_page_num, bc_page_num) VALUES (".$row->book_info_id.", ".$row->book_pages_id.", 0,".$tmp_page.");";
			$sql_book_comment = "UPDATE book_comment SET page_num = $tmp_page, page_col = $page_col, max_chars = $max_chars WHERE book_info_id=$book_info_id AND book_comment_id='{$row->book_comment_id}';";
			mysql_query($sql_book_comment) or die( mysql_error());
			echo "<br/> 2: $sql_book_comment 2:<br/>";
			$comment = '';
			$row = mysql_fetch_object($result);
			$comment = unserialize($row->comment_obj);
		}	
	}else
		$row = mysql_fetch_object($result);
	$page_num++;
}
mysql_free_result($result);

$execution_time['end'] = get_time();
$totaltime = ($execution_time['end'] - $execution_time['start']); 	
echo "<br/>totaltime : $totaltime";


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

//return 0 if comment length < maxChars, else return the max chars the area can contain
function getMaxCountOfChars($str,$layout,$col){
	$what_col = '';
	if ($col == 1)
		$what_col = 'max_container_height_col_l';
	else
		$what_col = 'max_container_height_col_r';
		
	$str_container = wordwrap($str, $layout['char_per_line'], "<br>");
	$num_lines = substr_count($str_container, '<br>');
	if ($num_lines > ($layout[$what_col] / $layout['height_per_line']))
		return ($layout[$what_col] / $layout['height_per_line']) *  $layout['char_per_line'];
	else 	
		return 0;
}

//return container size in pixel
function getCommentHeight($str,$layout){
	$str_container = wordwrap($str, $layout['char_per_line'], "<br>");
	$num_lines = substr_count($str_container, '<br>');
	return ($layout['height_per_line'] * $num_lines) + $layout['profile_pict_height'] + $layout['comment_border_height'];
}

//compare comments_container to comment_height, 0 for true, -1 for false 
function isCurrPage ($comments_container, $comment_height) {
	$ret_val = 0;
	if ($comments_container < $comment_height) $ret_val = -1;
	return $ret_val;
}

//compute the current time in microsec
function get_time(){
	$mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   return $mtime; 
}

//save the text data to the logfile or create if necessary
function logme($data){
	global $config;
	$file = $config['tools'] . '/logs/page_organizer.log'; 
	$cdate = date('n/j/Y h:i:s a');
	$handle = fopen($file, 'ab');	
	fwrite($handle, "$data => $cdate \n"); 
	fclose($handle); 
}
/*
?>
 "$data => $cdate \n"); 
	fclose($handle); 
}
*/
?>

<?php
include('pdf_creator/mpdf.php');

ini_set("pcre.backtrack_limit","1000000");
ini_set("memory_limit","128M");
ini_set('max_execution_time', 300);

error_reporting(E_ERROR||E_PARSE);

require_once('connect.php');
require_once('config.php');

$book_info_id = $_GET['book_info_id'];
$book_info = get_book_info($book_info_id);

$mpdf=new mPDF('utf-8', array(228.6,177.7),12,2.5);
$mpdf->useSubstitutions=false;
$mpdf->simpleTables = true;
$mpdf->debug = true;

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyleA4.css');
$mpdf->WriteHTML($stylesheet,1); // The parameter 1 tells that this is css/style only and no body/html/text

$html = '<div id="book_cover">
            <div id="book_container">
                <div id="book_cover_right">
                	<h1>'.$book_info->book_name.'</h1>'
					.get_background_cover($book_info_id).
				'</div>               
            </div>
        </div>';

echo $html;
$mpdf->WriteHTML($html);
$mpdf->Output('/storage/www/codebase/apps/devhardcover/tools/book_pdfs/cover_'.$book_info_id.'.pdf','F');
exit;

function get_book_info($book_info_id){
	$book_info_id = false;
	$sql = "SELECT friends_fbid FROM book_cover WHERE book_info_id='$book_info_id'";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_object($result)){
		$book_info_id = $row;		
	}
	mysql_free_result($result);
	return $book_info;
}

function get_background_cover($book_info_id){
	$sql = "SELECT friends_fbid FROM book_cover WHERE book_info_id='$book_info_id'";
	$result = mysql_query($sql);
	
	$x = 0;
	$list = '<ul id="ul_book_friend">';
	while ($row = mysql_fetch_object($result)){
		$fbid = $row->friends_fbid;
		$list .= "<li><img src='http://graph.facebook.com/$fbid/picture'></li>";
	}
	$list .= '</ul>';
	mysql_free_result($result);
	
	return $list;
}
?>
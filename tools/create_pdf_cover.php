<?php
include('pdf_creator/mpdf.php');

ini_set("pcre.backtrack_limit","1000000");
ini_set("memory_limit","128M");
ini_set('max_execution_time', 300);

error_reporting(E_ERROR||E_PARSE);

require_once('connect.php');
require_once('config.php');

$book_info_id = empty($argv[1])?$_GET['book_info_id']:$argv[1];
if (empty($book_info_id)) {
	//logme('empty book info id');
	die;
}
$book_info = get_book_info($book_info_id);

$mpdf=new mPDF('utf-8', array(228.6,177.7),12,2.5,20.32,12.7,12.7,12.7,0,0,'P'); //encoding;page size;font-size;
$mpdf->useSubstitutions=false;
$mpdf->simpleTables = true;
$mpdf->debug = true;

// LOAD a stylesheet
$html = ' 	<style type="text/css">
				div#book_cover {
					margin:25px auto;		
					padding:0;
					width:675px;
					height:525px;
					overflow:hidden;		
					background:#FAFAFA;
					border:1px solid #989898;
					line-height: 1;
					font: 100% "Lucida Sans Unicode", "Lucida Grande",tahoma,verdana,arial,sans-serif;
				}
				table{margin-top:10px;margin-left:115px}
				td{padding-bottom:10px;}
				div#book_container { margin:0 auto; overflow:hidden; padding:2px; }
				div#book_cover_right { width:100%; height:auto; margin:0;}
				div#book_cover_right h1 { font-size:20px; padding:6px; border:1px solid #989898; width:80%; position:relative; margin:50px auto; text-align:center; }				
				div.img_big { position:absolute; top:230;left:357px; border:1px solid #EEE; background:#FAFAFA;padding:3px;}
			</style>';
//$mpdf->WriteHTML($stylesheet); 


$html .= '<div class="img_big"><img src="https://graph.facebook.com/'.$book_info->facebook_id.'/picture?type=large"></div>
		<div id="book_cover">
            <div id="book_container">
                <div id="book_cover_right">
                	<h1>'.$book_info->book_name.'</h1>'
					
					.get_background_cover($book_info_id).
				'</div>               
            </div>
        </div>';
echo $html;
$mpdf->WriteHTML($html);
//$mpdf->Output();
logme($book_info_id);
$mpdf->Output('/storage/www/codebase/apps/devhardcover/tools/pdfs/cover_'.$book_info_id.'.pdf','F');
exit;


////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_book_info($book_info_id){
	$book_info = false;
	$sql = "SELECT * FROM book_info WHERE book_info_id='$book_info_id'";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_object($result)){
		$book_info = $row;		
	}
	mysql_free_result($result);
	return $book_info;
}

function get_background_cover($book_info_id){
	$sql = "SELECT friends_fbid FROM book_cover WHERE book_info_id='$book_info_id' limit 24";
	$result = mysql_query($sql);
	$total = mysql_num_rows($result);
	
	$table = '<table width="465" border="0" cellspacing="0" cellpadding="0">';	
	$x = 0;
	while ($x<4){
		$x++;
		$row = mysql_fetch_object($result);
		$fbid_1 = $row->friends_fbid;
		$row = mysql_fetch_object($result);
		$fbid_2 = $row->friends_fbid;
		$row = mysql_fetch_object($result);
		$fbid_3 = $row->friends_fbid;
		$row = mysql_fetch_object($result);
		$fbid_4 = $row->friends_fbid;
		$row = mysql_fetch_object($result);
		$fbid_5 = $row->friends_fbid;
		$row = mysql_fetch_object($result);
		$fbid_6 = $row->friends_fbid;
		
		$tds .= "<tr>
				 <td><img style='background:#F9F9F9;padding:3px;border:1px solid #EEEEEE;' src='http://graph.facebook.com/$fbid_1/picture'></td>		
				 <td><img style='background:#F9F9F9;padding:3px;border:1px solid #EEEEEE;' src='http://graph.facebook.com/$fbid_2/picture'></td>
				 <td><img style='background:#F9F9F9;padding:3px;border:1px solid #EEEEEE;' src='http://graph.facebook.com/$fbid_3/picture'></td>
				 <td><img style='background:#F9F9F9;padding:3px;border:1px solid #EEEEEE;' src='http://graph.facebook.com/$fbid_4/picture'></td>
				 <td><img style='background:#F9F9F9;padding:3px;border:1px solid #EEEEEE;' src='http://graph.facebook.com/$fbid_5/picture'></td>
				 <td><img style='background:#F9F9F9;padding:3px;border:1px solid #EEEEEE;' src='http://graph.facebook.com/$fbid_6/picture'></td>
				 </tr>";
	}
	$table .= $tds . '</table>';
	mysql_free_result($result);
	
	return $table;
}

function logme($data){
	global $config;
	$file = $config['tools'] . '/logs/create_pdf_cover.log'; 
	$cdate = date('n/j/Y h:i:s a');
	$handle = fopen($file, 'ab');	
	fwrite($handle, "$data => $cdate \n"); 
	fclose($handle); 
}
?>
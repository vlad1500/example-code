<?php
include('pdf_creator/mpdf.php');

ini_set("pcre.backtrack_limit","1000000");
ini_set("memory_limit","128M");
ini_set('max_execution_time', 300);

error_reporting(E_ERROR||E_PARSE);

//require_once('connect.php');
require_once('config.php');

$book_info_id = $_GET['book_info_id'];
$book_info = get_book_info($book_info_id);

$mpdf=new mPDF('utf-8', array(228.6,177.7),
							12,	// font size - default 0
							2.5,// default font family
							20.32,// margin_left
							12.7, // margin right
							12.7,// margin top
							12.7,// margin bottom
							0,// margin header
							0,// margin footer
							'P'); //encoding;page size;font-size;
$mpdf->useSubstitutions=false;
$mpdf->simpleTables = true;
$mpdf->debug = true;
$mpdf->allow_output_buffering = true;

$stylesheet = file_get_contents('css/content.css');
$mpdf->WriteHTML($stylesheet,1);

$html .= '<table width="675">
			<tr>
				<td width="337" valign="top">
					<img  height="150" src="images/tobe_commented.png">
				</td>
				<td  width="337" valign="top">
					<table width="337">						
						<tr class="tblrow">
							<td>
								<table class="tblcomment">
									<tr style="padding:0px;margin:0px;">
										<td valign="top" width="30">										
										<img style="padding:5px;" width="26" height="25" src="images/profilepic.png">									
										</td>
										<td valign="top" class="comment_by">Dennis Toribio</td>
									</tr>	
									<tr >
										<td colspan="2" style="padding-left:5px;">								
										<div class="comment_msg">kinsay magulang???</p>
										<p class="comment_date">03/15/10</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr class="tblrow">
							<td>
								<table class="tblcomment">
									<tr style="padding:0px;margin:0px;">
										<td valign="top" width="30">										
										<img style="padding:5px;" width="26" height="25" src="images/profilepic.png">									
										</td>
										<td valign="top" class="comment_by">Dennis Toribio</td>
									</tr>	
									<tr >
										<td colspan="2" style="padding-left:5px;">								
										<p class="comment_msg">kinsay magulang???</p>										
										<div class="comment_date">03/15/10</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>							
					</table>
				</td>				
			</tr>
		</table><pagebreak />';
$html .= '<table width="675">
			<tr>
				<td width="337" valign="top">
					<img src="images/tobe_commented.png">
				</td>
				<td  width="337" valign="top">
					<table width="337">
						<tr class="tblrow" width="100%">
							<td>
								<div class="commentbox">
									<div style="padding:5px;"><img width="26" height="25" src="images/profilepic.png"></div>
									<p class="comment_by">Dennis Toribio</p>
									<p class="comment_msg">kinsay magulang???</p>
									<p class="comment_date">03/15/10</p>
								</div>
							</td>
						</tr>
						<tr class="tblrow" >
							<td>
								<div class="commentbox">
									<img width="26" height="25" style="float:left;padding:0 5px 0 0;" src="images/profilepic.png">
									<p class="comment_by">Dennis Toribio</p>
									<p class="comment_msg">kinsay magulang???</p>
									<p class="comment_date">03/15/10</p>
								</div>
							</td>
						</tr>
						<tr class="tblrow">
							<td>
								<table class="tblcomment">
									<tr style="padding:0px;margin:0px;">
										<td valign="top" width="30">										
										<img style="padding:5px;" width="26" height="25" src="images/profilepic.png">									
										</td>
										<td valign="top" class="comment_by">Dennis Toribio</td>
									</tr>	
									<tr >
										<td colspan="2">								
										<p class="comment_msg">kinsay magulang???</p>
										<p class="comment_date">03/15/10</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>						
					</table>
				</td>
			</tr>
		</table>';		
//echo $html;
$mpdf->WriteHTML($html,2);
$mpdf->Output();
//$mpdf->Output('/storage/www/codebase/apps/devhardcover/tools/book_pdfs/cover_'.$book_info_id.'.pdf','F');
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


?>
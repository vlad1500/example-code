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
	logme('empty book info id');
	die;
}else{
	logme("updating book page number for book info id = $book_info_id");
}
$book_info = get_book_info($book_info_id);
logme($book_info_id);
$mpdf=new mPDF('utf-8', array(228.6,177.7),
							12,	// font size - default 0
							2.5,// default font family
							24.13,// margin_left	0.95inches
							12.7, // margin right	0.5inches
							12.7,// margin top		0.5inches
							12.7,// margin bottom	0.5inches
							0,// margin header
							0,// margin footer
							'P'); //encoding;page size;font-size;
$mpdf->useSubstitutions=false;
$mpdf->simpleTables = true;
$mpdf->debug = true;
$mpdf->allow_output_buffering = true;

$stylesheet = file_get_contents('css/content.css');
$mpdf->WriteHTML($stylesheet,1);

$sql = sprintf("SELECT book_info_id,fb_dataid, fbdata, page_layout,page_num,connection,page_col FROM book_pages 
				WHERE book_info_id=%d  ORDER BY page_num",
				mysql_real_escape_string($book_info_id));
$query = mysql_query($sql);
	
if (mysql_errno()) {
		die(mysql_errno().': '.mysql_error().'; '.$query."\n");
}else{
	while ($book_page=mysql_fetch_object($query)){
		$page = unserialize ($book_page->fbdata);
		
		$sql_comment = sprintf("SELECT bc.* FROM  book_comment bc 
				WHERE bc.book_info_id=%d AND bc.fb_dataid='%s' AND bc.status!='deleted' ",
				mysql_real_escape_string($book_info_id),
				mysql_real_escape_string($book_page->fb_dataid));
		$query_comment = mysql_query($sql_comment);
		
		$is_continuation = 0;
		$page_num = $book_page->page_num;
		$comments = '';
		$cont_page_counter = 0;
		$comments_continuation = array();	
		while ($fb_comment=mysql_fetch_object($query_comment)){
			$comment = unserialize($fb_comment->comment_obj);
			$profile_pict =  'https://graph.facebook.com/'.$comment->from->id.'/picture?type=small';
			$fb_date = date("m/d/y", strtotime($comment->created_time));									
			$comments_name = (string) $comment->from->name;
			$comments_message = (string) $comment->message;
			
			if ($page_num!=$fb_comment->page_num) {
				$is_continuation=1;
				$page_num = $fb_comment->page_num;
				$cont_page_counter++;
			}

			if ($is_continuation==0){
				switch ($book_page->page_layout){
					case 1:
					case 2:
						$comments .= '
							<table width="337" >
								<tr class="tblrow">
									<td>
										<table class="tblcomment">
											<tr>
												<td valign="top" width="30">										
													<img style="padding:5px;" width="26" height="25" src="'.$profile_pict.'">									
												</td>
												<td valign="top" class="comment_by" style="padding-top:5px;">'.$comments_name.'</td>
											</tr>	
											<tr >
												<td colspan="2" style="padding-left:5px;">								
													<p class="comment_msg">'.str_replace('/','',$comments_message).'</p>
												</td>
											</tr>
											<tr>
												<td colspan="2" style="padding:5px 0 0 5px;">
													<p class="comment_date">'.$fb_date.'</p>	
												</td>
											</tr>
										</table>
									</td>
								</tr>							
							</table>';
						break;
					case 3:
						$comments .= '
							<table width="675" >
								<tr class="tblrow">
									<td>
										<table class="tblcomment">
											<tr>
												<td valign="top" width="30">										
													<img style="padding:5px;" width="26" height="25" src="'.$profile_pict.'">									
												</td>
												<td valign="top" class="comment_by" style="padding-top:5px;">'.$comments_name.'</td>
											</tr>	
											<tr >
												<td colspan="2" style="padding-left:5px;">								
													<p class="comment_msg">'.str_replace('/','',$comments_message).'</p>
												</td>
											</tr>
											<tr>
												<td colspan="2" style="padding:5px 0 0 5px;">
													<p class="comment_date">'.$fb_date.'</p>	
												</td>
											</tr>
										</table>
									</td>
								</tr>							
							</table>';
						break;
				}
			}else{
				//this is for those comments that exceeds the page		
				$comments_continuation[$cont_page_counter] .= '	
					<table width="337" >
							<tr class="tblrow">
								<td>
									<table class="tblcomment">
										<tr>
											<td valign="top" width="30">										
												<img style="padding:5px;" width="26" height="25" src="'.$profile_pict.'">									
											</td>
											<td valign="top" class="comment_by" style="padding-top:5px;">'.$comments_name.'</td>
										</tr>	
										<tr >
											<td colspan="2" style="padding-left:5px;">								
												<p class="comment_msg">'.str_replace('/','',$comments_message).'</p>
											</td>
										</tr>
										<tr>
											<td colspan="2" style="padding:5px 0 0 5px;">
												<p class="comment_date">'.$fb_date.'</p>	
											</td>
										</tr>
									</table>
								</td>
							</tr>							
						</table>';								
			}//end if
		}//end while
		mysql_free_result($query_comment);
		
		if ($book_page->connection=='album_photos' || $book_page->connection=='photos'){
			$tobe_commented = '<img src="'.$page->images[0]->source.'">';
		}else{
			$tobe_commented = '<p>'.$page->message.'</p>';
		}
		
		//choose a page layout
		switch ($book_page->page_layout){
			case 1:
				$html = '<table width="675"  >
							<tr>
								<td width="337" valign="top">'.
									$tobe_commented									
								.'</td>
								<td  width="337" valign="top" >'.
									$comments
								.'</td>				
							</tr>
						</table><pagebreak />';
				break;
			case 2:			
				$html = '<table width="675"  >
							<tr>
								<td width="337" valign="top">'.
									$comments
								.'</td>
								<td  width="337" valign="top" >'.
									$tobe_commented
								.'</td>				
							</tr>
						</table><pagebreak />';											
				break;
			case 3:	
				$html = '<table width="675"  >
							<tr>
								<td width="675" valign="top" align="center">'.
									$tobe_commented
								.'</td>
							</tr>
							<tr>
								<td  width="675" valign="top" >'.
									$comments	
								.'</td>				
							</tr>
						</table><pagebreak />';						
				break;
			case  4:				
				break;
			case 5:										
				break;											
		}//end switch
		//echo $html;
		$mpdf->WriteHTML($html,2);

		//display page continuation which is all comments
		$cont_page_counter=0;
		$comments_only = '';
		$total_comments_only = count($comments_continuation);
		for ($x=1;$x<=$total_comments_only;$x++){
			$comment_left = $comments_continuation[$x];
			$x++;
			$comment_right = $comments_continuation[$x];
			
			$comments_only .= '	<table width="675"  >
									<tr>
										<td width="337" valign="top">'.
											$comment_left
										.'</td>
										<td  width="337" valign="top" >'.
											$comment_right
										.'</td>				
									</tr>
								</table><pagebreak />';				
						
		}//end for
		if ($comments_only){
			//$comments_only = substr($comments_only,0,-13);
			//echo $comments_only;
			$mpdf->WriteHTML($comments_only,2);		
		}
	}//end while
	mysql_free_result($query);
}//end if

/*						
$html .= '<table width="675"  >
			<tr>
				<td width="337" valign="top">
					<img  height="150" src="images/tobe_commented.png">
				</td>
				<td  width="337" valign="top" >
					<table width="337" >
						<tr class="tblrow">
							<td>
								<table class="tblcomment">
									<tr>
										<td valign="top" width="30">										
											<img style="padding:5px;" width="26" height="25" src="images/profilepic.png">									
										</td>
										<td valign="top" class="comment_by" style="padding-top:5px;">Dennis Toribio</td>
									</tr>	
									<tr >
										<td colspan="2" style="padding-left:5px;">								
											<p class="comment_msg">kinsay magulang???</p>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="padding:5px 0 0 5px;">
											<p class="comment_date">03/15/10</p>	
										</td>
									</tr>
								</table>
							</td>
						</tr>							
					</table>
				</td>				
			</tr>
		</table><pagebreak />';
*/	
//echo $html;	
$html = substr($html,0,-13);
$mpdf->WriteHTML($html,2);
//$mpdf->Output();
//logme($book_info_id);
$mpdf->Output('/storage/www/codebase/apps/devhardcover/tools/pdfs/content_'.$book_info_id.'.pdf','F');
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

function logme($data){
	global $config;
	$file = $config['tools'] . '/logs/create_pdf_content.log'; 
	$cdate = date('n/j/Y h:i:s a');
	$handle = fopen($file, 'ab');	
	fwrite($handle, "$data => $cdate \n"); 
	fclose($handle); 
}
?>
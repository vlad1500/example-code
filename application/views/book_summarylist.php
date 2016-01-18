<?php 
	//dennis:load this helper in the controller section
	$this->load->helper('url');
	
 	if(!isset($fbid))
 		//retrieve cookie variables in the controller and not here
 		$fbid = $_COOKIE['hardcover_fbid'];
 		
 		//dennis: there should be no query for model inside views; move this to the controller and pass the variable
		$album_new_contents = $this->main_model->get_bookpages_ready_to_share($fbid); 
?>
<?php
	
	$link=$_SERVER["REQUEST_URI"];
	$arr=explode('/',$link);

?>
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/lightbox/pop.css" type="text/css">
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/groupeditstyle.css" type="text/css" />
<title>Hardcover - <?php echo $arr['3']; ?></title>

<?php
	//dennis: there should be no logic inside the views
	
	//$fbuser_username = $user_details->fb_username;
	//$fbuser = getFacebookUserDetails($_COOKIE['hardcover_token']);
	
	//marlo edit starts here 12/28/2012
	//$deleted_albums = $this->Delete->getDeletedAlbums($fbid);
	$photos_share_sent = $this->GE->getShareSentInfo($fbid);
	//$albums_deleted = (array)$deleted_albums;
	
	//function deletedBook($parents, $searched) {
	//	if (empty($searched) || empty($parents)) {
	//		return false;
	//	}
	//	 
	//	foreach ($parents as $key => $value) {
	//		$exists = true;
	//		foreach ($searched as $skey => $svalue) {
	//			$parents[$key] = (array)$parents[$key];
	//			$exists = ($exists && isset($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
	//		}
	//		if ($exists) return $key;
	//	}
	//	return false;
	//}
	//marlo edit ends here 12/28/2012
?>

<div class="call-to-action clearfix">
	<input id="js-album-create-name" type="button" value="Create New Album" class="btn btn-orange" />
	<!--<a href="#myModal" id="js-newlightbox" class="btn btn-orange pull-left">Create New Album</a>-->
	<ul>
		<li><a href="#group_edit" class="js-books-tables">Collaborative Books</a></li>
		<li><a href="#album_user" class="js-books-tables">Books Friends Created</a></li>
		<li class="last"><a href="#album_table" class="js-books-tables">Books I've Created</a></li>
	</ul>
</div>

<div id="group_edit">
	<?php if (1) { ?>
		<div id="photos_added" >
			<h3 class="s6">Collaborative Books I Created <span style="font-weight:normal"> - and invited friends to contribute</span></h3>
			<table id="photo_approved_table" cellspacing="0" cellpadding="0" border="0" width="100%" class="hc-table">
				<thead class="fixed">
					<tr>
						<th width="40%" class="left-edge">Album Name</th>
						<th width="20%">Total Views</th>
						<th width="20%">New Items</th>
						<th width="20%" class="right-edge last-child">Date Added</th>
					</tr>
				</thead>
				<tbody class="scrollable">
					<?php					 
					if ($album_new_contents) {  
						for ($i = 0; $i < count($album_new_contents); $i++) {
							$album_new_contents[$i] = (array)$album_new_contents[$i];
							//if (deletedBook($albums_deleted, array("book_info_id" => $album_new_contents[$i]["book_info_id"])) === FALSE) {
								$book_name = $album_new_contents[$i]['book_name'];
								$new_items = $album_new_contents[$i]['new_items'];
								$post_date = date("m/d/Y", strtotime($album_new_contents[$i]['fbdata_postedtime']));
								$bii = $album_new_contents[$i]['book_info_id'];
								echo '
									<tr>
										<td class="first"><a rel="prettyPhoto[iframes]" href="'.$this->config->item('base_url').'/main/new_album_contents_page/'. $bii .'?iframe=true&width=825&height=500" rel="#overlay" title="Click to Approve Photos Added by Others in your album '. $book_name .'">'. $book_name .'</a></td>
										<td>0</td>
										<td>'. $new_items .'</td>
										<td>'. $post_date .'</td>
									</tr>
								';
							//}
						}
					} else {
						echo "<tr><td colspan='4'>No photos added by others</td></tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	<?php }

	if ($photos_share_sent) {  ?>
		<div id="photos_share_sent" style="display:none">
			<h3 class="s6">Group Books I’ve Created <span style="font-weight:normal">(Photos Added By Others)</span></h3>
			<table id="photo_approval_table" cellspacing="0" cellpadding="0" border="0" width="100%" class="hc-table">
				<thead class="fixed">
					<tr>
						<th width="30%" class="left-edge">Friend Name</th>
						<th width="40%" class="first">Album Name</th>
						<th width="30%" class="right-edge last-child">Date Requested</th>
					</tr>
				</thead>
				<tbody class="scrollable">
					<?php
						for ($i = 0; $i < count($photos_share_sent); $i++) {
							$photos_share_sent[$i] = (array)$photos_share_sent[$i];
							//if (deletedBook($albums_deleted, array("book_info_id" => $photos_share_sent[$i]["book_info_id"])) === FALSE) {
								$friend_name = $photos_share_sent[$i]["friend_name"];
								$album_name = $photos_share_sent[$i]["album_name"];
								$date_requested = date("m/d/Y", strtotime($photos_share_sent[$i]["date_requested"]));
								$book_info_id = $photos_share_sent[$i]["book_info_id"];
								echo '
									<tr>
										<td class="friend">'. $friend_name .'</td>
										<td class="first"><a href="images_uploader/'. $book_info_id .'/'. $fbid .'" rel="#overlay" title="Click to Approve '. $friend_name .' to Add Photos in your album '. $album_name .'">'. $album_name .'</a></td>
										<td>'. $date_requested .'</td>
									</tr>
								';
							//}
						}
					?>
				</tbody>
			</table>
		</div>
	<?php } ?>
	</div>

	<div id="album_summary">
		<div id="album_created">
			<h3 class="s6">Books Friends Created  <span style="font-weight:normal"> - and invited me to contribute</span></h3>
			<div id="album_user">
				<table class="hc-table">
					<tr>
						<th class="left-edge">Book Name</th>
						<th>Book Creator</th>
						<th class="right-edge last-child">Shared Url</th>
					</tr>
					<?php foreach($dashboard_detils as $k=>$v){   ?>
					<tr>
						<td><?php echo $v->book_name; ?></td>
						<td><?php echo $v->book_owner_facebook_name; ?></td>
						<td><a target="_blank" href="<?php echo base_url(); ?>books/<?php echo str_replace(" ", "_", $v->book_owner_facebook_name); ?>/<?php echo str_replace(" ", "_", $v->book_name); ?>/<?php echo $v->friends_fbid;?>">Click here view</a></td>
					</tr>
					<?php }?>
				</table>
			</div>
			<?php
			if (!$booklist) {
				echo "<!--<img src=\"". $this->config->item("image_url") ."/arrow.jpg\" width=\"20%\" height=\"20%\" />--><div align=center><h3><br />Click \"Create New Album\" button to make your newest digital album...</h3></div>";
			} else {
			?>
				<h3 class="s6">Books I’ve Created</h3>
				<table id="album_table" class="hc-table">
					<thead>
						<tr>
							<th width="28%" class="left-edge">Book Name / edit</th>
				     		<th width="20%">View Book</th>
							<th width="22%">Share</th>
							<th width="10%">Pages</th>
							<th width="10%">Last Update</th>
							<th width="10%" class="right-edge last-child">Delete</th>
						</tr>
					</thead>
					<tbody>
				<?php
					foreach($booklist as $book_info){                         
						$last_save = date('m/d/Y',strtotime($book_info->modify_date));
						$pdf_href_file = $this->config->item('base_url') .'/tools/pdfs/content_'.$book_info->book_info_id.'.pdf';
						$pdf_dir_file = $this->config->item('tools') .'/pdfs/content_'.$book_info->book_info_id.'.pdf';
					
						$bkid = str_replace(" ", "", $book_info->book_name);//$book_info->book_info_id + $this->config->item('book_info_id_key');
						$unique_href_file = $this->config->item('base_url').'/books/'.$book_info->fb_username.'/'.$bkid;
						$unique_dir_file = $this->config->item('unique_url').'/'.$book_info->fb_username.'_'.$bkid.'.htm';

						if (file_exists($unique_dir_file))
							$unique_url_link = '<a target="_blank" href="'.$unique_href_file.'" >Unique URL</a>';
						else
							$unique_url_link = 'no unique url';
							
							if (file_exists($pdf_dir_file))
								$pdf_link = '<a class="book_view_d"  target="_blank" href="'.$pdf_href_file.'" ><img src="'. $this->config->item('image_url') .'/HardCover_pdf_icon.png"/></a>';
							else
								$pdf_link = 'no pdf';
								$url_ref = str_replace('https','https',$this->config->item('base_url')).'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$book_info->book_name));
		
								echo '
									<tr>                                                                           
										<td class="first"><a target="_blank" href="#" id="'. $book_info->book_info_id .'" class="book_summary_class" title="Click to Edit your album '. $book_info->book_name .'">'. $book_info->book_name .'</a></td>						
										<td><a class="book_view_d"  target="_blank" href="'.$url_ref.'">view</a></td>
								
										<!--<td>'. $pdf_link .'</td>-->
										<td>
							 				<div class="book_icons">
						            			<a onclick="javascript:postToWall(\''.$this->config->item('base_url').'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$book_info->book_name)).'\',\''.strtolower(str_replace(' ','_',$book_info->book_name)).'\',\''.$book_info->book_info_id.'\'); return false;"><img title="Facebook" alt="facebook" src="'. $this->config->item('image_url') .'/facebook.png"></a>
											</div>
										</td>
		
										<td>'. $book_info->total_pages .'</td>
										<td>'. $last_save .'</td>
										<td><a href="#" id="delete" class="'. $book_info->book_info_id .'|'. trim($book_info->book_name) .'" title="Delete '. trim($book_info->book_name) .'?" ><img src="'. $this->config->item('image_url') .'/delete_hc.png" alt="Delete '. trim($book_info->book_name) .'?" /></a></td>
								</tr>';
					}
			?>
			</tbody>
		</table>
		<?php } ?>
	</div>			
</div>
		
<!-- overlayed element -->
<div class="apple_overlay" id="overlay">
	<!-- the external content is loaded inside this tag -->
	<div class="contentWraps"></div>
</div>

<script src="<?php echo $this->config->item("js_url"); ?>/book_summarylist.js"></script>
<script>
	function postToWall(url, book_name, book_info_id) {
		
		// console.log(url); - checker
		// send message to friends code start
				  
		FB.init({appId: '<?=$this->config->item('fb_appkey')?>', xfbml: true, cookie: true});
	
		FB.ui(
			{
				method: 'feed',
				name: book_name,
				link: url,
				picture: '<?=$this->config->item('image_url').'/hardcover-logo-thumb.png'; ?>',
				caption: '<?=$this->config->item('app_subtitle');?>',
				description: '<?=$this->config->item('app_description'); ?>'
			},
			function(response) {
				console.log(response);  	
				if (response && response.post_id) {
					alert('Post was published.');
				} else {
					alert('Post was not published.');
				}
			}
		);  
	}
</script>
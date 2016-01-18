<?php
	//dennis:load this helper in the controller section
	$this->load->helper('url');
	
 	if(!isset($fbid)) 		
 		$fbid = $_COOKIE['hardcover_fbid'];
    
//TODO to be deleted
//josh mod to add collabs
if ($album_new_contents) {
    $book_collab = "";
    for ($i = 0; $i < count($album_new_contents); $i++) {
        $album_new_contents[$i] = (array)$album_new_contents[$i];
        
        $book_name = $album_new_contents[$i]['book_name'];
        $new_items = $album_new_contents[$i]['new_items'];
        $post_date = date("m/d/Y", strtotime($album_new_contents[$i]['fbdata_postedtime']));
        $bii = $album_new_contents[$i]['book_info_id'];
        $book_collab[$book_name]["link"] = '<a rel="prettyPhoto[iframes]" href="'.$this->config->item('base_url').'/main/new_album_contents_page/'. $bii .'?iframe=true&width=825&height=500" rel="#overlay" title="Click to Approve Photos Added by Others in your album '. $book_name .'">'. $book_name .'</a>';
        if($new_items)
            $book_collab[$book_name]["count"] = $new_items;
        else
            $book_collab[$book_name]["count"] = 0;
        
    }
}       
?>
<script type="text/javascript">
function pretty_close(){
	$.prettyPhoto.close(); return false;  
}
$(document).ready(function(){
    $('a[rel^="prettyPhoto"]').prettyPhoto({
	theme:'facebook',
	default_width: 600,
		default_height: 500,
		social_tools: false
        // any configuration options as per the online documentation.
    });
            jQuery('.fb-share').on("click",function(event){
                var parentUL = $(this).parent().parent().parent();                
                var page_number = parentUL.attr('page_number');                
                event.preventDefault();
                event.stopPropagation();
                var page_image = getCurrentImg(page_number);                
                var testThis = page_image.split("src=");    
                console.log(testThis);          
                if(testThis[0] != page_image){
                    page_image = testThis;                
                    page_image = page_image[1].split("&h=");
                    page_image = page_image[0];    
                } 
                var title = parentUL.attr("title");
                var desc = "A HardCover book";
                var linked = parentUL.attr("u_url");
                    app_id = $("#pp_header").attr("app_id");	
                    FB.init({appId: app_id, xfbml: true, cookie: true});
                    FB.ui({
                        method: 'feed',
                        name: title,
                        link: linked,
                        picture: page_image,
                        description: desc
                    },
                        function(response) {                            
                            if (response && response.post_id) {
                                alert('Post was published.');
                            } else {
                                alert('Post was not published.');
                            }
                        }
                    );                              
            }); 
            jQuery('.twitter-share').click(function(event){
                var parentUL = $(this).parent().parent().parent();
                var page_number = parentUL.attr('page_number');
                event.preventDefault();
                event.stopPropagation();                
                var title = parentUL.attr("title");
                var message = "A HardCover book";
                var linked = parentUL.attr("u_url");
                var link = 'http://twitter.com/intent/tweet?url='+getCurrentImg(page_number)+'&text='+title+'. '+message+' '+encodeURI(getCurrentImg(page_number))+' '+encodeURI(linked)+'&hashtags=hardcover';                                   
                    newWindow = window.open(link,'_blank','width=700,height=260'); 
                    newWindow.focus();
            });
            jQuery('.pinterest-share').click(function(event){
                var parentUL = $(this).parent().parent().parent();
                var page_number = parentUL.attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var title = parentUL.attr("title");
                var message = "A HardCover book";
                var linked = parentUL.attr("u_url");
                var link = '//www.pinterest.com/pin/create/button/?url='+encodeURI(linked)+'&media='+encodeURI(getCurrentImg(page_number))+'&description='+title+'. '+message;
                    newWindow = window.open(link,'_blank','width=700,height=260');                    
                    newWindow.focus();
            }); 
            jQuery('.email-share').click(function(event){
                var parentUL = $(this).parent().parent().parent();
                var page_number = parentUL.attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var linked = parentUL.attr("u_url");
                var link = 'mailto:?subject=HardCover&amp;body=Check out my life in HardCover.'+encodeURI(linked);                
                    newWindow = window.open(link,'_parent','width=700,height=260');                    
                    newWindow.focus();
            }); 
            function getCurrentImg(page_number) {
                return $(".bookImage"+page_number).attr('src');
            }
});
</script>
<style>
.media--booklisting img {
    width: 200px;
}
.shareButtons img{
    width: 20px;
}
</style>
<div style="display:none;">
<?php
//var_dump($booked_info['data']);
?>
</div>
<div class="row" user_id="<?=$fbid ?>">
	<div class="col-sm-6">
		<a href="#" id="js-create-book" class="btn btn-orange">Create New Book</a>
	</div>
	<div class="col-sm-6"></div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="box">
        	<div style="display:none;"><?php //print_r($booklist); ?></div>
       		<?php
                    $book_count = 0;
                    $user_books_elem = "";
					foreach($booklist as $book_info){
                        $isPublish = $book_info->publish;
                        if($book_info->for_approval>0){
                            $link_collab = '<a rel="prettyPhoto[iframes]" href="/main/new_album_contents_page/'. $book_info->book_info_id .'?iframe=true&width=825&height=500" rel="#overlay" title="Click to Approve Photos Added by Others in your album '. $book_info->book_name .'">Content Waiting Approval ('.$book_info->for_approval.')</a>';
                            $count_collab = $book_collab[$book_info->book_name]["count"];
                        } else {
                            $link_collab = '<a href="javascript:void(0);">No Content Waiting Approval</a>';
                            $count_collab = 0;
                        }
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
                                if($isPublish)
								    $url_ref = 'href="'.$this->config->item('base_url').'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$book_info->book_name)).'"';
                                else   
                                    $url_ref = 'href="javascript:void(0);" style="color:#ccc;cursor: default;"';
                                if($book_count == 0)$first = 'first';
                                else $first = '';
                                
                                //$front_covered = $book_info->booked_info["data"]->front_cover_page;
                                $front_covered = $book_info->front_cover_location;
                                
                                if($front_covered == "" || $front_covered=='NULL')$cover_url = $this->config->item('image_url')."/196x144.png";
                                else $cover_url = $front_covered;
                                
                                //we will use timthumb for FB image use as cover
                                if (strpos($cover_url,'.fbcdn.net')===true)
                                    $cover_url = "/timthumb.php?src=$cover_url&h=144&w-196";
                                else
                                    $cover_url = str_replace('http://',PROTOCOL.'://',$cover_url);
                                
                                if ($book_info->ghost_writer_id===$fb_user->facebook_id){									
									$book_subtext = '<span class="red_text">Ghost Writer</span>';
									$delete_link = '';									
								}elseif ($book_info->is_chapter_user==='1'){
								    $book_subtext = '<span class="red_text">Chapter User</span>';
								    $delete_link = '';
								}else{
									$book_subtext = '';
									$delete_link = '<li><a href="#" id="delete" class="'. $book_info->book_info_id .'|'. trim($book_info->book_name) .'" title="Delete '. trim($book_info->book_name) .'?">Delete</a></li>';									
								}
								
                                
								$user_books_elem .= '
								                <li class="box__item '.$first.'">
													<div class="media media--booklisting">
													  <a target="_blank" href="#" id="'. $book_info->book_info_id .'" class="book_summary_class pull-left">
													    <img class="media-object bookImage'.$book_count.'" src="'.$cover_url.'" alt="" class="img-responsive">
													  </a>
													  <div class="media-body">
													    <h3 class="h4 media-heading">'.$book_info->book_name.'</h3>                		
								                		'.$book_subtext.'
													    <ul class="list list-inline strong clearfix">
													    	<li class="first-child"><a target="_blank" href="#" id="'. $book_info->book_info_id .'" class="book_summary_class" title="Click to Edit your album '. $book_info->book_name .'">Edit</a></li>
													    	<li><a target="_blank" '.$url_ref.'>View</a></li>
													    	'.$delete_link.'
													    </ul>
								
													    <ul class="list">
													    	<li>'. $book_info->total_pages .' page(s)</li>
													    	<li>'. $last_save .'</li>
													    	<li>New Photos ('.$count_collab.' friends)</li>
													    	<li>'.$link_collab.'</li>
													    </ul>
								
													    <ul class="list list-inline clearfix shareButtons" page_number="'.$book_count.'" title="'.$book_info->book_name.'" u_url="'.$url_ref.'">
													    	<li class="first-child">Share</li>
													    	<li>
													    		<span class="st_facebook" displaytext=""><a href="javascript:void(0);" class="fb-share"><img src="'.$this->config->item('base_url').'/images/facebook.png" alt="Facebook"></a></span>
													    	</li>
													    	<li>
													    	    <span class="st_twitter" displaytext=""><a href="javascript:void(0);" class="twitter-share"><img src="'.$this->config->item('base_url').'/images/twitter.png" alt="Twitter"></a></span>  	
													    	</li>
								                            <li>
								                                <span class="st_pinterest" displaytext=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="'.$this->config->item('base_url').'/images/pinterest.png" alt="Pinterest"></a></span>
								                            </li>
								                            <li>
								                                <span class="st_googleplus" displaytext=""></span><span class="st_email" displaytext=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="'.$this->config->item('base_url').'/images/mail.png" alt="mail"></a></span>
								                            </li>
													    </ul>
								
													  </div>
													</div>
												</li>'; 
                                $book_count++;   
                                unset($book_info);                            
					}
			?>
			<h3 class="h5 box__title">My Books (<?php echo $book_count; ?>)</h3>
			<ul class="box__content">
				<?php echo $user_books_elem; ?>
			</ul>
		</div>
	</div><!--End col-lg-6 -->
	<div class="col-sm-6">
		<div class="box">
            <div style="display:none;"><?php //print_r($dashboard_detils); ?></div>
            <?php
                    $book_count = 0;
                    $friends_books_elem = "";
					foreach($dashboard_detils as $k=>$v){
                        $isPublish = $book_info->publish;
						$last_save = date('m/d/Y',strtotime($v->modify_date));
						$pdf_href_file = $this->config->item('base_url') .'/tools/pdfs/content_'.$v->book_info_id.'.pdf';
						$pdf_dir_file = $this->config->item('tools') .'/pdfs/content_'.$v->book_info_id.'.pdf';
					
						$bkid = str_replace(" ", "", $v->book_name);
						$unique_href_file = $this->config->item('base_url').'/books/'.$v->fb_username.'/'.$bkid;
						$unique_dir_file = $this->config->item('unique_url').'/'.$v->fb_username.'_'.$bkid.'.htm';

						if (file_exists($unique_dir_file))
							$unique_url_link = '<a target="_blank" href="'.$unique_href_file.'" >Unique URL</a>';
						else
							$unique_url_link = 'no unique url';
							
							if (file_exists($pdf_dir_file))
								$pdf_link = '<a class="book_view_d"  target="_blank" href="'.$pdf_href_file.'" ><img src="'. $this->config->item('image_url') .'/HardCover_pdf_icon.png"/></a>';
							else
								$pdf_link = 'no pdf';
                                if($isPublish)
                                    $url_ref = 'href="'.$this->config->item('base_url').'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$v->book_name)).'"';
                                else   
                                    $url_ref = 'href="javascript:void(0);" style="color:#ccc;cursor: default;"';								 
                                if($book_count == 0)$first = 'first';
                                else $first = '';  
                                
                                //$front_covered = $v->booked_info["data"]->front_cover_page;
                                $front_covered = $v->front_cover_location;
                                
                                if($front_covered == "" || $front_covered=='NULL')$cover_url = $this->config->item('image_url')."/196x144.png";
                                else $cover_url = $front_covered;
                                
                                //we will use timthumb for FB image use as cover
                                if (strpos($cover_url,'.fbcdn.net')===true)
                                    $cover_url = "/timthumb.php?src=$cover_url&h=144&w-196";
                                else
                                    $cover_url = str_replace('http://',PROTOCOL.'://',$cover_url);
                                
                                
								$friends_books_elem .= '
                <li class="box__item '.$first.'">
					<div class="media media--booklisting">
					  <a target="_blank" '.$url_ref.' id="'. $v->book_info_id .'" class="pull-left">
					    <img class="media-object bookImage'.$book_count.'" src="'.$cover_url.'" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="h4 media-heading">'.$v->book_name.'</h3>
					    <ul class="list list-inline strong clearfix">					    	
					    	<li><a target="_blank" '.$url_ref.'>View</a></li>					    	
					    </ul>

					    <ul class="list">
					    	<li>'. $v->total_pages .' page(s)</li>
					    	<li>Last Updated: '. $last_save .'</li>					    	
					    	<li></li>
					    </ul>

					    <ul class="list list-inline clearfix shareButtons" page_number="'.$book_count.'" title="'.$v->book_name.'" u_url="'.$url_ref.'">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<span class="st_facebook" displaytext=""><a href="javascript:void(0);" class="fb-share"><img src="'.$this->config->item('base_url').'/images/facebook.png" alt="Facebook"></a></span>
					    	</li>
					    	<li>
					    	    <span class="st_twitter" displaytext=""><a href="javascript:void(0);" class="twitter-share"><img src="'.$this->config->item('base_url').'/images/twitter.png" alt="Twitter"></a></span>  	
					    	</li>
                            <li>
                                <span class="st_pinterest" displaytext=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="'.$this->config->item('base_url').'/images/pinterest.png" alt="Pinterest"></a></span>
                            </li>
                            <li>
                                <span class="st_googleplus" displaytext=""></span><span class="st_email" displaytext=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="'.$this->config->item('base_url').'/images/mail.png" alt="mail"></a></span>
                            </li>
					    </ul>

					  </div>
					</div>
				</li>'; 
                                $book_count++;                               
					}
			?>
			<h3 class="h5 box__title">Friends Books (<?php echo $book_count; ?>)</h3>
			<ul class="box__content">
				<?php echo $friends_books_elem; ?>
			</ul>
		</div>
	</div><!--End col-lg-6 -->
    <script src="<?php echo $this->config->item("js_url"); ?>/book_summarylist.js"></script>
 	<script>
 		$('#js-create-book').click(function (e) {
			e.preventDefault();
            var album_id = $.cookie('hardcover_book_info_id');
            $('#js-maincontent').fadeOut('slow');
            $.ajax({
                type: "POST",
                url: "/main/new_names_chapters",
                data 	: {},
                success: function(data){
                    var _obj = $.parseJSON(data);                            
                    $('#js-maincontent').html(_obj.data);
                    $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
                    $('#js-dropdown .dropdown-menu > li').removeClass('open');
                    $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-my-books').parent().siblings().removeClass('active');
                    $('#js-dropdown').addClass('open');
                    $('#js-name-chapters').addClass('active');
                }, error: function () {

                }
            });
		});

        $('a.book_summary_class').each(function(){
			$(this).live('click',function(){
			    console.log('clicked a book');
                FB.Canvas.scrollTo(0,0);
				$('#app_loader').fadeIn();
				var album_id = $(this).attr('id');
                console.log(album_id);
				$.cookie('hardcover_book_info_id',album_id);
				$.ajax({
					url     : '/main/edit_album',
					type    : 'post',
					cache   :  true,
					data 	: {'book_info_id':album_id},
					success: function(data){                        
                        var _obj = $.parseJSON(data);
                        $('#js-maincontent').html(_obj.data);
                        $('#js-maincontent').fadeIn('slow');
                        $('.dropdown-menu').show();
                        $('#js-dropdown .dropdown-menu > li').removeClass('open');
                        $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                        $('#js-my-books').parent().addClass('active');
                        $('#js-my-books').parent().siblings().removeClass('active');                        
                        $('#js-dropdown').addClass('open');
            			$('#js-editor').addClass('active');            			
                    }, error: function () {
                    }
				});	
				return false;
			});
		});
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
</div>
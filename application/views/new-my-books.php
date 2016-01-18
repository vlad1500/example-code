
<div class="section">
	<div class="row">
		<div class="col-md-12">
			<div class="call-to-action clearfix">
				<a href="javascript:void(0);" id="js-create-book" class="btn btn-orange">Create New Book</a>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="box">
        <div style="display:none;"><?php //print_r($fbid); ?></div>
        <?php
                    $book_count = 0;
                    $user_books_elem = "";
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
								$url_ref = $this->config->item('base_url').'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$book_info->book_name));
                                if($book_count == 0)$first = 'first';
                                else $first = '';  
                                if($book_info->front_cover_location == "" || $book_info->front_cover_location=='NULL')$cover_url = $this->config->item('image_url')."/200x220";
                                else $cover_url = str_replace($this->config->item('book_images_dir'),$this->config->item('book_images_url'),$book_info->front_cover_location);	
								$user_books_elem .= '
                <li class="box__item '.$first.'">
					<div class="media media--booklisting">
					  <a target="_blank" href="#" id="'. $book_info->book_info_id .'" class="book_summary_class pull-left">
					    <img class="media-object" src="/timthumb.php?src='.$cover_url.'&h=220&w=200&zc=1" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="t5 media-heading">'.$book_info->book_name.'</h3>
					    <ul class="list list-inline strong clearfix">
					    	<li class="first-child"><a target="_blank" href="#" id="'. $book_info->book_info_id .'" class="book_summary_class" title="Click to Edit your album '. $book_info->book_name .'">Edit</a></li>
					    	<li><a href="'.$url_ref.'">View</a></li>
					    	<li><a href="#" id="delete" class="'. $book_info->book_info_id .'|'. trim($book_info->book_name) .'" title="Delete '. trim($book_info->book_name) .'?">Delete</a></li>
					    </ul>

					    <ul class="list">
					    	<li>'. $book_info->total_pages .' pages</li>
					    	<li>'. $last_save .'</li>
					    	<li><a href="#">26 Collaborators</a></li>
					    	<li><a href="#">Content Waiting Approval</a></li>
					    </ul>

					    <ul class="list list-inline clearfix">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<div>
					    			<a class="social-icons img-circle" style="display:block;" onclick="javascript:postToWall(\''.$this->config->item('base_url').'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$book_info->book_name)).'\',\''.strtolower(str_replace(' ','_',$book_info->book_name)).'\',\''.$book_info->book_info_id.'\'); return false;"><i class="fa fa-facebook"></i></a>
					    		</div>
					    	</li>
					    	<li>
					    		<div>
					    			<a class="social-icons img-circle" style="display:block;" onclick="javascript:postToWall(\''.$this->config->item('base_url').'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$book_info->book_name)).'\',\''.strtolower(str_replace(' ','_',$book_info->book_name)).'\',\''.$book_info->book_info_id.'\'); return false;"><i class="fa fa-facebook"></i></a>
					    		</div>
					    	</li>

					    </ul>

					  </div>
					</div>
				</li>'; 
                                $book_count++;                               
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
            <?php
                    $book_count = 0;
                    $friends_books_elem = "";
					foreach($dashboard_detils as $k=>$v){
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
								$url_ref = str_replace('https','https',$this->config->item('base_url')).'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$v->book_name));
                                if($book_count == 0)$first = 'first';
                                else $first = '';  
                                if($v->front_cover == "")$cover_url = $this->config->item('image_url')."/200x220";
                                else $cover_url = $v->front_cover;	
								$friends_books_elem .= '
                <li class="box__item '.$first.'">
					<div class="media media--booklisting">
					  <a target="_blank" href="#" id="'. $v->book_info_id .'" class="book_summary_class pull-left">
					    <img class="media-object" src="/timthumb.php?src='.$cover_url.'&h=220&w=200&zc=1" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="t5 media-heading">'.$v->book_name.'</h3>
					    <ul class="list list-inline strong clearfix">
					    	<li class="first-child"><a target="_blank" href="#" id="'. $v->book_info_id .'" class="book_summary_class" title="Click to Edit your album '. $v->book_name .'">Edit</a></li>
					    	<li><a target="_blank" href="'.$url_ref.'">View</a></li>
					    	<li><a href="#" id="delete" class="'. $book_info->book_info_id .'|'. trim($book_info->book_name) .'" title="Delete '. trim($book_info->book_name) .'?">Delete</a></li>
					    </ul>

					    <ul class="list">
					    	<li>'. $v->total_pages .' pages</li>
					    	<li>'. $last_save .'</li>
					    	<li><a href="#">26 Collaborators</a></li>
					    	<li><a href="#">Content Waiting Approval</a></li>
					    </ul>

					    <ul class="list list-inline clearfix">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<div>
					    			<a class="social-icons img-circle" style="display:block;" onclick="javascript:postToWall(\''.$this->config->item('base_url').'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$v->book_name)).'\',\''.strtolower(str_replace(' ','_',$v->book_name)).'\',\''.$v->book_info_id.'\'); return false;"><i class="icon-facebook"></i></a>
					    		</div>
					    	</li>
					    	<li>
					    		<div>
					    			<a class="social-icons img-circle" style="display:block;" onclick="javascript:postToWall(\''.$this->config->item('base_url').'/books/'.$fb_user->fb_username.'/'.strtolower(str_replace(' ','_',$v->book_name)).'\',\''.strtolower(str_replace(' ','_',$v->book_name)).'\',\''.$v->book_info_id.'\'); return false;"><i class="icon-facebook"></i></a>
					    		</div>
					    	</li>

					    </ul>

					  </div>
					</div>
				</li>'; 
                                $book_count++;                               
					}
			?>
			<h3 class="h5 box__title">My Books Friends Collaborate on (<?php echo $book_count; ?>)</h3>
			<ul class="box__content">
				<?php echo $friends_books_elem; ?>
			</ul>
		</div>
	</div><!--End col-lg-6 -->
    <script src="<?php echo $this->config->item("js_url"); ?>/book_summarylist.js"></script>
    <script>
            $('a.book_summary_class').each(function(){
				$(this).live('click',function(e){
				    e.preventDefault();
                    $('#js-maincontent').fadeOut('slow');
				    console.log('clicked a book');
					$('#app_loader').fadeIn();
					var album_id = $(this).attr('id');
                    console.log(album_id);
					$.cookie('hardcover_book_info_id',album_id);
					$.ajax({
						url     : '/main/new_summary',
						type    : 'POST',
						cache   :  true,
						data 	: {'book_info_id':album_id},
						success: function(data){
                            var _obj = $.parseJSON(data);
                            $('#js-maincontent').html(_obj.data);
                            $('#js-maincontent').fadeIn('slow');
                            $('.dropdown-menu').fadeIn(1000);
                            $('#js-dropdown .dropdown-menu > li').removeClass('open');
                            $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                            $('#js-my-books').parent().addClass('active');
                            $('#js-dropdown').addClass('open');
                            $('#js-summary').addClass('active');
                        }, error: function () {
                        }
					});	
					return false;
				});
			});
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
 </script>
</div>

		
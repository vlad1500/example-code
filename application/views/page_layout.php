                             <?php	
								$_POST['page_num'];		
								$_POST['page_layout'];							
								$counter = "";	
								$cnt = 0;									
								$npage = 0;		
								$pages;	
								$page_n=0;	
								$last=0;							
								foreach ($book_pages as $book_page){
									$page = unserialize ($book_page->fbdata);
									$fb_comments = $book_page->comment;
									$is_continuation = 0;
									$page_num = $book_page->page_num;
									$comments = '';
									$cont_page_counter = 0;
									$comments_continuation = array();
									$page_n +=1;
									//Pagination list...	
									//if ($page_n < 21){								
										//$pages .= '<li id="page-'.$book_page->page_num.'">'.$book_page->page_num.'</li>';
										//$last = $book_page->page_num;									
									//}

									foreach ($fb_comments as $fb_comment){
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
											$comments .= '									
												<li id="'.$page->id.'">
													<img src="'.$profile_pict.'" width="'.$profile_width.'" height="'.$profile_height.'" class="float_left">
													<p class="comment_by">'.$comments_name.'</p><p class="fbcomment">'.str_replace('/','',$comments_message).'</p>
													<p class="comment_date">'.$fb_date.'</p>
												</li>';
										}else{
											//this is for those comments that exceeds the page										
											$comments_continuation[$cont_page_counter] .= '									
												<li><img src="'.$profile_pict.'" width="'.$profile_width.'" height="'.$profile_height.'" class="float_left">
													<p class="comment_by">'.$comments_name.'</p>
													<p class="fbcomment">'.str_replace('/','',$comments_message).'</p>
													<p class="comment_date">'.$fb_date.'</p>
													</p>
												</li>';
										}
									}																		
									
									if ($cnt%2 == 0){
										$counter="_left";
									}else{
										$counter="_right";
									}
									$cnt += 1;																					
										
									//choose a page layout
									switch ($book_page->page_layout){
										case 1:										
											if ($page->source != null || $page->source != ""){
												$height = floor($page->height * 0.52);
												$width = floor($page->width * 0.52);
													$img_content = '<!--Load Photo from Album Here-->
																<img width="'.$width.'" height="'.$height.'" src="'.$page->source.'" class="editable"/>';
												
											}else{
												$img_content = "";
											}	
											echo '
												<!--Booktype Image left / Comment right -->									
												<div class="canvas_container">															
														<div id="div-'.$page->id.'" class="float_left img_layout1" style="border:'.$page->border_size.'px solid #000">
															'.$img_content.$t.'																																																						
															<p>'.$page->message.'</p>												
														</div><!--End of Image-->
														<ul class="float_right comment_layout1">
															<!--Comments here-->   
															'.$comments.'                                    
														</ul>    
												</div><!--End of canvas_container-->
												   <!--<p class="float_left _page">'.$book_page->page_num.'</p>-->
												<!--End of Booktype Image left / Comment right -->';
											break;
										case 2:
											if ($page->source != null || $page->source != ""){
												$height = floor($page->height * 0.52);
												$width = floor($page->width * 0.52);
													$img_content = '<!--Load Photo from Album Here-->
																<img width="'.$width.'" height="'.$height.'" src="'.$page->source.'" id="img-'.$page->id.'"  class="editable"/>';

											}else{
												$img_content = "";
											}											
											echo '<div class="canvas_container">
														<ul class="comment_layout2 float_left">
															<!--Comments here-->   
															'.$comments.'  
														</ul>   
														<div id="div-'.$page->id.'" class="float_right img_layout2" style="border:'.$page->border_size.'px solid #000">
															<!--Load Photo from Album Here-->
															'.$img_content.'																								
															<p>'.$page->message.'</p>	
														</div>														
												   </div>
												   <!--<p class="float_left _page">'.$book_page->page_num.'</p>-->
												';										
											break;
										case 3:
											if ($page->source != null || $page->source != ""){
												$height = floor($page->height * 0.52);
												$width = floor($page->width * 0.52);
													$img_content = '<!--Load Photo from Album Here-->
																<img width="'.$width.'" height="'.$height.'" src="'.$page->source.'" id="img-'.$page->id.'"  class="editable"/>';
											}else{
												$img_content = "";
											}										
											echo '<div class="canvas_container">
													<div id="col_layout3_left" class="float_left">
														<div id="div-A'.$page->id.'" class="img_layout3">
															<!--Load Photo from Album Here-->
															'.$img_content.'																							
															<p>'.$page->message.'</p>	
														</div>
														<ul class="float_left comment_layout3_left">
															<!--Comments here-->   
															'.$comments.'  
														</ul>                                                 	
													</div><!---End of col_layout3-left-->
													<div id="col_layout3_right" class="float_right">
														<ul class="comment_layout3_right">
															<!--Comments here-->   
															'.$comments.'
														</ul>  
														<div id="div-B'.$page->id.'" class="img_layout3_down">
															<!--Load Photo from Album Here-->
															'.$img_content.'																							
															<p>'.$page->message.'</p>	
														</div>                                               	
													</div><!---End of col_layout3-left-->
											   </div><!--End of canvas_container-->
												   <!--<p class="float_left _page">'.$book_page->page_num.'</p>-->
											';
											break;
										case  4:
											if ($page->source != null || $page->source != ""){
												$height = floor($page->height * 0.52);
												$width = floor($page->width * 0.52);
													$img_content = '<!--Load Photo from Album Here-->
																<img width="'.$width.'" height="'.$height.'" src="'.$page->source.'" id="img-'.$page->id.'"  class="editable"/>';
											}else{
												$img_content = "";
											}											
											echo '<div class="canvas_container">
														<div id="div-'.$page->id.'"  class="img_layout4" style="border:'.$page->border_size.'px solid #000">
															<!--Load Photo from Album Here-->
															'.$img_content.'																								
															<p>'.$page->message.'</p>	
														</div>
														<ul class="comment_layout4">
															<!--Comments here-->   
															'.$comments.'
														</ul>   
												   </div>
												   <!--<p class="float_left _page">'.$book_page->page_num.'</p>-->
												';
											break;
										case 5:										
											break;											
									}
									
									//display page continuation which is all comments
									$cont_page_counter=0;
									foreach ($comments_continuation as $page_continuation){
										$cont_page_counter++;
										if ($cnt%2 == 0){
											$counter="_left";
										}else{
											$counter="_right";
										}
										$cnt += 1;	
										$page_n += 1; 	
										$pages .= '<li id="page-'.($book_page->page_num+$cont_page_counter).'">'.($book_page->page_num+$cont_page_counter).'</li>';
										echo '<div id="canvas_container">
													<ul class="comment_layout5 float_right">'.
													$page_continuation
													.'</ul>  
												</div>
												<!--<p class="float_left _page">'.($book_page->page_num+$cont_page_counter).'</p>-->
											';			
													
									}																				
								}
 							?>

<script type="text/javascript">
	$(document).ready(function(e) {        
			radioStyle();					
			
			$.ajax({
					url 	: 'main/get_fb_friends',
					type	: 'post',
					success : function(res){
						
					var x_img = "http://hardcover.shoppingthing.com/images/x.png";
						
					$("#book_cover_right").empty().html('<div id="book_cover_right_friends"><input type="text" id="book_cover_right_txt" value="<?=$book_info->book_name;?>" size="43"><br/><input type="text" id="book_cover_right_txt2" value="<?=$book_info->book_caption;?>" size="43"><a href="#" class="tooltip" title="Remove Caption" id="cap_delete"><img id="caption_rem" class="float_left" src="'+x_img+'"></a><br/></div><ul id="ul_book_friend"></ul></div>');					
					var _obj = $.parseJSON(res);	
					var split_obj = _obj.friends_fbid.split(';');
					
					$.each(split_obj,function(i){
						if (i == 24){
							return false;
						}
						var imgUrl = 'https://graph.facebook.com/'+split_obj[i]+'/picture';
						
						$('#ul_book_friend').append('<li id="'+split_obj[i]+'" class="float_left"><img src="'+imgUrl+'"><a href="#" class="tooltip" title="Remove photo" id="delete"><img id='+split_obj[i]+' class="ximg" src="'+x_img+'"></a></li>').fadeIn('slow');
					});							
					tooltip();
					}	
				});						
			
			$('#close_editor').on('click',function(){
				$("#slider" ).slider("destroy");
				$('#img_editor').fadeOut('slow');
			});				
						
			function launchEditor(id, src) {
				featherEditor.launch({
					image: id,
					url: src
				});
				return false;
			}

			var divcon = $('#book').html();
			$('#preview').on('click',function(){
					var _bookid = $.cookie('hardcover_book_info_id');
                    var url = 'http://hardcover.shoppingthing.com/books/'+_bookid+'?page_num=1';
                    var windowName = "popUp";
                    window.open(url, windowName);
                    event.preventDefault();				
			});
			$('#send_msg').myModal({
					sendMessage    : true,
				});	
			
			$('img.editable').each(function(){				
				var id = $(this).attr('id');
				$('#'+id).myHover({ message : 'Click Image to Edit', speed_in: 600, speed_out: 600 });
			});

		
		$('div.canvas_container').each(function(index, element) {  
			$(this).live('mouseenter',function(){ 
				var curr_id = $(this).parent().get(0).id;
				$.cookie('pagenum',curr_id);
				var cls = $('#'+curr_id).attr('class');
				var _this = $(this);
				$('#'+curr_id).addClass('current'); 
				$('.'+cls+'_icon').fadeIn('slow');			
				$('.'+cls+'_icon').hover(function(){
					$(this).css({'opacity':1}).stop(true);		
				}).click(function(){	
						var _id = $(this).children('div').get(0).id;	
						var _w	= $('#page-layout_option').width();	
						var _w2 = _w / 2;				
					if (cls == "_left"){
						$('#page-layout_option').css({left: $('#'+_id).offset().left - _w2 }).fadeIn('slow');
					}else{
						$('#page-layout_option').css({left: ($('#'+_id).offset().left - _w) - (_w2 - 40)  }).fadeIn('slow');
					}
				});							
			}).live('mouseleave',function(){ 				
				var curr_id = $(this).parent().get(0).id;
				$('#'+curr_id).removeClass('current'); 
				var cls = $('#'+curr_id).attr('class');		
				$('.'+cls+'_icon').fadeOut('slow');					
			}).live('mousedown',function(){
				$('#page-layout_option').fadeOut('slow');
			});
		});
		
		$('#page-layout_option ul li').each(function(){
			$(this).animate({'opacity' : 1}).hover(function() {
				$(this).animate({'opacity' : .3});
			}, function() {
				$(this).animate({'opacity' : 1});
			}).live('click',function(){
				var layout = $(this).attr('id').substr(7,1);
				var book_id = $.cookie('hardcover_book_info_id');
				var pagenum = $.cookie('pagenum');				
				$('#app_loader').fadeIn('slow');

				$.ajax({
					//url 	: 'main/set_page_layout',
					url		: 'main/set_page_layout_per_page', 	
					type	: 'post',
					cache	: false,
					data	: {'book_info_id':book_id,'page_num':pagenum,'page_layout':layout},
					success	: function(res){	
									var _obj = $.parseJSON(res);	
									$('#app_loader').fadeOut('slow');	
									$('section#'+pagenum).html(_obj.data);						
								}
				});
			});
		});
		
		$('#page-layout_option').live('mouseleave',function(){ $(this).fadeOut('slow'); });
		
	});	
	
</script>							
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta name="title" content="HardCover">
  <meta name="description" content="HardCover let's you create and share digital albums including group albums.">
  <link rel="image_src" href="<?=$base_url;?>/images/hardcover-logo.png" />
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Share HardCover to your Friends</title>
  <meta name="viewport" content="width=device-width">
  <link rel='stylesheet' href='/css/style.css' type='text/css'>
  <link rel='stylesheet' href='/css/ui/jquery-ui-1.8.18.custom.css' type='text/css'>  
  <script src='/js/libs/modernizr-2.5.3.min.js' type="text/javascript"></script>
  <style type="text/css">
  	#share_text p{
		padding: 5px 0;	
	}
	#book_label {
		margin:0 auto;
		position:relative;
		text-align:center;
	}
  </style>
</head>
<body>
  <!--[if lt IE 9]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Get Firefox here </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div id="fb-root"></div>
<script type="text/javascript">
  window.fbAsyncInit = function() {    
    FB.init({appId: '331059976950036', status: true, cookie: true,xfbml: true,channelURL : 'https://dev.hardcover.me/channel.html',oauth:true});
  };
  
  
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));
</script>  
<div id="app_loader" class="hideDiv"><div class="bar"></div></div> 
<div id='wrapper'>
<div id='content_wrapper'>   
  <div id='content'>  
  <!--<div id='logo' style="z-index:9999999999999999;"></div>--><a href="#" class="brand"><img alt="HardCover" src="<?=$base_url;?>/images/hardcover-logo.png"></a>
    <div id='help_about' class="float_right">
    	<p><a class="no_underline" href="#">Help</a> | <a class="no_underline" href="#">About</a></p>
    </div>
        <!--This is the Container for the Tab-->       
        <div class="tab2_container hideDiv">         
          <div id="main_inner">
          
            <!--home-->     
                <div id="my_home" class="tab2_content">
					<div id="edit_main_book">
                    <div id="share_text">
                        <p>Hi "<?=$friends_info->friends_name;?>"</p>
                        <p>Your friend <?=$book_creator->fname;?>, is printing a photo album and would like to add your</p>
                        <p>FB timeline info to that album so it can be viewed from both of your perspective.</p>
                        <p>Share your info with <?=$book_creator->fname;?></p>
                        <p><input type="button" value="Add App" id="add_app"/></p>            		                    
					</div>
                    <p id="book_label">"<?=$book_creator->book_name;?>" book is still being edited, but here is a sneak peek of the first 10 pages.</p>
                   <div id="book" class="modal">                    	
						<div id="button_container">
                            <div id="left_button_layout" class="float_left _left_icon hideDiv">
                                <div id="left_page_layout" class="float_left"></div>
                                <div id="left_deleted_comment" class="float_left"></div>
                            </div>
                            <div id="right_button_layout" class="float_right _right_icon hideDiv">
                                <div id="right_deleted_comment" class="float_left"></div>                        
                                <div id="right_page_layout" class="float_left"></div>                        	
                            </div>
                        </div>
                        <div id="page-layout_option" class="hideDiv">
                            <ul>
                                <li id="layout_1"></li>
                                <li id="layout_2"></li>
                                <li id="layout_3"></li>
                                <li id="layout_4"></li>
                                <li id="layout_5"></li>
                            </ul>
                        </div>  
                        <canvas id="pageflip-canvas"></canvas>                                                           
                        <div id="pages"> 
                        <div id="fold_left" class="float_left"><p></p></div>      
                        <div id="fold_right" class="float_right"><p></p></div> 
                        		<!--
                               	<section class="_left" id="page-A">
                                   <div id="canvas_container">    
                                   </div>
                                </section>
                                <section class="_right" id="page-B">
                                   <div id="canvas_container">    
                                   </div>
                                </section>-->                                                                         
                                <!--Needed for page setup-->                         	
                                <?php	
																
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
															<img width="'.$width.'" height="'.$height.'" src="'.$page->source.'"  srcset="'.$page->source.' 400w" id="img-'.$page->id.'"  class="editable"/>';
											}else{
												$img_content = "";
											}	
											echo '
												<!--Booktype Image left / Comment right -->									
												<section class="'.$counter.'" id="page-'.$book_page->page_num.'">
												<div class="canvas_container">															
														<div id="div-'.$page->id.'" class="float_left img_layout1">
															'.$img_content.$t.'																																																						
															<p>'.$page->message.'</p>												
														</div><!--End of Image-->
														<ul class="float_right comment_layout1">
															<!--Comments here-->   
															'.$comments.'                                    
														</ul>    
												</div><!--End of canvas_container-->
												   <!--<p class="float_left _page">'.$book_page->page_num.'</p>-->
												</section>
												<!--End of Booktype Image left / Comment right -->';
											break;
										case 2:
											if ($page->source != null || $page->source != ""){
												$height = floor($page->height * 0.52);
												$width = floor($page->width * 0.52);
												$img_content = '<!--Load Photo from Album Here-->
													<img width="'.$width.'" height="'.$height.'" src="'.$page->source.'"  srcset="'.$page->source.' 400w" id="img-'.$page->id.'"  class="editable"/>';
											}else{
												$img_content = "";
											}											
											echo '<section class="'.$counter.'" id="page-'.$book_page->page_num.'">
													<div class="canvas_container">
														<ul class="comment_layout2 float_left">
															<!--Comments here-->   
															'.$comments.'  
														</ul>   
														<div id="div-'.$page->id.'" class="float_right img_layout2">
															<!--Load Photo from Album Here-->
															'.$img_content.'																								
															<p>'.$page->message.'</p>	
														</div>														
												   </div>
												   <!--<p class="float_left _page">'.$book_page->page_num.'</p>-->
												</section> ';										
											break;
										case 3:
											if ($page->source != null || $page->source != ""){
												$height = floor($page->height * 0.52);
												$width = floor($page->width * 0.52);
												$img_content = '<!--Load Photo from Album Here-->
															<img  width="'.$width.'" height="'.$height.'" src="'.$page->source.'"  srcset="'.$page->source.' 400w" id="img-'.$page->id.'"  class="editable"/>';
											}else{
												$img_content = "";
											}										
											echo '<section class="'.$counter.'" id="page-'.$book_page->page_num.'">
												<div class="canvas_container">
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
											</section>';
											break;
										case  4:
											if ($page->source != null || $page->source != ""){
												$height = floor($page->height * 0.52);
												$width = floor($page->width * 0.52);
												$img_content = '<!--Load Photo from Album Here-->
															<img  width="'.$width.'" height="'.$height.'" src="'.$page->source.'"  srcset="'.$page->source.' 400w" id="img-'.$page->id.'"  class="editable"/>';
											}else{
												$img_content = "";
											}											
											echo '<section class="'.$counter.'" id="page-'.$book_page->page_num.'">
													<div class="canvas_container">
														<div id="div-'.$page->id.'"  class="img_layout4">
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
												</section> ';
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
										echo '  <section class="'.$counter.'" id="page-'.($book_page->page_num+$cont_page_counter).'">
												<div id="canvas_container">
													<ul class="comment_layout5 float_right">'.
													$page_continuation
													.'</ul>  
												</div>
												<!--<p class="float_left _page">'.($book_page->page_num+$cont_page_counter).'</p>-->
												</section>';			
													
									}																				
								}
 							?>
                            </div><!--end of pages-->                             
                         </div><!--end of book-->   
                               <div id="pagination">
                                <?php				
                                    $temp_x = $page_num_start - 20;
                                    //if ($temp_x >= 0 ){	//check first if we need to create PREV link
                                        //create previous navigation
                                        //<p id="_prev" class="float_left">&lt;</p>
                                        echo '	<div id="elips_left" class="float_left">									
                                                    <p id="'.$temp_x.'" class="first float_left">&lt;&lt;</p>
                                                    <p id="_curr" class="l_cover float_left">Cover</p>
                                                    <p class="elips float_left">...</p>                  
                                                </div>';
                                    //}
                                    
                                    //create pages numbers
                                    $max = ($total_pages > $page_num_end)?$page_num_end:$total_pages; 
                                    //$page_num_start++;
                                    echo '<ul id="paging">';
                                    for ($x=$page_num_start; $x<=$max; $x++){
                                        echo '<li id="page-'.$x.'">'.$x.'</li>';
                                    }
                                    echo '</ul>';
                                    
                                    $page_num_end++;
                                    //if ($page_num_end < $total_pages){
                                        //create next navigation
                                        //<p id="_next" class="float_left">&gt;</p>
                                        echo '	<div id="elips_right" class="float-right">
                                                    <p class="elips float_left">...</p>  
                                                    <p class="r_cover float_left">Cover</p>
                                                    <p id="'.$page_num_end.'" class="last float_left">&gt;&gt;</p>									
                                                </div>';
                                    //}
                                ?>                        
                            </div>
            </div><!--end of edit_main_book-->  	
                </div><!--End of my_home-->
            <!--fb_data-->        
                <div id="fb_data" class="tab2_content">
                </div><!--End of fb_data--> 
            
            <!--edit-->        
                <div id="my_edit" class="tab2_content">
                </div><!--End of my_edit--> 
            
            <!--albums-->        
                <div id="my_album" class="tab2_content">
                </div><!--End of my_album--> 
           
          </div><!--End of main_inner-->                    
        </div><!--End of tab2_container--> 
      </div>
	</div>
  </div>
</div>
</div>    

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write(' <script src="<?=$this->config->item('js_url');?>/libs/jquery-1.7.1.min.js" type="text/javascript"><\/script>')</script>
<script src='/js/plugins.js' type="text/javascript"></script>
<script src='/js/script.js' type="text/javascript"></script> 
<script src='/js/jquery.cookie.js' type="text/javascript"></script>
<script src="/js/booklet/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script src="/js/jquery.rotate.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
	
	$('#app_loader').fadeIn('fast',function(){ 		//PageFlip();
		myPageflip(702,273);
		$('#fold_left').css({height:$('#book').innerHeight(),top:$('#book').offset().top});
		$('#fold_right').css({height:$('#book').innerHeight(),left:parseInt($('#book').innerWidth())-20,top:$('#book').offset().top});
	}).delay(5000).fadeOut('fast',function(){ 
		$('.tab2_container').fadeIn('slow').removeClass('hideDiv'); 
	});
	
	//Pagination...
		$('div#pagination ul li').filter(':even').css({'border-radius':'5px 0 0 5px','-moz-border-radius':'5px 0 0 5px','-webkit-border-radius':'5px 0 0 5px'});
		$('div#pagination ul li').filter(':odd').css({'border-radius':'0 5px 5px 0','-moz-border-radius':'0 5px 5px 0','-webkit-border-radius':'0 5px 5px 0','margin-left':'-3px'});											
		$('div#pagination ul li').filter(':first').addClass('current').next().addClass('current');	
								
		$('p.first').css({'border-radius':'5px 0 0 5px','-moz-border-radius':'5px 0 0 5px','-webkit-border-radius':'5px 0 0 5px'});
		$('p.last').css({'border-radius':'0 5px 5px 0','-moz-border-radius':'0 5px 5px 0','-webkit-border-radius':'0 5px 5px 0'})

		$('#elips_right').css({'margin-left': '335px'});
	
	$.cookie('hardcover_friends_fbid','<?=$param->friends_fbid;?>',{path:'/'});
	$.cookie('hardcover_gave_info_to_bkid','<?=$param->book_info_id;?>',{path:'/'});
	$.cookie('hardcover_referer','share_url',{path:'/'});
		
	$('#add_app').on('click',function(){
		//this cookies will be use to determine if this user trying to give fb data  when it goes to the hardcover fb app
		
		
		FB.getLoginStatus(function(response) {
			if (response.status === 'connected') {
			// the user is logged in and has authenticated your
			// app, and response.authResponse supplies
			// the user's ID, a valid access token, a signed
			// request, and the time the access token 
			// and signed request each expire
				var fbid = response.authResponse.userID;
				var token = response.authResponse.accessToken;
				process_retrieval(fbid,token);
				
			} else if (response.status === 'not_authorized') {
			// the user is logged in to Facebook, 
			// but has not authenticated your app
				fblogin();
			} else {
				// the user isn't logged in to Facebook.
				fblogin();
			}
		});
		
		function fblogin(){
			FB.login(function(response) {
			   if (response.authResponse) {
					var token = response.authResponse.accessToken;
					var fbid = response.authResponse.userID;
					process_retrieval(fbid,token);
			   } else {
				 alert('User cancelled login or did not fully authorize.');
			   }
			 }, {scope: 'email,user_photos,read_stream,user_likes,user_status,status_update'});
		}
		
		function process_retrieval(fbid,token){
			$.ajax({
			url		:	'/main/add_app',
			type	:	'post',
			data	:   {'friends_fbid':fbid,'book_info_id':<?=$param->book_info_id;?>,'token':token},
			success	:	function(res){ 
							var _obj = $.parseJSON(res);
							//$('#main_inner').html(_obj.data);							
							//alert('FB Data retrieval has started and your data will be added soon in that book.');
							window.location.replace('https://apps.facebook.com/hardcoverdev/');
						},
			error	:	function(res){ console.log(res); }
		});
		}
		
		//window.location = '<?=$app_canvas;?>';
		
		/*
		$.ajax({
			url		:	'main/add_app',
			type	:	'post',
			data	:   {'friend_fbid:<?=$param->friends_fbid;?>','book_info_id:<?=$param->book_info_id;?>'},
			success	:	function(res){ console.log(res); },
			error	:	function(red){ console.log(res); }
		});
		*/
	});	
	
	
});
</script>
</body>
</html>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link rel='stylesheet' href='/css/style.css' type='text/css'>
  <link rel='stylesheet' href='/css/ui/jquery-ui-1.8.18.custom.css' type='text/css'>  
  <link rel='stylesheet' href="/css/theme_aviary.css" type="text/css" />
  <style type="text/css">	
	.preview_fbcomment {
		 { margin:0; font-size:90%;color:#555;line-height: 10px;}
	}
	.preview_comment_by { color:#565998;font-size:90%;margin:0;height:26px;line-height: 10px; }
	.preview_comment_date { color:#999;font-size:90%;margin-top:3px; }
	
}

</style>
  
  <script src='/js/libs/modernizr-2.5.3.min.js' type="text/javascript"></script>
  
  <script language="JavaScript">
  
  	
  	//Code Starts
	function GetQueryStringParams(sParam)
	{
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++) 
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) 
			{
				return sParameterName[1];
			}
		}
	}
	//Code Ends
	
	// use this when paging is already fixed
	//var v_page_num = GetQueryStringParams("page_num");
	var v_page_num = 1;
	
  </script>
</head>
<body>
  <!--[if lt IE 9]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Get Firefox here </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div id="fb-root"></div>
<script type="text/javascript">

  window.fbAsyncInit = function() {    
    FB.init({appId: '331059976950036', status: true, cookie: true,xfbml: true,channelURL : 'https://hardcover.shoppingthing.com/fbapp/channel.html',oauth:true});
  
  FB.Canvas.setAutoGrow();
  
  };
  
  
    (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
   
   
</script>  

<?php


//echo 'page_layout:'.$page_layout;
//echo "<br/><br/>";
$fbdata = unserialize($book_pages[0]->fbdata);

//$fbdata = $book_pages->fbdata;
//print_r($fbdata->images[4]->source);
//print_r($book_pages);
$my_pict =  'https://graph.facebook.com/'.$fbdata->from->id.'/picture?type=large';

$comments = $book_pages->comment;
//print_r($comments1);
$p_page_num = $_GET["page_num"];
?>


<div id="app_loader" class="hideDiv">
  <div class="bar"></div>
</div> 
<div id='wrapper'> 
  <div id='content_wrapper'> 
    <div id='content'> 
      <div id='logo' style="z-index:9999999999999999;" class="float_left"></div>
	  <?php
	  echo '
	  <div class="float_right"><img src="'.$my_pict.'" width="100"class="float_left">&nbsp;&nbsp;'.$fbdata->from->name.'&nbsp;&nbsp;</div>
	  ';
	  ?>
      <!--This is the Container for the Tab-->
      <div class="tab2_container hideDiv"> 
        <div id="main_inner"> 
          <!--home-->
          <div id="my_home" class="tab2_content"> 
            <div id="edit_main_book"> 
              <div id="book"> <canvas id="pageflip-canvas"></canvas> 
                <div id="pages"> 
                  <div id="fold_left" class="float_left"> 
                    <p></p>
                  </div>
                  <div id="fold_right" class="float_right"> 
                    <p></p>
                  </div>
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
												<li id="'.$comment->id.'">
													<img src="'.$profile_pict.'" width="35"class="float_left">
													<p class="preview_comment_by">'.$comments_name.'</p><p class="preview_fbcomment">'.str_replace('/','',$comments_message).'</p>
													<p class="preview_comment_date">'.$fb_date.'</p>
												</li>';
										}else{
											//this is for those comments that exceeds the page										
											$comments_continuation[$cont_page_counter] .= '									
												<li id="'.$comment->id.'"><img src="'.$profile_pict.'" width="35" class="float_left">
													<p class="preview_comment_by">'.$comments_name.'</p>
													<p class="preview_fbcomment">'.str_replace('/','',$comments_message).'</p>
													<p class="preview_comment_date">'.$fb_date.'</p>
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
											echo '
												<!--Booktype Image left / Comment right -->									
												<section class="'.$counter.'" id="page-'.$book_page->page_num.'">
												<div class="canvas_container">															
														<div id="div-'.$page->id.'" class="float_left img_layout1" style="border:'.$page->border_size.'px solid #000">
															<!--Load Photo from Album Here-->
															<img src="'.$page->images[4]->source.'" id="img-'.$page->id.'" width="100%" class="editable"/>																								
															<p>'.$page->message.'</p>												
														</div><!--End of Image-->
														<ul class="float_right comment_layout1">
															<!--Comments here-->   
															'.$comments.'                                    
														</ul>    
												</div><!--End of canvas_container-->
												<p class="float_left _page">'.$book_page->page_num.'</p>
												</section>
												<!--End of Booktype Image left / Comment right -->';
											break;
										case 2:
											echo '<section class="'.$counter.'" id="page-'.$book_page->page_num.'">
													<div class="canvas_container">
														<ul class="comment_layout2 float_left">
															<!--Comments here-->   
															'.$comments.'  
														</ul>   
														<div id="div-'.$page->id.'" class="float_right img_layout2" style="border:'.$page->border_size.'px solid #000">
															<!--Load Photo from Album Here-->
															<img src="'.$page->images[4]->source.'" id="img-'.$page->id.'" width="100%" class="editable"/>																								
															<p>'.$page->message.'</p>	
														</div>														
												   </div>
												   <p class="float_left _page">'.$book_page->page_num.'</p>
												</section> ';										
											break;
										case 3:
											echo '<section class="'.$counter.'" id="page-'.$book_page->page_num.'">
												<div class="canvas_container">
													<div id="col_layout3_left" class="float_left">
														<div id="div-A'.$page->id.'" class="img_layout3" style="border:'.$page->border_size.'px solid #000">
															<!--Load Photo from Album Here-->
															<img src="'.$page->images[4]->source.'" id="img-'.$page->id.'" width="100%" class="editable"/>																								
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
														<div id="div-B'.$page->id.'" class="img_layout3_down" style="border:'.$page->border_size.'px solid #000">
															<!--Load Photo from Album Here-->
															<img src="'.$page->images[4]->source.'" id="img-'.$page->id.'" width="100%" class="editable"/>																								
															<p>'.$page->message.'</p>	
														</div>                                               	
													</div><!---End of col_layout3-left-->
											   </div><!--End of canvas_container-->
											   <p class="float_left _page">'.$book_page->page_num.'</p>
											</section>';
											break;
										case  4:
											echo '<section class="'.$counter.'" id="page-'.$book_page->page_num.'">
													<div class="canvas_container">
														<div id="div-'.$page->id.'"  class="img_layout4" style="border:'.$page->border_size.'px solid #000">
															<!--Load Photo from Album Here-->
															<img src="'.$page->images[4]->source.'" id="img-'.$page->id.'" width="100%" class="editable"/>																								
															<p>'.$page->message.'</p>	
														</div>
														<ul id="comment_layout4">
															<!--Comments here-->   
															'.$comments.'
														</ul>   
												   </div>
												   <p class="float_left _page">'.$book_page->page_num.'</p>
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
												<p class="float_left _page">'.($book_page->page_num+$cont_page_counter).'</p>
												</section>';			
													
									}																				
								}
 							?>
                </div>
                <!--end of pages-->
              </div>
              <!--end of book-->
              <div id="pagination"> 
                <?php				
					$page_num_start = $page_num - 19;
					$page_num_end = $page_num;
					$temp_x = $page_num_start;
					
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
					
					//$max = ($total_pages > $page_num_end)?$page_num_end:$total_pages; 
					//$page_num_start++;
                	echo '<ul id="paging">';
					for ($x=$page_num_start; $x<=$page_num_end; $x++){
						echo '<li id="page-'.$x.'">'.$x.'</li>';
					}
					echo '</ul>';
					
					//$page_num_end++;
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
            </div>
            <!--end of edit_main_book-->
          </div>
		  <div id="modal_bckgrnd"></div>
		  <div id="modal_container"></div>
		  <button id="share_book" style="align: right;">Share Book</button>
          <!--End of my_home-->
        </div>
        <!--End of main_inner-->
      </div>
      <!--End of tab2_container-->
    </div>
  </div>
</div>

</div>

</div>    

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write(' <script src="<?=$this->config->item('js_url');?>/libs/jquery-1.7.1.min.js" type="text/javascript"><\/script>')</script>
<script src='/js/plugins.js' type="text/javascript"></script>
<script src='/js/script.js' type="text/javascript"></script> 
<script src='/js/fbfunc.js' type="text/javascript"></script>
<script src='/js/jquery.cookie.js' type="text/javascript"></script>
<script src="/js/booklet/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script src="/js/jquery.rotate.js" type="text/javascript"></script>
<script src="http://feather.aviary.com/js/feather.js" type="text/javascript"></script>
<script type="text/javascript">

var myPageFlipWidth = 880;
var myPageFlipHeight = 400;
var book_info_id = '<?=$book_pages[0]->book_info_id;?>';
$(document).ready(function(){

	function post_to_timeline(_userid){
		   //var _infoid = $.cookie('hardcover_book_info_id');						   	   	
		   var _token = $.cookie('hardcover_token');
		   
		   //checkPermissions();
		   $('#app_loader').fadeIn('slow');
		   FB.api(_userid+'/feed?access_token='+_token, 'post',
			 {
				message	:	'Kindly check my album in this cool album maker application',
				url		:	'hardcover.shoppingthing.com',
				link	: 	'http://hardcover.shoppingthing.com/books/'+book_info_id+'?page_num=1',
				picture :	'https://hardcover.shoppingthing.com/images/slide2/HardCover_logo.png',
			 },function(res){
				//console.log(res);
				 $('#app_loader').fadeOut('slow');
				 alert('This book has been shared...');
			});
	 }
		
	$('#app_loader').fadeIn('fast',function(){ myPageflip(myPageFlipWidth,myPageFlipHeight); }).delay(5000).fadeOut('fast',function(){ $('.tab2_container').fadeIn('slow').removeClass('hideDiv'); });
	
	$('#book').width(myPageFlipWidth);
	
	$('#fold_left').css({height:$('#book').innerHeight(),top:$('#book').offset().top});
	$('#fold_right').css({height:$('#book').innerHeight(),left:parseInt($('#book').innerWidth())-20,top:$('#book').offset().top});
	
	//Pagination...
		$('div#pagination ul li').filter(':even').css({'border-radius':'5px 0 0 5px','-moz-border-radius':'5px 0 0 5px','-webkit-border-radius':'5px 0 0 5px'});
		$('div#pagination ul li').filter(':odd').css({'border-radius':'0 5px 5px 0','-moz-border-radius':'0 5px 5px 0','-webkit-border-radius':'0 5px 5px 0','margin-left':'-3px'});											
		
		/*
		if (v_page_num % 2 == 0)
			v_page_num -= 1;
		*/
		
		$('div#pagination #page-1').addClass('current').next().addClass('current');
								
		$('p.first').css({'border-radius':'5px 0 0 5px','-moz-border-radius':'5px 0 0 5px','-webkit-border-radius':'5px 0 0 5px'});
		$('p.last').css({'border-radius':'0 5px 5px 0','-moz-border-radius':'0 5px 5px 0','-webkit-border-radius':'0 5px 5px 0'});
		
		//share book
		
		$('#share_book').live('click', function(){
			getCookie();
			_user = $.cookie('hardcover_fbid');		
			post_to_timeline(_user);
		});
		
		
		
		
});
</script>
</body>
</html>

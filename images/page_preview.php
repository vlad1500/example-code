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
	#page_preview {
		width: 98%;
		height: 98%;
		padding: 1%;
	}
	
	#preview_left {
		float: left;
		display: block;
		width: 50%;
		height: 100%;
		font-size: 13px;
		background: url('https://hardcover.shoppingthing.com/images/HardCover_previewBookLeftpage.png') 115% 0% no-repeat;; 
	}
	
	#preview_right {		
		float: right;
		display: block;
		width: 50%;
		height: 100%;
		font-size: 13px;
		background: url('https://hardcover.shoppingthing.com/images/HardCover_previewBookLeftPage.png') 115% 0% no-repeat;; 
	}

	#page_content
	{
		padding: 4%;
	}
	
	#preview_left ul,
	#preview_right ul
	{ display:block; }		
	
	.preview_fbcomment {
		 { margin:0; font-size:90%;color:#555;line-height: 10px;}
	}
	.preview_comment_by { color:#565998;font-size:90%;margin:0;height:26px;line-height: 10px; }
	.preview_comment_date { color:#999;font-size:90%;margin-top:3px; }
	
</style>
  <script src='/js/libs/modernizr-2.5.3.min.js' type="text/javascript"></script>
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
$fbdata_left = unserialize($book_pages[0]->fbdata);
$fbdata_right = unserialize($book_pages[1]->fbdata);
//print_r($fbdata->images[4]->source);
//print_r($book_pages);
$comments1 = $book_pages[0]->comment;
$comments2 = $book_pages[1]->comment;
//print_r($book_pages[0]->comment);
?>
<div>
	<div id="page_preview">			
		
		<div id="preview_right">
			<div id="page_content">
			<?php
				switch ($page_layout){
					case 1:
				?>
						<div class="float_left img_layout1">
						<!--Load Photo from Album Here-->
						<?php
							if ($book_pages[1]->connection == 'album_photos' || $book_pages[1]->connection == 'photos')
								echo '<img src="'.$fbdata_right->images[4]->source.'" id="img-'.$fbdata_right->id.'" width="100%" />';
							else
								echo '<div>'.$fbdata_right->message.'</div>';
						?>																								
						<p><?php '.$fbdata_right->message.' ?></p>												
						</div><!--End of Image-->
						<ul class="float_right comment_layout1">
							<!--Comments here-->   
							
						<?php
							foreach($comments2 as $comment){
								$oComment = unserialize($comment->comment_obj);									
								$profile_pict =  'https://graph.facebook.com/'.$oComment->from->id.'/picture?type=small';									
								$fb_date = date("m/d/y", strtotime($oComment->created_time));																		
								$comments_name = (string) $oComment->from->name;
								$comments_message = (string) $oComment->message;
								echo $oComment->page_num . '
											
								<li id="'.$oComment->id.'">
									<img src="'.$profile_pict.'" width="35" class="float_left">
									<p class="preview_comment_by">'.$comments_name.'</p><p class="preview_fbcomment">'.str_replace('/','',$comments_message).'</p>
									<p class="preview_comment_date">'.$fb_date.'</p>
								</li>';
								
							}
						?>
						</ul>    
						
				<?php 
						break;
					case 2:
				?>		
						<ul class="comment_layout2 float_left">
						<?php
							foreach($comments2 as $comment){
								$oComment = unserialize($comment->comment_obj);									
								$profile_pict =  'https://graph.facebook.com/'.$oComment->from->id.'/picture?type=small';									
								$fb_date = date("m/d/y", strtotime($oComment->created_time));																		
								$comments_name = (string) $oComment->from->name;
								$comments_message = (string) $oComment->message;
								echo $oComment->page_num . '
											
								<li id="'.$oComment->id.'">
									<img src="'.$profile_pict.'" width="35" class="float_left">
									<p class="preview_comment_by">'.$comments_name.'</p><p class="preview_fbcomment">'.str_replace('/','',$comments_message).'</p>
									<p class="preview_comment_date">'.$fb_date.'</p>
								</li>';
								
							}
						?>
						</ul>   
						<div class="float_right img_layout2">
						<!--Load Photo from Album Here-->
						<?php
							if ($book_pages[0]->connection == 'album_photos' || $book_pages[0]->connection == 'photos')
								echo '<img src="'.$fbdata_left->images[4]->source.'" id="img-'.$fbdata_left->id.'" width="100%" />';
							else
								echo '<div>'.$fbdata_left->message.'</div>';
						?>																								
						<p><?php '.$fbdata->message.' ?></p>	
						</div>														
				<?php 
						break;
					case 3:
				?>		
						<ul class="comment_layout2 float_left">
						<?php
							foreach($comments2 as $comment){
								$oComment = unserialize($comment->comment_obj);									
								$profile_pict =  'https://graph.facebook.com/'.$oComment->from->id.'/picture?type=small';									
								$fb_date = date("m/d/y", strtotime($oComment->created_time));																		
								$comments_name = (string) $oComment->from->name;
								$comments_message = (string) $oComment->message;
								echo $oComment->page_num . '
											
								<li id="'.$oComment->id.'">
									<img src="'.$profile_pict.'" width="35" class="float_left">
									<p class="preview_comment_by">'.$comments_name.'</p><p class="preview_fbcomment">'.str_replace('/','',$comments_message).'</p>
									<p class="preview_comment_date">'.$fb_date.'</p>
								</li>';
								
							}							
						?>
						</ul>   
						<div class="float_right img_layout2">
						<!--Load Photo from Album Here-->
						<?php
							if ($book_pages[0]->connection == 'album_photos' || $book_pages[0]->connection == 'photos')
								echo '<img src="'.$fbdata_left->images[4]->source.'" id="img-'.$fbdata_left->id.'" width="100%" />';
							else
								echo '<div>'.$fbdata_left->message.'</div>';
						?>																								
						<p><?php '.$fbdata->message.' ?></p>	
						</div>														
				<?php 
						break;
					case 4:
				?>		
						<div class="float_left img_layout1">
						<!--Load Photo from Album Here-->
						<?php
							if ($book_pages[0]->connection == 'album_photos' || $book_pages[0]->connection == 'photos')
								echo '<img src="'.$fbdata_left->images[4]->source.'" id="img-'.$fbdata_left->id.'" width="100%" />';
							else
								echo '<div>'.$fbdata_left->message.'</div>';
						?>																								
							<p>'.$page->message.'</p>																								
						<p><?php '.$fbdata->message.' ?></p>												
						</div><!--End of Image-->
						<ul class="float_right comment_layout1">
							<!--Comments here-->   
						<?php
							foreach($comments2 as $comment){
								$oComment = unserialize($comment->comment_obj);									
								$profile_pict =  'https://graph.facebook.com/'.$oComment->from->id.'/picture?type=small';									
								$fb_date = date("m/d/y", strtotime($oComment->created_time));																		
								$comments_name = (string) $oComment->from->name;
								$comments_message = (string) $oComment->message;
								echo $oComment->page_num . '
											
								<li id="'.$oComment->id.'">
									<img src="'.$profile_pict.'" width="35" class="float_left">
									<p class="preview_comment_by">'.$comments_name.'</p><p class="preview_fbcomment">'.str_replace('/','',$comments_message).'</p>
									<p class="preview_comment_date">'.$fb_date.'</p>
								</li>';
								
							}
						?>
						</ul>    
				
				<?php 
						break;
					case 5:										
						break;											
				} ?>
			</div><!---end of page_preview --->
	
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
</body>
</html>
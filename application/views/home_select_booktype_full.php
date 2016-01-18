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
  <link rel='stylesheet' href='<?=$this->config->item('css_url');?>/style.css' type='text/css'>
  <script src='<?=$this->config->item('js_url');?>/libs/modernizr-2.5.3.min.js' type="text/javascript"></script>
</head>
<body>
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser  </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div id="fb-root"></div>
<script type="text/javascript">

  window.fbAsyncInit = function() {    
    FB.init({appId: '<?=$this->config->item('fb_appkey');?>', status: true, cookie: true,xfbml: true,channelURL : '<?=$base_url;?>/channel.html',oauth:true});
  
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write(' <script src="<?=$this->config->item('js_url');?>/libs/jquery-1.7.1.min.js" type="text/javascript"><\/script>')</script>

<div id='wrapper'>
<div id='content_wrapper'>   
  <div id='content'>
  		<?php		
			include($this->config->item('views_dir').'/home_select_booktype.php');
		?>
  </div>
</div> 
</div> 
<script src='<?=$this->config->item('js_url');?>/plugins.js' type="text/javascript"></script>
<script src='<?=$this->config->item('js_url');?>/script.js' type="text/javascript"></script> 
<script src='<?=$this->config->item('js_url');?>/fbfunc.js' type="text/javascript"></script>

<!--personal js and will be consolidated to script.js on product mode--> 
<script src="<?=$this->config->item('js_url');?>/mych.js" type="text/javascript"></script>
</body>
</html>
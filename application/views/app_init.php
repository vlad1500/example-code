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
  <!--<link rel='stylesheet' href='css/ui/jquery-ui-1.8.18.custom.css' type='text/css'>  
  <link rel='stylesheet' href="css/theme_aviary.css" type="text/css" />-->
  <link rel='stylesheet' href="/css/wow_book.css" type="text/css" />
  <link rel='stylesheet' href="/css/preview.css" type="text/css" />
  <script src='/js/libs/modernizr-2.5.3.min.js' type="text/javascript"></script>
</head>
<body>
  <!--[if lt IE 9]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Get Firefox here </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
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
<div id="app_loader" class="hideDiv"><div class="bar"></div></div>
<div id='wrapper'>
<div id='content_wrapper'>   
  <div id='content'>  
  <div id='logo'></div>
    <!-- This is the Tab Filters -->
    <ul class="tabs2">
        <li id="home" class="active"><a href="#my_home">Home</a></li>
        <li id="data"><a href="#fb_data">Filter FB Data</a></li>
        <li id="edit"><a href="#my_edit">Edit</a></li>
        <li id="album"><a href="#my_album">My Albums</a></li>
    </ul>
    <div id='help_about' class="float_right">
    	<p><a class="no_underline" href="#invite">Invite friends</a> | <a id="help" class="no_underline" href="#help">Help</a> | <a id="about" class="no_underline" href="#about">About</a></p>
    </div>
        <!--This is the Container for the Tab-->       
        <div class="tab2_container">  
          <div id="main_inner">
          
            <!--home-->     
                <div id="my_home" class="tab2_content">	
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
<script src='/js/fbfunc.js' type="text/javascript"></script>
<script src='/js/jquery.cookie.js' type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script src="/js/wow_book.min.js" type="text/javascript"></script>
<script src="/js/jsmanipulate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/screen.js"></script>
<script type="text/javascript" src="/js/imageloader.js"></script>
<script type="text/javascript" src="/js/imgscale.js"></script>
<script type="text/javascript" src="/js/pagelayout.js"></script>
<script type="text/javascript" src="/js/jquery.onImagesLoad.js"></script>

<!--<script src="http://feather.aviary.com/js/feather.js" type="text/javascript"></script>-->

<script type="text/javascript">

$(document).ready(function(){
	$.ajax({
	url     : 'main/filter_page',
	type    : 'post',
	success : function(res){
		var _obj = $.parseJSON(res);
		//$(activeTab).html(_obj.data);	
		$('#main_inner').html(_obj.data);			
		$('#fb_data').fadeIn(); //Fade in the active ID content
	}
	});
	
	
});

</script>

<!--personal js and will be consolidated to script.js on product mode--> 
<script src="/js/mych.js" type="text/javascript"></script>

</body>
</html>
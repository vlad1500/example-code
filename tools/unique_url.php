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
<div id="app_loader" class="hideDiv"><div class="bar"></div></div> 
<div id='wrapper'>
<div id='content_wrapper'>   
  <div id='content'>  
  <div id='logo' style="z-index:9999999999999999;"></div>
    <div id='help_about' class="float_right">
    	<p><a class="no_underline" href="#">Help</a> | <a class="no_underline" href="#">About</a></p>
    </div>
        <!--This is the Container for the Tab-->       
        <div class="tab2_container hideDiv">         
          <div id="main_inner">
          
            <!--home-->     
                <div id="my_home" class="tab2_content">
					<div id="edit_main_book">            		                    
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
                            <div id="page-layout_option">
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
                               	<section class="_left" id="page-A">
                                   <div class="canvas_container">    
                                   </div>
                                </section>
                                <section class="_right" id="page-B">
                                   <div class="canvas_container">    
                                   </div>
                                </section>                                                                          
                                <section class="_left" id="page-C">
                                   <div class="canvas_container">    
                                   </div>
                                </section>
                                <section class="_right" id="page-D">
                                   <div class="canvas_container">    
                                   </div>
                                </section>              
                                <section class="_left" id="page-E">
                                   <div class="canvas_container">    
                                   </div>
                                </section>
                                <section class="_right" id="page-F">
                                   <div class="canvas_container">    
                                   </div>
                                </section>                                                              
                                <!--Needed for page setup-->                                   

                            </div><!--end of pages--> 
                         </div><!--end of book-->  
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
<script src='/js/fbfunc.js' type="text/javascript"></script>
<script src='/js/jquery.cookie.js' type="text/javascript"></script>
<script src="/js/booklet/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script src="/js/jquery.rotate.js" type="text/javascript"></script>
<script src="http://feather.aviary.com/js/feather.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){	
	$('#app_loader').fadeIn('fast',function(){ myPageflip(875,625); }).delay(5000).fadeOut('fast',function(){ $('.tab2_container').fadeIn('slow').removeClass('hideDiv'); });
});
</script>
</body>
</html>
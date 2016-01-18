<?php /* Smarty version Smarty-3.1.11, created on 2012-10-22 11:55:04
         compiled from "application/views/welcome.html" */ ?>
<?php /*%%SmartyHeaderCode:183747211450856c586d9ae5-46996737%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9ab5866714118013c59cb52b5497527680a582de' => 
    array (
      0 => 'application/views/welcome.html',
      1 => 1349547281,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183747211450856c586d9ae5-46996737',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'css' => 0,
    'js' => 0,
    'fb_appkey' => 0,
    'base_url' => 0,
    'img' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50856c58829a56_30001748',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50856c58829a56_30001748')) {function content_50856c58829a56_30001748($_smarty_tpl) {?><!doctype html>
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
  <link rel='stylesheet' href='<?php echo $_smarty_tpl->tpl_vars['css']->value;?>
/style.css' type='text/css'> 
  <link rel='stylesheet' href="<?php echo $_smarty_tpl->tpl_vars['css']->value;?>
/wow_book.css" type="text/css" />
  <link rel='stylesheet' href="<?php echo $_smarty_tpl->tpl_vars['css']->value;?>
/preview.css" type="text/css" />
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src='<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/libs/modernizr-2.5.3.min.js' type="text/javascript"></script>
  <style>
  
  		body { overflow: hidden; min-height: 700px; margin-top:-20px; padding-top:20px; }
  
  </style>
</head>
<body>
  <!--[if lt IE 9]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Get Firefox here </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div id="fb-root"></div>
<script type="text/javascript">

  	window.fbAsyncInit = function()  {    
    	FB.init( {
    		appId: '<?php echo $_smarty_tpl->tpl_vars['fb_appkey']->value;?>
', 
    		status: true, 
    		cookie: true,
    		xfbml: true,
    		channelURL : '<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/channel.html',
    		oauth:true
    	}); 
	
		FB.Canvas.setAutoGrow();
	 
	};
  
  window.onload = function() {
	   //Run the timer every 100 milliseconds, you can increase this if you want to save CPU cycles
	}
  
    (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));

</script>

<div id="app_loader" class="hideDiv"><div class="bar"><span></span></div></div>
<div id='wrapper'>
<div id='content_wrapper'>   
  <div id='content'>  
  <div id='logo'></div>
    <!-- This is the Tab Filters -->
    <ul class="tabs2 hideDiv">
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
					  <div id="main_container" role="main">
						<div class="container cont-wrapper">
					    	<div class="heading">
					      		<h2>What is HardCover?</h2>
					        	<h4>HardCover let's you create and share digital albums, including group albums.</h4>
					      	</div>
					        <div class="banner">
					      	<div class="box-group">
					            <div class="boxes boxes-active group-edit">
					            	<div class="default-content">
					                	<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/group-edit-icon.png" alt="Group Edit"/>
					                	<h4>Group Edit</h4>
					                </div>
					                <div class="hover-content">
					                	<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/group-edit-icon2.png" alt="Group Edit"/>
					                	<h4>Group Edit - create a group birthday albums by combining photos from multiple friends.</h4>	
					                </div>
					            </div>
					            <div class="boxes boxes-active print-pdf">
					                <div class="default-content">
					                	<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/print-pdf-icon.png" alt="Print PDF"/>
					                	<h4>Print PDF</h4>
					                </div>
					                <div class="hover-content">
					                	<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/print-pdf-icon2.png" alt="Print PDF"/>
					                	<h4>Save and print PDF's</h4>	
					                </div>
					            </div>
					        </div>
					        <div class="box-group">
					            <div class="boxes boxes-active create-share">
					                <div class="default-content">
					                	<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/create-share-icon.png" alt="Create and Share"/>
					                	<h4>Easily create and share beautiful digital albums</h4>
					                </div>
					                <div class="hover-content">
					                	<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/create-share-icon2.png" alt="Create and Share"/>
					                	<h4>Easily create and share beautiful digital albums</h4>	
					                </div>
					            </div>
					            <div class="boxes boxes-active make-quote">
					                <div class="default-content">
					                	<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/quote-books-icon.png" alt="Make quote books"/>
					                	<h4>Make quote books</h4>
					                </div>
					                <div class="hover-content">
					                	<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/quote-books-icon2.png" alt="Make quote books"/>
					                	<h4>Make quote books - all those amazing Facebook quotes can now live in HardCover</h4>	
					                </div>
					            </div>
					        </div>
					        <div class="box-group2">
					            <div class="boxes box-links">
					                <h4>View Examples</h4>
					                <ul class="unstyled">
					                	<li><a href="">Digital Album</a></li>
					                    <li><a href="">Group Album</a></li>
					                    <li><a href="">Quote Album</a></li>
					                    <li><a href="">PDF</a></li>
					                </ul>
					            </div>
					            <a id="getstarted" class="btn btn-default btn-small center" href="#">Get Started</a>
					        </div>
					      </div>                  </div>
                  </div>	
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
<script>window.jQuery || document.write(' <script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/libs/jquery-1.7.1.min.js" type="text/javascript"><\/script>')</script>

<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script src='<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquerycookie.js' type="text/javascript"></script>

<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/wow_book.min.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/manipulate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/screen.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/imageloader.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/pagelayout.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery.onImagesLoad.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/canvasdraw.js"></script>
<script src='<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/plugins.js' type="text/javascript"></script>
<script src='<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/script.js' type="text/javascript"></script> 
<script src='<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/fbfunc.js' type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/getstarted.js"></script>
<script src='<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/filterpage.js'></script>
<script>
  
$(document).ready(function(){
	$('#main_right').fadeIn('fast',function(){
		$('#fold_left').css({height:$('#book').innerHeight(),top:11});
		$('#fold_right').css({height:$('#book').innerHeight(),left:parseInt($('#book').innerWidth())-20,top:11});
	}).delay(5000);
	
});

</script>

<!--personal js and will be consolidated to script.js on product mode--> 
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/mych.js" type="text/javascript"></script>

</body>
</html><?php }} ?>
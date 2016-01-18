<?php /* Smarty version Smarty-3.1.11, created on 2012-09-29 20:57:01
         compiled from "application\views\getstarted.html" */ ?>
<?php /*%%SmartyHeaderCode:130975067447d03fbf1-10035266%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '98cdacf518f90ec480820dd17a05b0fbdcfcab8c' => 
    array (
      0 => 'application\\views\\getstarted.html',
      1 => 1348944121,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '130975067447d03fbf1-10035266',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'css' => 0,
    'js' => 0,
    'img' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5067447d0dbb13_20781581',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5067447d0dbb13_20781581')) {function content_5067447d0dbb13_20781581($_smarty_tpl) {?><!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/i/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>HardCover</title>
  <meta name="description" content="">

  <!-- Mobile viewport optimized: h5bp.com/viewport -->
  <meta name="viewport" content="width=device-width">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['css']->value;?>
/style.css">

  <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

  <!-- All JavaScript at the bottom, except this Modernizr build.
       Modernizr enables HTML5 elements & feature detects for optimal performance.
       Create your own custom Modernizr build: www.modernizr.com/download/ -->
  <script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/libs/modernizr-2.5.3.min.js"></script>
</head>
<body>
  <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
  <header>
	<div class="navbar">
    	<div class="container">
    		<a href="#" class="brand"><img alt="HardCover" src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
/hardcover-logo.png"></a>
        </div>
    </div>	
  </header>
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
      </div>
      
    </div>
  </div>
  <footer>

  </footer>


  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>

  <!-- scripts concatenated and minified via build script -->
  <script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/plugins.js"></script>
  <script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/getstarted.js"></script>
  <!-- end scripts -->


</body>
</html><?php }} ?>
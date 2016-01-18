<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>HardCover</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 0; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet/less" type="text/css" href="/less/styles.less">
    <script src="/less/less-1.3.0.min.js" type="text/javascript"></script>
	<script>
	
		document.cookie ='welcome_loaded=true; path=/';
	</script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar">
      <div class="navbar-inner">
        <div class="container">
          <!--<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>-->
          <a class="brand" href="#"><img src="/img/hardcover-logo.png" alt="HardCover"/></a>
          <!--<div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div>--><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container cont-wrapper">

      <div class="heading">
      	<h2>What is HardCover?</h2>
        <h4>HardCover let's you create and share digital albums, including group albums.</h4>
      </div>
      <div class="banner">
      	<div class="box-group">
            <div class="boxes boxes-active group-edit">
            	<div class="default-content">
                	<img src="/img/group-edit-icon.png" alt="Group Edit"/>
                	<h4>Group Edit</h4>
                </div>
                <div class="hover-content">
                	<img src="/img/group-edit-icon2.png" alt="Group Edit"/>
                	<h4>Group Edit - create a group birthday albums by combining photos from multiple friends.</h4>	
                </div>
            </div>
            <div class="boxes boxes-active print-pdf">
                <div class="default-content">
                	<img src="/img/print-pdf-icon.png" alt="Print PDF"/>
                	<h4>Print PDF</h4>
                </div>
                <div class="hover-content">
                	<img src="/img/print-pdf-icon2.png" alt="Print PDF"/>
                	<h4>Save and print PDF's</h4>	
                </div>
            </div>
        </div>
        <div class="box-group">
            <div class="boxes boxes-active create-share">
                <div class="default-content">
                	<img src="/img/create-share-icon.png" alt="Create and Share"/>
                	<h4>Easily create and share beautiful digital albums</h4>
                </div>
                <div class="hover-content">
                	<img src="/img/create-share-icon2.png" alt="Create and Share"/>
                	<h4>Easily create and share beautiful digital albums</h4>	
                </div>
            </div>
            <div class="boxes boxes-active make-quote">
                <div class="default-content">
                	<img src="/img/quote-books-icon.png" alt="Make quote books"/>
                	<h4>Make quote books</h4>
                </div>
                <div class="hover-content">
                	<img src="/img/quote-books-icon2.png" alt="Make quote books"/>
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
            <a class="btn btn-default btn-small center" href="" id="get_started" target="_self">Get Started</a>
        </div>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script>
		
  
    	$(document).ready( function () {
			$('.boxes-active').hover( function () {
				$(this).children('.hover-content').fadeIn();
				$(this).children('.default-content').hide();
				//$('.hover-content').fadeIn();
				//$('.default-content').hide();	
			}, function () {
				$(this).children('.default-content').fadeIn();
				$(this).children('.hover-content').hide();
			});
			
			

		});
    </script>

  </body>
</html>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>HardCover</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/bootstrap.min.css">
  <!--<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">-->
  <link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/main.css">
  <link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/website.css">
</head>
<body>
  <!--[if lt IE 9]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Get Firefox here </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div class="container cont-wrapper">
    <div class="heading clearfix">
        <div class="branding brand"></div>
        <!--<a href="#" class="brand" title="HardCover"><img alt="HardCover" src="images/hardcover-logo.png"></a>-->
        <div class="heading__title">Easily create collaborative photo books</div>
    </div>
    <div class="banner3">
      <div class="bubble"></div>
      <div class="object">

      </div>
      <a href="#" class="btn btn-orange btn-cta">Create a book now</a>
    </div><!-- end banner-->
    
    <div id="js-example-books" class="section section--no-bg">
      <div class="row">
        <div class="col-md-12">
          <h4>Example Books</h4>
        </div>
      </div>
      

      <div class="row book-thumbs">
        <div class="col-md-3">
          <div class="panel panel-default panel-book-thumbs">
            <div class="panel-body text-center clearfix">
              <div class="book-thumbs-title">
                <a href="#">Cookie book of wisdom</a>
              </div>
              <a href="#"><img src="images/thumb-1.jpg"/></a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="panel panel-default panel-book-thumbs">
            <div class="panel-body text-center">
              <div class="book-thumbs-title">
                <a href="#">How to use HardCover</a>
              </div>
              <a href="#"><img src="images/thumb-1.jpg"/></a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="panel panel-default panel-book-thumbs">
            <div class="panel-body text-center">
              <div class="book-thumbs-title">
                <a href="#">HardCover for brands</a>
              </div>
              <a href="#"><img src="images/thumb-1.jpg"/></a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="panel panel-default panel-book-thumbs">
            <div class="panel-body text-center">
              <div class="book-thumbs-title">
                <a href="#">Embed books on your site</a>
              </div>
              <a href="#"><img src="images/thumb-1.jpg"/></a>
            </div>
          </div>
        </div>

      </div>

      <footer class="footer">
        <div class="row">
          <div class="col-md-3">
            <p>&copy; copyright 2014.</p>
          </div>
          <div class="col-md-9">
            <ul class="nav nav-pills">
              <li><a href="#">About Us</a></li>
              <li><a href="#">Contact Us</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Terms &amp; Condition</a></li>
            </ul>
          </div>
        </div>
      </footer>

    </div>
</div><!-- end container-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write(' <script src="/js/libs/jquery-1.7.1.min.js" type="text/javascript"><\/script>')</script>
<script src="js/jquery.prettyPhoto"></script>
<script>
$(window).ready( function() {
  var hi = $(window).height();

  if (hi <= 768) {
    $("#js-example-books").addClass('example-books-fixed');
  
  } else {
  
    $("#js-example-books").removeClass('example-books-fixed');
  }

  $(window).resize( function() {
    var hi = $(window).height();

    if (hi <= 768) {
      $("#js-example-books").addClass('example-books-fixed');
    } else {
      $("#js-example-books").removeClass('example-books-fixed');
    }
  });

});
</script>
</body>
</html>
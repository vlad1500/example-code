<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>HardCover</title>
<meta name="description" content="HardCover let's you create and share digital albums including group albums.">
<meta name="viewport" content="width=device-width">
<!--<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/style.css" type="text/css">
<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/jquery-ui.css" />
<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/preview.css" type="text/css" />
<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/prettyPhoto.css" type="text/css" />-->

<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/bootstrap.min.css">
<!--<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">-->
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/main.css">
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/ui/jquery-ui-1.8.18.custom.css" type="text/css">
<link rel="shortcut icon" href="<?php echo $this->config->item("base_url"); ?>/images/fab_con.png">

<script src="<?php echo $this->config->item("js_url"); ?>/libs/modernizr-2.6.2.min.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true}; </script>

<script src="<?php echo $this->config->item("js_url"); ?>/libs/head.min.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/jquery.prettyPhoto.js"> > </script>
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
   
   function inviteFriend(){        
        FB.ui({method: 'apprequests',
            title: 'Add photos to my HardCover book.',
            message: 'Make group photo albums with HardCover.',
        }, 
        function(response) {
				console.log(response);  	
				if (response && response.post_id) {
					alert('Post was published.');
				} else {
					alert('Post was not published.');
				}
			}
        );
    }
</script>
<style>
.whoSeeItem {
    width: 45%;
    border: 1px solid #e9eaed;
    display: inline-block;
    margin: 0 0 13px 13px;
    padding: 0 10px 0 0;
    position: relative;
    vertical-align: top;         
}
.whoSeeItem a {
    color: #3b5998;
    cursor: pointer;
    text-decoration: none;
    font-weight: bold;
    font-size: 13px;
}
.whoSeeItem span {
    padding: 10px;
}
</style> 
</head>
<body>
<div style="display:none;">
<?php
//pirnt_r($signed_data);
?>
</div>
<div id="wrapper">
	<div id="content_wrapper">
		<div id="content" class="content">

			<!-- ===== HEADER ===== -->
			<!--<div class="row">
				<div class="col-xs-12">
					<div class="branding"></div>
				</div>
			</div>-->
			<div class="row">
				<div class="col-sm-2">
					<div class="branding"></div>
				</div>
				<div class="col-sm-10">
					<div class="row">
						<!-- This is the Tab Filters -->
						<div class="col-sm-9">
							<ul class="tabs2 navbar" id="js-nav">
								<li class="active"><a href="#" id="js-home">Home</a></li>
								<li><a href="#" id="js-my-books">My Books</a></li>
								<li ><a href="#" id="js-friends-books">Friends Books</a></li>
								<li ><a href="#" id="js-popular-books">Popular Books</a></li>
							</ul>
						</div>
						<div class="col-sm-3">
							<div id="help_about" class="help">
								<p><a href="#" id="js-website">No App</a> | <a id="invite" class="no_underline" href="javascript:inviteFriend();">Invite friends</a> | <a id="help" class="no_underline" href="#help">Help</a> | <a id="about" class="no_underline" href="#about">About</a></p>
							</div>
						</div>
					</div>
					<div class="row" id="js-dropdown">
						<ul class="dropdown-menu dropdown-menu--books" role="menu">
						   <li><a href="#" id="js-summary">Summary</a></li>
						   <li><a href="#" id="js-name-chapters">Create Book</a></li>
						   <li><a href="#" id="js-upload-images">Add Image</a></li>
						   <li><a href="#" id="js-editor">Editor</a></li>
						   <li><a href="#" id="js-rearrange">Rearrange</a></li>
						   <li><a href="#" id="js-design-cover">Design Cover</a></li>
						   <li><a href="#" id="js-new-seo">SEO</a></li>
		                   <li><a href="#" id="js-new-embed">Embed</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- ===== END HEADER ===== -->

			<div id="js-maincontent" class="maincontent">
				<!-- Content will be loaded here via ajax. -->
			</div>
		</div>
	</div>
</div>

<!-- ===== COMMON LIGHTBOX ===== -->
<div class="modal fade" id="js-modal-common">
    <div class="modal-dialog">
     	<div class="modal-content">
        	<div class="modal-header">
          		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
          		<h4 class="t6 modal-title"></h4>
        	</div>
	        <div class="modal-body">
	        	
	        </div>
      	</div>
    </div>
</div>

<!-- ===== COMMON DIALOG BOX ===== -->
<div class="modal fade" id="js-dialog-common">
    <div class="modal-dialog">
     	<div class="modal-content">
	        <div class="modal-body clearfix">
	        	<div class="dialog-content pull-left"></div>
	        	<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>	
	        </div>
      	</div>
    </div>
</div>


<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>
<script>
head.js(
	'<?php echo $this->config->item("js_url"); ?>/jquery.cookie.js',
	'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js',
	'<?php echo $this->config->item("js_url"); ?>/jsmanipulate.min.js',
	'<?php echo $this->config->item("js_url"); ?>/screen.js',
	'<?php echo $this->config->item("js_url"); ?>/imageloader.js',
	'<?php echo $this->config->item("js_url"); ?>/pagelayout.js',
	'<?php echo $this->config->item("js_url"); ?>/jquery.onImagesLoad.js',
	'<?php echo $this->config->item("js_url"); ?>/canvasdraw.js',
	'<?php echo $this->config->item("js_url"); ?>/plugins.js',
	'<?php echo $this->config->item("js_url"); ?>/script.js',
	'<?php echo $this->config->item("js_url"); ?>/fbfunc.js',
    '<?php echo $this->config->item("js_url"); ?>/sharescript.js',
	'<?php echo $this->config->item("js_url"); ?>/coverscript.js',
	'<?php echo $this->config->item("js_url"); ?>/jquery.flexisel.js'
);


</script>
<script type="text/javascript">
$(document).ready( function () {
    function cleanMenu() {
        $('#js-dropdown').removeClass('open');
    	$('.dropdown-menu').fadeOut(1000);
        $('.branding').css('style', '');
        $('#js-nav').attr('style', '');
        $('#help_about').attr('style', '');
    }
    //var
	// Handling drop-down menu
	$('#js-nav > li').click(function () {
		$('#js-nav > li').removeClass('active');
		$('#js-nav > li').removeClass('open');

		$(this).addClass('active');
	});	
	$('#js-home, #js-friends-books, #js-popular-books').click(function () {
		$('#js-dropdown').removeClass('open');
		$('.dropdown-menu').fadeOut(1000);
		$('.branding').css('style', '');
		$('#js-nav').attr('style', '');
	    $('#help_about').attr('style', '');
	});

	// Loads homepage content on initial load.
	$.ajax({
        type: "GET",
        url: "main/new_home",
        data: { },
        success: function(data){
            $('#js-maincontent').html(data);
            $('#js-maincontent').fadeIn('slow');
            FB.Canvas.setSize();
        }, error: function () {

        }
	});

	// Load this page if there's no book
	$('#js-website').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "GET",
	        url: "/no_hardcover_app",
	        data: { },
	        success: function(data){
	            $('#js-maincontent').html(data);
	            $('#js-maincontent').fadeIn('slow');
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	// Replace Content on main menu click
	$('#js-home').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "GET",
	        url: "../main/new_home",
	        data: { },
	        success: function(data){
	            $('#js-maincontent').html(data);
	            $('#js-maincontent').fadeIn('slow');
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	$('#js-my-books').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "GET",
	        url: "main/new_my_books",
	        data: { },
	        success: function(data){
                $(this).parent().siblings().removeClass('active');
                $(this).parent().addClass('active');
                $('#js-dropdown li a').removeClass('active');                        
                $('.dropdown-menu').fadeOut(1000);
	            $('#js-maincontent').html(data);
	            $('#js-maincontent').fadeIn('slow');
	            $('#js-dropdown .dropdown-menu > li').removeClass('open');
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	$('#js-friends-books').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "GET",
	        url: "main/new_friends_books",
	        data: { },
	        success: function(data){
	            $('#js-maincontent').html(data);
	            $('#js-maincontent').fadeIn('slow');
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	$('#js-popular-books').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "GET",
	        url: "main/new_popular_books",
	        data: { },
	        success: function(data){
	            $('#js-maincontent').html(data);
	            $('#js-maincontent').fadeIn('slow');
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	// Replace Content on drop-down menu click
	$('#js-summary').click(function (e) {
		e.preventDefault();
		$('#js-maincontent').fadeOut('slow');
		var album_id = $.cookie('hardcover_book_info_id');
        console.log(album_id);					
		$.ajax({
            url     : 'main/new_summary',
            type    : 'POST',
            cache   :  true,
            data 	: {'book_info_id':album_id},
            success: function(data){
                var _obj = $.parseJSON(data);
                if(_obj.xBid){
                    cleanMenu();
                    $('#js-maincontent').html(_obj.data);
                	$('#js-maincontent').fadeIn('slow');
                } else {
                    $('#js-maincontent').html(_obj.data);
                    $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
                    $('#js-dropdown .dropdown-menu > li').removeClass('open');
                    $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-dropdown').addClass('open');
    	    		$('#js-summary').addClass('active');
                }
                FB.Canvas.setSize();
            }, error: function () {
            }
		});
		return false;
	});
	
	$('#js-name-chapters, #js-create-book').click(function (e) {
		e.preventDefault();
        var album_id = $.cookie('hardcover_book_info_id');
		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "POST",
	        url: "main/new_names_chapters",
	        data 	: {},
	        success: function(data){
	            var _obj = $.parseJSON(data);
                if(_obj.xBid){
                    cleanMenu();
                    $('#js-maincontent').html(_obj.data);
                	$('#js-maincontent').fadeIn('slow');
                } else {
                    $('#js-maincontent').html(_obj.data);
                    $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
                    $('#js-dropdown .dropdown-menu > li').removeClass('open');
                    $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-my-books').parent().siblings().removeClass('active');
                    $('#js-dropdown').addClass('open');
    		    	$('#js-name-chapters').addClass('active');
                }
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	$('#js-upload-images').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "POST",
	        url: "filter/filter_page",
	        success: function(data){
	        	var _obj = $.parseJSON(data);
                if(_obj.xBid){
                    cleanMenu();
                    $('#js-maincontent').html(_obj.data);
                	$('#js-maincontent').fadeIn('slow');
                } else {
    	            $('#js-maincontent').html(_obj.data);
	                $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
    	            $('#js-dropdown .dropdown-menu > li').removeClass('open');
	                $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-dropdown').addClass('open');
    			    $('#js-upload-images').addClass('active');
                }
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	$('#js-editor').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "POST",
	        url: "main/new_ui_edit_album",
	        success: function(data){
	            //console.log(data);
	        	var _obj = $.parseJSON(data);
                if(_obj.xBid){
                    cleanMenu();
                    $('#js-maincontent').html(_obj.data);
                	$('#js-maincontent').fadeIn('slow');
                } else {
    	            $('#js-maincontent').html(_obj.data);
    	            $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
    	            $('#js-dropdown .dropdown-menu > li').removeClass('open');
    	            $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-dropdown').addClass('open');
        			$('#js-editor').addClass('active');
                }
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	$('#js-rearrange').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "POST",
	        url: "main/new_ui_rearrange",
	        success: function(data){
	        	var _obj = $.parseJSON(data);
                if(_obj.xBid){
                    cleanMenu();
                    $('#js-maincontent').html(_obj.data);
                	$('#js-maincontent').fadeIn('slow');
                } else {
    	            $('#js-maincontent').html(_obj.data);
	                $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
	                $('#js-dropdown .dropdown-menu > li').removeClass('open');
	                $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-dropdown').addClass('open');
    	    		$('#js-rearrange').addClass('active');
                }
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	$('#js-design-cover').click(function (e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "POST",
	        url: "cover/design",
	        success: function(data){
	        	var _obj = $.parseJSON(data);
                if(_obj.xBid){
                    cleanMenu();
                    $('#js-maincontent').html(_obj.data);
                	$('#js-maincontent').fadeIn('slow');
                } else {
    	            $('#js-maincontent').html(_obj.data);
	                $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
	                $('#js-dropdown .dropdown-menu > li').removeClass('open');
	                $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-dropdown').addClass('open');
    	    		$('#js-design-cover').addClass('active');
                }
                FB.Canvas.setSize();
	        }, error: function () {

	        }
    	});
	});

	$('#js-new-seo').click(function (e) {
		e.preventDefault();
        console.log('seo clicked');
		$('#js-maincontent').fadeOut('slow');
		var album_id = $.cookie('hardcover_book_info_id');
        console.log(album_id);					
		$.ajax({
            url     : 'main/new_seo',
            type    : 'POST',
            cache   :  true,
            data 	: {'book_info_id':album_id},
            success: function(data){
                var _obj = $.parseJSON(data);
                if(_obj.xBid){
                    cleanMenu();
                    $('#js-maincontent').html(_obj.data);
                	$('#js-maincontent').fadeIn('slow');
                } else {
                    $('#js-maincontent').html(_obj.data);
                    $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
                    $('#js-dropdown .dropdown-menu > li').removeClass('open');
                    $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-dropdown').addClass('open');
    	    		$('#js-new-seo').addClass('active');
                }
                FB.Canvas.setSize();
            }, error: function () {
            }
		});	
		return false;
	});
	
    
});

</script>

</body>
</html>
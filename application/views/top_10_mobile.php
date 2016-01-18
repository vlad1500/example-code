<?php
    $fbuser_username =  $user_details->fb_username;
    $bookUniqueURL = $this->config->item('base_url').'/books/'.$fbuser_username.'/'.strtolower(str_replace(' ','_',$book_data->book_name));
    $frontImageUrl = $booked_data->book_info->front_cover_location;
    $current_unique_version = "0.02 beta";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Hardcover - <?php echo $book_data->book_name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta property="og:title" content="Hardcover - <?php echo $book_data->book_name; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= $bookUniqueURL; ?>" />
    <meta property="og:image" content="<?=$frontImageUrl ; ?>" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="200" />
    <meta property="og:image:height" content="200" />
    <meta property="og:description" content="<?php echo $book_data->book_desc; ?>" />
    <meta name="description" content="<?php echo $book_data->book_desc; ?>" />
    <meta name="version" content="<?=$current_unique_version ?>" />
    <link rel="stylesheet" href="//code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />
	<link href="<?php echo $this->config->item('base_url'); ?>/mobile_files/photoswipe.css" type="text/css" rel="stylesheet" />
    <link href="<?=$this->config->item('js_url');?>/jquery-ui.css" rel="stylesheet" />
<style type="text/css">
div.gallery-row:after {
	clear:both;
	content:".";
	display:block;
	height:0;
	visibility:hidden;
}
	div.gallery-item {
	float:left;
	width:33.333333%;
}
	div.gallery-item a {
	display:block;
	margin:5px;
	border:1px solid #3c3c3c;
}
	div.gallery-item img {
	display:block;
	width:100%;
	height:auto;
}
	#Gallery1 .ui-content,#Gallery2 .ui-content {
	overflow:hidden;
}
</style>

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="//code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>/js/jquery.waituntilexists.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/libs/head.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>/mobile_files/simple-inheritance.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>/mobile_files/jquery.animate-enhanced.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>/mobile_files/jquery.photoswipe.1.0.11.mod.js"></script>
	<script type="text/javascript">
$(document).ready(function () {
	var fc = <?php echo json_encode($front_cover); ?>;
	var bc = <?php echo json_encode($back_cover); ?>;
	var bUrl = <?php echo json_encode($book_url); ?>;
	var fCount = fc.length;
	var l = window.location;
	var base_url = "<?php echo $this->config->item('base_url'); ?>";
	var temp = new Array();
	for (x=0;x<fCount;x++) {
		var bookUrl = bUrl[x];
		thumbImage = "//images.hardcover.me/uploads/" + 150 + "x" + 150 + "/" + fc[x];
		var top10Book = $(document.createElement('img'))
		                .addClass('top10Book'+x)
		                .appendTo(".top10Layer")
		                .attr("src",thumbImage)
		                .attr("title",base_url+"/books"+bookUrl)
		                .click(function() {
			                var gUrl = $(this).attr("title");
                			window.location.href = gUrl;
                		});
	}
});

	</script>
</head>
<body status="<?php echo $collaborative." ~ ".$can_add_photo; ?>" book_info_id="<?=$book_info_id; ?>">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $this->config->item("fb_appkey"); ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div data-role="page" id="Home">
	<div data-role="header">
		<h1>Hardcover - Top 10 books by: <?php echo $user_details->fname." ".$user_details->lname; ?></h1>
	</div>
	<div data-role="content" class="top10Layer">

	</div>

	<div data-role="footer">
		<h4>&copy; 2014 HardCover</h4>
	</div>
</div>
</body>
</html>
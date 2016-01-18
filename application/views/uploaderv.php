<?php
$fb_id = $_COOKIE['hardcover_fbid']; // the person uploading
//$fb_id = 100004733376032; //stash -> 511891773;
$fb_id_owner = $this->uri->segment(3); // the album owner
$bii = $this->uri->segment(2);
$friend_name = $this->uploadm->friend_name($fb_id);
$friend_img = "https://graph.facebook.com/$fb_id/picture?type=small";
$fb_photos = $this->uploadm->album_photos_raw_data($fb_id);
$url = "https://graph.facebook.com/$fb_id?fields=albums.fields(photos)";
$data = $this->uploadm->friend_fb_photos($url);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item("js_url"); ?>/uploader/css/classicTheme/style.css" />
	<?php /*<link type='text/css' rel='stylesheet' href='<?php echo $this->config->item("js_url"); ?>/carousel/css/liquidcarousel.css' />*/ ?>
	<?php /*<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/carousel/js/jquery-1.4.2.min.js"></script>*/ ?>
	<?php /* <script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/uploader/js/jquery.js"></script> */ ?>
	<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/uploader/js/ajaxupload-min.js"></script>
	<?php /*<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/carousel/js/jquery.liquidcarousel.js"></script>*/ ?>
	<script>
		function show(a, b) {
			document.getElementById(a).style.display = "block";
			document.getElementById(b).style.display = "none";
		}
		/*$(document).ready(function() {
			$('#liquid1').liquidcarousel({
				height: 150,		//the height of the list
				duration: 100,		//the duration of the animation
				hidearrows: false	//hide arrows if all of the list items are visible
			});
		});*/
	</script>
</head>
<body>
<div>Login as <img src="<?php echo $friend_img; ?>"/> <?php echo $friend_name; ?></div>
<br/>
	<input type="button" value="Upload from computer" onclick="show('local', 'fb');"/> <input type="button" value="Upload from Facebook" onclick="show('fb', 'local');"/>
	<span id="local" style="display:none">
		<?php include 'uploader_pc.php'; ?>
	</span>
	<span id="fb">
		<?php include 'uploader_fb.php'; ?>
	</span>
</body>
</html>
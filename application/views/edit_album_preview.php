<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>share_album_preview</title>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>		
		window.jQuery || document.write(' <script src="<?=$this->config->item('js_url');?>/libs/jquery-1.7.1.min.js" type="text/javascript"><\/script>');
		var album_id = null;
		var load_data = <?php echo $json; ?>;
		var canvases = load_data.canvases;		
		
	</script>
	
	<script type="text/javascript" src="/js/libs/modernizr-2.5.3.min.js"></script>
	<script type="text/javascript" src="/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type="text/javascript" src="/js/share_album_preview/wow_book.min.js"></script>
	<script type="text/javascript" src="/js/share_album_preview/share_album_preview.js"></script>
	<link rel="stylesheet" href="/css/share_album_preview/wow_book.css" type="text/css" />
	<link rel="stylesheet" href="/css/edit_album/layout.css" type="text/css" />
	<link rel="stylesheet" href="/css/share_album_preview/share_album_preview.css" type="text/css" />
	
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function()
		{
			if(screen.width <= 1400) {
				$("#hc_book").addClass("scale-80");				
			}
		});
	</script>
	
	<style type="text/css" media="screen">
		#invited, #thanks { background: none !important; }
	</style>

</head>
	<body>		
		<div id="hc_book"></div>
		<div id="measurement"></div>								
	</body>
</html>
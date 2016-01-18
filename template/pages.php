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
  <link rel='stylesheet' href='https://dev.hardcover.me/css/style.css' type='text/css'>
<style type="text/css">
/*Layout 1 - image_left - comment_right*/	
	.pic_cn { width:20%;overflow:hidden; margin:2px 7px 0 2px; }
	.img_layout1 p { padding:5px; box-sizing:border-box; }
	.img_layout1 img { image-rendering: optimizeQuality; -ms-interpolation-mode: bicubic; image-rendering:-webkit-optimize-contrast; }	
    .img_layout1 img:hover { cursor:pointer; }
	.img_layout1 { width:50%; border:none; padding:0;white-space:normal;text-align:center; overflow:hidden; }
	.comment_layout1 { width:42%; }
	.comment_layout1 li { overflow:hidden;width:100%;border-bottom:1px solid #BBB;background:#CDD3E0;margin:2px 1px 0;padding:3px;white-space:normal;box-shadow: 1px 0 0 #CCC;-moz-box-shadow:1px 0 0 #EEE;-webkit-box-shadow:1px 0 0 #EEE; min-height:40px; box-sizing:border-box; }
	.comment_layout1 img { padding:0 5px 0 0; image-rendering: optimizeQuality; -ms-interpolation-mode: bicubic; }
	.comment_layout1 li p { width:75%; padding: 3px 2px 0 5px; line-height: 1em; text-align: left; font-size:10px; }
	.comment_layout1 li p.fbcomment { margin:-15px 0 0 40px !important; font-size:70%;color:#555;line-height: 10px; box-sizing:border-box; }
	.comment_layout1 li p.comment_by { margin-left:40px;color:#565998;font-size:52%;height:26px;line-height: 10px; }
	.comment_layout1 li p.comment_date { color:#999;font-size:52%;margin-top:3px; }
	
/*Layout 2 - image_right- comment_left*/	
	.img_layout2 img:hover { cursor:pointer; }
	.img_layout2 p { padding:5px; box-sizing:border-box; }
    .img_layout2 { width:47%; border:none; padding:0; margin:0 -7px 0 0;white-space:normal;text-align:center; overflow:hidden;}
	.comment_layout2 { width:42%; margin:0 0 0 10px; }
	.comment_layout2 li { overflow:hidden;width:100%;border-bottom:1px solid #BBB;background:#CDD3E0;margin:1px 1px 0;padding:3px;white-space:normal;box-shadow: 1px 0 0 #CCC;-moz-box-shadow:1px 0 0 #EEE;-webkit-box-shadow:1px 0 0 #EEE; min-height:40px; box-sizing:border-box; }
	.comment_layout2 img { padding:0 5px 0 0; image-rendering: optimizeQuality; -ms-interpolation-mode: bicubic;}
	.comment_layout2 li p { width:75%; padding: 3px 2px 0 5px; line-height: 1em; text-align: left; font-size:10px; }
	.comment_layout2 li p.fbcomment { margin:-15px 0 0 40px !important; font-size:70%;color:#555;line-height: 10px; box-sizing:border-box;}
	.comment_layout2 li p.comment_by { margin-left:40px;color:#565998;font-size:52%;height:26px;line-height: 10px; }
	.comment_layout2 li p.comment_date { color:#999;font-size:52%;margin-top:3px; }	
/*Layout 3 - image_top - comment_dont and comment_top - image_down*/	
	.col_layout3_left img:hover,#col_layout3_right img:hover { cursor:pointer; }
    .col_layout3_left { width:48%; padding:0; margin:0;white-space:normal;text-align:center; overflow:hidden; }
    .img_layout3 { width:95%; border:none; padding:5px; margin:0 0 5px; }
	.comment_layout3_left { width:97%; margin:0; }
	.comment_layout3_left li, .comment_layout3_right li { overflow:hidden;width:100%;border-bottom:1px solid #BBB;background:#CDD3E0;margin:1px 1px 0;padding:3px;white-space:normal;box-shadow: 1px 0 0 #CCC;-moz-box-shadow:1px 0 0 #EEE;-webkit-box-shadow:1px 0 0 #EEE; }
	.comment_layout3_left img, .comment_layout3_right img { padding:0 5px 0 0; image-rendering: optimizeQuality; -ms-interpolation-mode: bicubic;}
	.comment_layout3_left li p, .comment_layout3_right li p { padding: 3px; line-height: 1.4em; text-align: justify; font-size:9px; }
	.comment_layout3_left li p.fbcomment, .comment_layout3_right li p.fbcomment { margin:0; font-size:100%;}
	.comment_layout3_left li p.comment_by, .comment_layout3_right li p.comment_by { color:#565998;font-size:100%;margin: 0 7px 0 0; }
	.comment_layout3_left li p.comment_date, .comment_layout3_right li p.comment_date { color:#999;font-size:100%;margin-top:10px; }	
    .col_layout3_right { width:48%; padding:0; margin:0;white-space:normal;text-align:center; overflow:hidden; }
    .img_layout3_down { width:95%; border:none; padding:0; margin:5px 0 5px -4px; }
	.comment_layout3_right { width:97%; margin:0 0 0 -4px; }
/*Layout 4 1 column - image_top - comment_down*/	
    .img_layout4 img:hover { cursor:pointer; }
	.img_layout4 p { padding:5px; box-sizing:border-box; }
    .img_layout4 { width:100%; border:none; padding:0; margin:0;white-space:normal;text-align:center; overflow:hidden; }
	.comment_layout4 { width:97%; margin:5px 0; }
	.comment_layout4 li { overflow:hidden;width:100%;border-bottom:1px solid #BBB;background:#CDD3E0;margin:1px 1px 0;padding:3px;white-space:normal;box-shadow: 1px 0 0 #CCC;-moz-box-shadow:1px 0 0 #EEE;-webkit-box-shadow:1px 0 0 #EEE; min-height:40px; }
	.comment_layout4 img { padding:0 5px 0 0; image-rendering: optimizeQuality; -ms-interpolation-mode: bicubic;}
	.comment_layout4 li p { width:75%; padding: 3px 2px 0 0px; line-height: 1em; text-align: left; font-size:10px; }
	.comment_layout4 li p.fbcomment { margin:-15px 0 0 0px !important; font-size:70%;color:#555;line-height: 10px; box-sizing:border-box;}
	.comment_layout4 li p.comment_by { margin-left:40px;color:#565998;font-size:52%;height:26px;line-height: 10px; }
	.comment_layout4 li p.comment_date { color:#999;font-size:52%;margin-top:3px; }	
/*Layout 5 - comment 1 column*/	
    .comment_layout5 { width:100%; padding:0; margin:2px 0 0; }
	.comment_layout5 li { overflow:hidden;width:46%;border-bottom:1px solid #BBB;background:#CDD3E0;margin:-2px 1px 0;padding:3px;white-space:normal;box-shadow: 1px 0 0 #CCC;-moz-box-shadow:1px 0 0 #EEE;-webkit-box-shadow:1px 0 0 #EEE;display:block; }
	.comment_layout5 img { padding:0 5px 0 0; image-rendering: optimizeQuality; -ms-interpolation-mode: bicubic;}
	.comment_layout5 li p { padding: 3px; line-height: 1.4em; text-align: justify; font-size:9px; }
	.comment_layout5 li p.fbcomment { margin:0; font-size:100%; box-sizing:border-box;}
	.comment_layout5 li p.comment_by { color:#565998;font-size:100%;margin: 0 7px 0 0; }
	.comment_layout5 li p.comment_date, #comment_layout5_right li p.comment_date { color:#999;font-size:100%;margin-top:10px; }
	#wrapper { margin:20px auto; border:1px solid #DDD; min-height:300px; width:100%; box-sizing:border-box; }
	#pages { background:#FAFAFA; width:800px; height:300px; margin:20px auto; border:1px solid #DDD; overflow:auto; padding:20px; }
	.float_left { float:left; }
	.float_right { float:right; }
	.pagefx { background:#CCC; border:1px solid #DDD; overflow:hidden; height: 300px; width: 400px; margin: 5px auto; padding:5px; }
	.cls_qtr { width:50%; height:100%; box-sizing:border-box; position:relative;  }
	.comment_layout0 { width:42%; }
	.comment_layout0 li { overflow:hidden;width:100%;border-bottom:1px solid #BBB;background:#CDD3E0;margin:2px 1px 0;padding:3px;white-space:normal;box-shadow: 1px 0 0 #CCC;-moz-box-shadow:1px 0 0 #EEE;-webkit-box-shadow:1px 0 0 #EEE; min-height:40px; box-sizing:border-box; }
	.comment_layout0 img { padding:0 5px 0 0; image-rendering: optimizeQuality; -ms-interpolation-mode: bicubic; }
	.comment_layout0 li p { width:75%; padding: 3px 2px 0 5px; line-height: 1em; text-align: left; font-size:10px; }
	.comment_layout0 li p.fbcomment { margin:-15px 0 0 40px !important; font-size:70%;color:#555;line-height: 10px; box-sizing:border-box; }
	.comment_layout0 li p.comment_by { margin-left:40px;color:#565998;font-size:52%;height:26px;line-height: 10px; }
	.comment_layout0 li p.comment_date { color:#999;font-size:52%;margin-top:3px; }	
</style>
</head>
<div id="wrapper">
	<div id="pages"></div>
</div>
<body>
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="screen.js"></script>
<script type="text/javascript" src="imgscale.js"></script>
<script type="text/javascript" src="pagelayout_test.js"></script>
<script type="text/javascript">
	$(document).ready(function(e) {
        $.ajax({
			url		: 'book.txt',
			type	: 'GET',
			success	: function(res){
				var _obj = $.parseJSON(res);

					$('#page').pageLayout({
						object		: _obj
					});
			}
		});
    });
</script>
</body>
</html>
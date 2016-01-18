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
  <link rel='stylesheet' href='/css/preview.css' type='text/css'>
  <link rel='stylesheet' href='/css/ui/jquery-ui-1.8.18.custom.css' type='text/css'>  
  <!---link rel='stylesheet' href="/css/theme_aviary.css" type="text/css" /--->
  <style type="text/css">	
	.preview_fbcomment {
		 { margin:0; font-size:90%;color:#555;line-height: 10px;}
	}
	.preview_comment_by { color:#565998;font-size:90%;margin:0;height:26px;line-height: 10px; }
	.preview_comment_date { color:#999;font-size:90%;margin-top:3px; }
	#page_container { width:100%; height:auto; overflow:hidden; padding:5px; margin:20px auto; clear:both; background: #FAFAFA; }
}

</style>
  
  <!---script src='/js/libs/modernizr-2.5.3.min.js' type="text/javascript"></script--->
  
  <script language="JavaScript">
  
  	
  	//Code Starts
	function GetQueryStringParams(sParam)
	{
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++) 
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) 
			{
				return sParameterName[1];
			}
		}
	}
	//Code Ends
	
	// use this when paging is already fixed
	//var v_page_num = GetQueryStringParams("page_num");
	var v_page_num = 1;
	
  </script>
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
<div id="page_container">
    <div id="fold_right" class="hideDiv"><p></p></div>
    <div id="fold_left" class="hideDiv"><p></p></div>                                                          
    <div id='pages'>        
        <div id='cover'></div>                                                                  	          		
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write(' <script src="<?=$this->config->item('js_url');?>/libs/jquery-1.7.1.min.js" type="text/javascript"><\/script>')</script>

<script src='/js/plugins.js' type="text/javascript"></script>
<script src='/js/script.js' type="text/javascript"></script> 
<script src='/js/fbfunc.js' type="text/javascript"></script>
<script src='/js/jquery.cookie.js' type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script src="/js/wow_book.min.js" type="text/javascript"></script>
<script src="/js/jsmanipulate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/screen.js"></script>
<script type="text/javascript" src="/js/imageloader.js"></script>
<script type="text/javascript" src="/js/pagelayout.js"></script>
<script type="text/javascript" src="/js/imgscale.js"></script>
<script type="text/javascript" src="/js/wow_book.min.js"></script>
<script type="text/javascript">
	
	$(document).ready(function(e) {        
	$('#app_loader').fadeIn();					
		var myCtr = 0;		
		var _store_obj;	
		var cls = '';
		var curr_id;
		console.log ('Book dimension :' + book_preview[1] + " " + book_preview[0]);
		$.ajax({
				url 		: 	'/main/get_book_pages/<?=$book_info_id;?>/0',
				type		:	'GET',
				data 		: 	{ 'book_info_id' : 0, 'pagebatch' : 0 },
				success		:	function(res){
								
									var _obj = $.parseJSON(res);
									_store_obj = _obj;									
									var pagenum = '';		
									//console.log('return:'+_obj.book_pages);
									if (_obj.book_pages===false){
										alert('Your book has no content. Try changing your filter info.');
									}else{
										
										$('#pages').append('<div class="pagefx"></div>');
										$.each(_obj.book_pages,function(i,el){		
											var _con = el.connection;
											var _fbdata = el.fbdata;
											var _layout = el.page_layout;
											var _id = el.fbdata.id;
											var _cmt = el.comment;
											
											$.loadImage(el.fbdata.source +'~'+ el.fbdata.id);
											
											$('#page').pageLayout({
												comment 	: _cmt,
												fbdata  	: _fbdata,										
												layout  	: _layout,
												divId		: _id,
												connection 	: _con
											});
	
										})//End of each._obj.book_pages
										
									}
								},
								
				complete	: function(){
								
									$('#pages').append('<div class="pagefx"></div>');
									$('#pages').append('<div id="back_cover"></div>');
									
									
										
									$('#pages').wowBook({
										 height : book_preview[1]
										,width  : book_preview[0]
										,centeredWhenClosed : false
										,hardcovers : true
										,turnPageDuration : 500
										//,numberedPages : [2,-3]
										,gutterShadow	  : false	
										,flipSound     	  : false
										,transparentPages : true
										,updateBrowserURL : true
										,pageNumbers	  :	false
										,controls : {
												zoomIn    : '#zoomin',
												zoomOut   : '#zoomout',
												next      : '#fold_right',
												back      : '#fold_left',
												first     : '#first',
												last      : '#last',
												slideShow : '#slideshow'
											}
										,hardPages		  : true 	
									}).css({'display':'none', 'margin':'auto'}).fadeIn(1000);
									$.wowBook("#pages").gotoPage('#cover');
									
									$('#app_loader').fadeOut('slow');
									$("#cover").click(function(){
										$.wowBook("#pages").advance();
									});
									
									var _book = $.wowBook("#pages");
									
									$('#fold_left').css({height:$('#pages').innerHeight(),top:$('#pages').offset().top, left:($('#pages').offset().left)-30 }).fadeIn('slow');
									$('#fold_right').css({height:$('#pages').innerHeight(),left:$('#pages').offset().left + $('#pages').innerWidth() - 10 ,top:$('#pages').offset().top}).fadeIn('slow');	
									
									$('#button_container').width($('#pages').width()).css({ 'left' : $('#pages').offset().left - 20 });
									
						}//end of complete function
						
					
			});
					
		/*
		$('#page-layout_option ul li').each(function(){
			$(this).animate({'opacity' : 1}).hover(function() {
				$(this).animate({'opacity' : .3});
			}, function() {
				$(this).animate({'opacity' : 1});
			}).live('click',function(){
				var _book = $.wowBook("#pages");
				var _layout = $(this).attr('id').substr(7,1);
				var book_id = $.cookie('hardcover_book_info_id');
				var _div = $.cookie('div_');				
				var _lp;		 					 
				
				if ( cls == '_left' ){					
					 _lp = _book.otherPage(_book.currentPage);	
					 //_book.pageIsOnTheLeft(_lp) == true ? _lp : _lp = _book.currentPage;				
				}else{
					 _lp = _book.currentPage;	
					 //_book.pageIsOnTheLeft(_lp) == true ? _lp = _book.otherPage(_book.currentPage) : _lp = _book.currentPage;					
				}

				__pagenum = '';				
				//ret_object == undefined ? ret_object = _store_obj : ret_object;
				////console.log(ret_object);
				$('#page').pageLayout({										
					layout  	: _layout,
					divId		: _div,
					isObject	: true,
					object		: _store_obj,
					pagenum		: _lp	
				});				
			});
		});
		*/
	});	

</script>

</body>
</html>

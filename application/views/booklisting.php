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
  <!--<link rel='stylesheet' href='css/ui/jquery-ui-1.8.18.custom.css' type='text/css'>  
  <link rel='stylesheet' href="css/theme_aviary.css" type="text/css" />-->
  <link rel='stylesheet' href="/css/wow_book.css" type="text/css" />
  <!--<link rel='stylesheet' href="/css/preview.css" type="text/css" />-->
  <script src='/js/libs/modernizr-2.5.3.min.js' type="text/javascript"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write(' <script src="<?=$this->config->item('js_url');?>/libs/jquery-1.7.1.min.js" type="text/javascript"><\/script>')</script>
</head>
<body>
  <!--[if lt IE 9]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Get Firefox here </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div id="fb-root"></div>
<script type="text/javascript">
  var fb_username = '<?=$fb_username;?>';
  window.fbAsyncInit = function() {    
    FB.init({appId: '<?=$this->config->item('fb_appkey');?>', status: true, cookie: true,xfbml: true,channelURL : 'https://dev.hardcover.me/fbapp/channel.html',oauth:true});  
  
	
	FB.getLoginStatus(function(response){
		if (response.status == 'connected'){
			FB.api('/me',function(res){	
				if (res.username==fb_username){
					 $("#wrapper").removeClass('hideDiv');
				}else{
					window.location = "<?=$base_url;?>main/error/404";
				}
			});		
		}else{			
			window.location = "http://www.facebook.com/dialog/oauth/?client_id=<?=$this->config->item('fb_appkey');?>&redirect_uri=<?=$base_url;?>books/<?=$fb_username;?>";
		}
	},true);	
	
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
<div id='wrapper' class="hideDiv">
<div id='content_wrapper'>   
  <div id='content'>  
  <div id='logo'></div>
    <!-- This is the Tab Filters -->

    <div id='help_about' class="float_right">
    	<p><a class="no_underline" href="#invite">Invite friends</a> | <a id="help" class="no_underline" href="#help">Help</a> | <a id="about" class="no_underline" href="#about">About</a></p>
    </div>
        <!--This is the Container for the Tab-->       
        <div class="tab2_container">  
          <div id="main_inner">          
           
            
            <div id='album_summary'>
                <div id="album_created">
                <h3>Albums I've created</h3>
                <table id="album_table">
                    <thead>
                        <tr>
                            <th width="30%" class="left-edge">Album Name</th>
                            <th width="10%">Pages</th>
                            <th width="10%">Last Save</th>
                            <th width="20%">New Comments</th>
                            <th width="10%">Share with Friends</th>
                            <th width="10%">Save as PDF</th>
                            <th width="10%" class="right-edge">Print Book</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($booklist as $book_info){
                        $last_save = date('m/d/Y',strtotime($book_info->modify_date));
                        $pdf_href_file = $this->config->item('base_url') .'/tools/book_pdfs/content_'.$book_info->book_info_id.'.pdf';
                        $pdf_dir_file = $this->config->item('tools') .'/book_pdfs/content_'.$book_info->book_info_id.'.pdf';
                        if (file_exists($pdf_dir_file))
                            $pdf_link = '<a target="_blank" href="'.$pdf_href_file.'" ><img src="'.$base_url.'/images/HardCover_pdf_icon.png"/></a>';
                        else
                            $pdf_link = 'no pdf yet';
                        
                        echo '<tr>
                                <td><a href="#" id="'.$book_info->book_info_id.'" class="book_summary_class">'.$book_info->book_name.'</a></td>
                                <td>'.$book_info->total_pages.'</td>
                                <td>'.$last_save.'</td>
                                <td>'.$book_info->total_newcomments.'</td>
                                <td><a href="#" rel="'.$book_info->book_info_id.'" class="share_url"><img src="'.base_url().'/images/HardCover_fsharebtn.png"/></a></td>
                                <td>'.$pdf_link.'</td>
                                <td><img src="'.base_url().'/images/HardCover_buy_icon.png"/></td>
                            </tr>';
                    }
                    ?>
                    </tbody>
                </table>
                </div>
                <div id="quotes_created">
                <h3>Quotes</h3>
                <table id="album_quotes">
                    <thead>
                        <tr>
                            <th width="30%" class="left-edge">Album Name</th>
                            <th width="30%">Quotes</th>
                            <th width="10%">Last Save</th>
                            <th width="10%">Share with Friends</th>
                            <th width="10%">Save as PDF</th>
                            <th width="10%" class="right-edge">Print Book</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>test</td>
                        <td>test</td>
                        <td>test</td>
                        <td><img src="<?php echo base_url().'/images/HardCover_fsharebtn.png'; ?>"/></td>
                        <td><img src="<?php echo base_url().'/images/HardCover_pdf_icon.png'; ?>"/></td>
                        <td><img src="<?php echo base_url().'/images/HardCover_buy_icon.png'; ?>"/></td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>            
           
          </div><!--End of main_inner-->                    
        </div><!--End of tab2_container--> 
      </div>
	</div>
  </div>
</div>
</div>    


<script src='/js/plugins.js' type="text/javascript"></script>
<script src='/js/script.js' type="text/javascript"></script> 
<script src='/js/fbfunc.js' type="text/javascript"></script>
<script src='/js/jquery.cookie.js' type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script src="/js/wow_book.min.js" type="text/javascript"></script>
<script src="/js/jsmanipulate.min.js" type="text/javascript"></script>


<!--<script src="http://feather.aviary.com/js/feather.js" type="text/javascript"></script>-->

<script type="text/javascript">
$(document).ready(function(){
	$('#main_right').fadeIn('fast',function(){
		//PageFlip();
		//myPageflip(400,200);
		$('#fold_left').css({height:$('#book').innerHeight(),top:11});
		$('#fold_right').css({height:$('#book').innerHeight(),left:parseInt($('#book').innerWidth())-20,top:11});
	}).delay(5000);
	
	$('#pages').wowBook({
		 height : 200
		,width  : 500
		,centeredWhenClosed : false
		,hardcovers : true
		,turnPageDuration : 500
		//,numberedPages : [2,-3]
		,flipSound     	  : false
		,transparentPages : true
		,updateBrowserURL : true
		,pageNumbers	  :	false
		,controls : {
				zoomIn    : '#zoomin',
				zoomOut   : '#zoomout',
				next      : '#next',
				back      : '#back',
				first     : '#first',
				last      : '#last',
				slideShow : '#slideshow'
			},
	}).css({'display':'none', 'margin':'auto'}).fadeIn(1000);
	
});
</script>

<script type="text/javascript">
	$(function(){
			$("a").animate({'opacity' : 1}).hover(function() {
				$(this).animate({'opacity' : .5});
			}, function() {
				$(this).animate({'opacity' : 1});
			});	
			
			$('a.book_summary_class').each(function(){
				$(this).live('click',function(){
					$('#app_loader').fadeIn();
					var album_id = $(this).attr('id');
					$.cookie('hardcover_book_info_id',album_id);
					$.ajax({
						url     : '/main/edit_album',
						type    : 'post',
						cache   :  true,
						data 	: {'book_info_id':album_id},
						success : function(res){
							$('#app_loader').fadeOut();
							var _obj = $.parseJSON(res);
							$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
							$('ul.tabs2 li#edit').addClass("active").fadeIn(); //Add "active" class to selected tab				
							$('#main_inner').html(_obj.data);
							$('#my_edit').fadeIn(); //Fade in the active ID content										
						},	
						error	: function(result){
							$('#app_loader').fadeOut();
							alert(result.textStatus);
						}		
						
					});	
					return false;
				});
			});
			
			$('a.share_url').css({'cursor':'pointer'});					
			
			$('a.share_url').each(function(){
				$(this).live('click',function(e){
						var _rel = $(this).attr('rel');
						var _book = $('a#'+_rel).text();
						
						FB.ui(
						  {
							method: 'feed',
							name: _book,
							link: '<?php echo $this->config->item("base_url");?>/books/'+_rel+'?page_num=1',
							picture: 'https://hardcover.shoppingthing.com/images/slide2/HardCover_logo.png',
							caption: 'HardCover Application',
							description: 'Make a book of your FB album.'
						  },
						  function(response) {
							if (response && response.post_id) {
							  alert('Your book was published.');
							} else {
							  //alert('Post was not published.');
							}
						  }
						);		
												
						e.preventDefault();
				});
			});
			
	})
</script>
<!--personal js and will be consolidated to script.js on product mode--> 
<!--<script src="/js/mych.js" type="text/javascript"></script>-->

</body>
</html>
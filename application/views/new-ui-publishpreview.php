<?php
    $cur_user = $_COOKIE["c_user"];
    $fbuser_username =  $user_details->fb_username;
    $bookUniqueURL = $this->config->item('base_url').'/books/'.$fbuser_username.'/'.strtolower(str_replace(' ','_',$book_data->book_name));
    $url_ref = $this->config->item('base_url').'/books/'.$fbuser_username.'/'.strtolower(str_replace(' ','_',$_COOKIE['book_name']));
?>
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $_POST["book_name"]; ?> - HardCover Preview and Publish Page</title>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/friendchooser/friendChooserMinimalistic.css" />
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/main.css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/jquery.quickfit.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/jquery.facebook.multifriend.select.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/friendchooser/jquery.friendChooser-packed.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/image.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/Book_.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/Page_.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/Main_.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/Thumb_.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/Detector_.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/IScroll4Custom_.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/Lightbox_.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/THREEx.FullScreen_.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/script_unique.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/jquery.pagination.js"></script>
<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-511636cd637f5aa6"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/publish-preview.js"></script>

<script type="text/javascript">
$(document).ready( function(){

	// Calling the publish modal
	$('#js-publish-book').click( function() {

		$('#js-publish-book-modal').modal();

	});

    // Calling the Friends List modal
    $('#js-friendslist').click( function(e) {

        e.preventDefault();
        $('#js-friendslist-modal').modal();

    });

	// Steps Events
	$('#js-publish-book-step1').fadeIn();
	$('#js-publish').hide();
	
	$('.publish-steps li').first().addClass('active');
	
	$('.js-steps-item').click(function () {
		$('.publish-steps li').removeClass('active');
		$(this).parent().addClass('active');
	});

	$('.js-publish-book-step1').click(function() {
		$('#js-publish-book-step2, #js-publish-book-step3, #js-publish-book-publish').hide();
		$('#js-publish-book-step1').fadeIn('slow');

		$('#js-publish').fadeOut('fast', function () {
			$('#js-next').fadeIn();
		});
	});
	$('.js-publish-book-step2').click(function() {
		$('#js-publish-book-step1, #js-publish-book-step3, #js-publish-book-publish').hide();
		$('#js-publish-book-step2').fadeIn('slow');

		$('#js-publish').fadeOut('fast', function () {
			$('#js-next').fadeIn();
		});
	});
	$('.js-publish-book-step3').click(function() {
		$('#js-publish-book-step1, #js-publish-book-step2, #js-publish-book-publish').hide();
		$('#js-publish-book-step3').fadeIn('slow');

		$('#js-next').fadeOut('fast', function () {
			$('#js-publish').fadeIn();
		});
	});

	// Step 1 Events
	$('#js-not-a-collaborative-book').click( function() {
		$('.is-this-a-collab-book').fadeOut();
        $('.js-need_approval_div').fadeOut();
	});

	$('#js-yes-a-collaborative-book').click( function() {
		$('.is-this-a-collab-book').fadeIn();
        $('.js-need_approval_div').fadeIn();
	});


});
</script> 
<script>
var thisURL = '<?=$bookUniqueURL; ?>';
function getRange(current,max) {
    var range = 0;
    for(var i=0;i<=max;i+=20){
        if(current > i && current <= i+20)
            range = i;
    }
    return range;
}
    
function postToFriend(thisID, thisName) {
    var thisUrl = thisURL;
    var thisImg = $("body").attr("coverThumbUrl");
    var thisTitle = "Add photos to <?php echo $book_data->book_name; ?>";
    var thisOwner = "<?php echo $user_details->fname." ".$user_details->lname; ?>";
    var obj = {
        method: 'feed',
        to: thisID,
        link: thisUrl,
        picture: thisImg,
        name: thisTitle,
        caption: 'Hardcover book invite for '+thisName,
        description: 'Your friend '+thisOwner+' is inviting you to view a hardcover book.'
    };
    function callback(response) {
        
    }
    FB.ui(obj, callback);               
} 
function notifyFriend(thisID, thisName){
    var address = "https://graph.facebook.com/" + thisID + "/notifications";
    var tempdata = {};
    var u_url = "<?php echo $bookUniqueURL; ?>";
    u_url = u_url.split("/");                        
    tempdata['access_token'] = "<?php echo $this->config->item("fb_appkey"); ?>|<?php echo $this->config->item("fb_appsecret"); ?>";
    tempdata['href'] = "?user="+u_url[4]+"&book="+u_url[5];
    tempdata['template'] = thisName+", your friend <?php echo $user_details->fname;  ?> <?php echo $user_details->lname; ?> has invited you to view a HardCover book.";
    jQuery.post(address, tempdata , function(data){
        console.log(data);
    });
}
function postToSocial(){                        
    var isFB = $("#postBookToMyFB").is(':checked');
    var isTW = $("#postBookToMyTW").is(':checked');
    var isPR = $("#postBookToMyPR").is(':checked');
    var thisUrl = thisURL;
    var thisImg = $("body").attr("coverThumbUrl");
    var thisTitle = "<?php echo $book_data->book_name; ?>";
    var thisOwner = "<?php echo $user_details->fname." ".$user_details->lname; ?>";
    if(isFB){
        FB.ui({
            method: 'feed',
            name: thisTitle,
            link: thisUrl,
            picture: thisImg,
            caption: 'Click to view this Hardcover Book.',
            description: '<?php echo $book_data->book_desc; ?>'
        },
        function(response) {                                
            if (response && response.post_id) {
                console.log('Post was published.');
            } else {
                console.log('Post was not published.');
            }
        }
        );
    } 
    if(isTW){
        var title = thisTitle;
        var message = 'Hardcover book created by: '+thisOwner+'.';
        var link = 'http://twitter.com/intent/tweet?url='+thisImg+'&text='+title+'. '+message+' '+encodeURI(thisImg)+' '+encodeURI(thisUrl)+'&hashtags=hardcover';                                            
        newWindow = window.open(link,'_blank','width=700,height=260'); 
        newWindow.focus();
    }  
    if(isPR){
        console.log("Pinerest not enabled");
    } 
    doNext();                  
}
function doNext(){
    var curActive = $(".publish-steps li.active");
    var currentStep = curActive.attr("title");                       
    curActive.removeClass("active");  
    console.log("current step: "+currentStep);               
    if(currentStep == 2){
        $( ".publish-steps li:nth-child("+((currentStep*1)+1)+")" ).addClass("active");
        var nextEle = $('#js-publish-book-step'+((currentStep*1)));  
        //nextEle.hide();  
        $('#js-publish-book-publish').fadeIn().show();			 
		nextEle.fadeOut().hide();
    } else if(currentStep == 3){                              
        $('.close').click();
    } else {
        $( ".publish-steps li:nth-child("+((currentStep*1)+1)+")" ).addClass("active");
            var nextEle = $('#js-publish-book-step'+((currentStep*1)+1));                 
            nextEle.fadeIn('slow').siblings().hide();                
            $('#js-publish').fadeOut('fast', function () {
                $('#js-next').fadeIn();
            });    
    }            
}
function loadGhostFriends(){
    jQuery('#popup-chooser').friendChooser({
        display: "popup",
        max: 1,
        min:1,
        useCheckboxes : true,
        showSubmit: true,
        returnData: "all",
        showSelectAllCheckbox: true,
        showCounter: true,                            
        onOpen: function() {
            var thisParent = $("#js-req-to-frnds");
            var position = thisParent.offset();
            var thisLeft = position.left;
            var thisTop = position.top;
            $(this).css("top",thisTop+"px")
            $(this).css("left",thisLeft+"px")
            $(this).slideDown(); 
        },
        onClose: function() { 
            $(".default_add").prop('checked', true);
            $(this).hide();
        },
        lang: {
            title: "Select friends",
            requestTitle: "Select friends",
            requestMessage: "Select friends"
        },       
        onSubmit: function(users) { 
            if(users.length) {
                var friends = "";
                for(i in users) {
                    var thisID = users[i].id;                                    
                    var thisName = users[i].name;;
                    //postToFriend(thisID, thisName);
                    friends += thisID+","; 
                }   
                $("#js-publish-book-step1 #js-user-data-ghost").val(friends);                                                                   
            } else {
                console.log("no friends selected");                                        
            }
        }
    });    
}

function loadPopupChooser(){
    jQuery('#popup-chooser').friendChooser({
        display: "popup",
        max: 0,
        showSubmit: true,
        returnData: "all",
        showSelectAllCheckbox: true,
        showCounter: true,                            
        onOpen: function() {
            var thisParent = $("#js-req-to-frnds");
            var position = thisParent.offset();
            var thisLeft = position.left;
            var thisTop = position.top;
            $(this).css("top",thisTop+"px")
            $(this).css("left",thisLeft+"px")
            $(this).slideDown(); 
        },
        onClose: function() { 
            $(".default_add").prop('checked', true);
            $(this).hide();
        },
        lang: {
            title: "Select friends",
            requestTitle: "Select friends",
            requestMessage: "Select friends"
        },       
        onSubmit: function(users) { 
            if(users.length) {
                var friends = "";
                for(i in users) {
                    var thisID = users[i].id;                                    
                    var thisName = users[i].name;;
                    //postToFriend(thisID, thisName);
                    friends += thisID+",";
                }   
                $("#js-publish-book-step1 #js-user-data").val(friends); 
                                                                                  
            } else {
                console.log("no friends selected");                                        
            }
            $("#total_ghost").html(users.length);
        }
    });    
}
function loadPopupChooserSee(){
    jQuery('#popup-chooser').friendChooser({
        display: "popup",
        max: 999,
        showSubmit: true,
        returnData: "all",
        showSelectAllCheckbox: true,
        showCounter: true,                            
        onOpen: function() {
            var thisParent = $("#js-some-frnd");
            var position = thisParent.offset();
            var thisLeft = position.left;
            var thisTop = position.top;
            $(this).css("top",thisTop+"px")
            $(this).css("left",thisLeft+"px")
            $(this).slideDown(); 
        },
        onClose: function() { 
            $(".default_see").prop('checked', true);
            $(this).hide();
        },
        lang: {
            title: "Select friends",
            requestTitle: "Select friends",
            requestMessage: "Select friends"
        },       
        onSubmit: function(users) { 
            if(users.length) {
                var friends = "";
                for(i in users) {
                    var thisID = users[i].id;                                    
                    var thisName = users[i].name;;
                    //postToFriend(thisID, thisName);
                    friends += thisID+","; 
                }   
                $("#js-publish-book-step1 #js-user-data-see").val(friends);                                                                   
            } else {
                console.log("no friends selected");                                        
            }
        }
    });    
}

$(document).ready(function () {
    /*
	var wW = $(document).width();
    var wH = $(document).height();  
    var bgWidth = wW / 2;
    var bgHeight = ((8 / 11)*bgWidth);
    console.log(bgHeight +" ~ "+ (wH-185)); 
    if(bgHeight > (wH-185)){
        bgWidth = (((wH-185)/8)*11);
        bgHeight = (wH-185);
    } 
    var smWidth = bgWidth / 10;
    var smHeight = bgHeight / 10;
	*/
	var wW=$(document).width();
	var wH=$(document).height();
	if (wW >= 1920) {
        bgWidth = 1920;
        bgHeight = 1440;
        nmWidth = 1440;
        nmHeight = 900;
        smWidth = 640;
        smHeight = 480;
    }
    if (wW < 1920) {
        bgWidth = 1680;
        bgHeight = 1050;
        nmWidth = 1366;
        nmHeight = 768;
        smWidth = 480;
        smHeight = 320;
    }
    if (wW < 1680) {
        bgWidth = 1440;
        bgHeight = 900;
        nmWidth = 1280;
        nmHeight = 1024;
        smWidth = 320;
        smHeight = 240;
    }
    if (wW < 1440) {
        bgWidth = 1366;
        bgHeight = 768;
        nmWidth = 1024;
        nmHeight = 768;
        smWidth = 150;
        smHeight = 150;
    }

	var smWidth=150;
	var smHeight=150;
	var thisURL = '<?=$bookUniqueURL; ?>';
    var thisBookName = '<?php echo $book_data->book_name; ?>';
    var thisOwner = '<?php echo $user_details->fname." ".$user_details->lname; ?>';
    var thisBookDesc = '<?php echo $book_data->book_desc; ?>';
    var bc = "<?=$booked_data->book_info->back_cover_page; ?>";
    var fc = "<?=$booked_data->book_info->front_cover_page; ?>";
    var fc_date = "<?=$booked_data->book_info->front_created_date; ?>";
    var bc_date = "<?=$booked_data->book_info->back_created_date; ?>";
    var book_pages = <?php echo json_encode($booked_data->book_pages); ?>;
    var data_len = <?php echo count($booked_data->book_pages); ?>;
    var author_name = "<?php echo $booked_data->book_info->author_name; ?>";
    var fbScopePerm = '<?php echo $this->config->item("fb_scope_permission"); ?>';
    var bs_id = "<?=$book_setting_data->bs_id; ?>";
    var ask_see_ids = "<?=$user_id; ?>";
    var temp = new Array();
    var xtra_page=0;
    var coverThumbUrl = fc;
    $("body").attr("coverThumbUrl", coverThumbUrl);
    var imgElementString = "";
    var imageDomain = "<?php echo $this->config->item('image_upload_'); ?>";
    var urlToUse = '/timthumb.php?src=' + imageDomain + "/";
    var thisImageUrl = urlToUse;
    var thisNormUrl = urlToUse;
    var thisThumbUrl = urlToUse;
    var curPage = 0;
    var curViewed = "Bookflip";
    var gHash = location.hash;
    if(gHash){
        gHash = gHash.replace("#","");
        gHash = gHash.split("~");
        curViewed = gHash[0];
        if(curViewed == 0) {
            curViewed = "Bookflip";
        } else {
            curPage = gHash[1];
        }
    } else {
        location.hash = curViewed+"~"+curPage;
    }
    //console.log(range);
    var pCount = 0;
    if(data_len%2==1){
   	    xtra_page=2;
    }else{
       	xtra_page=1;
    }

    data_len=data_len+xtra_page;
    var thisBody = $("body");
    if ($("#ImgContainerDiv").length > 0) $("#ImgContainerDiv").remove();
    var ImgContainerDiv = '<div id="ImgContainerDiv" style="display:none;"></div>';
    thisBody.append(ImgContainerDiv);
    var ImgContainerLoadFirstDiv = '<div id="ImgContainerLoadFirstDiv" style="display:none;"></div>';
    thisBody.append(ImgContainerLoadFirstDiv);
    var book_cover = "<?php echo $booked_data->book_info->front_cover_location; ?>";
    var l = window.location;
    var base_url = "<?php echo $this->config->item('base_url'); ?>"; //added this for a more dynamic base url.
    var coverThumbUrl = fc;
    var range = getRange(curPage,data_len);
    $("#js-step2-port").attr("src",book_cover);
    $("body").attr("coverThumbUrl",coverThumbUrl);

    var front_text = '<?php if($book_settings->is_show_book_title): ?><div class="front_book_title"><?php echo $book_data->book_name; ?></div><?php endif; ?><?php if($book_settings->is_show_book_author): ?><div class="front_book_author">by <?php echo $user_details->fname." ".$user_details->lname; ?></div><?php endif; ?>';

    for (var i = 0; i <= data_len; i++) {
        temp[i] = new Object;
        if (i == 0) {
            temp[i].originalSrc = thisImageUrl + fc + '&h=' + bgHeight + '&w=' + bgWidth+ '&zc=2';
            temp[i].originalThumb = thisThumbUrl + fc + '&h=' + smHeight + '&w=' + smWidth+ '&zc=2';
            var nImgUrl = thisImageUrl + fc + '&h=' + bgHeight + '&w=' + bgWidth+ '&zc=2';
            temp[i].title = "Cover";
            temp[i].src = thisImageUrl + fc + '&h=' + bgHeight + '&w=' + bgWidth+ '&zc=2';
            temp[i].thumb = thisThumbUrl + fc + '&h=' + smHeight + '&w=' + smWidth+ '&zc=2';
            temp[i].norm = thisNormUrl + fc + '&h=' + nmHeight + '&w=' + nmWidth+ '&zc=2';
            temp[i].htmlContent = front_text;
            var imgElement = '<img class="pageImg' + (i + 1) + '" src="' + nImgUrl + '" />';
            $("#ImgContainerLoadFirstDiv").append(imgElement)
        } else if (i <= data_len - xtra_page) {
            if (book_pages[i - 1].image_url != "") {
                var bImgUrl = thisImageUrl + book_pages[i - 1].image_url + '&h=' + bgHeight + '&w=' + bgWidth+ '&zc=2';
                var tImgUrl = thisThumbUrl + book_pages[i - 1].image_url + '&h=' + smHeight + '&w=' + smWidth+ '&zc=2';
                var nImgUrl = thisNormUrl + book_pages[i - 1].image_url + '&h=' + nmHeight + '&w=' + nmWidth+ '&zc=2';
                var oImg = thisImageUrl + book_pages[i - 1].image_url + '&h=' + bgHeight + '&w=' + bgWidth+ '&zc=2'
            } else {
                var nImgUrl = "";
                var tImgUrl = ""
            }
            temp[i].originalSrc = bImgUrl;
            temp[i].originalThumb = tImgUrl;
            if (i + 1 <= curPage * 1 + 1 && i + 1 >= curPage * 1 - 1) {
                var imgElement = '<img class="pageImg' + (i + 1) + '" src="' + bImgUrl + '" />';
                $("#ImgContainerLoadFirstDiv").append(imgElement);
                temp[i].src = bImgUrl;
                temp[i].thumb = tImgUrl;
            } else if (i > range && i <= range+20) {
                temp[i].src = bImgUrl;
                temp[i].thumb = tImgUrl;
            } else {
                //imgElementString = imgElementString + oImg + ",";
                temp[i].src = "";
                temp[i].thumb = "";
            }
            temp[i].norm = nImgUrl;
            temp[i].title =  book_pages[i - 1].title;
            temp[i].description =  book_pages[i - 1].description;
            temp[i].fb_username = author_name;
            temp[i].front_cover = fc;
            pCount++;
        } else if (i == data_len) {
            temp[i].originalSrc = thisImageUrl + bc + '&h=' + bgHeight + '&w=' + bgWidth+ '&zc=2';
            temp[i].originalThumb = thisThumbUrl + bc + '&h=' + smHeight + '&w=' + smWidth+ '&zc=2';
            temp[i].title = "Back Cover";
            if (bc) {
                temp[i].src = thisImageUrl + bc + '&h=' + bgHeight + '&w=' + bgWidth+ '&zc=2';
                temp[i].thumb = thisThumbUrl + bc + '&h=' + smHeight + '&w=' + smWidth+ '&zc=2';
                temp[i].norm = thisNormUrl + bc + '&h=' + nmHeight + '&w=' + nmWidth+ '&zc=2';
            }
        }
    }

    if(i%2 == 1){
        temp[i] = new Object();
        temp[i].src =       '/images/preloader.jpg';
        temp[i].thumb = 	'/images/preloader.jpg';
        temp[i].title = 	'last';
    }

    var page = JSON.stringify(temp);
    
    $("#container").attr("current_view","Bookflip");
    $("#container").flipBook({
        pages: jQuery.parseJSON(page),
        lightBox:false,
        pageWidth:bgWidth,
        pageHeight:bgHeight,
        thumbnailWidth:smWidth,
        thumbnailHeight:smHeight,
        webgl:false,
        pageHardness:2.5,
        coverHardness:8,
        zoom:.88,
        pageMaterial:'phong',			
		startPage:0
    });

    $(".main-wrapper").css({"top":"89px","height":"auto"});
    window.resizeTo(screen.width,screen.height);
    var FLheight = $(".bookflipLayer").height();
    var nFLhegith = FLheight - 90;
    console.log(FLheight+" ~ "+nFLhegith);
    
    $(".bookflipLayer").css("height",nFLhegith+"px");        
    
    $('#js_invite_friends_next').on('click', function(e) {
        e.preventDefault();
        var goView = 0;	
        $("#js-invite-friends-view .fb-friend").each(function(){
            if($(this).hasClass("selected-friend")) goView = 1;
        });			
        if(goView == 1)
            jQuery('#js-invite-friends-view').friendChooser('submit');
        else {
            var goAdd = 0;
            $("#js-invite-friends-collab .fb-friend").each(function(){
                if($(this).hasClass("selected-friend")) goAdd = 1;
            });  
            if(goAdd == 1)
                jQuery('#js-invite-friends-collab').friendChooser('submit');  
            else {
                $.ajax({
                    url: '<?=$this->config->item('base_url');?>/publish_book/choose_friends',
                    type: 'post',
                    data: $('form#choose_friends').serialize(),
                    success: function(data) {
                        doNext();			 
                    }
                }); 
            }                
        }            			
    });     
    $(".front_book_title").quickfit({ max: 22, min: 16, truncate: false });           
    $(".front_book_author").quickfit({ max: 22, min: 16, truncate: false });       
});
</script>
<style>
.friends-list span {
    width: 100%;
    padding: 3px;
    display: block;
}
.front_book_title {
    position: absolute;
    top: 15px;
    width:100%;
    text-align:center;
}
.front_book_author {
    position: absolute;
    bottom: 15px;
    width:100%;
    text-align:center;
}
#js-publish-book-publish { display: none; }
.flipbook-shadowRight, .flipbook-shadowLeft, .flipbook-page {    
    margin: auto;
    top: 0;
    bottom: 0;
}
body { min-height: inherit; }
#popup-chooser {
    width: 300px;
    z-index: 999999;
}
div.friend-selector-search input { height: 22px; }
.flipbook-page {
    background:transparent;
}
.likeCon {
    background:transparent;
}
</style>	 		
</head>
<body id="preview" cur_user"<?=$cur_user; ?>">
	<div id="fb-root"></div>
	<script type="text/javascript">
		window.fbAsyncInit = function() {
		    // init the FB JS SDK
		    FB.init({
		      appId      : '<?php echo $this->config->item("fb_appkey"); ?>',                     // App ID from the app dashboard
		      channelUrl : '', // Channel file for x-domain comms
		      status     : true,                                 // Check Facebook Login status
		      xfbml      : true                                  // Look for social plugins on the page
		    });

	    	// Additional initialization code such as adding Event Listeners goes here
	    	$(document).trigger('fbload');
	  	};

		// Load the SDK asynchronously
		(function(d, s, id){
		    var js, fjs = d.getElementsByTagName(s)[0];
		    if (d.getElementById(id)) {return;}
		    js = d.createElement(s); 
		    js.id = id;
		    js.src = "//connect.facebook.net/en_US/all.js";
		    fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

	</script>
	<script type="text/javascript">
        function doFriendChooser(){
                    $('#js-invite-friends-view').friendChooser({
                        display: "inline",
                        showSubmit: false,
                        returnData: "all",
                        max: 0,
                        lang: {
                            title: "Invite Friends to view your book",
                            requestTitle: "Invite Friends to view your book",
                            requestMessage: "Choose friends"
                        },
                        onSubmit: function(users) {
                            console.log("sub click for invite");
                            if(users.length) {
                                var friends = "";
                                for(i in users) {
                                    console.log("go invite "+i);
                                    var thisID = users[i].id;
                                    var thisName = users[i].name;
                                    notifyFriend(thisID, thisName);
                                    friends += users[i].id+",";
                                }
                                $("#js-publish-book-step1 #js-user-data-see").val(friends);
                                var goAdd = 0;
                                $("#js-invite-friends-collab .fb-friend").each(function(){
                                    if($(this).hasClass("selected-friend")) goAdd = 1;
                                });
                                if(goAdd == 1)
                                    jQuery('#js-invite-friends-collab').friendChooser('submit');
                                else {
                                    $.ajax({
                                        url: '<?=$this->config->item('base_url');?>/publish_book/choose_friends',
                                        type: 'post',
                                        data: $('form#choose_friends').serialize(),
                                        success: function(data) {
                                            doNext();
                                    }
                                    });
                                }
                            } else {
                                console.log("no friends invited");
                                jQuery('#js-invite-friends-collab').friendChooser('submit');
                            }
                        }
                    });
                    $('#js-invite-friends-collab').friendChooser({
                        display: "inline",
                        showSubmit: false,
                        returnData: "all",
                        max: 0,
                        lang: {
                            title: "Invite Friends to collaborate on your book",
                            requestTitle: "Invite Friends to collaborate on your book",
                            requestMessage: "Choose friends"
                        },
                        onSubmit: function(users) {
                            if(users.length) {
                                var friends = "";
                                for(i in users) {
                                    var thisID = users[i].id;
                                    var sFriends = $("#js-publish-book-step1 #js-user-data-see").val();
                                    var thisName = users[i].name;
                                    if(sFriends.indexOf(thisID) == -1)
                                        notifyFriend(thisID, thisName);
                                    friends += thisID+",";
                                }
                                $("#js-publish-book-step1 #js-user-data").val(friends);
                                $.ajax({
                                    url: '<?=$this->config->item('base_url');?>/publish_book/choose_friends',
                                    type: 'post',
                                    data: $('form#choose_friends').serialize(),
                                    success: function(data) {
                                        doNext();
                                    }
                                });
                            } else {
                                console.log("no friends selected");
                                $.ajax({
                                    url: '<?=$this->config->item('base_url');?>/publish_book/choose_friends',
                                    type: 'post',
                                    data: $('form#choose_friends').serialize(),
                                    success: function(data) {
                                        doNext();
                                    }
                                });
                            }
                        }
                    });
        }
		$(document).on('fbload', function() {            
			var friends;
            var isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),
                hasTouch = 'ontouchstart' in window && !isTouchPad,
                CLICK_EV = hasTouch ? 'touchend' : 'click'        
                ;
            var hasAlreadyClicked = 0;
			FB.getLoginStatus(function(response) {
        		if (response.status === 'connected') {
        		  $('#js_choose_friends_next').on('click', function(e) {
        		   if(hasAlreadyClicked == 0){
        		    //hasAlreadyClicked = 1;
        	        console.log("publishing book...");
			         e.preventDefault();
                     var self = this;
                     //$(this).on('click', false);
			         $.ajax({
                        url: '<?=$this->config->item('base_url');?>/publish_book/choose_friends',
                        type: 'post',
                        data: $('form#choose_friends').serialize(),
                        success: function(data) {
                            console.log("book published.");
                            doFriendChooser();
                            hasAlreadyClicked = 0;
                            //self.off('click', false);
                            doNext();
                        }
                    });
                   }
                });
                $("#js-req-to-frnds, #js-req-to-frnds-label").on("click", function() {
                    loadPopupChooser();
                    jQuery('#popup-chooser').friendChooser('open');
                });
                
                $("#js-ghostwriter").on("click", function() {
                    loadGhostFriends();
                    jQuery('#popup-chooser').friendChooser('open');
                });
                
                $("#js-some-frnd, #js-some-frnd-label").on("click", function() {
                    loadPopupChooserSee();
                    jQuery('#popup-chooser').friendChooser('open');
                });
                             
                $("#js-post-social").on("click", function() {
                    postToSocial();        
                });                
        	}
        });

	});
	</script>

	<div class="publish-preview">

		<div id="pp_header" class="publish-preview__header">
			<div class="row">
				<div class="col-lg-3">
					<div id="logo" class="branding"></div>
					<h3 class="publish-preview__title h5">Preview Mode</h3>
				</div>
				<div class="col-lg-4">
					<h3 class="publish-preview__book-title h5"><?php echo $book_data->book_name; ?></h3>
				</div>
				<div class="col-lg-5">
					<div class="row">
						<div class="col-lg-12 text-right">
							<span id="user_label" class="user-name">
								<?php echo $user_details->fname;  ?>&nbsp;<?php echo $user_details->lname; ?>
							</span>
							<img class="user-avatar" src="https://graph.facebook.com/<?php echo $book_data->facebook_id; ?>/picture?type=large">
						</div>
						<div class="col-lg-12 text-right">
							
							<?php
								if ($book_data->facebook_id==$user_details->facebook_id){
									echo '<a href="#js-publish-book-modal" class="btn btn-small btn-orange js-publish-book-btn" rel="prettyPhoto" id="js-publish-book">Publish Book</a>';
								}
							?>
							
							<a href="#" class="btn btn-default btn-small" id="js-closepreview">Close Preview</a>	
						</div>
					</div>
				</div>
			</div>
		</div><!-- End publish-preview__header-->

		<div class="modal fade" id="js-publish-book-modal">
		    <div class="modal-dialog">
		     	<div class="modal-content">
		        	<div class="modal-header clearfix">
		          		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		          		<h4 class="modal-title h4 pull-left">Publish Book</h4> 
		          		<ul class="publish-steps">
		          			<li title="1">
		          				<div class="publish-steps__item js-steps-item js-publish-book-step1">
		          					<div class="steps-no img-circle">1</div> Publish
		          				</div>
		          			</li>
		          			<li title="2">
		          				<div class="publish-steps__item js-steps-item js-publish-book-step2">
		          					<div class="steps-no img-circle">2</div> Share
		          				</div>
		          			</li>
		          			<!--
                            <li title="3">
		          				<div class="publish-steps__item js-steps-item js-publish-book-step3">
		          					<div class="steps-no img-circle">3</div> SEO
		          				</div>
		          			</li>-->
                            <li title="3">
		          				<div class="publish-steps__item js-steps-item js-publish-book-publish">
		          					<div class="steps-no img-circle">3</div> Done
		          				</div>
		          			</li>
		          		</ul>
		        	</div><!-- End modal-header -->
			        <div class="modal-body">
			        	<div id="js-publish-book-step1">
				        	<form id="choose_friends" name="choose_friends">
				        		<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Who can see this book?</h3>
								  	</div>
								  	<div class="panel-body">
								    	<label class="radio-inline">
						        			<input name="whocansee"  checked="checked" type="radio" <?php if(isset($book_settings->who_can_see) &&($book_settings->who_can_see == 'all')){?>checked="checked"<?php } ?> class="who-red default_see" value="all" id="optionsRadios1" /> Public Link
						        		</label>
						        		<label class="radio-inline">
						        			<input name="whocansee" type="radio" <?php if(isset($book_settings->who_can_see) &&($book_settings->who_can_see == 'friends')){?>checked="checked"<?php } ?> class="who-red" value="friends" id="optionsRadios2" /> Your Facebook Friends
						        		</label>
						        		<label class="radio-inline">
						        			<input name="whocansee" type="radio" <?php if(isset($book_settings->who_can_see) &&($book_settings->who_can_see == 'some_friends')){?>checked="checked"<?php } ?> class="who-red" value="some_friends" id="js-some-frnd" u_url="<?=$url_ref; ?>" /> Only Some Friends
						        		</label>
								  	</div>
								</div>

								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Is this a collaborative book? (allow others to add content)</h3>
								  	</div>
								  	<div class="panel-body">
								    	
								    	<div class="row" stat="<?=$book_settings->collaborative; ?>">
						        			<div class="col-lg-6">
						        				<label class="radio-inline">
								        			<input type="radio" value="0" <?php if(isset($book_settings->collaborative)&&($book_settings->collaborative == 0)){?>checked="checked"<?php } ?>  name="collaborative" id="js-not-a-collaborative-book" /> No
								        		</label>
								        		<label class="radio-inline">
								        			<input type="radio" value="1" name="collaborative" <?php if(isset($book_settings->collaborative)&&($book_settings->collaborative == 1)){?>checked="checked"<?php } ?> id="js-yes-a-collaborative-book" /> Yes
								        		</label>
								        		<ul class="list-unstyled is-this-a-collab-book every" id="js-every" <?php if(isset($book_settings->collaborative)&&($book_settings->collaborative == 1)){?>style="display:block;"<?php } ?>>
								        			<li><input onclick="show_approval('all');"  checked="checked" name="collaborate_with" type="radio" <?php if(isset($book_settings->who_can_contribute)&&($book_settings->who_can_contribute == 'all')){?>checked="checked"<?php } ?> class="content_approval_p default_add" value="all" /> Everyone</li>
								        			<li><input onclick="show_approval('friends');" name="collaborate_with" type="radio" <?php if(isset($book_settings->who_can_contribute)&&($book_settings->who_can_contribute == 'friends')){?>checked="checked"<?php } ?> class="content_approval_p" value="friends" /> Your Facebook Friends</li>
								        			<li><input id="js-req-to-frnds" name="collaborate_with" type="radio" <?php if(isset($book_settings->who_can_contribute)&&($book_settings->who_can_contribute == 'select')&&($book_settings->collaborative == 1)){?>checked="checked"<?php } ?> value="select" u_url="<?=$url_ref; ?>" /> Only Some Friends</li>
								        		</ul>
						        			</div>
						        			<div class="col-lg-6 js-need_approval_div" <?php if(isset($book_settings->collaborative)&&($book_settings->collaborative == 1)){?>style="display:block;"<?php } else { ?>style="display:none;"<?php } ?>>
						        				<div class="panel panel-default">
													<div class="panel-heading">
														<h3 class="panel-title">Content Approval</h3>
												  	</div>
												  	<div class="panel-body">
												    	<p>Do you want to review the content before it is added to the book? </p>
												    	<label class="radio-inline">
										        			<input checked="checked" id="js-content-appoval-yes" name="content_appoval" type="radio" <?php if(isset($book_settings->content_approval) &&($book_settings->content_approval == 1 )){?>checked="checked"<?php } ?> value="1" /> Yes
										        		</label>
										        		<label class="radio-inline">
										        			<input id="js-content-appoval-no" name="content_appoval" type="radio" <?php if(isset($book_settings->content_approval) &&($book_settings->content_approval == 0)){?>checked="checked"<?php } ?> value="0" /> No
										        		</label>
												  	</div>
												</div>
						        			</div>
						        		</div>

								  	</div>
								</div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Assign a Ghost Writer</h3>
                                    </div>
                                    <div class="panel-body">
                                        <p>Ghost writer assigned to this book: <span id="total_ghost">0</span></p>
                                        <p><a href="#" id="js-ghostwriter">Add / remove Ghost Writers</a></p>
                                    </div>
                                </div>

                                <input type="hidden" name="book_info_id" value="<?php echo $_COOKIE["hardcover_book_info_id"]; ?>" />
				                <input type="hidden" name="user_data" id="js-user-data" value="" />
				                <input type="hidden" name="user_data_see" id="js-user-data-see" value="" />
				                <input type="hidden" name="user_data_ghost" id="js-user-data-ghost" value="" />
				                <input type="hidden" name="share_facebook" id="js-share-facebook" value="t" />
                                <input type="hidden" name="ds_id" id="ds_id" value="<?php if(isset($book_settings->bs_id)){ echo $book_settings->bs_id; } ?>" />
                                <input type="hidden" name="book_url"  type="text" value="<?php echo $bookUniqueURL; ?>" />
				        	</form>
                            <div class="modal-footer">
			        	        <a href="javascript:void(0);" class="btn btn-small btn-orange" id="js_choose_friends_next">Publish</a>
                            </div><!-- End modal-footer -->
                            <div id="popup-chooser"></div>
			        	</div><!-- End js-publish-book-step1 -->

			        	<div id="js-publish-book-step2">
			        		<form>
				        		<div class="panel panel-default">
                                    <p style="padding:5px 15px;"><b>Book URL:</b><br /><a target="_blank" href="<?php echo $bookUniqueURL; ?>"><?php echo $bookUniqueURL; ?></a></p>                                    
									<div class="panel-heading">
										<h3 class="panel-title">Share:</h3>
								  	</div>
								  	<div class="panel-body">								    	
								    	<div class="row">
						        			<div class="col-lg-7">						        				
						        				<div class="media">
												  <a class="pull-left" href="#">
												    <img class="media-object img-responsive" src="<?=$this->config->item('image_url'); ?>/150x150" alt="" id="js-step2-port" />
												  </a>
												  <div class="media-body" style="padding: 15px 20px;">
                                                    <b><?php echo $book_data->book_name; ?></b>											    
												    <p>By: <?php echo $user_details->fname;  ?>&nbsp;<?php echo $user_details->lname; ?></p>
                                                    <p><?php echo $book_data->book_desc; ?></p>
												  </div>
												</div>
						        			</div>
						        			<div class="col-lg-5">
						        				<ul class="list-unstyled">
						        					<li>
						        						<input type="checkbox" name="postBookToMyFB" id="postBookToMyFB" /> Post to my Facebook wall
						        					</li>
						        					<li>
						        						<input type="checkbox" name="postBookToMyTW" id="postBookToMyTW" /> Post to Twitter
						        					</li>
						        					<li>
						        						<input type="checkbox" name="postBookToMyPR" id="postBookToMyPR" /> Post to Pinterest
						        					</li>
						        					<li>
						        						<a href="#">Post to friends wall</a>
						        					</li>
						        				</ul>
						    
						        			</div>
						        		</div>

								  	</div>
								</div><!-- End of Panel -->

								<div class="row">
									<div class="col-lg-6">
										<div class="panel-default">											
										  	<div class="panel-body" id="js-invite-friends-view"></div>
										</div><!-- End of Panel -->
									</div><!-- End of col-lg-6 -->
									<div class="col-lg-6">
										<div class="panel-default">											
										  	<div class="panel-body" id="js-invite-friends-collab"></div>
										</div><!-- End of Panel -->
									</div>
								</div><!-- End of row -->

                                <input type="hidden" name="book_info_id" value="<?php echo $_COOKIE["hardcover_book_info_id"]; ?>" />
				                <input type="hidden" name="user_data" id="js-user-data" value="" />
				                <input type="hidden" name="user_data_see" id="js-user-data-see" value="" />
				                <input type="hidden" name="share_facebook" id="js-share-facebook" value="t" />
                                <input type="hidden" name="ds_id" id="ds_id" value="<?php if(isset($book_settings->bs_id)){ echo $book_settings->bs_id; } ?>" />
				        	</form>
                            <div class="modal-footer">
			        	        <a href="javascript:void(0);" class="btn btn-small btn-orange" id="js_invite_friends_next">Share</a>			        	        
                            </div><!-- End modal-footer -->
			        	</div><!-- End js-publish-book-step2 -->
                                                
			        	<div id="js-publish-book-step3">

			        		<form class="form-horizontal" role="form">
			        			<div class="form-group">
			        				<label class="col-lg-3 control-label"></label>
			        				<div class="col-lg-8">
			        					<label class="radio-inline">
								  			<input type="radio" id="publish-book-with-seo" value="Publish book with SEO" checked="checked"> Publish book with SEO
										</label>
										<label class="radio-inline">
									  		<input type="radio" id="do-not-include-seo" value="Do not include SEO"> Do not include SEO
										</label>
			        				</div>
								</div>
								<div class="form-group">
							    	<label class="col-lg-3 control-label">Book Name:</label>
							    	<div class="col-lg-8">
							      		<input type="text" class="form-control" value="The Cookie Book of Wisdom">
							    	</div>
							  	</div>
							  	<div class="form-group">
							    	<label class="col-lg-3 control-label">Authors Name:</label>
							    	<div class="col-lg-8">
							      		<input type="text" class="form-control" value="Stash Harisson">
							    	</div>
							  	</div>
							  	<div class="form-group">
							    	<label class="col-lg-3 control-label">Addition Meta Tags: <br/> <span class="text-sm">(separate phrases with commas)</span></label>
							    	<div class="col-lg-8">
							      		<textarea class="form-control" rows="3"></textarea>
							    	</div>
							  	</div>

							  	<div class="form-group">
							    	<label class="col-lg-3 control-label">Hashtags:</label>
							    	<div class="col-lg-8">
							    		<div class="row">
							    			<div class="col-lg-2">
							    				<input type="text" class="form-control" value="">
							    			</div>
							    			<div class="col-lg-4">
							    				Post to Facebook
							    			</div>
							    			<div class="col-lg-2">
							    				<input type="text" class="form-control" value="">
							    			</div>
							    			<div class="col-lg-4">
							    				Post to Twitter
							    			</div>
							    		</div>
							    	</div>
							  	</div>

							</form>

			        	</div><!-- End js-publish-book-step3 -->
                        <div id="js-publish-book-publish">
                            <div id="js-book-published-modal">
                                <p style="padding:5px 15px;"><b>Book URL:</b><br /><a target="_blank" href="<?php echo $bookUniqueURL; ?>"><?php echo $bookUniqueURL; ?></a></p>
				                <p>Congratulations!!<br/>Your book is now published.</p>                                 
                            </div>
                            <div class="modal-footer">
			        	        <a href="javascript:void(0);" class="btn btn-small btn-orange" id="js-post-social">Done</a>			        	        
                            </div><!-- End modal-footer -->
                        </div>

			        </div><!-- End modal-body -->
			        
		      	</div><!-- End modal-content -->
		    </div><!-- End modal-dialog -->
		</div><!-- End modal -->

        <div class="modal fade" id="js-friendslist-modal">
            <div class="modal-dialog modal-dialog--friendslist">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <h4 class="modal-title" id="myModalLabel">Select Friendslist</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="clearfix">
                            <div class="form-group">
                                <input type="input" class="form-control" id="Search-friends" placeholder="Search among friends">
                            </div>
                            <div class="media">
                                <a class="pull-left" href="#"><img class="media-object" src="http://placehold.it/40x40"/></a>
                                <div class="media-body">
                                    <h5 class="media-heading">Friends Name</h5>
                                </div>
                            </div>
                            <div class="media">
                                <a class="pull-left" href="#"><img class="media-object" src="http://placehold.it/40x40"/></a>
                                <div class="media-body">
                                    <h5 class="media-heading">Friends Name</h5>
                                </div>
                            </div>
                            <div class="media">
                                <a class="pull-left" href="#"><img class="media-object" src="http://placehold.it/40x40"/></a>
                                <div class="media-body">
                                    <h5 class="media-heading">Friends Name</h5>
                                </div>
                            </div>
                            <div class="media">
                                <a class="pull-left" href="#"><img class="media-object" src="http://placehold.it/40x40"/></a>
                                <div class="media-body">
                                    <h5 class="media-heading">Friends Name</h5>
                                </div>
                            </div>
                            <div class="media">
                                <a class="pull-left" href="#"><img class="media-object" src="http://placehold.it/40x40"/></a>
                                <div class="media-body">
                                    <h5 class="media-heading">Friends Name</h5>
                                </div>
                            </div>
                            <div class="pull-left">
                                <div class="checkbox">
                                    <label><input type="checkbox">Select/Deselect All</label>
                                </div>
                            </div>
                            <div class="pull-right text-right">
                                <button type="submit" class="btn btn-orange">Submit</button> <button type="submit" class="btn btn-default">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

		<div id="container"></div>

		<!-- need this for send message for frinds - start-->
		<div id="fb-root"></div>
		<!-- need this for send message for frinds - end -->
	 
		<div id="publishbook"></div>

	</div><!--End publish-preview -->
    <script>
        // Content Approval
        function show_approval(val) { 
            jQuery('#js-content-appoval-yes').attr('checked','checked');
            $('.js-content-approval').show();     
        }
    </script>
    
</body>
</html>
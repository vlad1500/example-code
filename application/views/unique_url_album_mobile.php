<?php
    $fbuser_username =  $user_details->fb_username;
    $bookUniqueURL = $this->config->item('base_url').'/books/'.$fbuser_username.'/'.strtolower(str_replace(' ','_',$book_data->book_name));
    $frontImageUrl = $booked_data->book_info->front_cover_location;
    $current_unique_version = "0.02 beta";
    setcookie("hardcover_book_info_id",$book_info_id,time()+3600*24,'/');
    setcookie("hardcover_frd_fbid",$user_id,time()+3600*24,'/');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Hardcover - <?php echo $book_data->book_name; ?></title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
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
	<link href="/mobile_files/photoswipe.css" type="text/css" rel="stylesheet" />
    <link href="/js/jquery-ui.css" rel="stylesheet" />
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="/mobile_files/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="/mobile_files/css/jquery.fileupload.css">
    <link rel="stylesheet" href="/mobile_files/css/jquery.fileupload-ui.css">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="/mobile_files/css/jquery.fileupload-noscript.css"></noscript>
    <noscript><link rel="stylesheet" href="/mobile_files/css/jquery.fileupload-ui-noscript.css"></noscript>
    <!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<style type="text/css">
html, body {
background:#000;
}
#Gallery {
    position: relative;
    z-index:1;
}

div.gallery-row:after {
	clear:both;
	content:".";
	display:block;
	height:0;
	visibility:hidden;
}
	div.gallery-item {
	display:table;
	float:left;
	width:33.333333%;
    padding:3px;
}
	div.gallery-item a {
	display:table-cell; vertical-align:middle;
	margin:5px;
}
	div.gallery-item img {
	display:block;
    width:100%;
    border:1px solid #3c3c3c;
}
	#Gallery1 .ui-content,#Gallery2 .ui-content {
	overflow:hidden;
}
.plupload_container {
	min-height:100px;
}
#uploader_dropbox {
	display:none;
}
#uploader_buttons div {
	margin:0;
	padding:5px;
}
.plupload_filelist_footer {
	height:100%;
}
#addFromPhone {
	max-width:700px;
}
#form_filter_data {
	display: none;
}
.file-upload {
    position: relative;
}
.file-upload input {
    position: absolute;
    visibility: hidden;
    top: 0;
    left: 0;
}
.popup {
    display:none;
    width:94%;
    max-height:100%;
    max-width:400px;
    position:absolute;
    top:40%;
    left:0;
    margin:10px;
    padding:5px;
    z-index:9999;
    background:transparent;
    overflow:hidden;
    opacity:.92;
}
.hideMe {
    display:none;
}
.popup h3 {
    color:#fff;
    font-size: 20px;
}
.frontCover {
    width:100%;
    float:left;
}
#coverPic, #coverDetails {
    width:50%;
    display:block;
    float:left;
    padding:5px;
}
#coverDetails p, #coverDetails h3 {
    font-size: .8em;
    color: #fff;
    padding: 0px;
    margin: 0px;
}
#fbLoginDiv {
    background:#fff;
    height: 35%;
    top:30%;
    text-align: center;
}
.facebookButton {
    text-decoration: none;
    height: 23px;
    width: 85%;
    position: absolute;
    left: 6%;
    top: 37%;
}
#thisClose {
    position:absolute;
    bottom:10px;
    right:10px;
}
#addTop10 {
    min-height:96%;
    width:96%;
    top:0;
    left:0;
    background:#000;
    color:#fff;
    padding:10px;
}
#addTop10 ul {
    margin:0;
    padding:0;
}

#addTop10 ul li {
    list-style: none;
    width:100%;
    padding:10px;
}
#sharebuttons {
    position:fixed;
    bottom:45px;
    right:0px;
    width:55px;
    top:auto;
    left:auto;
    background:#ccc;
    opacity:.8;
}
#sharebuttons img {
    width:45px;
    padding:5px;
    opacity:1;
}
#addPhotoPop{
    background:#ccc;
}

</style>

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/libs/head.min.js"></script>
    <script type="text/javascript" src="/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/mobile_files/screenfull.js"></script>
    <script type="text/javascript" src="/mobile_files/simple-inheritance.min.js"></script>
	<script type="text/javascript" src="/mobile_files/jquery.animate-enhanced.min.js"></script>
	<script type="text/javascript" src="/mobile_files/jquery.photoswipe.1.0.11.mod.js"></script>
	<script type="text/javascript">
<?php if ($show_book == 1): ?>
var CurrentView = "imageView";
var l = window.location;
var thisBookUrl = "<?=$bookUniqueURL; ?>";
var thisURL = '<?=$bookUniqueURL; ?>';
var thisBookName = '<?php echo $book_data->book_name; ?>';
var thisOwner = '<?php echo $user_details->fname." ".$user_details->lname; ?>';
var thisBookDesc = '<?php echo $book_data->book_desc; ?>';
var ImageDir = '<?=$this->config->item('image_upload_'); ?>';
var bc = "<?=$booked_data->book_info->back_cover_page; ?>";
var fc = "<?=$booked_data->book_info->front_cover_page; ?>";
var ask_see_ids = "<?=$user_id; ?>";
var fc_date = "<?=$booked_data->book_info->front_created_date; ?>";
var book_pages = <?php echo json_encode($booked_data->book_pages); ?> ;
var data_len = <?php echo count($booked_data->book_pages); ?> ;
var author_name = "<?php echo $booked_data->book_info->author_name; ?>";
var fbScopePerm = '<?php echo $this->config->item("fb_scope_permission"); ?>';
var bs_id = "<?=$book_setting_data->bs_id; ?>";
        var hasClickAdd = 0;
        var hasClickTop = 0;
        var hasClickLike = 0;
        var hasClickShare = 0;
var wW = "";
var wH = "";
var bgWidth = 1920;
var bgHeight = 1440;
var smWidth = 640;
var smHeight = 480;
function fb_login(){
    console.log(ask_see_ids);
    FB.login(function(response) {
        console.log(response);
        if (response.status == "connected") {
            FB.api('/me', function(response) {
                console.log(response);
                $.cookie("hardcover_frd_fbid",response.id,{ expires: 1, path: '/' });
                ask_see_ids = response.id;
            });
            //window.location.reload();
        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');

        }
    }, {
        scope: 'publish_stream,email'
    });
}
function randomString(length, chars) {
    var mask = '';
    if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
    if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (chars.indexOf('#') > -1) mask += '0123456789';
    if (chars.indexOf('!') > -1) mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
    var result = '';
    for (var i = length; i > 0; --i) result += mask[Math.round(Math.random() * (mask.length - 1))];
    return result;
}
    function fbLike(){
        FB.api("/me/og.likes", "POST", {
                "object": thisBookUrl
            },
            function (response) {
                //alert(response.error.code);
                if (response && !response.error) {
                    showWarning(thisBookName+" Liked.");
                } else if(response.error.code == 2500) {
                    //loginFbPop();
                    $("#fbLoginDiv").show();
                } else if(response.error.code == 200) {
                    //loginFbPop();
                    $("#fbLoginDiv").show();
                } else {
                    showWarning(thisBookName+" already liked.");
                }
            }
        );
    }
    function fbShare(){
        var thisImg = ImageDir +"/"+ 640 + "x" + 480 + "/" + fc;
        FB.ui({
            method: 'feed',
            name: thisBookName,
            link: thisBookUrl,
            picture: thisImg,
            caption: "by: "+thisOwner,
            description: thisBookDesc
        },
        function(response) {
            if (response && response.post_id) {
                showWarning('Post was published.')
            } else {
                showWarning('Post was not published.');
            }
        });
    }
    function twShare(){
        var thisImg = ImageDir +"/"+ 640 + "x" + 480 + "/" + fc;
        var link = 'http://twitter.com/intent/tweet?url='+thisBookUrl+'&text='+thisBookName+' - '+thisBookDesc+' '+encodeURI(thisImg)+' '+encodeURI(thisBookUrl)+'&hashtags=hardcover';
        newWindow = window.open(link,'_blank');
        newWindow.focus();
        $(newWindow.document).ready(function(){

        });
    }
    function pnShare(){
        var thisImg = ImageDir +"/"+ 640 + "x" + 480 + "/" + fc;
        var link = '//www.pinterest.com/pin/create/button/?url='+encodeURI(thisBookUrl)+'&media='+encodeURI(thisImg)+'&description='+thisBookName+' - '+thisBookDesc;
        newWindow = window.open(link,'_blank');
        newWindow.focus();
        $(newWindow.document).ready(function(){

        });
    }
    function emShare(){
        var thisImg = ImageDir +"/"+ 640 + "x" + 480 + "/" + fc;
        var link = 'mailto:?subject=HardCover - '+thisBookName+'&amp;body='+thisBookName+'. '+encodeURI(thisBookUrl);
        newWindow = window.open(link,'_blank');
        newWindow.focus();
        $(newWindow.document).ready(function(){

        });
    }
            jQuery('.flipbook-page .email-share').unbind("click").click(function(event){
                var page_number = $(this).parent().parent().attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var link = 'mailto:?subject=HardCover&amp;body=Check out my life in HardCover.'+encodeURI(window.location.href);
                if(em_GO == 1){
                    em_GO = 0;
                    newWindow = window.open(link,'_parent','width=700,height=260');
                    newWindow.focus();
                    $(newWindow.document).ready(function(){
                        setTimeout(function () {
                            em_GO = 1;
                        }, 1000);
                    });
                }
            });
    function showWarning(thisMessage){
        var thisShowEle = '<div id="warningMessageDiv" style="position:absolute;top:30%;left:0;width:100%;height:44px;background:#fff;color:#000;text-align:center;z-index:99999;text-transform:uppercase;font-size:16px;line-height: 44px;">'+thisMessage+'</div>';
        $("#warningMessageDiv").each(function(){
            $(this).remove();
        });
        $("body").append(thisShowEle);
        $("#warningMessageDiv").fadeOut(3500);
    }
    function resizeBody(){
        var bodyWidth = $(document.body).width();
        var bodyHeight = $(document.body).height();
        $(".ps-toolbar").css("width",bodyWidth+"px");
        $(document.body).css("height",((bodyHeight*1)+10)+"px");
    }
    //    function fileExists(url){
    //        var http = new XMLHttpRequest();
    //        http.open('HEAD', url, false);
    //        http.send();
    //        console.log(http.status);
    //        return http.status!=404;
    //    }
    function createBook() {
        var thisPage = "";
        var rowCount = 0;
        for (var i = 0; i <= data_len; i++) {
            var sourceImage = "";
            if(i==0)
                var thisHide = 'style="display:none;"';
            else
                var thisHide = '';
            if (rowCount == 0 && i != 0)
                thisPage += '<div class="gallery-row">';
            if (i == 0 && fc) {
                sourceImage = fc;
            } else if (i == data_len && bc) {
                sourceImage = bc;
            } else {
                sourceImage = book_pages[i - 1].image_url;
            }
            if (sourceImage) {
                var maxHeight = wW / 3;
                fullImage = ImageDir +"/"+ bgWidth + "x" + bgHeight + "/" + sourceImage;
                thumbImage = ImageDir +"/"+ smWidth + "x" + smHeight + "/" + sourceImage;
                if(i==0)
                    $("#coverPic img").attr("src",thumbImage);
                thisPage += '<div class="gallery-item" '+thisHide+'><a href="' + fullImage + '" rel="external" id="'+i+'" class="imgToClick"><img src="' + thumbImage + '" alt="Image ' + i + '" style="max-height:'+maxHeight+'px;" /></a></div>';
                if (rowCount == 2) {
                    thisPage += '</div>';
                    rowCount = 0;
                } else {
                  if(i!=0)
                    rowCount++;
                }
            }
        }

        //$("#Gallery").html(thisPage);
    }
    function clickAddPhoto(){
        //alert(hasClickAdd);
          if(hasClickAdd == 0){
            hasClickAdd = 1;
            setTimeout(function () {
                hasClickAdd = 0;
            }, 2000);
            console.log(ask_see_ids);
            if(ask_see_ids != ""){
                var fileName = "m_"+randomString(30, '#Aa');
                $("#thisRandomFileName").val(fileName);
                $("#addFileInput").click();
            }else
                fb_login();
          }
    }
    function clickTop10(){
        if(hasClickTop == 0){
            hasClickTop = 1;
            setTimeout(function () {
                hasClickTop = 0;
            }, 1000);
            console.log("this is top10 2");
            if($("#addTop10").is(":visible"))
                $( "#addTop10" ).hide();
            else
                $( "#addTop10" ).show();
          }
    }
    function clickLike(){
        if(hasClickLike == 0){
            hasClickLike = 1;
            setTimeout(function () {
                hasClickLike = 0;
            }, 1000);
            fbLike();
          }
    }
    function clickShare(){
          if(hasClickShare == 0){
            hasClickShare = 1;
            setTimeout(function () {
                hasClickShare = 0;
            }, 1000);
            if($("#sharebuttons").is(":visible"))
                $( "#sharebuttons" ).hide();
            else
                $( "#sharebuttons" ).show();
            setTimeout(function () {
                $( "#sharebuttons" ).hide();
            }, 4000);
          }
    }
    function bindButtons(){
        console.log("buttons bound");
        $( ".ps-toolbar-aphoto" ).unbind("touchstart click").bind("touchstart click", function() {
            //showWarning('test: add photo clicked');
            clickAddPhoto();
        });
        $( ".ps-toolbar-top10" ).unbind("touchstart click").bind("touchstart click", function() {
            clickTop10();
        });
        $( ".ps-toolbar-fblike" ).unbind("touchstart click").bind("touchstart click", function() {
            clickLike();
        });
        $( ".ps-toolbar-fbshare" ).unbind("touchstart click").bind("touchstart click", function() {
            clickShare();
        });
        $(".imgToClick").bind("touchstart click", function() {
            var id = $(this).attr("id");
            window.location.hash = 'PhotoSwipe='+id;
            CurrentView = "imageView";
        });
    }
    function doInit(){
        console.log("wait fired");
        var id = window.location.hash;
        if(id.indexOf("PhotoSwipe=") != -1){
            id = id.replace("#PhotoSwipe=","");
            if(id <= data_len)
                $("#"+id+" img").click();
            else{
                $("#0 img").click();
                window.location.hash = "PhotoSwipe=0";
            }
        }else{
            $("#0 img").click();
            setTimeout(function () {
                Code.PhotoSwipe.Current.hide();
            }, 2000);
        }
    }
    function createTop10() {
            var fc = <?php echo json_encode($front_cover); ?> ;
            var bc = <?php echo json_encode($back_cover); ?> ;
            var bUrl = <?php echo json_encode($book_url); ?> ;
            var fCount = fc.length;
            var base_url = "<?php echo $this->config->item('base_url'); ?>";
            var temp = new Array();
            for (x = 0; x < fCount; x++) {
                var bookUrl = bUrl[x];
                thumbImage = ImageDir +"/"+ smWidth + "x" + smHeight + "/" + fc[x];
                var thisLiCon = '<li id="bookLI'+x+'"></li>';
                $(".top10Layer").append(thisLiCon);
                var top10Book = $(document.createElement('img'))
                    .addClass('top10Book' + x)
                    .appendTo("#bookLI"+x)
                    .attr("src", thumbImage)
                    .attr("title", base_url + "/books" + bookUrl)
                    .click(function () {
                        var gUrl = $(this).attr("title");
                        window.location.href = gUrl;
                    });
            }
        }
$(document).ready(function () {
    wW = $(document).width();
    wH = $(document).height();
    if (wW >= 1920) {
        bgWidth = 1920;
        bgHeight = 1440;
        smWidth = 640;
        smHeight = 480;
    }
    if (wW < 1920) {
        bgWidth = 1680;
        bgHeight = 1050;
        smWidth = 480;
        smHeight = 320;
    }
    if (wW < 1680) {
        bgWidth = 1440;
        bgHeight = 900;
        smWidth = 320;
        smHeight = 240;
    }
    if (wW < 1440) {
        bgWidth = 1366;
        bgHeight = 768;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 1366) {
        bgWidth = 1280;
        bgHeight = 1024;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 1280) {
        bgWidth = 1024;
        bgHeight = 768;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 1024) {
        bgWidth = 640;
        bgHeight = 480;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 640) {
        bgWidth = 480;
        bgHeight = 320;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 480) {
        bgWidth = 320;
        bgHeight = 240;
        smWidth = 150;
        smHeight = 150;
    }
    createBook();
    createTop10();

        var doNotLoad = 0;
        if (doNotLoad == 0) {
            doNotLoad = 1;
            setTimeout(function () {
                doInit();
            }, 10);
        }

    $(document).resize(function(){
        resizeBody();
    });
    resizeBody();
});
    document.addEventListener('DOMContentLoaded', function(){
        console.log("im here");
        var thumbEls = Code.photoSwipe('a', '#Gallery', {
			  <?php if (($collaborative == 1 && $can_add_photo == 1) || $book_owner == 1): ?>
              btnAddPhoto: true,
              <?php else : ?>
              btnAddPhoto: false,
              <?php endif; ?>
              doThis: true
        });
	}, false);
<?php endif; ?>
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
<?php if ($show_book != 1): ?>
<p>Login with Facebook to add photo and share</p>
<a href="javascript:void(0);" onclick="fb_login();" class="facebookButton"><img src="https://static.events.ch/img/facebook-connect-button.png" width="100%" /></a>
<?php endif; ?>
<div class="frontCover">
    <div id="coverPic"><img src="" alt="" width="100%" /></div>
    <div id="coverDetails">
        <h3><?php echo $book_data->book_name; ?></h3>
        <p>by: <?php echo $user_details->fname." ".$user_details->lname; ?></p>
        <br/>
        <p><?php echo $book_data->book_desc; ?></p>
    </div>
</div>
<div id="Gallery">

</div>
    <div class="popup" id="sharebuttons">
        <span class="st_facebook" displayText=""><a href="javascript:void(0);" class="fb-share" onclick="fbShare();"><img src="<?php echo $this->config->item('base_url'); ?>/images/facebook.png" alt="Facebook" /></a></span>
        <span class="st_twitter" displayText=""><a href="javascript:void(0);" class="twitter-share" onclick="twShare();"><img src="<?php echo $this->config->item('base_url'); ?>/images/twitter.png" alt="Twitter"/></a></span>
        <span class="st_pinterest" displayText=""><a target="_blank" href="javascript:void(0);" class="pinterest-share" onclick="pnShare();"><img src="<?php echo $this->config->item('base_url'); ?>/images/pinterest.png" alt="Pinterest"/></a></span>
        <span class="st_googleplus" displayText=""></span><span class="st_email" displayText=""><a target="_blank" href="javascript:void(0);" class="email-share" onclick="emShare();"><img src="<?php echo $this->config->item('base_url'); ?>/images/mail.png" alt="mail"/></a></span>
    </div>
    <div class="popup" id="fbLoginDiv" data-theme="b">
        <p>Login with Facebook to add photo and share</p>
        <a href="javascript:void(0);" onclick="fb_login();" class="facebookButton"><img src="https://static.events.ch/img/facebook-connect-button.png" width="100%" /></a>
        <input type="button" onclick="$(this).parent().hide();" id="thisClose" value="Cancel" />
    </div>
    <div class="popup" id="addTop10" data-theme="b">
        <ul data-role="listview" data-inset="true" style="min-width:210px;" class="top10Layer">
            <li data-role="list-divider">Top 10</li>
        </ul>
        <input type="button" onclick="$(this).parent().hide();" id="thisClose" value="Cancel" />
    </div>
    <div class="popup" id="addPhotoPop" data-theme="b">
        <!--Content for the Filter Page-->
<form id="fileupload" action="/" method="POST" enctype="multipart/form-data">
    <div id="filter_content">
        <div>
            <div class='panel-container'>
                <div id="tabs1-computer">
                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="row fileupload-buttonbar">
                        <div class="col-lg-7 hideMe">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Take pic...</span>
                            <input type="file" name="files[]" accept="image/*;capture=camera" id="addFileInput" />
                            </span>
                            <button type="submit" class="btn btn-primary start">
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Start upload</span>
                            </button>
                            <button type="reset" class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel upload</span>
                            </button>
                            <button type="button" class="btn btn-danger delete">
                                <i class="glyphicon glyphicon-trash"></i>
                                <span>Delete</span>
                            </button>
                            <input type="checkbox" class="toggle">
                            <!-- The global file processing state -->
                            <span class="fileupload-process"></span>
                            <input type="hidden" name="name" id="thisRandomFileName" value="" />
                        </div>
                        <!-- The global progress state -->
                        <div class="col-lg-5 fileupload-progress fade">
                            <!-- The global progress bar -->
                            <h3>Uploading photo to book...</h3>
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                            </div>
                            <!-- The extended global progress state -->
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </div>
                    <!-- The table listing the files available for upload/download -->
                    <table role="presentation" class="table table-striped hideMe">
                        <tbody class="files">
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="button_edit_right" class="text-right clearfix hideMe">
                <input id="filter_next_unique" type="button" class="btn btn-orange margin-right-sm filter_next_pro ui-btn-inline" data-mini="true" value="Next" name="submit" />
                <input id="filter_close" onclick="hideoverlay()" type="button" class="btn btn-orange ui-btn-inline" data-mini="true" value="Close" name="submit" />
                <input type="hidden" id="book_info_id" name="book_info_id" value="<?=$book_info_id; ?>" />
            </div>
        </div>
        <!-- End of #tabs-->
    </div>
</form>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script type="text/javascript">
head.js(
    '//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js',
    //'/mobile_files/js/vendor/jquery.ui.widget.js',
    '/mobile_files/js/tmpl.min.js',
    '/mobile_files/js/load-image.min.js',
    '/mobile_files/js/canvas-to-blob.min.js',
    '/mobile_files/js/jquery.blueimp-gallery.min.js',
    '/mobile_files/js/jquery.iframe-transport.js',
    '/mobile_files/js/jquery.fileupload.js',
    '/mobile_files/js/jquery.fileupload-process.js',
    '/mobile_files/js/jquery.fileupload-image.js',
    '/mobile_files/js/jquery.fileupload-audio.js',
    '/mobile_files/js/jquery.fileupload-video.js',
    '/mobile_files/js/jquery.fileupload-validate.js',
    '/mobile_files/js/jquery.fileupload-ui.js',
    '/mobile_files/js/main.js'
);

head.ready(function () {
    var wW = $(window).width();
    var wH = $(window).height();
    var book_id = $("body").attr("book_info_id");
    $("#addFromPhone-popup").css({
        "top": "0",
        "left": "0",
        //"width": wW + "px",
        //"height": wH + "px"
    });
    $("#book_input_id").val(book_id);
    $("#fileupload").show();

    $('#filter_next_unique').unbind("click").on('click', function () {
        console.log("next clicked");
        var book_id = $("body").attr("book_info_id");
        $("#fileupload #book_info_id").val(book_id);
        console.log("click book id: " + $("#fileupload #book_info_id").val());
        $(this).append('<div class="ajax_loader"></div>');
        var form_data = $('#fileupload').serialize();
        var ulink = '/filter/save_book_filter_cover_unique';

        $.ajax({
            cache: false,
            url: ulink,
            type: 'post',
            data: form_data,
            success: function (res) {
                $('.ajax_loader').remove();
                var _obj = $.parseJSON(res);

                if (_obj.status != 0) {
                    showWarning(_obj.msg);
                } else {
                    console.log('in else');
                    /*$('#main_inner_uploder_pop').html('');
								$('#main_inner_uploder_pop').css('display','none');
                                $('#main_inner').html(_obj.data);
                                $('#fb_data').fadeIn(); //Fade in the active ID content
                                $.ajax({
                                    cache   : false,
                                    url     : '../../../filter/createBookCover',
                                    type    : 'post',
                                    data    : { 'book_info_id' : $.cookie('hardcover_book_info_id') },
                                    success : function(res){}
                                });*/
                    //   var pathname = window.location.pathname;
                    //window.location.href = pathname;
                    $('#main_inner_uploder_pop').html('').remove();
                    $('#main_inner_overlay').css('display', 'none');
                    var link = '/main/get_last_insert_images';
                    $.ajax({
                        cache: false,
                        url: link,
                        type: 'post',
                        success: function (res) {
                            var _obj = $.parseJSON(res);

                            $('#last_inset_div ul#cvv_data').html(_obj.data);
                            $('#last_inset_div_a').click();
                            jQuery('#app_loader23').fadeOut(100);
                            window.location = window.location.pathname;
                        }
                    });
                };
            },
            error: function () {}
        });
        return false;
    });
    // Client side form validation
    $('form').submit(function (e) {
        var uploader = $('#uploader').plupload('getUploader');

        // Files in queue upload them first
        if (uploader.files.length > 0) {
            // When all files are uploaded submit form
            uploader.bind('StateChanged', function () {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $('form')[0].submit();
                }
            });
            $('#filter_next_unique').css('background', '#b3b3b3');
            uploader.start();
            $('#filter_next_unique').css('background', '#FF9839');
        } else
            showWarning('You must at least upload one file.');

        return false;
    });
});
</script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<script type="text/javascript">
function hideoverlay(){
	jQuery("#addPhotoPop").hide();
}

function get_album_photos(alb_id) {
    if ($("#id_" + alb_id).is(":checked")) {

        $.ajax({
            url: "../../../main/get_album_photos",
            type: "post",
            data: 'alb_id=' + alb_id,
            success: function (res) {
                if (res != '') {
                    $('#album_photo_raw_data').append(res);
                } else {}
            }
        });
    } else {
        $(".cla_" + alb_id).remove();
    }
}
</script>
    </div>
</body>
</html>
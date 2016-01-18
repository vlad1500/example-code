<?php
    $fbuser_username =  $user_details->fb_username;
    $bookUniqueURL = $this->config->item('base_url').'/books/'.$fbuser_username.'/'.strtolower(str_replace(' ','_',$book_data->book_name));
    $frontImageUrl = $booked_data->book_info->front_cover_location;
	$isTesting = 0;
	$showStatus = 0;
	$alertAgent = 0;
	if($_REQUEST['testImage'] == 1) $isTesting = 1;
	if($_REQUEST['showStatus'] == 1) $showStatus = 1;
	if($_REQUEST['showAgent'] == 1) $alertAgent = 1;
	$current_unique_version = "0.02 beta";
?>

<!DOCTYPE html>
<html>

<head>

<!-- Added on 27/6 for facebook page share -->

<title>Hardcover - <?php echo $book_data->book_name; ?></title>
<meta http-equiv="Cache-control" content="public">
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
<!-- Added on 27/6 for facebook page share -->
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="/min/index.php?g=uniqueCSS" />
<link rel="shortcut icon" href="<?php echo $this->config->item("image_url"); ?>/fab_con.png"  type="image/x-icon" />

<script type="text/javascript">
    var uagent = navigator.userAgent.toLowerCase();
    console.log(uagent);
    var isMobile = false;
    if(navigator.userAgent.match(/android|iPhone/i) != null)
        isMobile = true;
    <?php if($alertAgent): ?>
    alert(isMobile);
    <?php endif; ?>
	var album_id = "<?php
    if($_COOKIE["hardcover_book_info_id"] == "")
        //setcookie("hardcover_book_info_id", $book_info_id, time()+(3600*10));
        $_COOKIE["hardcover_book_info_id"] = $book_info_id;
    echo $_COOKIE["hardcover_book_info_id"];
    ?>";
    console.log("book id: "+album_id);
	var canvases = [];
	var fcover = '<?php echo  $front_cover; ?>';
	var bcover = '<?php echo $back_cover; ?>';
	var switchTo5x=true;
	var l = window.location;
    var base_url = "<?php echo $this->config->item('base_url'); ?>"; //added this for a more dynamic base url.
    var cName = "<?php echo $_COOKIE["hardcover_book_creator_name"]; ?>";
</script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/libs/head.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/json3.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/bootstrap.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/storyjs-embed.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>/min/index.php?g=uniqueJS"></script>
<script>
	//head.js("//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js",
//        "<?php echo $this->config->item("js_url"); ?>/bootstrap.js",
//        "//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js",
//        "<?php echo $this->config->item('base_url'); ?>"+"/min/index.php?g=uniqueJS"
//		);
</script>
<script type="text/javascript">
var isInIframe = (window.location != window.parent.location) ? true : false;
console.log("is iframe: "+isInIframe);
<?php if($show_book == 1): ?>
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
var thumbsHtml = "";
var gallery = "";
<?php endif ?>
var appId = "<?php echo $this->config->item("fb_appkey"); ?>";
var page = "";
var pageGlobal = "";
var curPage = 0;
var curViewed = "Bookflip";
var wW = "";
var wH = "";
var bgWidth = 1920;
var bgHeight = 1440;
var smWidth = 640;
var smHeight = 480;
var gHash = location.hash;
    if(gHash){
        gHash = gHash.replace("#","");
        gHash = gHash.split("~");
        curViewed = gHash[0];
        if(curViewed == 0) {
            curViewed = "Bookflip";
        } else {
            curPage = gHash[1];
           // if(curPage == 0 || curPage % 2)
//                curPage++;
//            else
//                curPage--;
        }
    } else {
        location.hash = curViewed+"~"+curPage;
    }
var dontShow = 0;
var canInitialize = 0;
        var front_text = '<?php if($book_settings->is_show_book_title): ?><div class="front_book_title"><?php echo $book_data->book_name; ?></div><?php endif; ?><?php if($book_settings->is_show_book_author): ?><div class="front_book_author">by <?php echo $user_details->fname." ".$user_details->lname; ?></div><?php endif; ?>';
        var front_invite = "";
        <?php if($user_id == '' && $collaborative == 1): ?>
        var bgUrl = "<?php echo $this->config->item('base_url'); ?>"+"/images/fb-connect.png";
        var front_invite = '<div class="buttonsFrontPage"><div style="position:absolute; top:30%; left:-60%; z-index:99999;"><p>Login with Facebook to add photo to this Book </p><button style="padding:0px; position:relative; top:0px; border:none; box-shadow:0px;background:url('+bgUrl+') no-repeat; height:25px; width:200px;" class="fb_login">&nbsp;</button></div></div>';
        <?php elseif($collaborative == 1 && $can_add_photo == 0): ?>
        var front_invite = '<div class="buttonsFrontPage"><p ><button class="ask_photo_permission" style="top: 30%; left: -68%; position: absolute; background: opacity:.8;" ><b>Ask for permission to Add Photo</b> <br/>Click here</button> </p></div>';
        <?php elseif($collaborative == 1 && $can_add_photo == 1): ?>
        front_invite = '<div class="buttonsFrontPage"><p ><button class="share_here" style="top: 50%; left: -75%; position: absolute; font-size:3em; font-family:arial; background: opacity:.8;" ><b>Add Photo to this book</b> <br/>Click here</button> </p></div>';
        <?php endif; ?>
        <?php if($user_id == $creator_fbid): ?>
	    front_invite = '<div class="buttonsFrontPage"><p><button class="share_here" style="top: 30%; left: -68%; position: absolute; background: opacity:.8;" >Add Photo to this book <br/>Click here</button></p><p><button class="invite-friends" style="top: 45%; left: -68%; position: absolute; z-index:1;" id="js-invite-friends-pop">Invite friends to <br/>View / Add Photo</button></p></div>';
        <?php endif; ?>
                    function postToFriend(e,t){function o(e){}var n=thisURL;var r=$("body").attr("coverThumbUrl");var i=thisBookName;var s={method:"feed",to:e,link:n,picture:r,name:i,caption:"Hardcover book invite for "+t,description:"Your friend "+thisOwner+" is inviting you to view a hardcover book."};FB.ui(s,o)}function postToSocial(){var e=$("#postBookToMyFB").is(":checked");var t=$("#postBookToMyTW").is(":checked");var n=$("#postBookToMyPR").is(":checked");var r=thisURL;var i=$("body").attr("coverThumbUrl");var s=thisBookName;if(e){FB.ui({method:"feed",name:s,link:r,picture:i,caption:"Click to view this Hardcover Book.",description:thisBookDesc},function(e){if(e&&e.post_id){console.log("Post was published.")}else{console.log("Post was not published.")}})}if(t){var o=s;var u="Hardcover book created by: "+thisOwner+".";var a="http://twitter.com/intent/tweet?url="+i+"&text="+o+". "+u+" "+encodeURI(i)+" "+encodeURI(r)+"&hashtags=hardcover";newWindow=window.open(a,"_blank","width=700,height=260");newWindow.focus()}if(n){console.log("Pinerest not enabled")}doNext()}function doNext(){$("#js-publish-book-modal").modal("hide")}function loadInlineChooser(){console.log("in function");$("#js-invite-friends-view").friendChooser({display:"inline",showSubmit:false,returnData:"all",max:0,min:0,lang:{title:"Invite Friends to view your book",requestTitle:"Invite Friends to view your book",requestMessage:"Choose friends"},onSubmit:function(e){console.log("sub click for invite");if(e.length){var t="";for(i in e){console.log("go invite "+i);var n=e[i].id;var r=e[i].name;postToFriend(n,r);t+=e[i].id+","}$("#js-publish-book-step1 #js-user-data-see").val(t);jQuery("#js-invite-friends-collab").friendChooser("submit")}else{console.log("no friends invited");jQuery("#js-invite-friends-collab").friendChooser("submit")}}});$("#js-invite-friends-collab").friendChooser({display:"inline",showSubmit:false,returnData:"all",max:0,min:0,lang:{title:"Invite Friends to collaborate on your book",requestTitle:"Invite Friends to collaborate on your book",requestMessage:"Choose friends"},onSubmit:function(e){if(e.length){var t="";for(i in e){var n=e[i].id;var r=e[i].name;postToFriend(n,r);t+=n+","}$("#js-publish-book-step1 #js-user-data").val(t);$.ajax({url:"/publish_book/choose_friends",type:"post",data:$("form#choose_friends").serialize(),success:function(e){postToSocial()}})}else{console.log("no friends selected");$.ajax({url:"/publish_book/choose_friends",type:"post",data:$("form#choose_friends").serialize(),success:function(e){postToSocial()}})}}});$("#js-publish-book-modal").modal()}
<?php if($isTesting): ?>
function imageLoadTest(){
<?php if($show_book == 1): ?>
        var wW=$(document).width();var wH=$(document).height();if(wW>1920)var bgWidth=1920;if(wW<1921)var bgWidth=1600;if(wW<1601)var bgWidth=1440;if(wW<1441)var bgWidth=1024;if(wW<1025)var bgWidth=640;if(wW<641)var bgWidth=480;if(wW<481)var bgWidth=280;var bgHeight=8/11*bgWidth;var smWidth=bgWidth/10;var smHeight=bgHeight/10;var thisBody=$("body");if($("#ImgContainerDiv").length>0)$("#ImgContainerDiv").remove();var ImgContainerDiv='<div id="ImgContainerDiv" style="display:none;"></div>';thisBody.append(ImgContainerDiv);var popMessage='<div id="timerPopMessage" style="display:block;position:fixed; top:54px; left:0; width:100%; background:#000;color:#fff; text-align:center;font-size:18px;"></div>';thisBody.append(popMessage);var temp=new Array;var xtra_page=0;if(data_len%2==1){xtra_page=2}else{xtra_page=1}var startSite=new Date;var startSec=startSite.getSeconds();var messageStart="<span><b style='color:#ccc'>Site start time:</b> "+startSite+"</span><br/>";$("#StartMessage").append(messageStart);$("#StartMessage").attr("iCount",0);$("#StartMessage").attr("tCount",data_len);for(var i=0;i<=data_len;i++){if(i==0){var nImgUrl="<?php echo $this->config->item('base_url'); ?>"+"/timthumb.php?src="+fc+"&h="+bgHeight+"&w="+bgWidth+"&zc=2"}else if(i<=data_len-xtra_page){if(book_pages[i-1].image_url!=""){var nImgUrl="<?php echo $this->config->item('base_url'); ?>"+"/timthumb.php?src="+book_pages[i-1].image_url+"&h="+bgHeight+"&w="+bgWidth+"&zc=2"}else{var nImgUrl=""}}else if(i==data_len){nImgUrl="<?php echo $this->config->item('base_url'); ?>"+"/timthumb.php?src="+bc+"&h="+bgHeight+"&w="+bgWidth+"&zc=2"}var imgElementString='<img class="pageImg'+i+'" src="'+nImgUrl+'" />';$("#ImgContainerDiv").append(imgElementString);var startTime=new Date;$(".pageImg"+i).attr("sTime",startTime);$(".pageImg"+i).attr("imageNumber",i);$(".pageImg"+i).load(function(){var e=$("#StartMessage").attr("tCount");var t=new Date($(this).attr("sTime"));var n=$(this).attr("imageNumber");var r=new Date-t;var i=(new Date).getSeconds()-t.getSeconds();var s="<span><b style='color:#ccc'>Page Image #:</b> "+n+" | <b style='color:#ccc'>time it took to load:</b> "+i+" seconds</span><br/>";$("#timerPopMessage").append(s);var o=$("#StartMessage").attr("iCount");o++;$("#StartMessage").attr("iCount",o);if(o==e){var u=new Date;var a=(new Date).getSeconds()-startSite.getSeconds();var f="<span><b style='color:#ccc'>Site end time:</b> "+u+" | <b style='color:#ccc'>time it took for site to load:</b> "+a+" seconds</span><br/>";$("#StartMessage").append(f)}})}$("#ImgContainerDiv").show()
<?php endif; ?>
}
<?php else: ?>
    function getRange(current,max) {
        var range = 0;
        for(var i=0;i<=max;i+=20){
            if(current > i && current <= i+20)
                range = i;
        }
        return range;
    }
function goCreateThisBook() { <?php
    if ($show_book == 1): ?>
    //alert("ok.");
    var messageStart = "<span>initializing bookflip...</span><br/>";
    $("#StartMessage").append(messageStart);

    var wW = $(document).width();
    var wH = $(document).height();
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
    //if (wW < 1366) {
//        bgWidth = 1280;
//        bgHeight = 1024;
//        nmWidth = 640;
//        nmHeight = 480;
//        smWidth = 150;
//        smHeight = 150;
//    }
//    if (wW < 1280) {
//        bgWidth = 1024;
//        bgHeight = 768;
//        nmWidth = 1440;
//        nmHeight = 320;
//        smWidth = 150;
//        smHeight = 150;
//    }
//    if (wW < 1024) {
//        bgWidth = 640;
//        bgHeight = 480;
//        nmWidth = 320;
//        nmHeight = 240;
//        smWidth = 150;
//        smHeight = 150;
//    }
//    if (wW < 640) {
//        bgWidth = 480;
//        bgHeight = 320;
//        nmWidth = 480;
//        nmHeight = 320;
//        smWidth = 150;
//        smHeight = 150;
//    }
//    if (wW < 480) {
//        bgWidth = 320;
//        bgHeight = 240;
//        nmWidth = 320;
//        nmHeight = 240;
//        smWidth = 150;
//        smHeight = 150;
//    }
    //var bgHeight=8/11*bgWidth;
    //var smWidth=bgWidth/10;
    //var smHeight=bgHeight/10;
    var smWidth = 150;
    var smHeight = 150;

    var thisBody = $("body");
    if ($("#ImgContainerDiv").length > 0) $("#ImgContainerDiv").remove();
    var ImgContainerDiv = '<div id="ImgContainerDiv" style="display:none;"></div>';
    thisBody.append(ImgContainerDiv);
    var ImgContainerLoadFirstDiv = '<div id="ImgContainerLoadFirstDiv" style="display:none;"></div>';
    thisBody.append(ImgContainerLoadFirstDiv);
    var temp = new Array;
    var xtra_page = 0;
    if (data_len % 2 == 1) {
        xtra_page = 2
    } else {
        xtra_page = 1
    }
    data_len = data_len + xtra_page;
    var today = new Date;
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    console.log(base_url);
    var tDatesCon = '<div class="created_dates" style="display:none;"></div>';
    $("#js-step2-port").attr("src", "<?php echo $this->config->item('image_upload_'); ?>" + "/" + 150 + "x" + 150 + "/" + fc);
    $("body").append(tDatesCon);
    var arrDates = new Array;
    for (var i = 0; i <= data_len; i++) {
        var cDate = "";
        if (i == 0) {
            cDate = fc_date;
        }else if(i == data_len){
            cDate = bc_date;
        } else {
            var thisFormat = yyyy + "," + mm + "," + dd + "," + i + "," + "0,0";
            if (book_pages[i - 1]) cDate = book_pages[i - 1].created_date;
            if (typeof cDate === "undefined") cDate = thisFormat;
            if (cDate == "undefined") cDate = thisFormat;
            if (cDate == "") cDate = thisFormat;
        }
        var tDate = '<span class="created_date' + i + '">' + cDate + "</div>";
        arrDates.push(cDate + "~" + i)
    }
    arrDates.sort();
    for (var i = 0; i <= data_len; i++) {
        var arr = arrDates[i].split("~");
        var cDate = arr[0];
        var tDate = '<span class="created_date' + i + '" title="' + arr[1] + '">' + cDate + "</div>";
        $(".created_dates").append(tDate)
    }
    var coverThumbUrl = fc;
    $("body").attr("coverThumbUrl", coverThumbUrl);
    var imgElementString = "";
    var thisImageUrl = "<?php echo $this->config->item('image_upload_'); ?>" + "/" + bgWidth + "x" + bgHeight + "/";
    var thisNormUrl = "<?php echo $this->config->item('image_upload_'); ?>" + "/" + nmWidth + "x" + nmHeight + "/";
    var thisThumbUrl = "<?php echo $this->config->item('image_upload_'); ?>" + "/" + smWidth + "x" + smHeight + "/";
    var range = getRange(curPage,data_len);
    //console.log(range);
    var pCount = 0;
    for (var i = 0; i <= data_len; i++) {
        var shareEle = '<div id="sharebuttons" page_number="' + (i + 1) + '"><span class="st_info" displayText=""><a href="javascript:void(0);" class="info-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/info.png" alt="Info"/></a></span><span class="st_facebook" displayText=""><a href="javascript:void(0);" class="fb-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/facebook.png" alt="Facebook"/></a></span><span class="st_twitter" displayText=""><a href="javascript:void(0);" class="twitter-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/twitter.png" alt="Twitter"/></a></span><span class="st_pinterest" displayText=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/pinterest.png" alt="Pinterest"/></a></span><span class="st_googleplus" displayText=""></span><span class="st_email" displayText=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/mail.png" alt="mail"/></a></span></div>';
        temp[i] = new Object;
        if (i == 0) {
            temp[i].originalSrc = thisImageUrl + fc;
            temp[i].originalThumb = thisThumbUrl + fc;
            var shareEle = '<div id="sharebuttons" page_number="' + (i + 1) + '"><span class="st_info" displayText=""><a href="javascript:void(0);" class="info-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/info.png" alt="Info"/></a></span><span class="st_facebook" displayText=""><a href="javascript:void(0);" class="fb-shareFront"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/facebook.png" alt="Facebook"/></a></span><span class="st_twitter" displayText=""><a href="javascript:void(0);" class="twitter-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/twitter.png" alt="Twitter"/></a></span><span class="st_pinterest" displayText=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/pinterest.png" alt="Pinterest"/></a></span><span class="st_googleplus" displayText=""></span><span class="st_email" displayText=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/mail.png" alt="mail"/></a></span></div>';
            var nImgUrl = thisImageUrl + fc;
            temp[i].title = "Cover";
            temp[i].src = thisImageUrl + fc;
            temp[i].thumb = thisThumbUrl + fc;
            temp[i].norm = thisNormUrl + fc;
            temp[i].htmlContent = front_text + front_invite + shareEle;
            var imgElement = '<img class="pageImg' + (i + 1) + '" src="' + nImgUrl + '" />';
            $("#ImgContainerLoadFirstDiv").append(imgElement)
        } else if (i <= data_len - xtra_page) {
            if (book_pages[i - 1].image_url != "") {
                var bImgUrl = thisImageUrl + book_pages[i - 1].image_url;
                var tImgUrl = thisThumbUrl + book_pages[i - 1].image_url;
                var nImgUrl = thisNormUrl + book_pages[i - 1].image_url;
                var oImg = thisImageUrl + book_pages[i - 1].image_url
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
            temp[i].htmlContent = shareEle;
            pCount++;
        } else if (i == data_len) {
            temp[i].originalSrc = thisImageUrl + bc;
            temp[i].originalThumb = thisThumbUrl + bc;
            temp[i].title = "Back Cover";
            if (bc) {
                temp[i].src = thisImageUrl + bc;
                temp[i].thumb = thisThumbUrl + bc;
                temp[i].norm = thisNormUrl + bc;
            }
        }
    }
    //$("#ImgContainerDiv").append(imgElementString);
    if (i % 2 == 1) {
        temp[i] = new Object;
        temp[i].src = "/images/preloader.jpg";
        temp[i].thumb = "/images/preloader.jpg";
        temp[i].title = "last"
    }
    //console.log(temp);
    page = JSON.stringify(temp);
    pageGlobal = temp;
    $("#container").attr("current_view", curViewed);
    canInitialize = 1;
    init_book() <?php endif; ?>
}
<?php endif; ?>
function init_book() {
    var wW=$(document).width();var wH=$(document).height();var bgWidth=wW/2*.98;var bgHeight=8/11*bgWidth;if(bgHeight>wH-185){bgWidth=(wH-185)/8*11;bgHeight=wH-185}var smWidth=bgWidth/10;var smHeight=bgHeight/10;console.log("current page: "+curPage);var current_page=$("#container").attr("current_view");$("#container").html("");
    if(isMobile == false){
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
            zoom:.98,
            btnViews:true,
            btnTopTen:true,
            viewTimeline:true,
            currentView:current_page,
            viewSlideshow:true,
            <?php if(($collaborative == 1 && $can_add_photo == 1) || $book_owner == 1): ?>
            btnAddPhoto:true,
            <?php else: ?>
            btnAddPhoto:false,
            <?php endif; ?>
            pageMaterial:'phong',
            zoomMax:1,
            isFramed:isInIframe,
            bookName:"<?php echo $book_data->book_name; ?>",
            bookDesc:"<?php echo $book_data->book_desc; ?>",
            bookDate:"<?php echo $timeCreatedDate; ?>",
            isMobile:isMobile,
			startPage:curPage
        });
    }else{
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
            zoom:.99,
            btnViews:false,
            btnTopTen:true,
            viewBook:false,
            currentPage:false,
            currentView:"Slideshow",
            viewTimeline:false,
            viewSlideshow:true,
            <?php if(($collaborative == 1 && $can_add_photo == 1) || $book_owner == 1): ?>
            btnAddPhoto:true,
            <?php else: ?>
            btnAddPhoto:false,
            <?php endif; ?>
            pageMaterial:'phong',
            zoomMax:1,
            isFramed:isInIframe,
            bookName:"<?php echo $book_data->book_name; ?>",
            bookDesc:"<?php echo $book_data->book_desc; ?>",
            bookDate:"<?php echo $timeCreatedDate; ?>",
            isMobile:isMobile,
			startPage:curPage
        });
    }
        $("#js-invite-friends-pop").unbind("click").on("click",function(e){e.preventDefault();e.stopPropagation();loadInlineChooser()});$(".front_book_title").quickfit({max:18,min:12,truncate:false});$(".front_book_author").quickfit({max:18,min:12,truncate:false});$("#js-publish-book-step2").fadeIn();$("#js_invite_friends_next").on("click",function(e){e.preventDefault();var t=0;$("#js-invite-friends-view .fb-friend").each(function(){if($(this).hasClass("selected-friend"))t=1});if(t==1)jQuery("#js-invite-friends-view").friendChooser("submit");else{var n=0;$("#js-invite-friends-collab .fb-friend").each(function(){if($(this).hasClass("selected-friend"))n=1});if(n==1)jQuery("#js-invite-friends-collab").friendChooser("submit");else{$.ajax({url:"<?php echo $this->config->item('base_url'); ?>"+"/publish_book/choose_friends",type:"post",data:$("form#choose_friends").serialize(),success:function(e){postToSocial()}})}}});if($.cookie("has_visited")!="yes"){$(".fa-question").click()}$.cookie("has_visited","yes",{expires:365,path:"/"})
    <?php if($login==0) { ?>
            var goShare=1;$(".share_here").unbind("click").on("click",function(e){e.preventDefault();e.stopPropagation();if(goShare==1){goShare=0;setTimeout(function(){goShare=1},1e3);console.log("share_here clicked");$.ajax({url:"/main/home_select_booktype_unique",success:function(e){console.log("loading uploader");var t=$.parseJSON(e);if(t.status){if($("#main_inner").find("#my_edit").length==0)$("#main_inner").append("<div class='tab2_content' id='my_edit'></div>");$("#main_inner .tab2_content").html("");$("#main_inner_uploder_pop").html(t.data).css("display","block");$("#main_inner_overlay").css("display","block");getCookie();ch()}},error:function(e,t,n){alert("\n"+t.toUpperCase()+': "Page '+n+'"');$("#app_loader").fadeOut("slow")}});return true}});$(".ask_photo_permission").on("click",function(){$.ajax({url:"<?php echo $this->config->item('base_url'); ?>"+"/publish_book/ask_photo_permission",type:"post",data:{bs_id:bs_id,ask_add_ids:ask_see_ids},success:function(e){alert("Request sent to owner of book.")}})});$(".ask_see_permission").on("click",function(){$.ajax({url:"<?php echo $this->config->item('base_url'); ?>"+"/publish_book/ask_see_permission",type:"post",data:{bs_id:bs_id,ask_see_ids:ask_see_ids},success:function(e){alert("Request sent to owner of book.")}})})
    <?php } else if($login == 1) { ?>
			$(".share_here").on("click",function(e){e.preventDefault();e.stopPropagation();$("#app_loader").fadeOut("slow");$("#shared_active").hide();$("#album_for_me_unique").click();return true});var goLogin=1;$(".fb_login").unbind("click").on("click",function(e){e.preventDefault();e.stopPropagation();if(goLogin==1){goLogin=0;setTimeout(function(){goLogin=1},1e3);FB.login(function(e){if(e.authResponse){console.log("Welcome!  Fetching your information.... ");FB.api("/me",function(e){console.log("Good to see you, "+e.name+".");window.location.reload()})}else{console.log("User cancelled login or did not fully authorize.")}})}});if(!Modernizr.svg){$(".fb-share img").attr("src","../images/facebook.png");$(".twitter-share img").attr("src","../images/twitter.png");$(".pinterest-share img").attr("src","../images/pinterest.png");$(".email-share img").attr("src","../images/mail.png")}
<?php } ?>
    <?php if($showStatus == 1): ?>
    if(dontShow == 0){
        dontShow = 1;
        var thisTime = new Date($("#StartMessage").attr("siteLoadStart"));
        var endTime = new Date();
        var inSec = new Date().getSeconds() - thisTime.getSeconds();
        $("#StartMessage").attr("siteLoadEnd",endTime);
        alert("Whole Site took "+inSec+" seconds to load.");
    }
    <?php endif; ?>
}
function FBInitialized(){

}
function goFB() {
    window.fbAsyncInit = function () {
        FB.init({
            appId: <?php echo $this->config->item("fb_appkey"); ?>,
            status: true,
            cookie: true,
            xfbml: true,
            channelURL: "<?php echo $this->config->item('base_url'); ?>" + "/channel.html",
            oauth: true
        });
        console.log("fbAsyncInit");
        FB.getLoginStatus(function (e) {
            console.log("get fb login");
            if (e.status === "connected") {
                FB.api("/me", function (e) {
                    console.log("Hi " + e.first_name + "! your fb id is: " + e.id);
                    $.ajax({
                        url: "/books/set_cookie_value",
                        async: false,
                        data: "first_name=" + e.first_name + "&facebook_id=" + e.id,
                        type: "post",
                        success: function (e) {
                            var t = $.cookie("hardcover_fb_user_id");
                            if (t == null) t = "";
                            var n = "<?=$user_id; ?>";
                            console.log("current cookie fb id: " + n + " ~ result cookie fb id: " + t);
                            if (n != t) {

                                console.log("FB out of sync reloading site.");
                                window.location.reload()
                            } else setTimeout(function () {
                                if (typeof FBInitialized == "function") FBInitialized()
                            }, 1e3)
                        }
                    })
                })
            } else if (e.status === "not_authorized") {
                console.log("Hi, you're not authorized. Please authorize this app.");

                FB.login(function (e) {
                    if (e.authResponse) {
                        FB.api("/me", function (e) {
                            console.log("Hi " + e.first_name + "! your fb id is: " + e.id);
                            $.ajax({
                                url: "/books/set_cookie_value",
                                async: false,
                                data: "first_name=" + e.first_name + "&facebook_id=" + e.id,
                                type: "post",
                                success: function (e) {
                                    var t = $.cookie("hardcover_fb_user_id");
                                    if (t == null) t = "";
                                    var n = "<?=$user_id; ?>";
                                    console.log("current cookie fb id: " + n + " ~ result cookie fb id: " + t);
                                    if (n != t) {
                                        console.log("FB out of sync reloading site.");
                                        window.location.reload()
                                    } else setTimeout(function () {
                                        if (typeof FBInitialized == "function") FBInitialized()
                                    }, 1e3)
                                }
                            })
                        })
                    } else {
                        console.log("User cancelled login or did not fully authorize.")
                    }
                })
            } else {
                console.log("Hi, you're not logged in.");
                $.ajax({
                    url: "/books/set_cookie_value",
                    async: false,
                    data: "first_name=" + "" + "&facebook_id=" + "",
                    type: "post",
                    success: function (e) {
                        var t = $.cookie("hardcover_fb_user_id");
                        if (t == null) t = "";
                        var n = "<?=$user_id; ?>";
                        console.log("current cookie fb id: " + n + " ~ result cookie fb id: " + t);
                        if (n != t) {
                            console.log("FB out of sync reloading site.");
                            window.location.reload()
                        } else setTimeout(function () {
                            if (typeof FBInitialized == "function") FBInitialized()
                        }, 1e3)
                    }
                })
            }
        })
    }
}
jQuery(document).ready(function(){
//head.ready(function() {
    var l = window.location;
    var base_url = l.protocol + "//" + l.host;
    goFB();
<?php if($isTesting): ?>
    imageLoadTest();
<?php else: ?>
    goCreateThisBook();
<?php endif; ?>
    $( window ).resize(function() {
        if(canInitialize == 1)
            init_book();
    });
    $( window ).keypress(function(e) {
        var curKey = e.keyCode;
        //console.log( "key code: "+curKey );
        //if(curKey == 86){
//            alert("current version: <?=$current_unique_version ?>");
//        }
    });
//});
});
</script>
<style>
.icon-arrow-right-big {
    background: url(/images/right-arrow-big.png)top left no-repeat;
    width:70px;
    height:70px;
    position: absolute;
    top: 45%;
    right: -80px;
    cursor: pointer;
}
.icon-arrow-left-big {
    background: url(/images/left-arrow-big.png)top left no-repeat;
    width:70px;
    height:70px;
    position: absolute;
    top: 45%;
    left: -80px;
    cursor: pointer;
}
.flipbook-paginationCon span {
    left:0;
}
    .top10Layer{
        margin: 50px;
        width: 700px;
        left: 5%;
    }
    .top10Layer img{
        box-shadow: 0 3px 10px rgba(0,0,0,.5);
        height: 150px;
        margin: 10px;
    }
.front_book_title {
    position: absolute;
    top: 15px;
    width:100%;
    padding: 0 50px 0 50px;
    text-align:center;
}
.front_book_author {
    position: absolute;
    bottom: 15px;
    width:100%;
    padding: 0 50px 0 50px;
    text-align:center;
}
.flipbook-bookLayer {
    z-index:1;
}
.flipbook-menuWrapper {
    z-index: 2;
    min-height: 45px;
}
.flipbook-thumbContainer img{
    padding: 2px;
}
.flipbook-thumbHolder {
    -webkit-box-shadow: none;
    box-shadow: none;
    background-color: rgba(0, 0, 0, 0.498039);
}
.flipbook-shadowRight, .flipbook-shadowLeft, .flipbook-page {
    margin: auto;
    top: 0;
    bottom: 0;
}
#sharebuttons {
    position:absolute; z-index:9998; bottom:-50px;
    right: 30%;
    width: auto;
    text-align: center;
    margin-left:0;
}
body {
    min-height: inherit;
}
.ask_photo_permission {
    height: auto;
    width: 250px;
    padding: 0.7em 2em;
    background: #ff9839;
    -webkit-box-shadow: 0 3px 0 0 #bb6212;
    box-shadow: 0 3px 0 0 #bb6212;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    border: 0;
    color: #fff;
    font-size: 13px!important;
    font-weight: bold;
    text-shadow: 0 1px 0 rgba(0, 0, 0, 0.5);
    -webkit-transition: 1s;
    transition: 1s;
}
.bookflipLayer .fb-like {
    position: absolute;
    z-index: 9;
    bottom: -46px;
    left: -30px;
}
.slideshowLayer {
   overflow:hidden;
}

.slideshowLayer .fb-like {
    position: absolute;
    z-index: 999;
    bottom: -46px;
    left: 0px;
}
.likeCon {
    position: absolute;
}
.slideshow-enlarge {
    position: absolute;
    z-index: 999;
    bottom: -40px;
    right: 0;
}
.likeCon .fb_iframe_widget span, .likeCon .fb_iframe_widgeth span iframe {
    width: 78px !important;
    height: 20px !important;
}
.flipbook-currentPage span{
  float:left;
  position:relative;
  cursor:pointer;
  display:table-cell;
  vertical-align: middle;
  padding:0 5px;
  float:none;
}
.thumb_con {
    position: relative;
    display: table-cell;
    vertical-align: middle;
    padding: 0 2px;
    float: none;
}
.thumb_con > div{
    padding: 0px 2px;
    margin: 0px 0px -5px;
}
.flipbook-page {
    background:#fff;
}
.likeCon {
    background:transparent;
}
.galleria-thumbnails #sharebuttons {
    bottom:10px;
    right:45%;
}
.gallery-thumbHolder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow-x:hidden;
    overflow-y:auto;
    background: url(/images/thumbBG.png);
    z-index: 2;
    padding:20px;
}
.galleryThumbs {
    float: left;
    margin: 0 7px 7px 0;
    border: 3px solid #fff;
    cursor: pointer;
}
.infoContainer {
    background:#000;
    padding:5px;
    color:#ccc;
    text-align:left;
    margin:10px;
}
.infoContainer h2 {
    font-size:14px;
    color:#ccc;
}
.infoContainer p {
    font-size:12px;
    color:#ccc;
}
.infoContainer .infoClose {
    width:100%;
    text-align:right;
}

.infoContainer .infoClose span{
    padding:2px;
    margin:5px;
    color:#fff;
    background:#404040;
    cursor:pointer;
}

</style>
</head>

<body class="published" user_name="<?=$fbuser_username; ?>" book_info_id="<?=$book_info_id; ?>" status="<?=PROTOCOL ?>">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $this->config->item("fb_appkey"); ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div style="display:none;">
<?php
//var_dump($_SESSION);
//var_dump($_COOKIE);
//var_dump($booked_data->book_info);
?>
</div>
<div style="opacity: 0;" class="fbLikeParent"><div class="fb-like" data-href="<?php echo $bookUniqueURL; ?>" data-width="90" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" style="position:absolute;"></div></div>
<!-- added here -->

<div id="main_inner_overlay" style="display:none;position:absolute;height:100%; background:#000000;width:100%;z-index:233; opacity:0.70; top:0; left:0;"></div>
<div id="main_inner_uploder_pop" style="display:none;left:50%;margin:-225px 0px 0px -300px; top:35%; position:fixed;z-index:23333; " >test</div>
<div id="main_inner" ></div>

<div id="app_loader1" class="hideDiv"><div class="bar"><span></span></div></div>

<div id="app_loadercover" class="hideDiv"> </div>
	<!--[if lt IE 9]><p class=chromeframe>You're not using the <em>latest</em> version of your browser. Please <a href="http://browsehappy.com/">get Firefox here </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome</a> to experience the full features of this website.</p><![endif]-->

<div id="pp_header" app_id="<?php echo $this->config->item("fb_appkey"); ?>"></div>
<!-- added here -->

<div id="container" class="book-flip-url" status="<?php echo "User ID: ".$user_id." ~ Condition ID: ".$perm." ~ Login type: ".$login." ~ show book: ".$show_book." ~ can add photo: ".$can_add_photo." ~ book info id: ".$book_info_id." ~ rightside: ".$rightside." ~ creator id: ".$creator_fbid; ?>">
	<!-- BOOKFLIP START -->
	<div id="flipBookContainer">
		<div id="bookContainer"></div>
	</div>
			<!-- BOOKFLIP END -->
    <?php if($login == 1 && $show_book != 1) { ?>
                        <div style="position:absolute; top:45%; left:45%;">
							<p>Login with Facebook to view this Book </p>
							<button style="padding:0px; position:relative; top:0px; border:none; box-shadow:0px;background:url('<?php echo $this->config->item("base_url"); ?>/images/fb-connect.png') no-repeat; height:25px; width:200px;" class="fb_login">&nbsp;</button>
                        </div>
    <!-- josh add for ask -->
    <?php } else if($login == 0 && $user_id != "" && $show_book != 1) { ?>
                        <div style="position:absolute; top:45%; left:45%;">
                            <p ><button class="ask_see_permission" style="width: 320px; height: 100px; top: 0; left: 0; position: absolute; font-size:1.6em; font-family:'arial'; background: opacity:.8; z-index:99999;" ><b>Ask for permission to view book
							</b> <br/>Click here</button> </p>
                        </div>
    <?php } else { ?>
                        <div style="position:absolute; top:45%; left:45%;" class="ajaxLoaderDiv">
							<img src="<?php echo $this->config->item("base_url"); ?>/images/ajax-loaderback.gif" />
                        </div>
    <?php } ?>
	<?php if($see_book==555) { ?>
                        <button  name="<?php echo $this->config->item("base_url") ."/uploader/". $_COOKIE["hardcover_book_info_id"] ."/". $creator_fbid; ?>" class="share_here">Login to check the book of <?php echo $creator_fname; ?><br /><br /><h6>Click Here</h6></button><a name="album_for_me_unique" id="album_for_me_unique" style="display:none;">click</a>
    <?php } // if see_book ?>
    <?php if($contributors == 1){ ?>
                        <div style="text-align:left; margin-top:40px; margin-left:50px;"> <img src="https://graph.facebook.com/<?php echo $fbb_id; ?>/picture?type=small"> &nbsp;&nbsp;Created By :<?php echo json_decode(file_get_contents('http://graph.facebook.com/'.$fbb_id))->name;  ?></div><br/><h3 style="text-align:left;   margin-left:50px; margin-bottom:4px;">Contributers</h3>
        <?php  foreach($contributers_data as $k=>$v) {   ?>
                        <p style="text-align:left;   margin-left:50px;"><img style="height:35px; width:35px;" src="https://graph.facebook.com/<?php echo $v->friends_fbid; ?>/picture?type=small">&nbsp;&nbsp;<?php  echo $v->friends_name ?> </p>
        <?php } ?>
    <?php } ?>
    <?php if($login == 2) { ?>
							<div>
							   Only Friends of <?php echo $creator_fname; ?> can view this Book<br/><br/>
							   <a href="http://apps.facebook.com/hardcoverdev">Click here </a> to create your own book instead
							</div>
    <?php } ?>
    <?php if($rightside == 1) { ?>
							<div id="share_here_container" style="margin-top:150px; text-align:center">
						          <a name="album_for_me_unique" id="album_for_me_unique" style="display:none;">click</a>
                            </div>
    <?php } ?>
			<!--Right side copy end -->
			</div>

            <!-- added upto here -->
        <div class="modal fade" id="js-publish-book-modal">
		    <div class="modal-dialog">
		     	<div class="modal-content">
			        <div class="modal-body">
			        	<div id="js-publish-book-step1">
				        	<form id="choose_friends" name="choose_friends">
						        			<input name="whocansee"  checked="checked" type="radio" <?php if(isset($book_setting_data->who_can_see) &&($book_setting_data->who_can_see == 'all')){?>checked="checked"<?php } ?> class="who-red" value="all" id="optionsRadios1" />
						        		    <input name="whocansee" type="radio" <?php if(isset($book_setting_data->who_can_see) &&($book_setting_data->who_can_see == 'friends')){?>checked="checked"<?php } ?> class="who-red" value="friends" id="optionsRadios2" />
						        			<input name="whocansee" type="radio" <?php if(isset($book_setting_data->who_can_see) &&($book_setting_data->who_can_see == 'some_friends')){?>checked="checked"<?php } ?> class="who-red" value="some_friends" id="js-some-frnd" />
						        			<input type="radio" value="0" <?php if(isset($book_setting_data->collaborative)&&($book_setting_data->collaborative == 0)){?>checked="checked"<?php } ?>  name="collaborative" id="js-not-a-collaborative-book" />
                                            <input type="radio" name="collaborative" <?php if(isset($book_setting_data->collaborative)) { if($book_setting_data->collaborative == 1){?>checked="checked"<?php } }else{ ?>checked="checked"<?php } ?> value="1" id="js-yes-a-collaborative-book" />
								        			<input onClick="show_approval('all');"  checked="checked" name="collaborate_with" type="radio" <?php if(isset($book_setting_data->who_can_contribute)&&($book_setting_data->who_can_contribute == 'all')){?>checked="checked"<?php } ?> class="content_approval_p" value="all" />
                                                    <input onClick="show_approval('friends');" name="collaborate_with" type="radio" <?php if(isset($book_setting_data->who_can_contribute)&&($book_setting_data->who_can_contribute == 'friends')){?>checked="checked"<?php } ?> class="content_approval_p" value="friends" />
                                                    <input id="js-req-to-frnds" name="collaborate_with" type="radio" <?php if(isset($book_setting_data->who_can_contribute)&&($book_setting_data->who_can_contribute == 'select')){?>checked="checked"<?php } ?> value="select" />
								        			<input checked="checked" id="js-content-appoval-yes" name="content_appoval" type="radio" <?php if(isset($book_setting_data->content_approval) &&($book_setting_data->content_approval == 1 )){?>checked="checked"<?php } ?> value="1" />
								        			<input id="js-content-appoval-no" name="content_appoval" type="radio" <?php if(isset($book_setting_data->content_approval) &&($book_setting_data->content_approval == 0)){?>checked="checked"<?php } ?> value="0" />
                                <input type="hidden" name="book_info_id" value="<?php echo $_COOKIE["hardcover_book_info_id"]; ?>" />
				                <input type="hidden" name="user_data" id="js-user-data" value="" />
				                <input type="hidden" name="user_data_see" id="js-user-data-see" value="" />
				                <input type="hidden" name="share_facebook" id="js-share-facebook" value="t" />
                                <input type="hidden" name="ds_id" id="ds_id" value="<?php if(isset($book_setting_data->bs_id)){ echo $book_setting_data->bs_id; } ?>" />
                                <input type="hidden" name="book_url"  type="text" value="<?php echo $bookUniqueURL; ?>" />
				        	</form>
			        	</div><!-- End js-publish-book-step1 -->

			        	<div id="js-publish-book-step2">
			        		<form>
				        		<div class="panel panel-default">
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
                                <input type="hidden" name="ds_id" id="ds_id" value="<?php if(isset($book_setting_data->bs_id)){ echo $book_setting_data->bs_id; } ?>" />
				        	</form>
                            <div class="modal-footer">
			        	        <a href="javascript:void(0);" class="btn btn-small btn-orange" id="js_invite_friends_next">Close</a>
                            </div><!-- End modal-footer -->
			        	</div><!-- End js-publish-book-step2 -->
			        </div><!-- End modal-body -->

		      	</div><!-- End modal-content -->
		    </div><!-- End modal-dialog -->
		</div><!-- End modal -->


<!-- added from here -->

<?php if($login==0) { ?>
	<script>
		function ch(){
			 return true;
		}
	</script>
<?php } else if($login == 1) { ?>
	<script>
		function ch(){
			 <?php if($data['to_login']==0){ ?>
			// jQuery("#tabs ul").find("li:eq(1)").remove();
			// jQuery("#tabs1-facebook").remove();
			 <?php } ?>
		}
	</script>
<?php } ?>
<script type="text/javascript">
  	function auth_login(){
		$('.share_here').click();
	}
</script>
</body>

</html>
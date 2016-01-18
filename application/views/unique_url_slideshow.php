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
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/json3.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/bootstrap.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/storyjs-embed.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/galleria-1.3.5.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/galleria.history.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/jquery.waituntilexists.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/jquery.ba-hashchange.min.js"></script>
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
var curPage = 0;
var curViewed = "Slideshow";
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
    if (wW < 1366) {
        bgWidth = 1280;
        bgHeight = 1024;
        nmWidth = 640;
        nmHeight = 480;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 1280) {
        bgWidth = 1024;
        bgHeight = 768;
        nmWidth = 1440;
        nmHeight = 320;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 1024) {
        bgWidth = 640;
        bgHeight = 480;
        nmWidth = 320;
        nmHeight = 240;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 640) {
        bgWidth = 480;
        bgHeight = 320;
        nmWidth = 480;
        nmHeight = 320;
        smWidth = 150;
        smHeight = 150;
    }
    if (wW < 480) {
        bgWidth = 320;
        bgHeight = 240;
        nmWidth = 320;
        nmHeight = 240;
        smWidth = 150;
        smHeight = 150;
    }
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
        var shareEle = '<div id="sharebuttons" page_number="' + (i + 1) + '"><span class="st_facebook" displayText=""><a href="javascript:void(0);" class="fb-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/facebook.png" alt="Facebook"/></a></span><span class="st_twitter" displayText=""><a href="javascript:void(0);" class="twitter-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/twitter.png" alt="Twitter"/></a></span><span class="st_pinterest" displayText=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/pinterest.png" alt="Pinterest"/></a></span><span class="st_googleplus" displayText=""></span><span class="st_email" displayText=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/mail.png" alt="mail"/></a></span></div>';
        temp[i] = new Object;
        if (i == 0) {
            temp[i].originalSrc = thisImageUrl + fc;
            var shareEle = '<div id="sharebuttons" page_number="' + (i + 1) + '"><span class="st_facebook" displayText=""><a href="javascript:void(0);" class="fb-shareFront"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/facebook.png" alt="Facebook"/></a></span><span class="st_twitter" displayText=""><a href="javascript:void(0);" class="twitter-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/twitter.png" alt="Twitter"/></a></span><span class="st_pinterest" displayText=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/pinterest.png" alt="Pinterest"/></a></span><span class="st_googleplus" displayText=""></span><span class="st_email" displayText=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="' + "<?php echo $this->config->item('base_url'); ?>" + '/images/mail.png" alt="mail"/></a></span></div>';
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
            temp[i].title = "cc " + i;
            temp[i].fb_username = author_name;
            temp[i].front_cover = fc;
            temp[i].htmlContent = shareEle;
            pCount++;
        } else if (i == data_len) {
            temp[i].originalSrc = thisImageUrl + bc;
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
    page = temp;
    $("#container").attr("current_view", curViewed);
    canInitialize = 1;
    init_book() <?php endif; ?>
}
function init_book() {
    //console.log(page[0]);
    var thumbArray = [];
    var dataJSON = [
        {
            thumb: page[0].thumb,
            image: page[0].norm,
            big: page[0].src,
            title: page[0].title,
            description: page[0].title
        }
    ];
    thumbArray.push(0);
    var maxPages = page.length;
    var maxCount = 20;
    var curPage = parseInt( location.hash.substr(11), 10 );
    var range = getRange(curPage,maxPages);    //console.log(range);

    var toBeLoaded = [];
    var arrCount = 0;
    var timesCount = 0;
    toBeLoaded.push(new Array());
    for ( i = 1; i <= maxPages; i++) {
        var range = getRange(i,maxPages);
        var setNum = range/10;
     if(page[i]){
      if(typeof page[i].src !== "undefined" && page[i].src != ""){
        dataJSON.push({
            thumb: page[i].thumb,
            image: page[i].norm,
            big: page[i].src,
            title: page[i].title,
            description: page[i].title
        });
        toBeLoaded[setNum].push({
            thumb: page[i].originalThumb,
            image: page[i].norm,
            big: page[i].originalSrc,
            title: page[i].title,
            description: page[i].title
        });
        timesCount++;
        arrCount++;
        thumbArray.push(i);
      } else if(typeof page[i].src !== "undefined" && page[i].originalSrc != ""){
        //console.log(setNum+"~"+arrCount);
        dataJSON.push({
            thumb: page[i].originalThumb,
            image: page[i].norm,
            big: page[i].originalSrc,
            title: page[i].title,
            description: page[i].title
        });
        toBeLoaded[setNum].push({
            thumb: page[i].originalThumb,
            image: page[i].norm,
            big: page[i].originalSrc,
            title: page[i].title,
            description: page[i].title
        });
        arrCount++;
      }
     }
     if(arrCount == 10){
        toBeLoaded.push(new Array());
        arrCount = 0;
     }
    }
    console.log(thumbArray);
    //console.log(dataJSON);
    //console.log(toBeLoaded);
    $("body").append('<div id="galleria"></div>');
    var thisHeight = $(document).height();
    Galleria.loadTheme('/js/themes/classic/galleria.classic.min.js');
    // Initialize Galleria
    Galleria.run('#galleria', {
        dataSource: dataJSON,
        dataSourceOrig: toBeLoaded,
        height: thisHeight-45,
        maxScaleRatio: 1,
        preload: 0,
        queue: false,
        responsive : true,
        thumbQuality: false,
        wait: true,
        imageCrop: false,
        thumbCrop: false,
        //dummy: '/images/preloader.jpg',
        thumbnails: "lazy",
        clickNext: true
    });
    Galleria.ready(function(opt) {
        this.lazyLoad( thumbArray, function() {
            console.log(thumbArray);
        });
        gallery = this;
        if(curPage == "" || isNaN(curPage))
            curPage = 0;
        //console.log(curPage);
        //console.log(self.options.pages);
        $(".galleria-thumbnails").html(page[curPage].htmlContent);
        goPaginationSlide();
    });
    var dontDoHash = 0;
    $(window).hashchange(function(){
        var thisHash = location.hash;
        var curView = "Slideshow";
        var vCount = (curView.length * 1)+2;
        var curPage = parseInt( thisHash.substr(vCount), 10 );
        if(curPage == "" || isNaN(curPage))
            curPage = 0;
        var maxCount = 20;
        var curCount = timesCount;
        var range = getRange(curPage,maxPages);
        var setNum = range/10;
        thisHash = thisHash.replace("#","").split("~")[0];
        curViewed = thisHash;
        console.log(curViewed);
        if (!dontDoHash && curViewed == "Slideshow"){
            dontDoHash = 1;
            goPaginationSlide();
            setTimeout(function () {
                dontDoHash = 0;
            }, 1000);
        }
    });
            var fb_GO = 1;
            var tw_GO = 1;
            var pn_GO = 1;
            var em_GO = 1;
            var img_GO = 1;
            var desc = $('meta[name="description"]').attr('content');
            jQuery('.galleria-thumbnails .fb-shareFront').unbind("click").live("click",function(event){
                var page_number = ($(this).parent().parent().attr('page_number'));
                event.preventDefault();
                event.stopPropagation();
                var page_image = getCurrentImg(page_number);
                //console.log(page_image);
                var title = document.title;
                var caption = window.location.href;
                caption = caption.split("/");
                caption = caption[2];
                if(fb_GO == 1){
                    fb_GO = 0;
                    app_id = $("#pp_header").attr("app_id");
                    FB.init({appId: app_id, xfbml: true, cookie: true});
                    FB.ui({
                        method: 'feed',
                        name: title,
                        link: window.location.href,
                        picture: page_image,
                        caption: caption,
                        description: desc
                    },
                        function(response) {
                            fb_GO = 1;
                            if (response && response.post_id) {
                                alert('Post was published.');
                            } else {
                                alert('Post was not published.');
                            }
                        }
                    );
                }
            });
            jQuery('.galleria-thumbnails .fb-share').unbind("click").live("click",function(event){
                var currentElement = $(this).parent().parent();
                var page_number = currentElement.attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var page_image = l.protocol+getCurrentImg(page_number);
                //console.log(page_image);
                var capt = page_image;
                var title = document.title;
                if(fb_GO == 1){
                    fb_GO = 0;
                    app_id = $("#pp_header").attr("app_id");
                    //FB.init({appId: app_id, xfbml: true, cookie: true});
                    //FB.init({appId: app_id, status: true, cookie: true, xfbml: true, channelURL : "https://dev.hardcover.me/channel.html", oauth:true});
                    FB.login(function(response) {
                        fb_GO = 1;
                        if (response.authResponse) {
                            var access_token = FB.getAuthResponse()['accessToken'];
                            FB.api("/me/picture?width=50&height=50&access_token="+access_token,  function(response) {
                                var user_pic = response.data.url;
                                var book_image = getCurrentImg(page_number);
                                book_image = book_image.replace("&h=1080&w=1485&zc=2", "&h=48&w=90&zc=2");
                                $(".createdPopDiv").remove();
                                var bookDiv = '<div class="bookDiv"><img src="'+book_image+'" /><h3>'+title+'</h3></div>';
                                var popDiv = '<div id="dialog" class="createdPopDiv" title="Facebook Post"><div class="popPic"><img src="'+user_pic+'" /></div><div class="popMsg"><textarea  name="fb_message" placeholder="Say something about this..." id="fb_message" style="display:block;width:100%;height:40px;"></textarea>'+bookDiv+'</div></div>';
                                currentElement.append(popDiv);
                                $("#dialog").dialog({
				                    modal: true,
				                    resizable: false,
				                    buttons: {
                                        "Post": function() {
                                            var msg = $("#fb_message").val()+'\r\n'+"- "+window.location.href;
                                            FB.api('/me/photos?access_token='+access_token, 'post', { url: page_image, message: msg, access_token: access_token }, function(response) {
                                                if (!response || response.error) {
                                                    alert('Error occured: ' + JSON.stringify(response.error));
                                                } else {
                                                    alert('Image posted on your wall.');
                                                }
                                            });
                                            $(this).dialog("close");
                                        },
                                        "Cancel": function() {
                                            $(this).dialog("close");
                                        }
                                    }
                                });
                                $(".ui-dialog").each(function(){
                                    $(this).css("z-index","9999");
                                    $(this).css("width","400px");
                                });
                                $(".ui-dialog-buttonset button:nth-child(2)").addClass("cancel-button");
                            });
                        } else {
                            alert('User cancelled login or did not fully authorize.');
                        }
                    }, {scope: 'publish_stream'});
                }
            });
            jQuery('.galleria-thumbnails .twitter-share').unbind("click").live("click",function(event){
                var page_number = $(this).parent().parent().attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var title = document.title;
                var message = "A HardCover book";
                var link = 'http://twitter.com/intent/tweet?url='+getCurrentImg(page_number)+'&text='+title+'. '+message+' '+encodeURI(getCurrentImg(page_number))+' '+encodeURI(window.location.href)+'&hashtags=hardcover';
                if(tw_GO == 1){
                    tw_GO = 0;
                    newWindow = window.open(link,'_blank','width=700,height=260');
                    newWindow.focus();
                    $(newWindow.document).ready(function(){
                        setTimeout(function () {
                            tw_GO = 1;
                        }, 1000);
                    });
                }
            });
            jQuery('.galleria-thumbnails .pinterest-share').unbind("click").live("click",function(event){
                var page_number = $(this).parent().parent().attr('page_number');
                event.preventDefault();
                event.stopPropagation();
	           var title = document.title;
	           var message = "A HardCover book";
	           var link = '//www.pinterest.com/pin/create/button/?url='+encodeURI(window.location.href)+'&media='+encodeURI(getCurrentImg(page_number))+'&description='+title+'. '+message;
	           if(pn_GO == 1){
                    pn_GO = 0;
                    newWindow = window.open(link,'_blank','width=700,height=260');
                    newWindow.focus();
                    $(newWindow.document).ready(function(){
                        setTimeout(function () {
                            pn_GO = 1;
                        }, 1000);
                    });
                }
            });
            jQuery('.galleria-thumbnails .email-share').unbind("click").live("click",function(event){
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
            function getCurrentImg(page_number) {
                var thisSrc = page[page_number-1].originalSrc;
                return thisSrc;
            }

    function goPaginationSlide() {
            //console.log(pages);
            var curPage = parseInt( location.hash.substr(11), 10 );
            if(curPage == "" || isNaN(curPage))
                curPage = 0;
            var pTotal = $(".flipbook-paginationCon").find(".MenuItem").length;
            //console.log(pTotal + " ~ " + curPage);
            var cMax = 0;
            var thisCounter = 1;
            var thumbArray = [];
            for(var i=0;i<=pTotal;i++){
              var range = getRange(i,pTotal);
              var setNum = range/10;
              //console.log("#"+curView+"-MenuItem-"+i);
              if($("#Slideshow-MenuItem-"+i)){
                //console.log(curPage + " ~ " + cMax + " ~ " + i + " ~ " + pTotal);
                if(curPage == 0) curPage = 1;
                if (curPage > cMax && curPage <= cMax+10){
                    thumbArray.push(i);
                    $("#Slideshow-MenuItem-"+i).css("display","table-cell");
                    $("#Slideshow-MenuItem-"+i).css("visibility","visible");
                    //console.log(setNum);
                    var thisSrc = $("#Slideshow-MenuItem-"+i+" img").attr("src");
                    if(thisSrc && thisSrc.indexOf("/images/placeholder_icon.png") != -1){
                        var newThumb = page[i].originalThumb;
                        $("#Slideshow-MenuItem-"+i+" img").attr("src",newThumb);
                    }
                }else
                    $("#Slideshow-MenuItem-"+i).hide();
                $("#Slideshow-MenuItem-"+i+" img").css("width","58px");
                $("#Slideshow-MenuItem-"+i+" img").attr("width","58");
                $("#Slideshow-MenuItem-"+i).css("width","58px");
                if(i == (cMax+10))
                    cMax += 10;
              }
            }
            console.log(thumbArray);
            var gallery = Galleria.get(0);
            gallery.lazyLoad( thumbArray, function() {
                console.log(thumbArray);
            });
            var newSrc = page[curPage].originalSrc;
            var bigSrc = $(".galleria-images .galleria-image:nth-child(1) img").attr("src");
            //console.log(bigSrc);
            if(bigSrc && bigSrc.indexOf("/images/placeholder_icon.png") != -1){
                $(".galleria-images .galleria-image:nth-child(1) img").attr("src",newSrc);
            }
            var bigSrc = $(".galleria-images .galleria-image:nth-child(2) img").attr("src");
            //console.log(bigSrc);
            if(bigSrc && bigSrc.indexOf("/images/placeholder_icon.png") != -1){
                $(".galleria-images .galleria-image:nth-child(2) img").attr("src",newSrc);
            }
            //console.log(self.options.pages[curPage].htmlContent);
            $(".galleria-thumbnails").html(page[curPage].htmlContent);
    }
}
function FBInitialized(){

}
function goFB() {

}
jQuery(document).ready(function(){
    var l = window.location;
    var base_url = l.protocol + "//" + l.host;
    goFB();
    goCreateThisBook();
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
    right: 35%;
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
<div class="flipbook-menuWrapper skin-color-bg" style="display: block;">
  <div class="flipbook-leftMenu col-md-3">
    <div class="branding-small">
    </div>
    <span aria-hidden="true" class="flipbook-menu-btn addPhoto skin-color">
      Add Photo
    </span>
    <span aria-hidden="true" class="flipbook-menu-btn topTen skin-color">
      Top 10
    </span>
  </div>
  <div class="flipbook-helpBg invisible transition" style="position: absolute; width: 100%; bottom: 0px; height: 75px; background-color: rgba(0, 0, 0, 0.498039); z-index: 9; background-position: initial initial; background-repeat: initial initial;">
  </div>
  <div class="flipbook-nextTwentyHolder flipbook-next skin-color-bg invisible transition">
    <i class="skin-color">
      View next 20 pages.
    </i>
    <div class="arrow-down" style="position: absolute; bottom: -10px; left: 42%;">
    </div>
  </div>
  <div class="flipbook-currentPage col-md-6">
    <div class="flipbook-paginationCon">
      <span class="page-num skin-color pagination" title="0">
        Start
      </span>
      <span aria-hidden="true" class="fa fa-angle-double-left fa-2x skin-color">
      </span>
      <div class="thumbPlay" style="overflow: hidden; position: relative; visibility: visible; display: table-cell; width: 41px; height: 35px;">
        <img class="imgPlay" src="/images/thumbPlay.png" style="display: block; opacity: 1; min-width: 0px; min-height: 0px; max-width: none; max-height: none; -webkit-transform: translate3d(0px, 0px, 0px); width: 41px; height: 35px;">
      </div>
      <div class="thumb_con galleryThumbCon">

      </div>
      <span aria-hidden="true" class="fa fa-angle-double-right fa-2x skin-color">
      </span>
      <span class="page-num skin-color pagination" title="NaN">
        End
      </span>
    </div>
  </div>
  <div class="flipbook-menu col-md-3">
    <span aria-hidden="true" class="flipbook-menu-btn fa fa-question skin-color">
    </span>
    <span aria-hidden="true" class="flipbook-menu-btn icon-general icon-list skin-color">
    </span>
    <span aria-hidden="true" class="flipbook-menu-btn icon-general icon-layout skin-color">
    </span>
    <span aria-hidden="true" class="flipbook-menu-btn icon-general icon-resize-enlarge">
    </span>
    <span aria-hidden="true" class="flipbook-menu-btn icon-general changeView skin-color" style="line-height: 2;">
      Change View
    </span>
  </div>
  <div class="flipbook-thumbHolder invisible" style="position: absolute; display: none; overflow: visible; width: 100%; height: 51.8px; left: auto; right: auto; top: auto; bottom: 45px;">
    <div class="flipbook-thumbContainer" style="margin: 0px auto; padding: 0px; position: relative; -webkit-transition: -webkit-transform 0ms; transition: -webkit-transform 0ms; -webkit-transform-origin: 0px 0px; -webkit-transform: translate(0px, 0px) translateZ(0px); height: 51.8px; width: 641.025px;">

    </div>
  </div>
</div>

</body>

</html>
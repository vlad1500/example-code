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
    setcookie("hardcover_book_info_id", $book_info_id, time()+(3600*10));
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
<script type="text/javascript" src="/js/libs/head.min.js"></script>
<script type="text/javascript" src="/js/json3.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/storyjs-embed.js"></script>
<script type="text/javascript" src="/min/index.php?g=uniqueTop10JS"></script>
<script>
$(document).ready(function(){
        var wW = $(document).width();
        var wH = $(document).height();
        var bgWidth = (wW / 2)*.98;
        var bgHeight = ((8 / 11)*bgWidth);
        var smWidth = bgWidth / 10;
        var smHeight = bgHeight / 10;
        $("#container").flipBook({
            pages:[
                    {src:"<?=$this->config->item('image_url'); ?>/150x150", thumb:"<?=$this->config->item('image_url'); ?>/150x150", title:"mock"},
                    {src:"<?=$this->config->item('image_url'); ?>/150x150", thumb:"<?=$this->config->item('image_url'); ?>/150x150", title:"mock"},
                    {src:"<?=$this->config->item('image_url'); ?>/150x150", thumb:"<?=$this->config->item('image_url'); ?>/150x150", title:"mock"},
                    {src:"<?=$this->config->item('image_url'); ?>/150x150", thumb:"<?=$this->config->item('image_url'); ?>/150x150", title:"mock"},
                    ],
            lightBox:false,
            pageWidth:bgWidth,
            pageHeight:bgHeight,
            thumbnailWidth:smWidth,
            thumbnailHeight:smHeight,
            webgl:false,
            btnToc:false,
            btnViews:false,
            btnTopTen:false,
            viewTimeline:false,
            viewSlideshow:false,
            currentPage:false,
            viewBook:true,
            btnAddPhoto:false,
            pageMaterial:'phong',
			startPage:0
        });
        $(".flipbook-bookLayer").hide();
        $(".top10Layer").show();
        var fc = <?php echo json_encode($front_cover); ?>;
        var bc = <?php echo json_encode($back_cover); ?>;
        var bUrl = <?php echo json_encode($book_url); ?>;
        var fCount = fc.length;
        var l = window.location;
        var base_url = "<?php echo $this->config->item('base_url'); ?>";
        var temp = new Array();
        for(x=0;x<fCount;x++){
            var bookUrl = bUrl[x];
            thumbImage = "//images.hardcover.me/uploads/" + 150 + "x" + 150 + "/" + fc[x];
            var top10Book = $(document.createElement('img'))
                .addClass('top10Book'+x)
                .appendTo(".top10Layer")
                .attr("src",thumbImage)
                .attr("title",base_url+"/books"+bookUrl)
                .click(function(){
                    var gUrl = $(this).attr("title");
                    window.location.href = gUrl;                    
                })
            ;            
        }  
        $(".top10Layer").css("height",(wH-170)+"px");
});
</script>
<style>
.flipbook-menuWrapper {
    height:45px;
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
        cursor: pointer;
    }
</style>
</head>

<body class="published" status="<?=$booked_data->sql ?>">

<!-- added here -->

<div id="main_inner_overlay" style="display:none;position:absolute;height:100%; background:#000000;width:100%;z-index:233; opacity:0.70; top:0; left:0;"></div>
<div id="main_inner_uploder_pop" style="display:none;left:50%;margin:-225px 0px 0px -300px; top:35%; position:fixed;z-index:23333; " >test</div> 
<div id="main_inner" ></div>

<div id="app_loader1" class="hideDiv"><div class="bar"><span></span></div></div>

<div id="app_loadercover" class="hideDiv"> </div>
	<!--[if lt IE 9]><p class=chromeframe>You're not using the <em>latest</em> version of your browser. Please <a href="http://browsehappy.com/">get Firefox here </a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome</a> to experience the full features of this website.</p><![endif]-->
<div id="fb-root"></div>
<script src='//connect.facebook.net/en_US/all.js'></script>
<script type="text/javascript">
    window.fbAsyncInit = function() {	   
		FB.init({appId: "<?php echo $this->config->item("fb_appkey"); ?>", status: true, cookie: true, xfbml: true, channelURL : "<?php echo $this->config->item("base_url"); ?>/channel.html", oauth:true});
		FB.getLoginStatus(function(response) {		      
			if (response.status === "connected") { 
				FB.api("/me", function(res) {
				    console.log("Hi "+res.first_name+"! your fb id is: "+res.id);
					$.ajax({ 
							url     : '<?php echo $this->config->item("base_url"); ?>/books/set_cookie_value',
							async	: false,
							data	: 'first_name='+res.first_name+'&facebook_id='+res.id,
							type    : 'post',
							success : function(res){
											 
									}	 		
					});		
				}); 
			} else if (response.status === 'not_authorized') {
                console.log("Hi, you're not authorized. Please authorize this app.");
                FB.login(function(response) {                        
                    if (response.authResponse) {
                        FB.api("/me", function(res) {
				            console.log("Hi "+res.first_name+"! your fb id is: "+res.id);
                            $.ajax({ 
                                url     : '<?php echo $this->config->item("base_url"); ?>/books/set_cookie_value',
                                async	: false,
                                data	: 'first_name='+res.first_name+'&facebook_id='+res.id,
                                type    : 'post',
                                success : function(res){
											 
								}	 		
                            });		
			             });    
                    } else {
                        console.log('User cancelled login or did not fully authorize.');
                    }
                });
            } else {                
                console.log("Hi, you're not logged in.");
				$.ajax({ 
					url     : '<?php echo $this->config->item("base_url"); ?>/books/set_cookie_value',
					async	: false,
					data	:  'first_name='+''+'&facebook_id='+'',
					type    : 'post',
					success : function(res){
						 
							}	 		
				}); 
			} 
		});
        setTimeout(function() {
            if(typeof FBInitialized == "function") FBInitialized();                    
        }, 1000);
	};
(function(d, debug){
	var js, id = "facebook-jssdk", ref = d.getElementsByTagName("script")[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement("script"); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all"+ (debug ? "/debug" : "") +".js";
	ref.parentNode.insertBefore(js, ref); 
}(document, /*debug*/ false));
</script>  
<script type="text/javascript">
function FBInitialized(){
    loadInlineChooser();
}
function loadInlineChooser() {
    console.log("in function");
    var thisURL = '<?=$bookUniqueURL; ?>';
                    $('#js-invite-friends-view').friendChooser({
                        display: "inline",
                        showSubmit: false,
                        returnData: "all",
                        max: 0,
                        min: 0,
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
                                    var thisName = users[i].name;;
                                    postToFriend(thisID, thisName);
                                    friends += users[i].id+",";
                                }
                                $("#js-publish-book-step1 #js-user-data-see").val(friends);
                                jQuery('#js-invite-friends-collab').friendChooser('submit');
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
                        min: 0,
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
                                    var thisName = users[i].name;;
                                    postToFriend(thisID, thisName);
                                    friends += thisID+",";
                                }
                                $("#js-publish-book-step1 #js-user-data").val(friends);
                                $.ajax({
                                    url: '<?=$this->config->item('base_url');?>/publish_book/choose_friends',
                                    type: 'post',
                                    data: $('form#choose_friends').serialize(),
                                    success: function(data) {
                                        postToSocial();
                                    }
                                });
                            } else {
                                console.log("no friends selected");
                                $.ajax({
                                    url: '<?=$this->config->item('base_url');?>/publish_book/choose_friends',
                                    type: 'post',
                                    data: $('form#choose_friends').serialize(),
                                    success: function(data) {
                                        postToSocial();
                                    }
                                });
                            }
                        }
                    });
                    function postToFriend(thisID, thisName) {
                        var thisUrl = thisURL;
                        var thisImg = $("body").attr("coverThumbUrl");
                        var thisTitle = "<?php echo $book_data->book_name; ?>";
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
                                description: 'Hardcover book created by: '+thisOwner+'.'
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
                        $('#js-publish-book-modal').modal('hide');
                    }
        		}
	</script>
<div id="pp_header" app_id="<?php echo $this->config->item("fb_appkey"); ?>"></div>
<!-- added here -->

<div id="container" class="book-flip-url" test="<?php echo "User ID: ".$user_id." ~ Condition ID: ".$perm." ~ Login type: ".$login." ~ show book: ".$show_book." ~ can add photo: ".$can_add_photo." ~ book info id: ".$book_info_id." ~ rightside: ".$rightside." ~ creator id: ".$creator_fbid; ?>">
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
                        <button  name="<?php echo $this->config->item("base_url") ."/uploader/". $_COOKIE["hardcover_book_info_id"] ."/". $creator_fbid; ?>" class="share_here">Login to check the book <?php echo $creator_fname; ?><br /><br /><h6>Click Here</h6></button><a name="album_for_me_unique" id="album_for_me_unique" style="display:none;">click</a>
    <?php } // if see_book ?>
    <?php if($contributors == 1){ ?>
                        <div style="text-align:left; margin-top:40px; margin-left:50px;"> <img src="https://graph.facebook.com/<?php echo $fbb_id; ?>/picture?type=small"> &nbsp;&nbsp;Created By :<?php echo json_decode(file_get_contents('http://graph.facebook.com/'.$fbb_id))->name;  ?></div><br/><h3 style="text-align:left;   margin-left:50px; margin-bottom:4px;">Contributers</h3>
        <?php  foreach($contributers_data as $k=>$v) {   ?>
                        <p style="text-align:left;   margin-left:50px;"><img style="height:35px; width:35px;" src="https://graph.facebook.com/<?php echo $v->friends_fbid; ?>/picture?type=small">&nbsp;&nbsp;<?php  echo $v->friends_name ?> </p>
        <?php } ?>
    <?php } ?>
    <?php if($login == 2) { ?>
							<div>
							   Only Friends of <?php echo $creator_fname; ?> can view his Book<br/><br/>
							   <a href="http://apps.facebook.com/hardcoverdev">Click here </a> to create your own book instead
							</div>
    <?php } ?>
    <?php if($rightside == 1) { ?>
							<div id="share_here_container" style="margin-top:150px; text-align:center">
        <?php if($login == 1 && $can_add_photo == 1 ){?>

        <?php }else{ ?>
            <?php if($user_id == '') { ?>
				        <div style="position:absolute; top:45%; left:15%; z-index:99999;">
							<p>Login with Facebook to add photo to this Book </p>
							<button style="padding:0px; position:relative; top:0px; border:none; box-shadow:0px;background:url('<?php echo $this->config->item("base_url"); ?>/images/fb-connect.png') no-repeat; height:25px; width:200px;" class="fb_login">&nbsp;</button>
                        </div>
            <?php } else if($can_add_photo == 1) { ?>

            <?php } else if($can_add_photo == 0) { ?>
							<p ><button class="ask_photo_permission" style="width: 320px; height: 100px; top: 40%; left: 15%; position: absolute; font-size:1.6em; font-family:'arial'; background: opacity:.8; z-index:99999;" ><b>Ask for permission to Add Photo
							</b> <br/>Click here</button> </p>
            <?php } ?>
        <?php } ?>
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
						        				<h3 class="h5"><?php echo $book_data->book_name; ?></h3>
						        				<div class="media">
												  <a class="pull-left" href="#">
												    <img class="media-object img-responsive" src="<?=$this->config->item('image_url'); ?>/150x150" alt="" id="js-step2-port" />
												  </a>
												  <div class="media-body">
												    <p>Author: <?php echo $user_details->fname;  ?>&nbsp;<?php echo $user_details->lname; ?></p>
												  </div>
												</div>
						        			</div>
						        			<div class="col-lg-5">
						        				<ul class="list-unstyled">
						        					<li>
						        						<input type="checkbox" name="postBookToMyFB" id="postBookToMyFB" checked="checked"/> Post book to my facebook wall
						        					</li>
						        					<li>
						        						<input type="checkbox" name="postBookToMyTW" id="postBookToMyTW" /> Post to Twitter
						        					</li>
						        					<li>
						        						<input type="checkbox" name="postBookToMyPR" id="postBookToMyPR" /> Post to Pinterest
						        					</li>
						        				</ul>

						        			</div>
						        		</div>

								  	</div>
								</div><!-- End of Panel -->

								<div class="row">
									<div class="col-lg-6">
										<div class="panel panel-default">
										  	<div class="panel-body" id="js-invite-friends-view"></div>
										</div><!-- End of Panel -->
									</div><!-- End of col-lg-6 -->
									<div class="col-lg-6">
										<div class="panel panel-default">
										  	<div class="panel-body" id="js-invite-friends-collab"></div>
										</div><!-- End of Panel -->
									</div>
								</div><!-- End of row -->
				        	</form>
                            <div class="modal-footer">
			        	        <a href="javascript:void(0);" class="btn btn-small btn-orange" id="js_invite_friends_next">Done</a>
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


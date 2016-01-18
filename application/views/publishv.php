<?php
$fb_id = $_COOKIE['hardcover_fbid'];
?>
<html>
<head>

	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript"> google.load("jquery", "1"); </script>
    <script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/friendchooser/jquery.easing.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/friendchooser/jquery.friendChooser-packed.js"></script>
        
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/friendchooser/friendChooserDefault.css" />
    <style type="text/css">
        a.custom-submit {
            color: #ffffff; 
            background-color: #6D84B4; 
            border: 1px solid #3B5998; 
            text-decoration: none; 
            padding: 3px 10px; 
            font-family: Arial; 
            font-size: 12px;    
            border-radius: 5px;
            linear-gradient: (bottom, #435A87 8%, #637AAD 62%);
        }
    </style>

</head>
<body>

<div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {  
                FB.init({
                    appId      : '250624528397914', // App ID
                    status     : true, // check login status
                    cookie     : true, // enable cookies to allow the server to access the session
                    oauth      : true, // enable OAuth 2.0
                    xfbml      : true  // parse XFBML
                });
                
                loadPopupChooser();       
            };

            // Load the SDK Asynchronously   
            (function(d){
                 var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
                 js = d.createElement('script'); js.id = id; js.async = true;
                 js.src = "//connect.facebook.net/en_US/all.js";
                 d.getElementsByTagName('head')[0].appendChild(js);
            }(document));
            
            
            
            function loadPopupChooser(){
                $('#popup-chooser').friendChooser({
                    display: "popup",    
                    min: 0,
                    max: 0,
                    //showRandom: 10,
                    showCancel: false,
                    showClose: true,
                    showSelectAllCheckbox: true,
                    showCounter: false,
                    useCheckboxes: true,
                    sendRequest: true,
                    returnData: "all",
                    excludeIds: ['1473096444', '733231996', '1019689150', '100003007654062', '1609986014', '1207867377', '526442359', '1658442803', '1289118935', '1174998527'],
                    lang: {
                        requestTitle: "Custom Facebook Friend Chooser",
                        requestMessage: "Facebook friend chooser is used to display a list of friends using the Facebook JavaScript API. User can select a few people and send them a message for example."
                    },
                    onSubmit: function(users) {
                        //window.location.href = '<?php echo base_url()?>publish_book/choose_friends';
						var data = "";
						for (var i = 0, len = users.length; i < len; i += 1) {
							data += " " + users[i].id
						}
						document.myform.user_data.value = data;
                    }
                });    
            }
        </script>   

<script>
	function showmore(val) {
		document.getElementById('amore').style.display = val;
	}
</script>

<div id="popup-chooser"></div>

<form name="myform" action="<?php echo base_url()?>publish_book/choose_friends" method="POST">
<table width="50%" border="1">
<tr>
	<td valign="top" width="50%">
		<div>
			<h2>Who can see this book?</h2>
			<input type="radio" name="whocansee" value="all" checked/> Everyone<br/>
			<input type="radio" name="whocansee" value="friends_of_friends"/> Friends and friends of friends<br/>
			<input type="radio" name="whocansee" value="friends"/> Your Facebook friends
		</div>
		<div>
			<h2>Is this a collaborative book? <span><br/>can others add content</span></h2>
			<input type="radio" name="collaborative" value="0"  onclick="showmore('none');" checked/> No &nbsp;
			<input type="radio" name="collaborative" value="1" onClick="showmore('block');"/> Yes
		</div>
		<div id="amore" style="display:none">
			<input type="radio" name="collaborate_with" value="all"/> Everyone<br/>
			<input type="radio" name="collaborate_with" value="friends_of_friends"/> Friends and friends of friends<br/>
			<input type="radio" name="collaborate_with" value="friends"/> Your Facebook friends<br/>
			<input type="radio" name="collaborate_with" value="select" onClick="$('#popup-chooser').friendChooser('open'); return true;"/> Only these people
		</div>
	</td>
	
	<td valign="top">
		<div>
			<h2>Your book unique URL</h2>
			<input type="text" name="book_url" size="35" value="http://www.hardcover.me/books/<?php echo $fb_id?>/"/>
		</div>
		<div>
			<h2>Share to Facebook</h2>
			<input type="checkbox" name="share_facebook" value="1"/> Share to my Facebook wall<br/>
			Share to friends wall <a href="">Select friends</a>
		</div>
		<div>
			Email link<br/>
			Tweet link
		</div>
	</td>
</tr>
</table>
<input type="hidden" name="user_data" value=""/>
<input type="hidden" name="book_info_id" value=""/>
<input type="submit" name="submit" value="Submit"/>
</form>
</body>
</html>
<?php $this->load->helper('url'); ?>

<script>
$(document).ready(function(){	
	$('#friendslist').click(logIn);
	
	function checkPermissions() {
		FB.api('me/permissions', function(res) {
			var perms = ['user_photos','friends_photos','read_stream','publish_stream','publish_actions'];
	   		$.each(perms, function(i, perm) {
				if(!res || !res.data[0] || !(perm in res.data[0])) {
		 			logIn();
				}
	   		});
	  	});
	}
 	
	function logIn(){
  		FB.login(function(response) { 
     		if (response.authResponse){  
     			init();
     		} 
   		},
  		{scope:'user_photos,friends_photos,read_stream,publish_stream,publish_actions'});
 	}
	
	function init() {
		/*$('#friendslist').myModal({
				sendMessage    : false,
				friendSelector : true
		});*/
		
		$.fn.myModal({
			sendMessage    : false,
			friendSelector : true
		});
		/*FB.api('/me', function(response) {
			$("#username").html("<img src='https://graph.facebook.com/" + response.id + "/picture'/><div>" + response.name + "</div>");
		});*/
	}
});
</script>
        <div id="filter_content">
            <!--fb_data-->        
            <div id="fb_data" class="tab2_content">
            
            	<div class="leftcolumn-cont">
                	<h3>Select a book size</h3>
                	<div class="leftcolumn center-content">
                    	<p><img src="<?php echo base_url(); ?>/images/9x7-photobook.png"/></p>
                		<h3>Landscape</h3>
                        <p>9 x 7 in Photo Book</p>
                        <p>starts at $14.95</p>
                	</div>
                    
                    <div class="leftcolumn">
                		<h3>Soft Cover $14.95 <br/> Hard Cover $24.95</h3>
                        <p>Price per 20 page book (10 sheets)</p>
                        <p>Each additional page: $1.00</p>
                	</div>	
                </div>
                
                <div class="rightcolumn-cont">
                	<form id="form_filter_data"> 
                        <h3>Who</h3>
                        <?php						
                        switch ($book_filter->album_for_who){
                            case ALBUM_FOR_ME:
                                $check_album_for_me = 'checked=checked';
                                break;
                            case ALBUM_FOR_FRIENDS:
                                $check_album_for_friend = 'checked=checked';
                                break;
                            case QUICK_BOOK:
                                $check_quick_book = 'checked=checked';
                                break;
                            case GROUP_GIFT:
                                $check_group_gift = 'checked=checked';
                                break;										
                            default:
                                $check_album_for_me = 'checked=checked';
                        }
                        ?>
                        <p><input type="radio" name="filter_menu_radio" value="filter_me"  class="styled" <?php echo $check_album_for_me;?>/> Album for me</p>
                        <p><input type="radio" name="filter_menu_radio" value="filter_friend"  class="styled" <?php echo $check_album_for_friend;?> id="friendslist"/> Album for friend(s)</p>
                        <p><input type="radio" name="filter_menu_radio" value="filter_quick"  class="styled" <?php echo $check_quick_book;?>/> Quick book <br/><span>(see what all my friends
        did over weekend in your city)</span></p>                            
                        <p><input type="radio" name="filter_menu_radio" value="filter_group"  class="styled" <?php echo $check_group_gift;?>/> Group gift <br/><span>(several friends buy a bday album for one friend)</span></p>
                        
                        <div id="username"></div>
                </form>
                		
                </div><!-- end .rightcolumn-cont -->
                <div class="clear_fix"></div>
                <button class="bold float_right button-large" id="button_main_next">Next</button>
                
            </div><!-- end #fb_data -->
        </div><!-- end #filter_content -->

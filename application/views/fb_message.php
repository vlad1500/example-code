<?php if($_POST['ret'] == 'send') { ?>
<div id="msg_container">
	<h1>New Message</h1>
    <div id="form_container">
        <form id="send_friend">
          	<p class="lbl float_left">To</p>
            <p class="inp float_left"><input type="text" placeholder="Search Name" size="55" id="f_name"><ul id="sel_friends" class="hideDiv"></ul></p>
            <div class="clearfix"></div>
        	<p class="lbl float_left">Message</p>
            <p class="inp float_left">
            	<textarea rows="15" cols="57" id="txtmsg">
        		</textarea>
            </p> 
            <p class="float_right" style="margin-right:34px;"><input type="submit" id="submit_form" name="submit" value="Send"/><input type="button" id="cancel_form" name="reset" value="Cancel"/></p>   
        </form>
    </div>
</div>
<?php }else if($_POST['ret'] == 'friends') { ?>
<div id="msg_container_bottom">
<div id="msg_container_left">
	<!--<h1>Suggest HardCover to friends</h1>-->
	<h1>Add Friends to Share for HardCover gift</h1>
    <div id="form_container_left">
        <form>
	        <p class="inp_f"><input type="text" placeholder="Search" size="81"></p>
            <ul id="friend_selector">
            	<script type="text/javascript">
					/*$(function(){
						$.ajax({
							url 	: 'main/get_fb_friends_withname',
							type	: 'post',
							success : function(res){				
							var _obj = $.parseJSON(res);	
							var split_obj = _obj.friends.split(';');
							$.each(split_obj,function(i,elem){
								var _objname = elem.split(':');								
								var _name = _objname[1].length > 20 ? _objname[1].substring(0,17)+'...' : _objname[1].substring(0,20);
								var imgUrl = 'https://graph.facebook.com/'+_objname[0]+'/picture';
								var x_img = "http://hardcover.shoppingthing.com/images/x.png";
								$('#friend_selector').append('<li id="'+_objname[0]+'" class="float_left"><input type="checkbox" class="float_left" name="friend_chk"/><img class="float_left" width="30" height="30" src="'+imgUrl+'"><p class="float_left">'+_name+'</p></li>');
							 });
							}
						 });	
					});*/
				</script>
            </ul>
            <p class="float_right" style="margin-right:5px;"><input type="button" id="submit" name="submit" value="Send Invite"/><input type="button" id="cancel" name="cancel" value="Cancel"/></p> 
        </form>
    </div>
</div>    
<div id="msg_container_right">
	<h1>Group Payment (example)</h1>
    <div id="form_container_right">
    	<h4>Book: <?php echo ($_POST["book_name"] ? $_POST["book_name"] : "?"); ?></h4>
        <table id="book_price_details">
            <tr><td>Pages:</td><td>60</td></tr>
            <tr><td>Landscape:</td><td>9X7</td></tr>         	
            <tr><td>Price:</td><td>$54.95 (HardCover)</td></tr>
            <tr><td>Shipping:</td><td>$7.99 (estimated)</td></tr>
            <tr><td><b>Total</b></td><td>$62.94</td></tr>      
        </table>
        <h4 class="clearfix">Number of Friends to Share (<span id="number_of_friends"></span>):</h4>
        <table id="friends_to_share">
            <tr><td>You +</td></tr>
            <tr><td>Dennis Jade Toribio</td></tr>
            <tr><td>Stash Harrison</td></tr>
            <tr><td>Otrebor Panes</td></tr>
            <tr><td>Mychelle</td></tr>
            <tr><td>Khimes</td></tr>
        </table>
        <h3>Each pay: <span id="each_pay">?</span></h3>
	    <a href="#" id="gpayment_works" style="position:relative;top:160px;">How group payment works?</a>
    </div>
</div>
</div>
<?php }else if($_POST['ret'] == 'print') { ?>
<div id="msg_container">
    <h1>Print Book</h1>
    <div id="form_container">
        <form id="print_book">
          	<h1>HardCover uses LULU for printing</h1>
            <div class="clearfix"></div>
            <h2>Click Print Book to proceed</h2>
            <div class="clearfix"></div>
			<p class="float_right" style="margin-right:34px;"><input type="button" id="cancel" value="Cancel">&nbsp;<input type="button" id="group_gift" value="Group Gift">&nbsp;<input type="button" id="print_book" value="Print Book"></p>
        </form>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
<!--
	var orig_content = $("#msg_container_bottom").html();
	$("#gpayment_works").live("click", function(obj){
		var gpayment_works = '<div id="msg_container_left" style="float:left;display:block;opacity:0;"><h1>Group Payment Details</h1>';
		gpayment_works += '<div id="form_container_left">';
		gpayment_works += '<blockquote><form><p><h2> To make the group payment simple.</h2></p>';
		gpayment_works += "<p><ol><li> -We send a PayPal request to your friends asking them to pay you the amount.</li>";
		gpayment_works += "<li> -Your friends pay you via PayPal.</li>";
		gpayment_works += "<li> -You buy the book from LULU with your credit card.</li></ol></p>";
		gpayment_works += '<p><h2> OR</h2> -You can demand a cash from them.<br> -We can send FB Message to them for you. <a href="#" id="click_here">Click here</a></p>';
		gpayment_works += "</form></blockquote></div></div>";
		$('#msg_container_bottom').animate({opacity:100},'slow','linear',function(){
		 	$(this).html(gpayment_works);
			var w = $('#modal_inner').width() / 2;
			var wt = $('#msg_container_bottom').width() / 2;
			var _left = (w - wt) + 25;
	 		$('#msg_container_left').css({'opacity':100,'z-index':10000,'margin-left':_left});
	 	});
		$("#click_here").live("click", function(obj){
			$('#msg_container_left').animate({opacity:0},'slow','linear',function(){
				$("#msg_container_bottom").html(orig_content).css({opacity:100});
				//sendMessage();
				modal_position();
			});
			return false;
		});
	});
-->
</script>

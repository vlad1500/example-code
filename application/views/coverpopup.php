<div id="msg_container">
	<h1>Add/Remove Friends in Thumbnails</h1>
	<div id="form_container_arf">
		<img src="<?php echo $this->config->item("image_url"); ?>/search.png" id="search" /><input type="text" placeholder="Search..." class="input_search" />
		<ul id="add_remove_friends">
		<div class="ajax_loader"></div>
		<br><p id="loading" align="center">Loading...</p><p align="center"><a href="#" id="show_all" class="hideDiv">Show All</a></p>
		</ul>
		<p class="float_right" style="margin-right:5px;padding:5px;"><input type="button" id="ok" name="ok" value="Ok" /><input type="button" id="cancel" name="cancel" value="Cancel" /></p>
	</div>	
</div>
<script type="text/javascript">
//JS Code for Add/Remove Friends Pop-up screen
var active_cover = "", my_friends = ($.cookie("hardcover_friends_fbid") === null ? "" : $.cookie("hardcover_friends_fbid")), n_friends, is_checked = 0, obj_friends, split_obj, _objname, _name, imgUrl;
	
	$("#form_container_arf .input_search").on("keydown", function(event) {
		/**
		 * Search bar - input text
		 *
		 */
		if (event.which == 13) {
			$("#form_container_arf #search").click();
			event.preventDefault();
		}
	});
	
	$("#form_container_arf #search").on("click", function() {
		/**
		 * Search image
		 *
		 */
		searchList($("#form_container_arf .input_search").val());
	});
	
	//$("#add_remove_friends li input[type=checkbox]").click(function(e) {
		/**
		 * Adding/Removing each friends from the thumbnails by checking/unchecking the checkbox 
		 *
		 */
		/*var tmp_friend = new Array(), friend_id = 0;
		my_friends = ($.cookie("hardcover_friends_fbid") === null ? "" : $.cookie("hardcover_friends_fbid"));
		e.stopPropagation();
		friend_id = $(this).attr("id");
		$("#msg_container h1").append("<br>ID:"+$(this).target.nodeName);
		if (my_friends.search(friend_id) < 0) {
			if (isLimitReached()) {
				alert("You've reached the maximum number of friends to show in thumbnails.");
				return false;
			}
			tmp_friend.push(friend_id);
			my_friends = tmp_friend.join(";");
		} else {
			tmp_friend = my_friends.split(";");
			tmp_friend.splice(tmp_friend.indexOf(friend_id), 1);
			my_friends = tmp_friend.join(";");
		}
		$.cookie("hardcover_friends_fbid", my_friends);
	});*/
	
	$("#ok").on("click", function() {
		/**
		 * Ok button
		 *
		 */
		var tmp_friend = new Array(), friend_id = 0;
		$(".ajax_loader").fadeIn("slow");
		$("#add_remove_friends li input[type=checkbox]:checked").each(function() {
			if ($(this).is(":checked")) {
				friend_id = $(this).attr("id");
				tmp_friend.push(friend_id);
			}
		});
		tmp_friend = tmp_friend.slice(0, n_friends);
		my_friends = tmp_friend.join(";");
		
		if (!isLimitReached()) {
			alert("Add "+ friendsToAdd() + (friendsToAdd() > 1 ? " more" : " ") +" friend"+ (friendsToAdd() > 1 ? "s" : "") +" to make your cover look better.");
			return false;
		} else {
			$.cookie("hardcover_friends_fbid", my_friends);
			$(".ajax_loader").fadeOut("slow");
			$("#cancel").click();
			$("#app_loader").fadeIn("slow");
			putFBFriends(my_friends);
			$("#app_loader").fadeOut("slow");
		}
	});
	
	$.expr[":"].icontains = function(obj, index, meta, stack) {
		return (obj.textContent || obj.innerText || jQuery(obj).text() || "").toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;
	};
	
	$("#show_all").click(function(e) {
		/**
		 * Show all the friends from the list
		 *
		 */
		searchList("");
		e.preventDefault();
		return false;
	});
	
	function isLimitReached() {
		/**
		 * Determine whether the number of added friends already exceed the limit number (Front = 24, Back = 35)
		 *
		 */
		var split_friends;
		
		split_friends = my_friends.split(";");
		if (split_friends.length >= n_friends) {
			return true;
		} else {
			return false;
		}
	}
	
	function friendsToAdd() {
		/**
		 * Get the remaining number of friends to add in your cover thumbnails
		 *
		 */
		var split_friends;
		
		split_friends = my_friends.split(";");
		return (n_friends - (my_friends == "" ? 0 : split_friends.length));
	}
	
	function searchList(search_val) {
		/**
		 * Search within the Friends list
		 *
		 * @param	String search_val Keyword to search friends' name
		 */
		$(".ajax_loader").fadeIn("slow");
		if ($.trim(search_val) != "") {
			$("ul#add_remove_friends li").hide();
			$("ul#add_remove_friends li:icontains('"+ search_val +"')").show("slow");
			$("p#loading").fadeOut("slow");
			$(".ajax_loader").fadeOut("slow");
			$("#show_all").fadeIn("slow");
			if ($("#add_remove_friends li").find(":visible").length <= 0) {
				$("p#loading").html("No match found...").fadeIn("slow");
			}
		} else {
			$("p#loading").html("Loading...").fadeOut("slow");
			$("#show_all").fadeOut("slow");
			$("ul#add_remove_friends li").show();
			$(".ajax_loader").fadeOut("slow");
		}
		return false;
	}
	
	function showFriendsList(friends_list) {
		/**
		 * Display the list of your Facebook Friends
		 *
		 * @param	String friends_list Semi-colon separated values of your Facebook Friends' ID
		 */
		split_obj = friends_list.split(";");
		$(".ajax_loader").fadeIn("slow");
		$.each(split_obj, function(i, elem) {
			_objname = elem.split(":");
			_name = _objname[1];
			imgUrl = "https://graph.facebook.com/"+ _objname[0] +"/picture";
			if (my_friends != "") {
				is_checked = (my_friends.search(_objname[0].substring(1)) >= 0 ? 1 : 0);
			} else {
				is_checked = 0;
			}
			$("#add_remove_friends").append('<li id="li'+ _objname[0] +'"><input id="'+ _objname[0] +'" type="checkbox" class="hc_checkbox"'+ (is_checked ? ' checked' : '') +' /><label for="'+ _objname[0] +'" class="float_left"></label><img class="float_left" width="30px" height="30px" src="'+ imgUrl +'"><span>'+ _name +'</span></li>').fadeIn("slow");
			if (i >= (split_obj.length-1)) {
				$("p#loading").fadeOut("slow");
				$(".ajax_loader").fadeOut("slow");
			}
		});
	}
	
	$(function() {
		active_cover = $("#cover_menu_left ul li.selected").attr("id");
		active_cover = active_cover.replace("_cover", "");
		n_friends = ($.trim(active_cover) == "front" ? 24 : 35);
		my_friends = (eval(active_cover +"_friends_fbid") === null ? $.cookie("hardcover_friends_fbid") : eval(active_cover +"_friends_fbid"));
		$.ajax({
			url		: "/cover/getFBFriends",
			type	: "post",
			success	: function(result) {
				obj_friends = $.parseJSON(result);
				if (obj_friends.status) {
					showFriendsList(obj_friends.friends);
				}
			}
		});
		$("#form_container_arf .input_search").focus();
	});
</script>
/**
 * JS Codes for Cover Preview page
 * 
 * 
 */
var active_cover = "";
	
	function putFBFriends(fb_friends) {
		/**
		 * Display Facebook Friends in thumbnails
		 *
		 */
		var split_obj = "", img_url = "", friends_thumbs = "";
		if (fb_friends !== null) {
			$("#cover_friends_pic").empty().fadeOut("slow");
			friends_fbid = fb_friends;
			if(friends_fbid.length>0)
			{
			split_obj = friends_fbid.split(";");
			$.each(split_obj, function(i) {
				img_url = "https://graph.facebook.com/"+ split_obj[i] +"/picture?width=80&height=80";
				friends_thumbs += "<li id=\""+ split_obj[i] +"\" class=\"float_left\"><img src=\""+ img_url +"\" width=\"70px\" height=\"70px\" /></li>";
				/*$("#cover_friends_pic li:first").clone(true).attr("id", split_obj[i]).appendTo("#cover_friends_pic");
				$("#cover_friends_pic #"+ split_obj[i] +" img").attr("src", img_url);*/
			});
		  }
			$("#cover_friends_pic").html(friends_thumbs).fadeIn("slow");
			/*$("#cover_friends_pic li:first").remove();
			$("#cover_friends_pic").fadeIn("slow");*/
		}
	}
	
	function profilePic(user_pic) {
		/**
		 * Get the large square size of user's profile picture
		 *
		 * @param	String user_pic URL of FB user's profile picture
		 */
		user_pic = $.trim(user_pic);
		var img_url;
		if (user_pic == "" || user_pic === null) {
			$("div#cover_prof_pic").hide();
		} else {
			user_pic = user_pic.substring(0, user_pic.lastIndexOf("?"));
			img_url = "<img src=\"https://"+ user_pic +"?width=205&height=205\" width=\"225px\" height=\"225px\" />";
			//img_url = "https://"+ user_pic +"?width=205&height=205";
			//$("div#cover_prof_pic img").attr("src", img_url);
			$("div#cover_prof_pic").html(img_url);
			$("div#cover_prof_pic").show();
		}
	}
	
	function loadCover() {
		/**
		 * Loading the Cover Preview Page
		 * 
		 */
		var my_url = window.location.href, get_cover = "";
		$("#app_loader1").fadeIn("slow");
		if ($.cookie("hardcover_book_info_id") !== null) {
			get_cover = $.trim($("#cover_preview_content").attr("class"));
			if (get_cover == "cover_preview_") {
				active_cover = "front";
			} else {
				active_cover = get_cover.substring(get_cover.lastIndexOf("_") + 1);
			}
			//active_cover = my_url.substring(my_url.lastIndexOf("/") + 1);
			$.ajax({
				url 	: "/cover/getCoverInfo/"+ $.cookie("hardcover_book_info_id") +"/"+ active_cover,
				type	: "post",
				success : function(value) {
					var _obj = $.parseJSON(value);
					if (_obj.status) {
						if (active_cover == "front") {
							$("#cover_title").val(_obj.cover.book_name);
							$("#cover_author").val(_obj.cover.author);
							profilePic(_obj.cover.user_pic);
						}
						putFBFriends(_obj.cover.friends_pic);
						//$("#cover_preview_page").clone(true).empty().appendTo("#hc_book");
						$("#hc_cover").fadeIn("slow");
						$("#app_loader1").fadeOut("slow");
						
					} else {
						alert(_obj.msg);
					}
				}
			});
		}
	}
	

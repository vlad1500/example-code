$(function () {
	
	/*var page = JSON.stringify(temp);
	$("#bookContainer").flipBook({
            pages: jQuery.parseJSON(page),
            lightBox:false,

            webgl:false,
            pageHardness:2.5,
            coverHardness:8,
            pageMaterial:'phong',
			
    });*/

	function request_to_friends() {
		jQuery('#js-content-appoval-no').attr('checked','checked');
		FB.init({appId: '331059976950036', xfbml: true, cookie: true});
		FB.ui({method: 'apprequests',
			display:'popup',           
			message: 'Hard Cover Request',
			title:'HardCover group album request'
		}, requestCallback);
	}

	function request_to_friends_see() {
        var u_url = $("#js-some-frnd").attr("u_url");
		// send message to friends code start
		FB.init({appId: '331059976950036', xfbml: true, cookie: true});
		FB.ui({method: 'apprequests',
			display:'popup',
            data: u_url,
			message: 'Hard Cover Request',
			title:'HardCover group album request'
		}, requestCallbacksee);
	}

	function select_friends() { 
		
		//FB.init({appId: '331059976950036', xfbml: true, cookie: true});
		FB.api('/me', function(response) {

			$("#username").html("<img src='https://graph.facebook.com/" + response.id + "/picture'/><div>" + response.name + "</div>");

			$("#js-jfmfs-container").jfmfs({ 
				max_selected: 15, 
				max_selected_message: "{0} of {1} selected",
				friend_fields: "id,name,last_name",
				sorter: function(a, b) {
					var x = a.last_name.toLowerCase();
					var y = b.last_name.toLowerCase();
					return ((x < y) ? -1 : ((x > y) ? 1 : 0));
				}
			});

			$("#js-jfmfs-container").live("jfmfs.friendload.finished", function() { 
				window.console && console.log("finished loading!"); 
			});

			$("#js-jfmfs-container").live("jfmfs.selection.changed", function(e, data) { 
				window.console && console.log("changed", data);
			});                     

			$("#logged-out-status").fadeOut().hide();
			$("#js-show-friends").fadeIn().show();
			$('#js-back-to-settings').fadeIn().show();
			$("#js-show-friends").css('margin-bottom','10px');

			$("#js-jfmfs-container").fadeIn().show();

			$("#js-preview-tab").fadeOut().hide();
		});
	}

	function share_on_wall() {
		// send message to friends code start
		  
		FB.init({appId: '331059976950036', xfbml: true, cookie: true});

	 	FB.ui({
    		method: 'stream.publish',
    		message: 'Message here.',
    		attachment: {
				picture: "http://fbrell.com/f8.jpg",
       			name: 'HardCover dev',
       			caption: 'Caption Testing.',
       			description: ('description here'),
       			href: 'https://dev.hardcover.me'
     		},
     		action_links: [
       			{ text: 'Code', href: 'https://dev.hardcover.me' }
     		],
    		user_prompt_message: 'Personal message here'
   		},
   		function(response) {
     		if (response && response.post_id) {
       			alert('Post published.');
     		} else {
       			alert('Post not published.');
     		}
   		}); 
	}

	function callbacktoshare(response)	{
		$('#js-share-facebook').val(response['post_id']);
	}
   	
   	function requestCallback(response) {   	    
		$('#js-user-data').val(response.to);
        var book_id = $.cookie("hardcover_book_info_id");
        $.each( response.to, function( key, value ) {
		  $.ajax({
			 url     : '/edit_album/save_wall_friends',
			 type    : 'post',
			 data 	: 'book_info_id='+book_id+'&friend_fb_id='+value,
			 success : function(res){
			 }
		  });
        });
	}
	
	function requestCallbacksee(response) {
		$('#js-user-data-see').val(response.to);
        var book_id = $.cookie("hardcover_book_info_id");
        $.each( response.to, function( key, value ) {
		  $.ajax({
			 url     : '/edit_album/save_wall_friends',
			 type    : 'post',
			 data 	: 'book_info_id='+book_id+'&friend_fb_id='+value,
			 success : function(res){
			 }
		  });
        });
	}

	function coll_hide(){
		$('#js-every').fadeIn().hide();
		$('#js-content-approval').fadeIn().hide(); 
	}
  
	function coll_show(){
	    $('#js-every').fadeIn().show();
		$('#js-content-approval').fadeIn().show();
	}	

	$('.js-collab-show').on("click", function() {
		coll_show();
	});

	$('.js-collab-hide').on("click", function() {
		coll_hide();
	});
	
	//CESAR: back to the settings page action	
	$('#js-back-to-settings').on("click", function() {
		$("#logged-out-status").fadeOut().hide();
		$("#js-show-friends").fadeIn().hide();
		$('#js-back-to-settings').fadeIn().hide();
		$("#js-jfmfs-container").fadeIn().hide();
		$("#js-preview-tab").fadeOut().show();
	});
	
	$(".js-select-friends").on("click", function() {
		$('#js-back-to-settings').show();
		$('#js-show-friends').show();
		select_friends();
		
	});

	$("#js-closepreview").on("click", function() {
		window.close();
	});

	//$("#js-publishbook").on("click", function() {
//
//	});

    function inviteFriend(thisID) {
        
    }
    function collabFriend(thisID) {
        
    }

	function pretty_close(){
		$.prettyPhoto.close(); 
		return false;  
	}
});

/* JS Codes for Share pop-up screen */
var share_window, my_url = window.location.href, x_slash = "";
	
	if (share_window) {
		if (share_window.closed) {
			$("#cancel").click();
		}
	}
	if (my_url.toLowerCase().search("hardcover.") > 0) {
		x_slash = "/";
	}
	
	function openNewWin(_url, _name, _specs) {
		/**
		 * Open a modal pop-up window for all browsers
		 *
		 * @param String _url This is the URL of the window you want to see in your pop-up window
		 * @param String _name This is the name of the pop-up window
		 * @param String _specs Contains the properties of the pop-up window (e.g. width, height, location, toolbar, resizable, menubar, etc.)
		 */
		var _win;
		
		if (_url.replace(/\s */gi, "").length < 1) { //set default value for _url
			_url = "";
		}
		if (_name.replace(/\s */gi, "").length < 1) { //set default value for _name
			_name = "HardCoverModalPopUpWindow";
		}
		if (_specs.replace(/\s */gi, "").length < 1) { //set default value for _specs
			_specs = "width=500,height=280,left=150,toolbar=0,status=0,menubar=0,location=0,titlebar=0,resizable=0,scrollbars=0,modal=1";
		} else {
			_specs += ",modal=1";
		}
		
		if (window.showModalDialog) { //for IE
			_specs = "dialogWidth="+ _specs.substring(_specs.search("width") + 6, _specs.search("width") + 9);
			_specs += ",dialogHeight="+ _specs.substring(_specs.search("height") + 7, _specs.search("height") + 10);
			_specs += ",scroll=0,resizable=0,status=0";
			_win = window.showModalDialog(_url, _name, _specs);
		} else { //for other browsers
			_win = window.open(_url, _name, _specs);
		}
		share_window = _win;
		//if (!_win) $("#cancel").click();
	}
	
	function modalMessageBox(_title, _msg) {
		/**
		 * Display a modal message box. Use this instead of alert box.
		 *
		 * @param	String _title Text title of the message box
		 * @param	String _msg Texts you want to say in the message box
		 */
		/*var _html = "", popup_screen;
		
		popup_screen = $(this).myModal(_title);
		if (_title && _msg) {
			_html = "<div id=\"msg_container\"><h1>"+ _title +"<a href=\"#\" id=\"cancel\" class=\"float_right\"><img src=\"/images/close-img.png\" /></a></h1>";
			_html += "<div id=\"form_container\" align=\"center\"><h2>"+ _msg +"</h2>";
			_html += "<p><input type=\"button\" id=\"cancel\" value=\"OK\" /></p></div></div>";
		}
		$("#modal_inner").html(_html);*/
		/*$("#modal_inner").html("<div id=pop_alert></div>");
		$("#pop_alert").parentsUntil("#modal_container").css({"z-index":"99999999999999"}).html(_html, function() {
			$("div#pop_alert #cancel").on("click", function() {
				$("div#pop_alert").parentsUntil("#modal_container").animate({opacity:0},'slow','linear',function(){$(this).remove();});
				return false;
			});
		});*/
        $('#js-modal-common .modal-title').html("");
		$('#js-modal-common .modal-body').html("");        
		$("#app_loader").remove();
		$('#js-modal-common').modal();
		$('#js-modal-common .modal-title').html(_title);
		$('#js-modal-common .modal-body').html(_msg);
	}
	
	function modalPosition() {
		/**
		 * Align the modal message pop-up screen in proper position
		 *
		 */
		$("#modal_container").css({"width":"100%","height":"100%"});
		$("#msg_container").css({"margin-left":"auto","margin-right":"auto"});
		$("#modal_inner").css({"margin-top":(screen.height/5)});
		$("#msg_container").focus();
		$("#modal_inner").fadeIn("slow");
	}
		
	function sendFBMessage(base_url, user_name, recepients) {
		/**
		 * Show Facebook Send Dialog pop-up screen
		 *
		 */
		var _url = "", _to = new Array(), book_name = "", book_info_id = $.cookie("hardcover_book_info_id"), obj, obj_res, popup_title = "", popup_msg = "", txtarea_val = "", shared_link = "";
		
		_url = base_url;
		if ($.trim(base_url).length < 1 || base_url.toLowerCase().search("hardcover.me") < 0) {
			_url = "http://dev.hardcover.me";
		}
		
		if ($.trim(user_name).length > 0) {
			fb_uname = user_name;
		} else {
			FB.api("/me", function(result) {
				fb_uname = result.username;
			});
		}
		
		if ($.trim(recepients).length < 1) {
			//_to[0] = $.cookie("hardcover_fbid");
			_to[0] = "";
		}
		
		$.ajax({
			url	: x_slash +"invite/getBookName",
			type	: "post",
			success	: function(result) {
				obj = $.parseJSON(result);
				if (obj.status) {
					book_name = $.trim(obj.book_name);
					shared_link = _url +"/books/"+ fb_uname +"/"+ book_name +"?m="+ book_info_id; //_url +"/share_album/preview/"+ book_info_id
					FB.ui({
						method		: "send",
						display		: "async",
						name		: book_name,
						to			: _to,
						link		: shared_link,
						picture 	: _url +"/images/hardcover-logo-thumb.jpg",
						description	: "HardCover let's you create and share digital albums including group albums.",
						show_error	: true
					},	function(response) {
						//alert($("#feedform_user_message").html() +"\n\n"+ $("div.tokenarea").html());
						if (response !== undefined) {
							if (response.success) {
								popup_title = "Sending Success";
								popup_msg = "You successfully sent a message to your friend(s) to add contents in \""+ book_name +"\" album.";
							} else {
								popup_title = "Sending Fail";
								popup_msg = "Sorry, but it seems like there\'s an error while sending message to your friend(s).<br /><br />\nPlease try to send a message again.";
							}
							if (popup_title && popup_msg) {
								modalMessageBox(popup_title, popup_msg);
								modalPosition();
							}
						} else {
							$("#cancel").click();
						}
					});
					/*$("div#pop_content textarea#feedform_user_message").ready(function(e) {
						FB.api("/me", function(result) {
							txtarea_val = "I\'m creating a digital album using photos and wall post from Facebook. I would like to add your content so that my album will have the perspective from you and me.\n\nThanks a lot,\n";
							txtarea_val += result.first_name;
							alert(txtarea_val);
							$(this).html(txtarea_val);
							//$(this).text(txtarea_val);
							alert(txtarea_val+"\n\n"+$(this).text()+"\n\n"+$("#pop_content").html());
						});
					});*/
				}
			}
		});
		/*try to set a customized text in message textarea of FB Send Dialog
		$("#feedform_user_message").ready(function(e) {
			var x=document.getElementById('feedform_user_message');
			alert(x.value);
		});
		$("html#facebook textarea#feedform_user_message").ready(function(e) {
			FB.api("/me", function(result) {
				txtarea_val = "I\'m creating a digital album using photos and wall post from Facebook. I would like to add your content so that my album will have the perspective from you and me.\n\nThanks a lot,\n";
				txtarea_val += result.first_name;
				if (txtarea_val.length > 0) {
					//alert(txtarea_val);
					$("html#facebook textarea#feedform_user_message").html(txtarea_val);
					$("html#facebook tr.dataRow th.label").append(txtarea_val);
					alert(txtarea_val+"\n\n"+$("html#facebook div.textMetrics textMetricsInline").html()+"\n\n"+$("html#facebook textarea.textMetrics").html());
				}
			});
						//obj_res = JSON.stringify(response);
						FB Send Dialog (display=page) still has a bug - by default the TO input textbox has blank value and the SEND BUTTON is not disabled (upon focusing in TO input textbox the SEND BUTTON will then be disabled, so when you directly put focus in SEND BUTTON you can send the message w/o any entered value(s) and the Send dialog will still return success:true)
						alert(obj_res+"\n\nHas recepient(s)? "+$("html#facebook div.textMetrics textMetricsInline").html()+">>"+($("html#facebook div.textMetrics textMetricsInline").html() == "Enter a friend, group, or email address..." ? "no" : "yes"));
						
						+"<br><br>"+$("html#facebook div.textMetrics textMetricsInline").html()+"<br><br>"+$("html#facebook textarea.textMetrics").html());
						
		});*/
	}
	
	function showSharePopup(book_info, sh_type) {
		/**
		 * Show window pop-up screen for Facebook & Twitter icons
		 *
		 */
		var book, book_url, book_img_url, book_title, hc_desc, _wt, _ht, _left, _top, _url, hc_url, user_name;
		
		if (book_info !== undefined) {
			book = book_info.split("|");
			book_title = encodeURIComponent(book[1]);
			hc_url = book[2];
			user_name = book[3];
			//book_url = encodeURIComponent("http://dev.hardcover.me/main/share_url?book_info_id="+ book[0] +"&rated="+ $.cookie("hardcover_fbid"));
			book_url = encodeURIComponent(hc_url +"/books/"+ user_name +"/"+ book_title.replace(/%20*/gi, "") +"?m="+ book_info[0]);// +"/"+ fb_friend_id);
			book_img_url = encodeURIComponent(hc_url +"/images/hardcover-logo-thumb.jpg");
			hc_desc = encodeURIComponent("HardCover let's you create and share digital albums including group albums.");
			_wt = 500;
			_ht = 280;
			if (sh_type.toLowerCase() == "fb") { //Share on Facebook
				_url = "https://www.facebook.com/sharer.php?s=100&p[title]="+ book_title +"&p[summary]="+ hc_desc +"&p[url]="+ book_url +"&p[images][]="+ book_img_url +"&p[]="+ $.cookie("hardcover_fbid");
			} else if (sh_type.toLowerCase() == "twit") { //Share on Twitter
				book_title += " by "+ user_name;
				_url = "https://www.twitter.com/intent/tweet?original_referer="+ book_url +"&related=HardCover&text="+ book_title +"&url="+ book_url +"&via=HardCover&token="+ $.cookie("hardcover_token");
			} else if (sh_type.toLowerCase() == "pin") { //Pin it on Pinterest
				_url = "https://www.pinterest.com/pin/create/button/?description="+ book_title +" by "+ user_name +", "+ book_url +"&media="+ book_img_url +"&title="+ book_title +"&url="+ book_url;
			}
			
			if (_url) openNewWin(_url, "ShareHardCover", "toolbar=0,status=0,menubar=0,location=0,titlebar=0,resizable=0,scrollbars=0,width="+ _wt +",height="+ _ht);
		} else {
			modalMessageBox("Share Album", "Please make sure you have a HardCover album to Share.");
			modalPosition();
		}
	}
	
	$("div a#share_fb").each(function() {
		/**
		 * Function for all the Facebook icon
		 *
		 */
		$(this).click(function(event) {
			modalMessageBox("Share on Facebook", "Tell to your friends about your album \""+ ($(this).attr("rel").split("|"))[1] +"\".<br><h4>*Make sure to allow pop-up window in your browser</h4>");
			modalPosition();
			showSharePopup($(this).attr("rel"), "fb");
			event.preventDefault();
			return false;
		});
	});
	
	$("div a#share_twit").each(function() {
		/**
		 * Function for all the Twitter icon
		 *
		 */
		$(this).click(function(event) {
			modalMessageBox("Share on Twitter", "Tell to your friends about your album \""+ ($(this).attr("rel").split("|"))[1] +"\".<br><h4>*Make sure to allow pop-up window in your browser</h4>");
			modalPosition();
			showSharePopup($(this).attr("rel"), "twit");
			event.preventDefault();
			return false;
		});
	});
	
	$("div a#share_pin").each(function() {
		/**
		 * Function for all the Pinterest icon
		 *
		 */
		$(this).click(function(event) {
			modalMessageBox("Pin it on Pinterest", "Tell to your friends about your album \""+ ($(this).attr("rel").split("|"))[1] +"\".<br><h4>*Make sure to allow pop-up window in your browser</h4>");
			modalPosition();
			showSharePopup($(this).attr("rel"), "pin");
			event.preventDefault();
			return false;
		});
	});
	
	$("div a#share_txt").each(function() {
		/**
		 * Function for all the Share text
		 *
		 */
		var obj_friends = "", book_info = "";
		$(this).click(function(event) {
			$("#app_loader").fadeIn("slow");
			book_info = $(this).attr("rel").split("|");
			$.ajax({
				url		: x_slash +"invite/sharePopup",
				type 	: "post",
				data	:	{"book_name" : book_info[1], "link" : book_info[2], "fb_username" : book_info[3]},
				success	: function(result) {
					obj_friends = $.parseJSON(result);
					if (obj_friends.status) {
						$.cookie("hardcover_book_info_id", book_info[0]);
						//$(this).myModal();
						modalMessageBox("Share HardCover", "");
						$("#modal_inner").html(obj_friends.popup).fadeIn("slow");
						modalPosition();
						$("#app_loader").fadeOut("slow");
					}
				}
			});
			event.preventDefault();
			return false;
		});
	});
	
	$("div a#share_img").each(function() {
		/**
		 * Function for all the Mail image
		 *
		 */
		var book_info = "";
		$(this).click(function(event) {
			$("#app_loader").fadeIn("slow");
			book_info = $(this).attr("rel").split("|");
			$.cookie("hardcover_book_info_id", book_info[0]);
			sendFBMessage(book_info[2], book_info[3], "");
			$("#app_loader").fadeOut("slow");
			event.preventDefault();
			return false;
		});
	});	

// JavaScript Document

//Help -------------------------------------------------------------------------------------------------
  $('#help').live('click',function(){
   $.ajax({
    url  : '/main/help',
    type : 'post',
    success : function(res){
      var _obj = $.parseJSON(res),
			_active_tab_link = $('.tabs2'),
			curr_page = _active_tab_link.find('a').attr('href');
			
		$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
		$(".tab2_content").hide(); //Hide all tab content	
		$('.tabs2').removeClass('hideDiv');
		$(curr_page).html(_obj.data);
		$(curr_page).fadeIn();
		getCookie();
    }, 
   });
   return false;
  });
  
   //About -------------------------------------------------------------------------------------------------
  $('#about').live('click',function(){
   $.ajax({
    url  : '/main/about',
    type : 'post',
    success : function(res){
		var _obj = $.parseJSON(res),
			_active_tab_link = $('.tabs2'),
			curr_page = _active_tab_link.find('a').attr('href');
			
		$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
		$(".tab2_content").hide(); //Hide all tab content	
		$('.tabs2').removeClass('hideDiv');
		$(curr_page).html(_obj.data);
		$(curr_page).fadeIn();
		getCookie();
    }, 
   });
   return false;
  });
  
  
	$('#preview').live('click',function(){
		//var _bookid = $.cookie('hardcover_book_info_id');
		var _bookid = $('#secured_book_info_id').val();
		var _fb_username = $('#fb_username').val();
		var _page_num = 0; //this will be dynamic to point directly to the page number
		var url = 'http://dev.hardcover.me/preview';
		//var url = 'http://dev.hardcover.me/preview/'+ _fb_username + '/' + _bookid+'/'+ _page_num;
		var windowName = "popUp";
		//var windowSize = windowSizeArray[$(this).attr("rel")];
		
		window.open(url, windowName);
		
		event.preventDefault();				
	});  
	
	$('#done').live('click',function(){
		var _bookid = $('#secured_book_info_id').val();
		var _fb_username = $('#fb_username').val();

		$.ajax({
			url  : '/main/create_static_book',
			data : {'book_info_id':_bookid,'fb_username':_fb_username}, 
			type : 'post',
			success : function(res){
				var _obj = $.parseJSON(res);
				alert('Unique URL for your book will be created in a while. This will be the link: ' + _obj.data)
			},
		});		
	});  
	
	
	function save_as () {
		//modal_position();
		
		var form_fields = '<div id="form_container" "><p>Book Name: <input type="text" name=""book_name" id="book_name" /> </br>';
			form_fields += '<input type="button" name="set_save_as" id="set_save_as" value="Save As"/> <input type="button" name="Cancel" value="Cancel" id="cancel" /> </p>';
			form_fields += '</div>';
			$('#modal_inner').append(form_fields);
			var w = $('#modal_inner').width() / 2;
			var wt = $('#msg_container').width() / 2;			
			var _left = w - wt;
			$('#msg_container').css({opacity:1,'margin-left':_left});
			
	}
	
	$('#set_save_as').live('click',function(){
		var _infoid = $.cookie('hardcover_book_info_id');
		var book_name = $('#book_name').val();
		$.ajax({
			
				url 	: 	'/main/set_save_as_book',
				type	:	'POST',
				data 	:	{'book_info_id': _infoid, 'book_name': book_name},
				success	:	function(res){
							var _obj = res;
							console.log('res' + res);
				}
			});
		$('#modal_bckgrnd,#modal_container').animate({opacity:0},'slow','linear',function(){$(this).remove();});
	});
	
	
	function AddFriendsToBook_msg(){
		 /*
 		   var _infoid = $.cookie('hardcover_book_info_id');						   	   	
		   var _token = $.cookie('hardcover_token');
		 
							var msg ="";
								msg	+= 'Hi username,\n\n\n';
								msg += 'I\'m printing a photo album using images and wall post from my FB timeline.\n\n';
								msg += 'I would like to add your content to my album as well, so my album will have the perspective from you and me.\n\n\n';
								msg += 'Thanks a lot,\n\n\n';
								msg += 'You';
							FB.ui({
							 	method: 'send',
							  	name: 'HardCover Application',
							  	message: msg,
								picture: 'https://hardcover.shoppingthing.com/images/new-logo-colored-v1.3.1.jpg',
							  	link: 'http://hardcover.shoppingthing.com/main/share_url?book_info_id='+_infoid,
							},function(){
								$('#modal_bckgrnd,#modal_container').animate({opacity:0},'slow','linear',function(){$(this).remove();});	
							});		
							$('#feedform_user_message').val(msg);
							$('.fb_dialog, .fb_dialog_advanced').css({'z-index':'99999999999'}); 
		 */
		 	$.ajax({
				url 	: 	'main/fb_message',
				data 	:	{'ret':'send'},
				type	:	'post',
				success	:	function(res){
							var _obj = $.parseJSON(res);
						 	$('#modal_inner').append(_obj.data).fadeIn('slow');													
												
							modal_position();	
				
							var w = $('#modal_inner').width() / 2;
							var wt = $('#msg_container').width() / 2;			
							var _left = w - wt;																
							
							/*
							FB.ui({method: 'apprequests',
								message: 'test',
								redirect_uri : 'http://hardcover.shoppingthing.com/main/share_url?book_info_id=9'
							  }, function(){
								$('#modal_bckgrnd,#modal_container').animate({opacity:0},'slow','linear',function(){$(this).remove();});
							});
							*/
																					
							var _friendname;
							var msg ="";
								msg	+= 'Hi username,\n\n\n';
								msg += 'I\'m printing a photo album using images and wall post from my FB timeline.\n\n';
								msg += 'I would like to add your content to my album as well, so my album will have the perspective from you and me.\n\n\n';
								msg += 'Thanks a lot,\n\n\n';
								msg += 'You';
							    $('#txtmsg').val(msg);	
							   $('#f_name').keypress(function(event){
								   if (event.which == 13){
																			   
											$.ajax({
												url		: 'main/get_fb_names',
												type	: 'post',
												data	: {' first_name':$('#f_name').val()},
												success	: function(res){
															var _obj = $.parseJSON(res);
															if (_obj.status == 0){
																var friends = _obj.data.split(';');
																$('#sel_friends').empty();
																$.each(friends,function(i,elem){
																	var _arr = elem.split(':');				
																	var imgUrl = 'https://graph.facebook.com/'+_arr[0]+'/picture';																											
																	$('#sel_friends').append('<li id="'+_arr[0]+'"><img src="'+imgUrl+'" width="20">'+_arr[1]+'<input type="checkbox" id="'+_arr[0]+'" class="float_right"/></li>');
																	$('#sel_friends').fadeIn('slow');															
																});
																
																var _val;
																$.cookie('_friendid',null);
																$("ul#sel_friends li").each(function() {
																	$(this).live('click',function(){

																		var _fbid = $(this).attr('id');																																																																															 																		console.log(_fbid);																		
																		//if(_fbid != undefined){
																			//_val += ','+_fbid;
																			_friendname = $(this).text();
																			$('#f_name').val($(this).text());
																			$.cookie('_friendid',$(this).attr('id'));																																				
																			$('input[type="checkbox"]#'+_fbid).toggle(
																			  function(e){																			 
																				$(this).attr('checked', 'true');
																				e.preventDefault(); 
																			  },
																			  function(e){    
																				$(this).attr('checked', 'false');
																				e.preventDefault(); 
																			});
																		//}
																		var _owner;	
																		FB.api($.cookie('hardcover_fbid'),
																			function(res){ 
																				_owner = res.name; console.log(res.name); 
																				var msg1 = "";
																					msg1	+= 'Hi '+_friendname+',\n\n\n';
																					msg1 += 'I\'m printing a photo album using images and wall post from my FB timeline.\n\n';
																					msg1 += 'I would like to add your content to my album as well, so my album will have the perspective from you and me.\n\n\n';
																					msg1 += 'Thanks a lot,\n\n\n';
																					msg1 += res.name;
																		$('#txtmsg').val(msg1);																			
																			}
																		);	
																		
																	}).animate({'opacity' : 1}).hover(function() {
																		$(this).animate({'opacity' : .8});
																	}, function() {
																		$(this).animate({'opacity' : 1});
																	});
																});																
																
																$("ul#sel_friends").mouseleave(function(e) {
                                                                    //$('#sel_friends').fadeOut('slow');
                                                                });
															}
														}		
											});
										 
										 event.preventDefault();
										 
								   }
								}).focus(function(){
									$('#sel_friends').fadeOut('slow');
								}).click(function(){
									$('#sel_friends').fadeOut('slow');
								});								
								
				
				
								$('#msg_container').css({opacity:1,'margin-left':_left});								   																															
								
							    $('#submit_form').click(function(event){		
									var _userid = $.trim($.cookie('_friendid'));
									
									$('#sel_friends').fadeOut('slow');
									
									post_to_timeline( _userid );	
									
									$.ajax({
										url 	: 'main/friends_being_requested_for_fbdata',
										type	: 'post',	
										data	: {'friends_fbid':_userid},
										sucess	: function(){},
										error	: function(){},
									});									

									event.preventDefault();
							    });
													
							    							
								
								$('#cancel_form').live('click',function(){
									$('#modal_bckgrnd,#modal_container').animate({opacity:0},'slow','linear',function(){$(this).remove();});
									$('#sel_friends').fadeOut('slow');
									return false;								
								});
						}						
			});	

	 }
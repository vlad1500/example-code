//Function to check if proper permission has been authorized...
function checkPermissions() {
	FB.api('me/permissions', function(res) {
		var perms = ['user_photos','user_likes','read_stream','user_status','status_updates'];
		$.each(perms, function(i, perm) {
			if(!res || !res.data[0] || !(perm in res.data[0])) {
				logIn();
			}
		});
	});
}

//Function to authorized the user...
function logIn(){
	FB.login(function(response) { 
	   if (response.authResponse){  
				
	   } 
	 },
	{scope:'user_photos,user_likes,user_status,staus_updates,read_stream'});
	return false;
}

//Function to load the friends image on the hardcover album page...
function loadFriendsImage(){
	FB.getLoginStatus(function(response){
		if (response.status == 'connected'){
			//checkPermissions();	
			$('#ul_friend_img li').remove();
			FB.api('me/friends',function(res){					
				$.each(res.data,function(i){
					if (i == 27){
						return false;
					}
					var imgUrl = 'https://graph.facebook.com/'+res.data[i].id+'/picture';
					var x_img = "http://dev.hardcover.me/images/x.png";
					$('#ul_friend_img').append('<li id="'+res.data[i].id+'" class="float_left"><img src="'+imgUrl+'"><img id='+res.data[i].id+' class="ximg" src="'+x_img+'"></li>');
				});		
			});		
			gPicture();		
		}else{
			logIn();
		}
	},true);	
}

function getUserID(){
	var user_id;
	FB.api('me',function(response){ 		
		user_id = response.id;	
		return user_id;
	});		
}

//Function to get the user image for the hardcover album to at the center of the page...
function gPicture(div_id){	
	FB.api('me',function(res){ 		
		var imgUrl = 'https://graph.facebook.com/'+res.id+'/picture?type=large';															
		$('#' + div_id).prepend('<img src="'+imgUrl+'" class="float_left" style="margin:152px 0 0 255px;position:absolute;z-index:999999999;padding:10px;border:1px solid #CBCBCB;background:#FAFAFA;" width=180 height=150/>');	
		//$('#my_cover_title').val(res.first_name + " life in HardCover");	
	});		
}

//Function to load the album and corresponding picture...
function loadAlbum(_id){
		FB.getLoginStatus(function(response){
			if (response.status == 'connected'){
				//checkPermissions(); 
				$('#photos_from_album li').remove();
				FB.api('/me/albums&access_token='+response.authResponse.accessToken,
					function(res){			
					    if (_id!=undefined){
							if ( _id.length != 0 || _id.length != undefined){
								var _album = _id.split(';')		
										$.each(res.data,function(i){
										  if (res.data[i].count != undefined){
												var _ret = false;
												$.each(_album,function(x,el){
													if( el == res.data[i].id ){
														var imgUrl = 'https://graph.facebook.com/'+res.data[i].cover_photo+'/picture?type=thumbnail&access_token='+response.authResponse.accessToken;
														$('#photos_from_album').append('<li id='+res.data[i].id+'><img src='+imgUrl+' id='+res.data[i].id+' width=75 height=75/><p><input type="checkbox" name="album_'+res.data[i].id+'" checked="checked"><span>'+res.data[i].count+'</span></p></li>').load(function(){$(this).animate({opacity:1},'slow')});	
														_ret = true;
														return false;											
													}
												}); 				
												
												if ( !_ret ){
														var imgUrl = 'https://graph.facebook.com/'+res.data[i].cover_photo+'/picture?type=thumbnail&access_token='+response.authResponse.accessToken;
														$('#photos_from_album').append('<li id='+res.data[i].id+'><img src='+imgUrl+' id='+res.data[i].id+' width=75 height=75/><p><input type="checkbox" name="album_'+res.data[i].id+'"><span>'+res.data[i].count+'</span></p></li>').load(function(){$(this).animate({opacity:1},'slow')});													
												}								  
											}
										});															   																									   																							
										//http://graph.facebook.com/USER_ID/picture?type=square 								
							}else{
								$.each(res.data,function(i){
									if (res.data[i].count != undefined){	
										var imgUrl = 'https://graph.facebook.com/'+res.data[i].cover_photo+'/picture?type=thumbnail&access_token='+response.authResponse.accessToken;
										$('#photos_from_album').append('<li id='+res.data[i].id+'><img src='+imgUrl+' id='+res.data[i].id+' width=75 height=75/><p><input type="checkbox" name="album_'+res.data[i].id+'"><span>'+res.data[i].count+'</span></p></li>').load(function(){$(this).animate({opacity:1},'fast')});								
									}
								});
							}//end of if (_id != 0 || _id == undefined){
							//checkBox();
						}
					}
				);
			}else{
				logIn();
			}
		},true);
}

function addToAlbum(_url,fb_dataid){		
		FB.getLoginStatus(function(response) { 				  		  
		  if (response.status === 'connected') {
		            //checkPermissions();
					FB.api('/me/albums&access_token='+response.authResponse.accessToken,
					function(res){	
						FB.api('me/photos?access_token='+response.authResponse.accessToken, 'post',
							 {
							message:'Edited by HardCover Application',
							url: _url
						}, function(resp){
						  if (!resp || resp.error) {
							  alert(resp.error.message);						
						   } else {				
								  	var _url = resp.id+'/?access_token='+response.authResponse.accessToken;	
									alert('This image has been posted in your album...');
									FB.api(_url,
											function (_obj){
												console.log(_obj);
												$.ajax({
													url		: 	'main/set_edited_image',
													type	:	'post',
													data	:	{'fb_dataid': fb_dataid, 'url' : _obj.source},
													error	:	function(msg){
																	console.log(msg.error);
																}
												});			
										}
									);
						   }	
							
						});//End of Post
					});	
			
		  
		  } else if (response.status === 'not_authorized') {
			logIn();	
		  }
		},true);
	}

function getCookie(){
//	FB.getLoginStatus(function(response){
//		if(response.authResponse.accessToken != $.cookie('hardcover_token')){
//			$.cookie('hardcover_token',response.authResponse.accessToken)
//		}else{
//			//alert($.cookie('hardcover_token')+' == '+ response.authResponse.accessToken);
//		}
//	},true);
}


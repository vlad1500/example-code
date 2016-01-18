function doUnqiueStuff() {
    
}

function get_add_friends_pop(){
    FB.init({appId: '331059976950036', xfbml: true, cookie: true});
		FB.ui({method: 'apprequests',
			display:'popup',
			message: 'Hard Cover Request',
			title:'HardCover view my book request'
		}, requestCallbacksee);
}
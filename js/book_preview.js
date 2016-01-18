var APIURL = '/'; 

//Called when a photo is successfully retrieved

function onPhotoURISuccess(imageURI) {
  requestCrossDomain(APIURL+'edit_album/get_book_pages_uni', function(res) { });
}

function requestCrossDomain( site, callback ) {  
	
    if ( !site ) {  
        alert('No site was passed.');   
        return false;  
    }  

    $.getJSON( site+"?c=sri"+$.now(), function(data){ imagesDisplay(data.book_pages); } );
}  

var fimg = '';
function imagesDisplay(data) 
{  

	var temp = new Array();
	//alert(data.length);
	var data_len=data.length;
	var xtra_page=0;
	if(data_len%2==1)
	 {
		 xtra_page=2;
	 }
	 else
	 {
		xtra_page=1; 
	 }
	data_len=data_len+xtra_page;

		//console.log(data[i].image_url);
		var bc = data[0].back_cover;
		//alert(bc);

	for(var i=0;i<=data_len; i++) {
		//console.log(data[i].image_url);
		temp[i] = new Object();
		//temp[i].back_cover = 	data[i].back_cover;
		//alert(temp[i].back_cover);
		
		/*temp[i].src = 	        data[i].image_url;
		temp[i].thumb = 	data[i].image_url;
		temp[i].title = 	'cc '+i;
		temp[i].fb_username = 	data[i].fb_username;
		temp[i].front_cover = 	data[i].front_cover;
		temp[i].back_cover = 	data[i].back_cover;*/
		
		//alert(temp[i].fb_username);
		//alert(temp[i].front_cover);
		//temp[i].back_cover = 	data[i].back_cover;
		//alert(temp[i].back_cover);
		// temp[i].fb_username = 	data[i].fb_username;
		// document.getElementById('test').innerHTML=temp[i].fb_username;
		// alert(temp[i].fb_username);
		if(i==0)
		{
		 temp[i].title = 	'Cover';
		 temp[i].src = 	        data[i].front_cover;
		temp[i].thumb = 	data[i].front_cover;
		temp[i].htmlContent = "Ananda Prithvi";
		//temp[i].fb_username = 	data[i].fb_username;
		
		}
		else if(i<=data_len-xtra_page)
		 {
		temp[i].src = 	data[i-1].image_url;
		temp[i].thumb = 	data[i-1].image_url;
		temp[i].title = 	'cc '+i;
		temp[i].fb_username = 	data[i-1].fb_username;
		temp[i].front_cover = 	data[i-1].front_cover;
		
		//alert(i+"--"+data_len);
		
		 }
		else if(i==data_len)
		  {
			  
		temp[i].title = 	'Back Cover';
		temp[i].src = 	 bc;
		temp[i].thumb = 	bc;
		
		//temp[i].back_cover = 	data[i].back_cover;
		//alert(temp[i].back_cover);
		
			  
			   /*temp[i].title = 	'Back cover';
			       temp[i].src = 	data[0].back_cover; 
				   alert(data[0].back_cover);*/
				 //alert(i+"--"+data_len);
		          //temp[0].thumb = 	data[0].back_cover; 
		  }
		
	}
	
	if(i%2 == 1)
	{
		temp[i] = new Object();
		temp[i].src = 	        '/images/preloader.jpg';
		temp[i].thumb = 	'/images/preloader.jpg';
		temp[i].title = 	'last';
	}
	var page = JSON.stringify(temp);
	
	
	console.log(page);
	
 	$("#flipBookContainer #bookContainer").flipBook({
            pages: jQuery.parseJSON(page),
            lightBox:false,

            webgl:false,
            pageHardness:2.5,
            coverHardness:8,
            pageMaterial:'phong',
			
        });
	//temp[i].fb_username = 	data[i].fb_username;
	//document.getElementById('test1').innerHTML="Ananda Chakraborty";
}

head.ready(function(){
	onPhotoURISuccess('');
});
$(function () {

    /*function pretty_close(){
		$.prettyPhoto.close();
		return false;  
	}

	$('a[rel^="prettyPhoto"]').prettyPhoto({
		theme:'facebook',
		default_width: 600,
		default_height: 500,
		social_tools: false
    });*/

    function get_me() {
		$('#main_inner').append('<div class="ajax_loader"></div>');

		$.ajax({
			cache   : false,
			url     : 'filter/filter_page_cover',
            async: true,
			type    : 'post',
			success : function(res){
				var _obj = $.parseJSON(res);
				$('.ajax_loader').remove();	
				$('#main_inner').html(_obj.data);
			 	// $('#upload_popup_y').html(_obj.data);
			  	//$('#upload_upp').click();
			 	$(activeTab).fadeIn(); //Fade in the active ID content
			}
	  	});
	}

	function insert_cover_photos_album() {

		var alb_data = $('#album_data_al_cover').serializeArray();
		var ids = '';
		
		$.each(alb_data, function()  { 
	  		if(ids=='') {
				ids = this.value;
	    	} else {
		   		ids = ids+','+this.value;
			}
		});

		var mode = $('#mode').val();
		$.ajax({
			url		: "cover/insert_for_album_cover",
			type	: "post",
			data    : "form_data="+ids+"&mode="+mode,
			success	: function(result) {
				$('.pp_inline #album_data_al_cover').before('<p style="color:#46B940; font-weight:bold;">Album updated</p>');
				
				if($("#front_cover").hasClass("selected")) {
					$("#front_cover").trigger("click");
				} else {
					$("#back_cover").trigger("click");
				}
				
				//jQuery.prettyPhoto.close();
			}
		});
	}

	function insert_photos_album() {
	
		var alb_data = $('.pp_inline #album_data_al').serializeArray();
		var ids = '';
		
		$.each(alb_data, function()  { 
	  		if(ids=='') {
				ids = this.value;
	    	} else {
		  		ids = ids+','+this.value;
			}
		
		});
		
		var mode = $('#mode').val();
		
		$.ajax({
			url		: "cover/insert_for_album",
			type	: "post",
			data    : "form_data="+ids+"&mode="+mode,
			success	: function(result) {
				$('.pp_inline #album_data_al').before('<p style="color:#46B940; font-weight:bold;">Album updated</p>');
				
				if($("#front_cover").hasClass("selected")) {
					$("#front_cover").trigger("click");
				} else {
					$("#back_cover").trigger("click");
				}
				
				//jQuery.prettyPhoto.close();

			}
		});
	}

	function thumbnail_image(val) {
	
		if(val == 'thumbnail') {
			$("#cover_prof_pic").removeClass("back_display").addClass("front_display");
			$("#cover_friends_pic").removeClass("front_display").addClass("back_display");
			$('#thumb_icons').show();
			$('#book_v_icons').hide();
		} else {
			$("#cover_friends_pic").removeClass("back_display").addClass("front_display");
			$("#cover_prof_pic").removeClass("front_display").addClass("back_display");
			$('#thumb_icons').hide();
			$('#book_v_icons').show();
		}
	}

	/*
	$("#previewer").on('click', function(){
		alert('coverview.js is loaded.');
		var WinId = window.open('edit_album/preview/', 'edit_album_preview', 'width=' + screen.width +',height='+screen.height);
	});*/
	

	//Show pop up for Cover Image Uploader.
	/* ===== PLESE DONT DELETE THIS ===== */
	/*
	$("#album_for_me_cover").on("click", function() {

		$('#shared_active').css('display','block');
	
		$.ajax({
			url : "../../../main/home_select_booktype_cover",	 
			success : function(res){
			
				var _obj = $.parseJSON(res);
				
				if (_obj.status) {
					$('#main_inner_uploder_pop').html(_obj.data).show();
					$('#app_loader23').css('display','block');

					$(document).scrollTop();

		         	window.fbAsyncInit;
					
					getCookie();
							 
					ch();
				}

			},error : function(res, err, errTxt) {
				alert("\n"+ err.toUpperCase() +": \"Page "+ errTxt +"\"");
				$("#app_loader").fadeOut("slow");
			}
		});
				
		return false;
	});*/

	function ch() {
		return true;
	}

});


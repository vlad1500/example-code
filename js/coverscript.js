$('.cover_upload_pc').unbind("click").live("click",function(e){
    console.log("test click");
    $('#js-modal-common').unbind('shown.bs.modal');
	var front_back = $(this).attr('rel');
    console.log(front_back);
	$('#js-modal-common').modal();
	$('#js-modal-common .modal-body').html("");
	$('#js-modal-common .modal-title').html('Upload '+ front_back.toUpperCase() + ' Cover Image');
	$('#js-modal-common .modal-dialog').addClass('modal-dialog--cover-uploader');
	
	//$('#js-modal-common').on('shown.bs.modal', function () {
		$('#shared_active').css('display','block');
		console.log("upload from pc");
		$.ajax({
			url : 'cover/getCoverUploadPC',
			data: 'front_back=' + front_back,
			type: 'POST',	 
			success : function(res){					
				var _obj = $.parseJSON(res);
				
				if (_obj.status) {
					$('#js-modal-common .modal-body').html(_obj.data);
					
					$(document).scrollTop();	
					window.marker = 'cover_upload_pc';
				}
			},error : function(res, err, errTxt) {
				alert("\n"+ err.toUpperCase() +": \"Page "+ errTxt +"\"");
				$("#app_loader").fadeOut("slow");
			}
		});					
		return false;	
	//});
});

$('.cover_upload_fb').unbind("click").live("click",function(e){
	var front_back = $(this).attr('rel');
    var fb_id = $(this).attr('rel');
	
	$('#js-modal-common .modal-body').html("");
	$('#js-modal-common').modal();
	$('#js-modal-common .modal-body').html("");
	$('#js-modal-common .modal-title').html('Upload '+ front_back.toUpperCase() + ' Cover Image');
	$('#js-modal-common .modal-dialog').addClass('modal-dialog--cover-uploader');
	
	//$('#js-modal-common').on('shown.bs.modal', function () {
		$('#shared_active').css('display','block');
		console.log("upload from fb");
		$.ajax({
			url : '/cover/getCoverUploadFB',
			data: 'front_back=' + front_back,
			type: 'POST',	 
			success : function(res){
			     console.log(res);
				var _obj = $.parseJSON(res);
				
				if (_obj.status) {
					$('#js-modal-common .modal-body').html(_obj.data);					
					$(document).scrollTop();	
					window.marker = 'cover_upload_fb';
				}
			},error : function(res, err, errTxt) {
				alert("\n"+ err.toUpperCase() +": \"Page "+ errTxt +"\"");
				$("#app_loader").fadeOut("slow");
			}, complete : function (){
				 
			}
		});
		return false;	
	//});		
	
});
	

$(".user_album").live("click", function () {
	if (this.checked){
		$(".user_album").prop('checked', false);
		$(this).prop('checked', true);	
	}
	
	if ($(this).is(':checked')){
		$('#album_photo_raw_data').html("");
		var album_id = $(this).attr("rel");

	   	$.ajax({
			url  : "main/getPhotosOfAlbums",
			type : "post",
			data : 'album_id='+album_id,
			success : function(res){	
				var _obj = $.parseJSON(res);

				if (_obj.status==200){
					$('#album_photo_raw_data').append(_obj.data);
				}
			} 
		});
	}else
		$('#album_photo_raw_data').html("");
				
});

$("#save_cover").live("click", function () {
	if ( $(".album_photo:checked").length == 0 ) {
		alert("Please select a photo to upload first.");
	}else{
		var front_back = $("#front_back").val();
		var cover_img = $(".album_photo:checked").attr("rel");
		
	   	$.ajax({
			url  : "cover/saveCoverUploadFB",
			type : "post",
			data : 'front_back=' + front_back + '&cover_img=' + cover_img,
			success : function(res){	
				var _obj = $.parseJSON(res);

				if (_obj.status==200){
					$(".book-cover__pic").html("<img src='" + _obj.url_image + "' />");
					if ($("#front_back").val()=='front')						
						window.front_cover = _obj.url_image;
					else
						window.back_cover = _obj.url_image;
				}
			} 
		});		
	}	
});


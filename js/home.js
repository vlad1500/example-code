$(function () {

    function pretty_close(){
		$.prettyPhoto.close(); return false;  
	}

	$('a[rel^="prettyPhoto"]').prettyPhoto({
		theme:'facebook',
		default_width: 600,
		default_height: 500,
		social_tools: false
        // any configuration options as per the online documentation.
    });

	function callback(response) { 
		return true;
	}

	function csubmit(){
		submit_book_name();
		return false;
	}

	function submit_book_name(){

		if($.cookie('hardcover_fbid')==null) {
			alert('Session has been expired. Please Refresh the Hardcover App. ');	
			return false;
		}

		$("#app_loader").fadeIn("slow");
		var bname =  $('.pp_inline #book_name_pop').val();
		//var bname =  $('#book_name_pop').val(); 
		$('.err_div').html("");
		if(bname == "") {
			$('.err_div').html('<h3>Book name Should not empty</h3>');
			$("#app_loader").fadeOut("slow");
		} else  {
			$.ajax({
			type:'post',
			url 	: "../../../main/set_name_book_info",
			data:"bname="+bname,
			success : function(res){
	 			if(res!='') {
					$('.pp_close').click();
					$("#app_loader").fadeOut("slow");

					$('#album_for_me').trigger('click');

				} else { 
					$('.err_div').html('<p style="color:#FF0000; font-weight:bold;">Book name already exists</p><br/>');
	   				$("#app_loader").fadeOut("slow");
				}
			}
		});
		return false;
		}
	}
});

(function(d){
	var js, id = "facebook-jssdk", ref = d.getElementsByTagName("script")[0];
	if (d.getElementById(id)) {
		return;
	}
	js = d.createElement("script"); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));

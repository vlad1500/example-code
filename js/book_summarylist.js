head.ready(function() {
	$(function () {
		function popitup(url,windowName) {			
			newwindow=window.open(url,windowName,'height=400,width=350');
			
			if (window.focus) {
				newwindow.focus()
			}
			return false;
		}		
	});
	
	/* --- old Create Album ---*/
	/*$("#js-album-create-name").on("click", function() {
		$('#book_name_pop').val('');
		$('.err_div').html(''); 
		$('#create_book_name_popu_clcik').click(); 
	});*/
	$("#js-album-create-name").on("click", function() {
		//CESAR changed order and test
		$('#book_name_pop').val('');
		$('.err_div').html('');
		
		$('#js-create-album-modal').addClass('in');
		$('#js-create-album-modal').css('display','block');
		$('#js-create-album-modal').attr('aria-hidden','false');
		$('#js-create-album-modal').modal();
		/*$('#js-create-album-modal').modal({
				onClose : function() {
				$('div').removeClass('modal-backdrop fade in');
				$('div').removeClass('modal-backdrop fade');
				$('.modal-backdrop').remove();
				$.modal.close(); 
				
			}
			
		}); */
	});
	
	$( ".close" ).click(function() {
		if ($( this ).attr( "data-dismiss" ) == 'modal'){
			$('.modal.fade').css('display','none');
			$('.modal-backdrop').remove();
			$('.modal-backdrop.fade').remove();
			$('.modal-backdrop.fade.in').remove();
		}
	});
	
	$('a.book_summary_class').each(function(){
		$(this).live('click',function(){
			$('#app_loader').fadeIn();
			var album_id = $(this).attr('id');
			$.cookie('hardcover_book_cat','book');

			$.cookie('hardcover_book_info_id',album_id);
			//console.log('hardcover_book_info_id ---- ' + $.cookie('hardcover_book_info_id') );
			$.ajax({
			url     : 'main/edit_album',
			type    : 'post',
			cache   :  true,
			data 	: {'book_info_id':album_id},
			success : function(res){
				$('#app_loader').fadeOut();
				var _obj = $.parseJSON(res);
				$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
				$('ul.tabs2 li#edit').addClass("active").fadeIn(); //Add "active" class to selected tab
				$('#main_inner').html(_obj.data);
				$('#my_edit').fadeIn(); //Fade in the active ID content
			},error	: function(res, err, errTxt){
				modalMessageBox("Error", err.toUpperCase() +" "+ res.status +": \""+ res.responseText +"\"");
				modalPosition();
				$("#msg_container").fadeOut("slow");
				var orig_msg = $("#msg_container").html();
				$("#msg_container").empty();
				$("#msg_container").html("<div id=\"form_container\">"+ res.responseText +"<p align=center><input type=\"button\" id=\"cancel\" value=\"OK\" /></p></div>");
				$("#msg_container").fadeIn("slow");
				$("#app_loader").fadeOut("slow");
			}
		});
		return false;
		});
	});

	$('a.chapters_summary_class').each(function(){
		$(this).live('click',function(){
			$('#app_loader').fadeIn();
			var album_id = $(this).attr('id');
			console.log('hardcover_book_info_id - ' + album_id);
			$.cookie('hardcover_book_info_id',album_id);
			$.cookie('hardcover_book_cat','chapter');
			console.log('hardcover_book_info_id -- ' + $.cookie('hardcover_book_info_id') );
			$.ajax({
				url     : 'main/edit_album',
				type    : 'post',
				cache   :  true,
				data 	: {'book_info_id':album_id},
				success : function(res){
					$('#app_loader').fadeOut();
					var _obj = $.parseJSON(res);
					$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
					$('ul.tabs2 li#edit').addClass("active").fadeIn(); //Add "active" class to selected tab
					$('#main_inner').html(_obj.data);
					$('#my_edit').fadeIn(); //Fade in the active ID content
				},
				error	: function(result){
					$('#app_loader').fadeOut();
					alert(result.textStatus);
				}		
				
			});
			return false;
		});
	});

	$('a.share_url').css({'cursor':'pointer'});
		
	$('a.share_url').each(function(){
		$(this).live('click',function(e){
			var _rel = $(this).attr('rel');
			var _book = $('a#'+_rel).text();
			
			FB.ui({
				method: 'feed',
				name: _book,
				link: '<?php echo $this->config->item("base_url");?>/books/'+_rel+'?page_num=1',
				picture: 'https://dev.hardcover.me/images/slide2/HardCover_logo.png',
				caption: 'HardCover Application',
				description: 'Make a book of your FB album.'
			}, function(response) {
				if (response && response.post_id) {
					alert('Your book was published.');
				}
			});
			e.preventDefault();
		});
	});

	/*$("a#delete").each(function() {
		$(this).click(function(e) {
			var book_info = $(this).attr("class").split("|"), reason_why = "";
			modalMessageBox("Delete Album \"<b>"+ book_info[1] +"</b>\"", "<span style='font-weight:normal; font-size:18px;'>Delete album </span><br>"+ book_info[1] +"");
			$("#form_container p").prepend("<input type=\"button\" id=\"yes\" value=\"YES\" />");
			$("#form_container p #cancel").attr("value", "NO");
			$("#form_container #reason").css({"width":"430px","font-weight":"normal"});
			$("#form_container h6").css({"margin":"0"});

			$("#yes").click(function(e) {
				reason_why = $("#form_container #reason").val();
				$.ajax({
					url		: "delete/deleteBook/"+ book_info[0] +"/"+ reason_why,
					type	: "post",
					data	: {"book_info_id":book_info[0],"reason":reason_why},
					success	: function(res) {
						var _obj = $.parseJSON(res);
						if (_obj.status) {
							$("#home a").click();
							$("#cancel").click();
						}
						$("#app_loader").fadeOut(10);
					},error	: function(res, err, errTxt){
						$("#msg_container").fadeOut("slow");
						var orig_msg = $("#msg_container").html();
						$("#msg_container").empty();
						$("#msg_container").html("<div id=\"form_container\">"+ res.responseText +"<p align=center><input type=\"button\" id=\"cancel\" value=\"OK\" /></p></div>");
						$("#msg_container").fadeIn("slow");
					}
				});
			});

			modalPosition();
			e.preventDefault();
			return false;
		});
	});*/
	$("a#delete").each(function() {
		$(this).click(function(e) {
			var book_info = $(this).attr("class").split("|"), reason_why = "";

			$('#js-modal-common').modal();
			$('#js-modal-common .modal-title').html("Delete Album");

			$('#js-modal-common').on('shown.bs.modal', function () {

				$('#js-modal-common .modal-body').html("<p class=\"h4\">Delete Album -> "+ book_info[1] +"?</p>");
				
				$("#js-modal-common .modal-body").append("<p><input type=\"button\" id=\"yes\" value=\"YES\" class=\"btn btn-small btn-orange\"/><input type=\"button\" id=\"cancel\" value=\"NO\" class=\"btn btn-small btn-orange\"/></p>");
				$("#form_container p #cancel").attr("value", "NO");

				$("#yes").click(function(e) {
					reason_why = $("#form_container #reason").val();
					$.ajax({
						url		: "delete/deleteBook/"+ book_info[0] +"/"+ reason_why,
						type	: "post",
						data	: {"book_info_id":book_info[0],"reason":reason_why},
						success	: function(res) {

							var _obj = $.parseJSON(res);
							if (_obj.status) {
								$("#home a").click();
								$("#cancel").click();
							}
							$("#app_loader").fadeOut(10);
							$('#js-modal-common .close').click();
							$('#js-modal-common .modal-body').html('');
							$('#js-home').click();

						},error	: function(res, err, errTxt){

							$('#js-modal-common .close').click();
							var orig_msg = $("#js-modal-common .modal-body").html();
							$("#js-modal-common .modal-body").empty();
							$("#js-modal-common .modal-body").html("<p class=\"h4\">"+ res.responseText +"<p align=center><input type=\"button\" id=\"cancel\" value=\"OK\" /></p>");
							$('#js-modal-common').modal();
						}
					});
				});

				e.preventDefault();
				return false;
			});
		});
	});

	$('#cancel').on('click', function () {
		$('#js-modal-common .close').click();
		$('#js-modal-common .modal-body').html('');
	});

	$('#js-modal-common').on('hide.bs.modal', function () {
		$('#js-modal-common .modal-body').html('');
	});
	
});

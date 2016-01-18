$(document).ready( function () {
	$('.boxes-active').hover( function () {
		$(this).children('.hover-content').fadeIn();
		$(this).children('.default-content').hide();
		//$('.hover-content').fadeIn();
		//$('.default-content').hide();	
	}, function () {
		$(this).children('.default-content').fadeIn();
		$(this).children('.hover-content').hide();
	});
	
	$('#getstarted').live('click',function(e){		
		e.preventDefault();
		window.location = "/main";
		/*			
		$.ajax({
			url     : 'main/init_fb',
			type    : 'post',
			cache   :  true,
			success : function(res){
				var _obj = $.parseJSON(res);
				if (_obj.status!=0){
					alert(_obj.msg);
				}else{
					$('.tabs2').removeClass('hideDiv');
					$('#main_container').html(_obj.data);
					$('#main_container').fadeIn();
					$('#app_loader').fadeOut('slow');
				}
			}
		});		
		*/
		return false;
	});
});
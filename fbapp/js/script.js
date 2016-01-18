/* Author:

*/
$(document).ready(function() {
    
	$('div#hardcover_menu table tr:odd').css({'background':'#FFFFFF'})
	
	$(".tab2_content").hide(); //Hide all content
    $("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
    $(".tab2_content:first").show(); //Show first tab content
	
    //On Click Event
    $("ul.tabs2 li").live('click',function() {
        $("ul.tabs2 li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab2_content").hide(); //Hide all tab content
        var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
        $(activeTab).fadeIn(); //Fade in the active ID content
        return false;
    });
        
	$("li a").animate({'opacity' : 1}).hover(function() {
		$(this).animate({'opacity' : .3});
	}, function() {
		$(this).animate({'opacity' : 1});
	});	
	
	$('#button_main_next').live('click',function(){
		$.ajax({
			url     : 'main/home_select_booktype',
			type    : 'post',
			success : function(res){
				var _obj = $.parseJSON(res);
				$('#content').html(_obj.data);
			}			
			
		});
	});
        
})






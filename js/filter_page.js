$(document).ready(function(e) {
    console.log("filter_page.js loaded");
    $.ajaxSetup({ cache:false });
    
    var referer = '';   
    
    $('#opt_fb').live('click',function(){
        $('#opt_div_upload').addClass('hideDiv').fadeOut('slow');
        $('#opt_div_fb').removeClass('hideDiv').fadeIn('slow');
    });
    
    $('#opt_computer').live('click',function(){
        $('#opt_div_fb').addClass('hideDiv').fadeOut('slow');       
        $('#opt_div_upload').removeClass('hideDiv').fadeIn('slow',function(){
            //$('#opt_div_upload').append('<div class="bar"><span></span></div>');          
        });     
    });
    

            
    //This is the script for the album carousel...
    $('#photos_from_album li:first').before($('#photos_from_album li:last'));     
    $('#right_scroll img').live('click',function(){
        var item_width = $('#photos_from_album li').outerWidth() + 10;
        var left_indent = parseInt($('#photos_from_album').css('left')) - item_width;
        $('#photos_from_album').animate({'left' : left_indent},50,
        function(){    
            $('#photos_from_album li:last').after($('#photos_from_album li:first')); 
            $('#photos_from_album').css({'left' : '0px'});
        });
    });
    
    $('#left_scroll img').live('click',function(){
        var item_width = $('#photos_from_album li').outerWidth() + 10;
        var left_indent = parseInt($('#photos_from_album').css('left')) + item_width;
        $('#photos_from_album').animate({'left' : left_indent},50,
        function(){           
            $('#photos_from_album li:first').before($('#photos_from_album li:last')); 
            $('#photos_from_album').css({'left' : '0px'});
        });
    }); 
      
      // start of Entire Timeline and Date range - MYCHELLE
    
    $( "#date_range_from" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#date_range_to" ).datepicker( "option", "minDate", selectedDate );
            }
        }
    );  
    $( "#date_range_from" ).live('click',function(){
        $( "#entire_timeline").attr('checked', false);
        $( "#date_range").attr('checked', 'checked');
        
    });
    
    $( "#date_range_to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#date_range_from" ).datepicker( "option", "maxDate", selectedDate );
            }
    });
    $( "#date_range_to" ).live('click',function(){
        $( "#entire_timeline").attr('checked', false);
        $( "#date_range").attr('checked', 'checked');
        
    });
    
    $( "#entire_timeline" ).live('click',function(){
        $( "#date_range_from").val('mm/dd/yyyy');
        $( "#date_range_to").val('mm/dd/yyyy');
        
    });
    
    
    
    // end of Entire Timeline and Date range - MYCHELLE
      
     // START OF ACCORDION FUNCTIONS - MYCHELLE
      
      //ACCORDION BUTTON ACTION (ON CLICK DO THE FOLLOWING)
    $('.accordionButton').click(function() {

        //REMOVE THE ON CLASS FROM ALL BUTTONS
        $('.accordionButton').removeClass('on');
          
        //NO MATTER WHAT WE CLOSE ALL OPEN SLIDES
        $('.accordionContent').slideUp('normal');
   
        //IF THE NEXT SLIDE WASN'T OPEN THEN OPEN IT
        if($(this).next().is(':hidden') == true) {
            
            //ADD THE ON CLASS TO THE BUTTON
            $(this).addClass('on');
              
            //OPEN THE SLIDE
            $(this).next().slideDown('normal');
         } 
          
     });
      
    
    /*** REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
    
    //ADDS THE .OVER CLASS FROM THE STYLESHEET ON MOUSEOVER 
    $('.accordionButton').mouseover(function() {
        $(this).addClass('over');
        
    //ON MOUSEOUT REMOVE THE OVER CLASS
    }).mouseout(function() {
        $(this).removeClass('over');                                        
    });
    
    /*** END REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
    
    
    /********************************************************************************************************************
    CLOSES ALL S ON PAGE LOAD
    ********************************************************************************************************************/   
    $('.accordionContent#2').hide();
      
      $('.accordion .head').click(function() {
            $(this).next().animate({height:80},2000,'easeInOutCubic', callback_close ());
            return false;
        }).next().hide();
        
    // END OF ACCORDION FUNCTIONS - MYCHELLE

    var menu_status = $('#filter_menu_status'),
    menu_posts = $('#filter_menu_post'),
    album_content = $("input[name='album_content']");
                    
    if ($('#photo_only').is(':checked')) {  
        $('#filter_menu_status').hide();
        $('#filter_menu_post').hide();
    }

    $('#photo_only').click( function() {
        $('#filter_menu_status').fadeOut();
        $('#filter_menu_status p input').attr('checked', false);
        $('#filter_menu_post').fadeOut();
        $('#filter_menu_post p input').attr('checked', false);
    });
    
    var status = $('#status_my_update'),
            iStatus = $('#status'),
            cb = $(':checkbox');
    
    $('#all').click( function() {
        if($(this).is(':checked')) {
            $('#filter_menu_status').fadeIn();
            $('#filter_menu_post').fadeIn();
            $('#post_photos').removeAttr('disabled');
            $('#post_comment').removeAttr('disabled');
            $('#post_article').removeAttr('disabled');
            
            if ($('#status_my_update').is(':checked')) {  
                iStatus.find('._child').removeAttr('disabled');
            }
            else {
                iStatus.find('._child').attr('checked', false);
                iStatus.find('._child').attr('disabled', 'disabled');
            }
        }
    });


    if ($('#status_my_update').is(':checked')) {  
        iStatus.find('._child').removeAttr('disabled');
    }
    else {
        iStatus.find('._child').attr('checked', false);
        iStatus.find('._child').attr('disabled', 'disabled');
    }
            
    status.click(function() {
       if(status.is(':checked')) { 
        iStatus.find('._child').removeAttr('disabled');
       }
       else {
        iStatus.find('._child').attr('checked', false);
        iStatus.find('._child').attr('disabled', 'disabled');
       }
    });
        
        
    cb.click(function() {
        var el_ID = this.id;
        if ($('#'+el_ID).is(':checked')) {
            $('#'+el_ID).val(1);
        }
    });
    
    if ($('#post_all').is(':checked')) {  
        $('#post_photos').attr('disabled', 'disabled');
        $('#post_comment').attr('disabled', 'disabled');
        $('#post_article').attr('disabled', 'disabled');
    }
    else {
        $('#post_photos').removeAttr('disabled');
        $('#post_comment').removeAttr('disabled');
        $('#post_article').removeAttr('disabled');
    }
    
    $('#post_all').click(function() {
        if ($('#post_all').is(':checked')) {  
            $('#post_photos').attr('disabled', 'disabled');
            $('#post_comment').attr('disabled', 'disabled');
            $('#post_article').attr('disabled', 'disabled');
        }
        else {
            $('#post_photos').removeAttr('disabled');
            $('#post_comment').removeAttr('disabled');
            $('#post_article').removeAttr('disabled');
        }
    });
        
    if ($.cookie('hardcover_referer')) {
        referer = $.cookie('hardcover_referer');
    }
    
    if (referer == 'share_url') {
        $('#album_for_me_f').hide().parent().hide();
        $('#album_quick_book').hide().parent().hide();
        $('#album_for_friends').attr('checked', 'checked');
        $('#filter_location').fadeIn();
        var new_book_id = $.cookie('hardcover_gave_info_to_bkid');
        $.cookie('hardcover_book_info_id', new_book_id);
    }
    
     $('#filter_next1').die('click').live('click',function() {
          $(this).append('<div class="ajax_loader"></div>');
          var form_data = $('#form_filter_data').serialize();
          var ulink = '/filter/save_book_filter';
          
          $.ajax({
            cache   :   false,
            url     :   ulink,
            type    :   'post',
            data    :   form_data,      
            success :   function(res){
            				$('.ajax_loader').remove();
                            var _obj = $.parseJSON(res);

                            if (_obj.status!=0){
                                alert(_obj.msg);
                            }else{
                                console.log("done next");
                                $('#js-editor').click();
                               // $('#main_inner').html(_obj.data);          
                               // $('#fb_data').fadeIn(); //Fade in the active ID content                              	
                                //$.ajax({
//                                    cache   : false,
//                                    url     : '/filter/createBookCover',
//                                    type    : 'post',
//                                    data    : { 'book_info_id' : $.cookie('hardcover_book_info_id') },
//                                    success : function(res){
//										$('ul.tabs2 li:eq(2)').trigger('click');
//										}
//                                });
                            };
                        },  
            error   :   function(){
                        }             
          });
        return false;
    });
    
     $('#filter_next_cover').die('click').live('click',function() {
		 
          $(this).append('<div class="ajax_loader"></div>');
          var form_data = $('#form_filter_data').serialize();
          var ulink = '../../../filter/save_book_filter_cover';
          
          $.ajax({
            cache   :   false,
            url     :   ulink,
            type    :   'post',
            data    :   form_data,      
            success :   function(res){
            				$('.ajax_loader').remove();
                            var _obj = $.parseJSON(res);

                            if (_obj.status!=0){
                                alert(_obj.msg);
                            }else{   
								/*$('#main_inner_uploder_pop').html(''); 
								$('#main_inner_uploder_pop').css('display','none');     
                                $('#main_inner').html(_obj.data);          
                                $('#fb_data').fadeIn(); //Fade in the active ID content                              	
                                $.ajax({
                                    cache   : false,
                                    url     : '../../../filter/createBookCover',
                                    type    : 'post',
                                    data    : { 'book_info_id' : $.cookie('hardcover_book_info_id') },
                                    success : function(res){}
                                });*/
                               //   var pathname = window.location.pathname;
                                //window.location.href = pathname;
                                $('#main_inner_uploder_pop').html('').css('display','none');
                                $('#main_inner_overlay').css('display','none');
                                var link = '../../../main/get_last_insert_images';
									 $.ajax({
										cache   :   false,
										url     :   link,
										type    :   'post',      
										success :   function(res){
											 var _obj = $.parseJSON(res);                            
                                    
											 $('#last_inset_div ul#cvv_data').html(_obj.data);
											 //$('#last_inset_div_a').click();
											 insert_cover_photos_album();
											 jQuery('#app_loader23').fadeOut(100); 
											}
									});
                            };
                        },  
            error   :   function(){
                        }             
          });
        return false;
    });
    
    
    $('#filter_next_unique').die('click').live('click',function() {
        console.log("next clicked");
        var book_id = $("body").attr("book_info_id");
        $("#form_filter_data #book_info_id").val(book_id);
        console.log("click book id: "+$("#form_filter_data #book_info_id").val());
        $(this).append('<div class="ajax_loader"></div>');
          var form_data = $('#form_filter_data').serialize();
          console.log(form_data);
          var ulink = '/filter/save_book_filter_cover_unique';
          
          $.ajax({
            cache   :   false,
            url     :   ulink,
            type    :   'post',
            data    :   form_data,
            success :   function(res){
            				$('.ajax_loader').remove();
                            var _obj = $.parseJSON(res);

                            if (_obj.status!=0){
                                alert(_obj.msg);
                            }else{   
                                console.log('in else');
								/*$('#main_inner_uploder_pop').html(''); 
								$('#main_inner_uploder_pop').css('display','none');     
                                $('#main_inner').html(_obj.data);          
                                $('#fb_data').fadeIn(); //Fade in the active ID content                              	
                                $.ajax({
                                    cache   : false,
                                    url     : '../../../filter/createBookCover',
                                    type    : 'post',
                                    data    : { 'book_info_id' : $.cookie('hardcover_book_info_id') },
                                    success : function(res){}
                                });*/
                               //   var pathname = window.location.pathname;
                                //window.location.href = pathname;
                                $('#main_inner_uploder_pop').html('').remove();
                                $('#main_inner_overlay').css('display','none');
                                var link = '/main/get_last_insert_images';
									 $.ajax({
										cache   :   false,
										url     :   link,
										type    :   'post',      
										success :   function(res){
											 var _obj = $.parseJSON(res);                            
                                    
											 $('#last_inset_div ul#cvv_data').html(_obj.data);
											 $('#last_inset_div_a').click();
											 jQuery('#app_loader23').fadeOut(100);
											 window.location = window.location.pathname;
											}
									});
                            };
                        },  
            error   :   function(){
                        }             
          });
        return false;
    });
    
    
   /* $('#filter_next').die('click').live('click',function() {
          $(this).append('<div class="ajax_loader"></div>');
          var form_data = $('#form_filter_data').serialize();
          var referer = $.cookie('hardcover_referer');
          var ulink = 'filter/save_book_filter';
          
          $.ajax({
            cache   : false,
            url     :   ulink,
            type    :   'post',
            data    :   form_data,      
            success :   function(res){
            				$('.ajax_loader').remove();
                            var _obj = $.parseJSON(res);

                            if (_obj.status!=0){
                                alert(_obj.msg);
                            }else{           
                                $('#main_inner').html(_obj.data);          
                                $('#fb_data').fadeIn(); //Fade in the active ID content                              	
                                $.ajax({
                                    cache   : false,
                                    url     : 'filter/createBookCover',
                                    type    : 'post',
                                    data    : { 'book_info_id' : $.cookie('hardcover_book_info_id') },
                                    success : function(res){}
                                });
                            };
                        },  
            error   :   function(){
                        }             
          });
        return false;
    });
    */
    $('#share_filter_next').die('click').live('click',function() {
        $(this).append('<div class="ajax_loader"></div>');
        var form_data = $('#form_filter_data').serialize();
       
        $.ajax({
          cache   : false,
          url     :   'filter/saveShareBookPagesContent',
          type    :   'post',
          data    :   form_data,      
          success :   function(res){
                          var _obj = $.parseJSON(res);
                          _getobj = _obj;

                          if (_obj.status!=0){
                              alert(_obj.msg);
                          }else{
                              $('#main_inner').append('<div class="ajax_loader"></div>');
                              $.ajax({
                                  cache   : false,
                                  url     : 'filter/filter_more',
                                  type    : 'post',
                                  data    : { 'book_info_id' : $.cookie('hardcover_book_info_id') },
                                  success : function(res){
                                      var _obj_ = $.parseJSON(res);
                                      $('.ajax_loader').remove();             
                                      $('#main_inner').html(_obj_.data);          
                                      $('#fb_data').fadeIn(); //Fade in the active ID content 
                                  } 
                              });     
                          };
                      },  
          error   :   function(){
                      }             
        });
      return false;
  });    
});

function clickMe(el_name) {
	var element = $('#' + el_name);
	var status = $('#status_my_update'),
			iStatus = $('#status'),
			cb = $(':checkbox');
			
	if (element.is(':checked')) { 
		element.attr('checked', false);
	}
	else {
		element.attr('checked', 'ckecked');
	}
	
	
	if ($('#all').is(':checked')) {
		$('#filter_menu_status').fadeIn();
		$('#filter_menu_post').fadeIn();
		$('#post_photos').removeAttr('disabled');
		$('#post_comment').removeAttr('disabled');
		$('#post_article').removeAttr('disabled');
		
		if ($('#status_my_update').is(':checked')) {  
			iStatus.find('._child').removeAttr('disabled');
		}
		else {
			iStatus.find('._child').attr('checked', false);
			iStatus.find('._child').attr('disabled', 'disabled');
		}
	}
		
	if ($('#photo_only').is(':checked')) {  
		$('#filter_menu_status').hide();
		$('#filter_menu_post').hide();
	}
	
	if ($('#status_my_update').is(':checked')) {  
		iStatus.find('._child').removeAttr('disabled');
	}
	else {
		iStatus.find('._child').attr('checked', false);
		iStatus.find('._child').attr('disabled', 'disabled');
	}
}	 



function deleteCity (city) {
	
	var el_cities = document.getElementById('deleted_cities');
	if (el_cities.value.length > 0) {
		el_cities.value = el_cities.value + ' || ' + city.value;
	}
	else
	{
		el_cities.value = city.value;
	}
}


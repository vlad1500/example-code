/* Author:

*/
var _arr_;
	$(function(){
		$.ajaxSetup({ cache:false });

		$('body').css({'height' : $('#iframe_canvas').innerHeight() });
		$.cookie("storedObj", null);
		$.cookie("hardcover_book_info_id", null);		
		$(".tab2_content").hide(); //Hide all content		 
		$(".tab2_content:first").show(); //Show first tab content
		//Page Tabs onClick Event
		$("ul.tabs2 li").live('click',function() {
	        $("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	        $(this).addClass("active"); //Add "active" class to selected tab
	        $(".tab2_content").hide(); //Hide all tab content
	        var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
	        //alert(activeTab);
			if(activeTab == '#my_home'){
				$('#my_home').fadeIn();
				//$.cookie("storedObj", null);
				//$.cookie("hardcover_book_info_id", null);
				$('#main_inner').append('<div class="ajax_loader"></div>');
				$.ajax({
					url     : 'main/init_fb',
					type    : 'post',
					success : function(res){
						var _obj = $.parseJSON(res);
						//$('.tabs2').removeClass('hideDiv');
						$('.ajax_loader').remove();
						$('#my_home').html(_obj.data);
						//$('#my_home').fadeIn();
						$(activeTab).fadeIn();
						 
						//getCookie();
					}
				});	
													
			}else if (activeTab == '#fb_data'){
				$.cookie('storedObj',null);
				if ($.cookie('hardcover_book_info_id') === null || $.cookie('hardcover_book_info_id') === undefined){
					$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	        		$('#home').addClass("active"); //Add "active" class to selected tab
					$('#main_inner').append('<div class="ajax_loader"></div>');
					$.ajax({
						url     : 'main/init_fb',
						type    : 'post',
						success : function(res){
							var _obj = $.parseJSON(res);
							$('.ajax_loader').remove();
							$('#my_home').html(_obj.data);
							$('#my_home').fadeIn();
							//getCookie();
						}
					});
				}else{
					$('#main_inner').append('<div class="ajax_loader"></div>');
					$.ajax({
						cache   : false,
						url     : 'filter/filter_page',
						type    : 'post',
						success : function(res){
							var _obj = $.parseJSON(res);
							//$(activeTab).html(_obj.data);
							$('.ajax_loader').remove();
							if($("#main_inner").find("#my_album").length == 0)
								$("#main_inner").append("<div class='tab2_content' id='my_album'></div>");
							$("#main_inner .tab2_content").html("");
							$('#main_inner').find("#my_album").html(_obj.data).css("display","block");
							$(activeTab).fadeIn(); //Fade in the active ID content
						}
					});
				}
			}else if (activeTab == '#my_edit'){
				if ($.cookie('hardcover_book_info_id') === null || $.cookie('hardcover_book_info_id') === undefined){
					$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	        		$('#home').addClass("active"); //Add "active" class to selected tab
					$('#main_inner').append('<div class="ajax_loader"></div>');
					$.ajax({
						url     : 'main/init_fb',
						type    : 'post',
						success : function(res){
							var _obj = $.parseJSON(res);
							$('.ajax_loader').remove();
							$('#my_home').html(_obj.data);
							$('#my_home').fadeIn();
							//$(activeTab).fadeIn();
							//getCookie();
						}
					});
				}else{
					$('#main_inner').append('<div class="ajax_loader"></div>');
					$.ajax({
						url     : 'main/edit_album',
						type    : 'post',
						success : function(res){
							var _obj = $.parseJSON(res);
							$('.ajax_loader').remove();

							if($("#main_inner").find("#my_edit").length == 0)
								$("#main_inner").append("<div class='tab2_content' id='my_edit'></div>");
							$("#main_inner .tab2_content").html("");
							$('#main_inner').html(_obj.data).css("display","block"); 
							$(activeTab).fadeIn(); //Fade in the active ID content
						}
					});
				}
			}else if (activeTab == "#my_cover"){
				$("#main_inner").append("<div class=\"ajax_loader\"></div>");
				$.ajax({
					url     : "cover/design",
					type    : "post",
					success : function(res){
						var _obj = $.parseJSON(res);
						$(".ajax_loader").remove();
						$("body").prepend("<div id=\"app_loader\"><div id=\"bar\"><span></span></div></div>");
						if($("#main_inner").find("#my_cover").length == 0)
								$("#main_inner").append("<div class='tab2_content' id='my_cover'></div>");
							$("#main_inner .tab2_content").html("");
							$('#main_inner').find("#my_cover").html(_obj.data).css("display","block");
						
						//$("#main_inner").html(_obj.data);
						if ($.cookie("hardcover_book_info_id") === null || $.cookie("hardcover_book_info_id") === undefined) {
							$("#app_loader").remove();
							modalMessageBox("No Album Data Found", "You still need to select an album or create a new album.");
							//modalPosition();
							$("#home a").click();
						} else {
							$("#app_loader").fadeOut("slow");
							$(activeTab).css({display:"block"}).fadeIn("slow");

							loadBookAndAuthorName();
						}

					}
				});
		
			}else if (activeTab == "#my_album"){
				$("#main_inner").append("<div class=\"ajax_loader\"></div>");
				$.ajax({
					url     : "album/my_album",
					type    : "post",
					success : function(res){
						var _obj = $.parseJSON(res);
						$(".ajax_loader").remove();
						if($("#main_inner").find("#my_cover").length == 0)
								$("#main_inner").append("<div class='tab2_content' id='my_cover'></div>");
							$("#main_inner .tab2_content").html("");
							$('#main_inner').find("#my_cover").html(_obj.data).css("display","block");
							$(activeTab).fadeIn(); //Fade in the active ID content
					}
				});
			}
	        return false;
	    });//end of Page Tabs onClick Event
		
		$('#button_main_next').live('click',function(e){
			e.preventDefault();
			$('#app_loader').fadeIn('slow');
			$.ajax({
				url     : 'main/init_fb',
				type    : 'post',
				cache   :  true,
				success : function(res){
					var _obj = $.parseJSON(res);
					$('.tabs2').removeClass('hideDiv');
					$('#my_home').html(_obj.data);
					$('#my_home').fadeIn();
					$('#app_loader').fadeOut('slow');
					getCookie();
				}
			});
			return false;
		});
		
		$("button").animate({'opacity' : 1}).hover(function() {
			$(this).animate({'opacity' : .8});
		}, function() {
			$(this).animate({'opacity' : 1});
		});
	
		//Cover Page Scripts -------------------------------------------------------------------------------------------------
		$('#cover_next').live('click',function(){
			var book_title = $('#my_cover_title').val();
			$.ajax({
				cache   : false,
				url     : 'main/filter_page',
				type    : 'post',
				data	: {'book_name':book_title},
				success : function(res){
					var _obj = $.parseJSON(res);
					//$(activeTab).html(_obj.data);
					$('.tabs2').removeClass('hideDiv');
					$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
					$('ul.tabs2 li#data').addClass("active").fadeIn(); //Add "active" class to selected tab
					if($("#main_inner").find("#my_cover").length == 0)
						$("#main_inner").append("<div class='tab2_content' id='my_cover'></div>");
					$("#main_inner .tab2_content").html("");
					$('#main_inner').find("#my_cover").html(_obj.data).css("display","block");					

					//$('#main_inner').html(_obj.data);
					$('#fb_data').fadeIn();
					//loadAlbum();
				}
			});
		});
		$("#album_for_me_cover").on("click", function() { 
			$('#shared_active').css('display','block');

			$.ajax({
				url : "../../../main/home_select_booktype_cover",
				 
				success : function(res){
					 
					  
					var _obj = $.parseJSON(res);
					if (_obj.status) {						
						 $('#main_inner_uploder_pop').html(_obj.data);
						  $('#my_image').click();
						  getCookie();
						  ch();
						  }
				},error : function(res, err, errTxt){
					alert("\n"+ err.toUpperCase() +": \"Page "+ errTxt +"\"");
					$("#app_loader").fadeOut("slow");
				}
			});
			
			return false;
		});
		
		 //TO get the uploader based on the unique URL

		$("#album_for_me_unique").on("click", function() { 
			$('#shared_active').css('display','block');
		//	$("#app_loader").fadeIn("slow");
			$.ajax({
				url : "../../../main/home_select_booktype_unique",
				success : function(res){
					var _obj = $.parseJSON(res);
					if (_obj.status) {
						if($("#main_inner").find("#my_edit").length == 0)
								$("#main_inner").append("<div class='tab2_content' id='my_edit'></div>");
							$("#main_inner .tab2_content").html("");
							//$('#main_inner').find("#my_edit").html(_obj.data).css("display","block");
 $('#main_inner_uploder_pop').html(_obj.data).css('display','block');
 $('#main_inner_overlay').css('display','block');
 //$('#main_inner_uploder_pop_a').click();
 
						getCookie();
					 
						ch();
						 
						//$("#app_loader").fadeOut("slow");
					}
				},error : function(res, err, errTxt){
					alert("\n"+ err.toUpperCase() +": \"Page "+ errTxt +"\"");
					$("#app_loader").fadeOut("slow");
				}
			});
			
			return false;
		});	
		
		$("#album_for_me1").on("click", function() { 
			$("#app_loader").fadeIn("slow");
			$.ajax({
				url : "../../../main/home_select_booktype",
				success : function(res){
					 
					  
					var _obj = $.parseJSON(res);
					if (_obj.status) {
						
						if($("#main_inner").find("#my_edit").length == 0)
								$("#main_inner").append("<div class='tab2_content' id='my_edit'></div>");
							$("#main_inner .tab2_content").html("");
							$('#main_inner').find("#my_edit").html(_obj.data).css("display","block");
 
						//$("#main_inner").html(_obj.data);
						 
						getCookie();
					 
						ch();
						 
						$("#app_loader").fadeOut("slow");
					}
				},error : function(res, err, errTxt){
					alert("\n"+ err.toUpperCase() +": \"Page "+ errTxt +"\"");
					$("#app_loader").fadeOut("slow");
				}
			});
			
			return false;
		});	
			
		$("#album_for_me_share").on("click", function() { 
			$("#app_loader").fadeIn("slow");
			$.ajax({
				url : "../../../main/home_select_booktype1",
				success : function(res){
					 
					  
					var _obj = $.parseJSON(res);
					if (_obj.status) {
						
						if($("#main_inner").find("#my_edit").length == 0)
								$("#main_inner").append("<div class='tab2_content' id='my_edit'></div>");
							$("#main_inner .tab2_content").html("");
							$('#main_inner').find("#my_edit").html(_obj.data).css("display","block");
 
						//$("#main_inner").html(_obj.data);
						 
						getCookie();
					 
						ch();
						 
						$("#app_loader").fadeOut("slow");
					}
				},error : function(res, err, errTxt){
					alert("\n"+ err.toUpperCase() +": \"Page "+ errTxt +"\"");
					$("#app_loader").fadeOut("slow");
				}
			});
			
			return false;
		});
		
		//marlo starts here 12.23.2012
		$("#album_for_me").on("click", function() {
		 
			$("#app_loader").fadeIn("slow");
			 
			$.ajax({
				url 	: "../../../main/home_select_booktype",
				success : function(res){
					 
					var _obj = $.parseJSON(res);
					 
					if (_obj.status) {   
						$(".tabs2").removeClass("hideDiv"); 
						$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
						 
						$("ul.tabs2 li#data").addClass("active").fadeIn(); //Add "active" class to selected tab
						 //console.log(_obj.data);
						 $('#my_home').hide();
						
						 temp = _obj.data;
						 $("#app_loader").fadeOut("slow");
						 getCookie();
						 if($("#main_inner").find("#upload_cont").length == 0)
						   $("#main_inner").append("<div id='upload_cont' class='tab2_content'></div>");
						 $("#main_inner").find("#upload_cont").css("display","block").html(temp);
						
						// $("#main_inner #upload_cont");
						 
						//$("#main_inner").html(_obj.data);
						//$("#fb_data").fadeIn();
						
						 
						
					}  
				},error : function(res, err, errTxt){
					alert("\n"+ err.toUpperCase() +": \"Page "+ errTxt +"\"");
					$("#app_loader").fadeOut("slow");
				}
			});
			return false;
		});
		//marlo ends here 12.23.2012
		$('#delete img').live('click',function(){
			var id_ = $(this).attr('id');
			$('li#'+id_).remove();
			$("#tooltip").remove();
			return false;
		});		
			
		//this is a temporary function and can be deleted on live
		$('#button_kihm').live('click',function(){
			$.ajax({
				url     : '../../../main/kihm_view',
				type    : 'post',
				cache   :  true,
				success : function(res){
					var _obj = $.parseJSON(res);
					$('.tabs2').removeClass('hideDiv');
					$('#my_home').html(_obj.data);
					$('#my_home').fadeIn();
				}
			});  
		});
		
	});//end of $(function(){ -----> document.ready
	
	this.tooltip = function(){
		xOffset = 10;
		yOffset = 20;
		
		$(".tooltip").hover(function(e){
			this.t = this.title;
			this.title = "";
			$("body").append("<p id='tooltip'>"+ this.t +"</p>");
			e.pageX > 750 ?  e.pageX = 600 : e.pageX;
			$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");
		}, function(){
			this.title = this.t;	
			$("#tooltip").remove();
		});
		$(".tooltip").mousemove(function(e){		
			$("#tooltip")
			.css("top",(e.pageY - (xOffset+27)) + "px")
			.css("left",(e.pageX + (yOffset-18)) + "px");
		});
	};//end this.tooltip = function(){ 
	
	(function($){ //truncate comment
		$.fn.truncate_comment = function (settings) {
			var opts =  $.extend({}, $.fn.truncate_comment.defaults, settings);
		
			this.each(function () {
				$(this).data("opts", opts);
				if ($(this).html().length > opts.substr_len) {
					abridge($(this));
					linkage($(this));
				}
			});
		
			function linkage(elem) {
				elem.append(elem.data("opts").more_link);
				elem.children(".more").click( function () {
					$(this).hide();
					$(this).siblings("span:not(.hidden)").hide().siblings("span.hidden").animate({'opacity' : 'toggle'},1000);
				});
			}
		
			function abridge(elem) {
				var opts = elem.data("opts");
				var txt = elem.html();
				var len = opts.substr_len;
				var dots = "<span>" + opts.ellipses + "</span>";
				var charAtLen = txt.substr(len, 1);
				while (len < txt.length && !/\s/.test(charAtLen)) {
					len++;
					charAtLen = txt.substr(len, 1);
				}
				var shown = txt.substring(0, len) + dots;
				var hidden = '<span class="hidden" style="display:none;">' + txt.substring(len, txt.length) + '</span>';
				elem.html(shown + hidden);
			}
			return this;
		};//end of $.fn.truncate_comment
		
		$.fn.truncate_comment.defaults = {
			substr_len: 500,
			ellipses: '&#8230;'
			//more_link: '<a class="more" style="font-size:10px;">Read&nbsp;More</a>'
		};
	})(jQuery);//end of (function($){ --->truncate comment
		
	function checkBox(){
		$('input[type=checkbox]').each(function(i,elem) {	
			if ($(this).is(':checked')) {	
			    //console.log(elem);			
				var span = $('<span class="checked ' + $(this).attr('type') + ' ' + $(this).attr('class') + '"></span>').click(doCheck).mousedown(doDown).mouseup(doUp);
			}else{
				var span = $('<span class="' + $(this).attr('type') + ' ' + $(this).attr('class') + '"></span>').click(doCheck).mousedown(doDown).mouseup(doUp);	
			}
			$(this).wrap(span).hide();
		});
		
		function doCheck() {							
			if ($(this).hasClass('checked')) {
				$('input[type=checkbox]').attr('value',0);		
				$(this).removeClass('checked');
				$(this).children().attr("checked", false);
			} else {
				$('input[type=checkbox]').attr('value',1);
				$(this).addClass('checked');			
				$(this).children().attr("checked", true);					
			}			
		}
		
		function doDown() {
			$(this).addClass('clicked');
		}
		
		function doUp() {
			$(this).removeClass('clicked');
		}		
	}//end of function checkBox(){
	
	function radioStyle(){
		$('input[type=radio]').each(function(i,elem) {									
			if ($(this).is(':checked')) {
				var span = $('<span rel="'+$(this).attr('name')+'" id="radio_'+i+'" class="' + $(this).attr('type') + ' ' + $(this).attr('class') + ' checked"></span>').click(doCheck).mousedown(doDown).mouseup(doUp);
				$('#radio_'+i).addClass('checked');
			}else{
				//alert('radio_'+i);$('#radio_'+i).addClass('checked'); 
				var span = $('<span rel="'+$(this).attr('name')+'" id="radio_'+i+'" class="' + $(this).attr('type') + ' ' + $(this).attr('class') + '"></span>').click(doCheck).mousedown(doDown).mouseup(doUp);
			}
			$(this).wrap(span).hide();
		});
		
		function doCheck() {	
			var this_id = $(this).attr('id');
			var this_rel = $(this).attr('rel');		
			$('span.radio').each(function(i) {
				if (this_rel == $(this).attr('rel')){
					if(this_id == $(this).attr('id')){
						$('span#'+this_id).addClass('checked');
						$(this).children().prop("checked", true);
					}else{
						$(this).removeClass('checked');
						$(this).children().prop("checked", false);
					}
				}
			});
			/*if ($(this).hasClass('checked')) {
				$(this).removeClass('checked');
				$(this).children().prop("checked", false);
			} else {			
				$(this).addClass('checked');	
				$(this).children().prop("checked", true);					
			}*/			
		}//end of function doCheck() {
		
		function doDown() {
			$(this).addClass('clicked');
		}
		
		function doUp() {
			$(this).removeClass('clicked');
		}		
	}//end of function radioStyle(){
	
	(function($){
		$.fn.myHover = function (settings) {
			var opts =  $.extend({},$.fn.myHover.defaults, settings);
			var element = $(this);
			//if (element.data('myHover')) return element.data('myHover');
			this.each(function () {		
				//var div_id = "";
				$(this).live('mouseenter',function(e){
					var div_id =  $(this).parent().get(0).id;	 	
					var this_id = $(this).attr('id');
					var curr_height = $('#'+div_id).height();
					var img_top = $(this).offset().top;
					var img_h = $(this).height();
					$('#'+div_id).css({'height':curr_height});
					$('#'+div_id).append('<div class="edit_img_container"><p>'+opts.message+'</p></div>');					
					$('.edit_img_container').animate({bottom:20},opts.speed_in);
				}).live('mouseleave',function(){
					$('.edit_img_container').animate({bottom:0},opts.speed_out,function(){ $('.edit_img_container').remove();});
				}).live('click',function(e){
					var this_id = $(this).attr('id');
					var div_id =  $(this).parent().get(0).id;	
																												
					$.cookie('obj_',null);
					$.cookie('obj_',div_id); 
					$.cookie('img_',null);
					$.cookie('img_',this_id); 
					$.cookie('imgurl_',null);
					$.cookie('imgurl_',$('#'+this_id).attr('src')); 
					
					var images = $('#'+this_id); 
					var img_curr_width = images.width();
					var curr_height = parseInt($('#'+this_id).height()) + parseInt($('#'+this_id).offset().top)-30;
					//var par_id = $('#'+div_id).parent().get(1).id;
					var curr_left = parseInt($('section.current').offset().left); //$('#'+div_id).position().left;
					//$('#'+div_id).css({'height':curr_height});
					curr_height > parseInt($('#book').innerHeight()) ? curr_height = parseInt($('#book').offset().top) + (parseInt($('#book').innerHeight()) - 100)  : curr_height; 
					$('#img_editor').fadeOut('fast').css({top:curr_height,left:curr_left}).fadeIn('slow');
					//$('#edit_aviary').click(function(){ $('#img_editor').fadeOut('fast'); });
					$('img#'+this_id).draggable({stop:function(event, ui){								
						console.log($(this).position());
					},'cursor':'move','handle':'img'});
					$('select#border_option').change(function () {								
						$("select#border_option option:selected").each(function () {
							if ( $(this).attr('value') == 0 ){
								var _border = 'none';
							}else{
								var _border = $(this).attr('value')+'px solid #000';
							}
							$('div#'+div_id).css({'border':_border});
						});							   
					});
					return false;	
				});
			});
			return this;
		};//end of $.fn.myHover
		$.fn.myHover.defaults = {
			speed_in 	: 200,
			speed_out 	: 200,
			message		: 'Message...'
		};
	})(jQuery);
	
	(function($){
		$.fn.myEditor = function (settings) {
			var opts =  $.extend({},$.fn.myEditor.defaults, settings);
			var _buttons =	'<input type="button" value="Blur" id="blur"  style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Contrast" id="contrast"  style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Brightness" id="brightness" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Saturation" id="saturation" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Sharpen" id="sharpen" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Grayscale" id="grayscale" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Hue" id="hue" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Invert" id="invert" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Noise" id="noise" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Sepia" id="sepia" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Adjust color" id="rgb" style="float:left; margin:18px 5px 0;"/>';
				_buttons +=	'<input type="button" value="Close" id="_closeEffect" style="float:left; margin:18px 5px 0;"/>';
			
			var _optsborder =  '<p class="float_left" style="margin:5px;">';
				_optsborder += '<select id="border_option">';
				_optsborder += '<option >Border</option>';
				_optsborder += '<option value="0">None</option>';
				_optsborder += '<option value="1">1px</option>';
				_optsborder += '<option value="2">2px</option>';
				_optsborder += '<option value="3">3px</option>';
				_optsborder += '<option value="4">4px</option>';
				_optsborder += '<option value="5">5px</option>';                    
				_optsborder += '</select>';  
				_optsborder += '</p>';
			
			var _defbutton =  '<div style="float:left; margin:18px 5px 0;"><div id="icon_minus" class="float_left"></div>';
				_defbutton += '<div id="slider" class="float_left"><input id="slide_scale" type ="range" min ="0" max="0" step ="10"/></div>';
				_defbutton += '<div id="icon_plus" class="float_left"></div>';
				_defbutton += '<p class="float_left" style="margin:5px;"><span><input type="text" name="percentage" id="percentage" disabled="disabled" value="100%" style="text-align:center;width:40px;"/></span> <span>1:1</span> </p>';	
				_defbutton += _optsborder;
				_defbutton += '</div>';
				_defbutton += '<input type="button" value="Effects" id="effects" style="float:left; margin:18px 5px 0;"/>';
				_defbutton += '<input type="button" value="Rotate-L" id="rotate_l" style="float:left; margin:18px 5px 0;"/>';
				_defbutton += '<input type="button" value="Rotate-R" id="rotate_r" style="float:left; margin:18px 5px 0;"/>';			
				_defbutton += '<input type="button" value="Crop" id="crop" style="float:left; margin:18px 5px 0;"/>';
				_defbutton += '<input type="button" value="Revert" id="revert" style="float:left; margin:18px 5px 0;"/>';
				_defbutton += '<input type="button" value="Save" id="savecanvas" style="float:left; margin:18px 5px 0;"/>';
				_defbutton += '<input type="button" value="Close" id="xclose" style="float:left; margin:18px 5px 0;"/>';
			
			//this.each(function () {
			var elem = $(this);			
			var _img_elem = $(this).find('img');
			var html = '<div id="modal_container" style="box-sizing:border-box;height:auto;">';
				//html += '<a href="#" id="close_modal" class="float_right">Close</a>';
				html += '<a href="#" id="close_modal" class="float_right"><img src="../images/close.png" /></a>';
			    html += '<div id="modal_inner" style="overflow:hidden;"><div id="icons_"><div id="icons_placer" style="overflow: hidden;min-width: 200px;position: absolute;"></div></div><div id="edit_img" style="border:none;display:none;"><div class="ajax_loader"></div><canvas id="mycanvas" style="padding:5px;"></canvas></div></div>';
				html += '</div>';
				html += '<div id="modal_bckgrnd"></div>';
			$('body').prepend(html);
			$('#modal_bckgrnd').css({'width':$('body').width(),'height': '1000px','opacity':0, 'background': '#000'}).animate({opacity:.7},'slow',function(){
				$('#modal_container').css({'width':$('body').width(),'height':$('body').height(),'opacity':1}).fadeIn('slow',function(){
					$('#modal_inner').css({'margin':'10px auto', 'width':'80%','height':'auto', 'box-sizing':'border-box', 'padding': '10px 10px 20px', 'background' : '#F9F9F9', 'border': '1px solid #EEE', 'border-radius': '10px','-moz-border-radius': '10px', '-webkit-border-radius': '10px'});
					$('#edit_img').css({ 'height':'auto', 'min-height':'500px' }).fadeIn('slow',function(){
						$('#edit_img img').removeClass('editable').addClass('imgedit');
						$('#icons_placer').append(_defbutton).fadeIn('slow');
						$('#icons_placer').css({ 'margin-left': Math.floor(($('#icons_').width() / 2) - ($('#icons_placer').width() / 2)) });
					});
				});
			});		
			
			var fileNameIndex = elem.attr('src').lastIndexOf("/") + 1;
			var _file = elem.attr('src').substr(fileNameIndex);
			var _imgID = elem.attr('id');
			var _gimgID = $.trim(_imgID.substr(4,30));
			var canvas = document.getElementById("mycanvas");
			var context = canvas.getContext("2d");
			var _newUri;
			var imgObj = new Image();
			
			$.ajax({
				url		: '/main/save_img_file',
				type	: 'POST',
				data	: { 'filename' : _file, 'uri_' : elem.attr('src') },
				success	: function(res){
					localStorage.clear();
					_newUri = '/uploads/'+res;									
					imgObj.src = _newUri;			
					$("#mycanvas").hide();												
					saveToStorage(imgObj,canvas,context);
				}
			});
			
			function saveToStorage(imgObject,imgCanvas,imgContext){
				// localStorage with image
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				storageFilesDate = storageFiles.date;
				//date = new Date(),
				//todaysDate = (date.getMonth() + 1).toString() + date.getDate().toString();
				
				// Compare date and create localStorage if it's not existing/too old   
				if (typeof storageFiles.imgObject === "undefined") {
					// Take action when the image has loaded
					imgObject.addEventListener("load", function () {
						// Make sure canvas is as big as the picture
						imgCanvas.width = imgObject.width;
						imgCanvas.height = imgObject.height;
						var minval = imgObject.width * .5;
						var maxval = imgObject.width * 1.5;
						$('#slide_scale').attr('min',minval).attr('max',maxval).attr('value',imgObject.width);
						var _thisW = imgCanvas.width / 2;
						var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
						
						$(canvas).css({ 'margin-left' : _toLeft });
						
						// Draw image into canvas element
						imgContext.drawImage(imgObject, 0, 0, imgObject.width, imgObject.height);
						
						// Save image as a data URL
						storageFiles.imgObject = imgCanvas.toDataURL("image/jpg");
						
						// Set date for localStorage
						//storageFiles.date = todaysDate;
						// Save as JSON in localStorage
						try {
							localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
						} catch (e) {
							console.log("Storage failed: " + e);
							alert('Please click revert to reload...');
						}
						$("#mycanvas").fadeIn('slow');	
						$('.ajax_loader').hide('slow');
						
					}, false);//end of imgObject.addEventListener
					
					// Set initial image src    
					imgObject.setAttribute("src", imgObject.src);
				} else {
					// Use image from localStorage					
					imgObject.setAttribute("src", storageFiles.imgObject);
					imgObject.addEventListener("load", function () {
						// Make sure canvas is as big as the picture
						imgCanvas.width = imgObject.width;
						imgCanvas.height = imgObject.height;
						var minval = imgObject.width * .5;
						var maxval = Math.floor(imgObject.width * 1.5); 
						$('#slide_scale').attr('min',minval).attr('max',maxval).attr('value',imgObject.width);						
						var _thisW = imgCanvas.width / 2;
						var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
						
						// Draw image into canvas element
						imgContext.drawImage(imgObject, 0, 0, imgObject.width, imgObject.height);
						//var data = imgContext.getImageData(0,0,canvas.width, canvas.height); 
						
						//imgContext.putImageData(data,0,0);	
						storageFiles.imgObject = imgCanvas.toDataURL("image/jpg");
						
						// Save as JSON in localStorage
						try {
							localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
							imgObject.setAttribute("src", storageFiles.imgObject);
						} catch (e) {
							console.log("Storage failed: " + e);
							alert('Please click revert to reload...');
							$('#edit_img').removeClass('bar');
						}
						$("#mycanvas").fadeIn('slow');
						$('.ajax_loader').hide('slow');
						
					}, false);//end of imgObject.addEventListener
					
				}//end of if typeof storageFiles.imgObject		
			 
				
			}//end of function saveToStorage
			
			/* 
			 * myEditor's feature
			 * 
			*/
			$('#rotate_l').live('click',function(){
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.save();
					context.translate( centerX, centerY );
					context.rotate(rotate * Math.PI / 180);
					context.drawImage(imgObject, centerX - (imgW * (scale/100)), centerY - (imgH * (scale/100)), imgW * (scale/100), imgH * (scale/100) );
					context.restore();
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
					
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#rotate_l')
			
			$('#border_option').live('change',function(){
				$('.ajax_loader').fadeIn('slow');
				var _thisNum = $(this).val().substr(0,1);
				if ( !isNaN( _thisNum ) ){
					var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
					var _imgobj = imgObj;
					_imgobj.setAttribute("src", storageFiles.imgObject);
					
					_imgobj.addEventListener("load", function () {
						canvas.width = _imgobj.width;
						canvas.height = _imgobj.height;
						context.setTransform(1, 0, 0, 1, 0, 0);
						context.clearRect(0, 0, canvas.width, canvas.height);
						context.save();
						var _thisW = canvas.width / 2;
						var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
						context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
						
						var _per = $(this).val() / 2;
						context.rect(_per,_per,_imgobj.width,_imgobj.height);
						context.strokeStyle = '#000';
						context.lineWidth = _thisNum;
						context.stroke();
						
						storageFiles.imgObject = canvas.toDataURL("image/jpg");
						
						// Save as JSON in localStorage
						try {
							localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
						} catch (e) {
							console.log("Storage failed: " + e);
							alert('Please click revert to reload...');
						}
						$('.ajax_loader').hide('slow');
						
					},false);//end of _imgobj.addEventListener
				}
			});//end of $('#border_option')
			
			$('#slide_scale').live('change',function(){
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var imgObj_ = new Image();
				imgObj_.setAttribute("src", storageFiles.imgObject);
				var ratio = imgObj_.width / imgObj_.height;
				var width = $(this).val();
				var height = Math.floor($(this).val() / ratio);
				var prcnt = Math.round(($(this).val() / imgObj_.width) * 100) + '%';
				$('#percentage').val(prcnt);
				imgObj_.onload = function(){
					canvas.width = width;
					canvas.height = height;
					context.drawImage(imgObj_, 0, 0, canvas.width, canvas.height);
				};
			});//end of $('#slide_scale')
			
			$('#effects').live('click',function(){
				$('#icons_placer').empty().append(_buttons).fadeIn('slow');
				$('#icons_placer').css({ 'margin-left': Math.floor(($('#icons_').width() / 2) - ($('#icons_placer').width() / 2)) });
			});
			
			$('#_closeEffect').live('click',function(){
				$('#icons_placer').empty().append(_defbutton).fadeIn('slow');
				$('#icons_placer').css({ 'margin-left': Math.floor(($('#icons_').width() / 2) - ($('#icons_placer').width() / 2)) });
			});
			
			$('#revert').live('click',function(){
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				localStorage.clear();
				
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", _newUri);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					//var data = context.getImageData(0,0,canvas.width, canvas.height);
					//context.putImageData(data,0,0);
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
					
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#revert')
		
			$('#blur').live('click',function(){	
				$('.ajax_loader').fadeIn('slow');	
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};			
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height); 
					new BlurFilter().filter(data, {amount : 1 });
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}					
					$('.ajax_loader').hide('slow');	
					
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#blur')
			
			$('#contrast').live('click',function(){	
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};			
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);	
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new ContrastFilter().filter(data, {amount : .7 });
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
									
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#contrast')
			
			$('#brightness').live('click',function(){	
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new BrightnessFilter().filter(data, {amount : .2 });
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
			
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
									
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#brightness')
			
			$('#saturation').live('click',function(){	
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};			
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;				
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
									
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new SaturationFilter().filter(data, {amount : .5 });
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
					
				},false);//end of _imgobj.addEventListener
				 
			});//end of $('#saturation')
			
			$('#sharpen').live('click',function(){
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new SharpenFilter().filter(data);
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
					
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#sharpen')
			
			$('#grayscale').live('click',function(){
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					
					new GrayscaleFilter().filter(data);
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}					
					$('.ajax_loader').hide('slow');
					
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#grayscale')
			
			$('#hue').live('click',function(){
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new HueFilter().filter(data,{ amount: .5 });
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}					
					$('.ajax_loader').hide('slow');
					
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#hue')
					
			$('#invert').live('click',function(){
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new InvertFilter().filter(data);
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}					
					$('.ajax_loader').hide('slow');
									
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#invert')
			
			$('#noise').live('click',function(){
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new NoiseFilter().filter(data, { amount : 25 });
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
					
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#noise')
			
			$('#sepia').live('click',function(){
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					
					context.drawImage(_imgobj, 0, 0,canvas.width, canvas.height);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new SepiaFilter().filter(data, { amount : 25 });
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
									
				},false);//end of _imgobj.addEventListener
				
			});//end of $(#sepia)
			
			$('#rgb').live('click',function(){
				$('.ajax_loader').fadeIn('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var _imgobj = imgObj;
				_imgobj.setAttribute("src", storageFiles.imgObject);
				
				_imgobj.addEventListener("load", function () {
					canvas.width = _imgobj.width;
					canvas.height = _imgobj.height;
					context.setTransform(1, 0, 0, 1, 0, 0);
					context.clearRect(0, 0, canvas.width, canvas.height);
					context.save();
					
					var _thisW = canvas.width / 2;
					var _toLeft = Math.floor( ($(canvas).parent().width() / 2 ) - _thisW );
					context.drawImage(_imgobj, 0, 0);
					var data = context.getImageData(0,0,canvas.width, canvas.height);
					new RGBAdjustFilter().filter(data, { red : 1, green : 1, blue : 2 });
					context.putImageData(data,0,0);
					
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					
					// Save as JSON in localStorage
					try {
						localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
					} catch (e) {
						console.log("Storage failed: " + e);
						alert('Please click revert to reload...');
					}
					$('.ajax_loader').hide('slow');
					
				},false);//end of _imgobj.addEventListener
				
			});//end of $('#rgb') 
			
			$('#savecanvas').live('click',function(e){
				e.preventDefault();
				$('.ajax_loader').show('slow');
				var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};
				var dataurl;
				$('#mycanvas').animate({ opacity : .3 });
				//$('#edit_img').append('<p id="progressnum" style="position:absolute;padding:5px 10px; background:#333; color:#FFF; border-radius: 5px; -moz-border-radius: 5px;, -webkit-border-radius: 5px; left: 40%, top: 40%; z-index: 9999999999; "><progress id="progressBar" value="0" max="100"> </progress></p>').fadeIn('slow');
				
				if (typeof storageFiles.imgObject === "undefined") {
					alert('There is no image to save...');
					return false;
				}else{
					$('.ajax_loader').fadeIn('slow');
					storageFiles.imgObject = canvas.toDataURL("image/jpg");
					dataurl = storageFiles.imgObject;
					var _origurl = $('#'+_imgID).attr('src');
					var b_info = $.cookie('hardcover_book_info_id');
					var fb_id = $.cookie('hardcover_fbid');
					var _elemID = _imgID;
					console.log(_gimgID);
					
					$.ajax({
						'url' 		: '/main/set_save_edited_photos',
						'type'		: 'post',
						'data'		: {'book_info_id' : b_info,'fb_id' : fb_id,'origin' : 'fb','origin_id' : _gimgID,'original_url' : _origurl,'edited_url': 'http://dev.hardcover.me/uploads/'+_file},
						'success'	: function(res){
							var _gimg = document.getElementById(_elemID);
							_gimg.src = '/uploads/'+_file;
							_gimg.addEventListener("load", function () {
								$('#progressnum').remove();
								console.log('saved....');
								$('.ajax_loader').hide('slow');
								$('#mycanvas').animate({ opacity : 1 });
								try {
									localStorage.setItem("storageFiles", JSON.stringify(storageFiles));
								} catch (e) {
									console.log("Storage failed: " + e);
									alert('Please click revert to reload...');
								}
							},false);//end of _gimg.addEventListener
							
							var ajax = new XMLHttpRequest();
							ajax.upload.addEventListener('progress',function(evt){
								 
							},false);//end of ajax.upload.addEventListener
							
							ajax.upload.addEventListener("load", function(evt) {
							}, false);
							
							ajax.upload.addEventListener("error", function (evt) {
								alert("There was an error attempting to upload the file.");
							}, false);
							
							ajax.upload.addEventListener("abort", function(evt) {
								alert("The upload has been canceled by the user or the browser dropped the connection.");
							}, false);
							
							console.log('saving...');
							ajax.open("POST", '/main/save_canvas_file?filename='+_file, false);
							ajax.setRequestHeader('Content-Type', 'application/upload');
							ajax.send(dataurl);
						}//end of success
						
					});//end of ajax-set_saved_edited_photos
					
				}//end of if typeof storageFiles.imgObject
				
				return false;
			});
			//end of myEditor's feature
			
			$('#close_modal, #xclose').live('click',function(){
				localStorage.clear();
				$('#modal_bckgrnd,#modal_container').animate({opacity:0},'slow','linear',function(){$(this).remove();});
				return false;
			});
			
			return this;
		};
		//end of $.fn.myEditor
		
	})(jQuery);
	
	$('#cancel').live('click',function(){
		$('#modal_bckgrnd,#modal_container').animate({opacity:0},'slow','linear',function(){$(this).remove();});
		return false;
	});
	 
	
	function inline(){
		$('.modal').clone(true).appendTo('#modal_inner');
		modal_position();
	}	
	
	function deletePages () {
	 	modal_position();
	 			
	}
	
	function sendMessage(){
		 
		$.ajax({
			url 	: 	'main/fb_message',
			data 	:	{'ret':'send'},
			type	:	'post',
			success	:	function(res){
				var _obj = $.parseJSON(res);
			 	$('#modal_inner').append(_obj.data).fadeIn('slow');
			 	
				modal_position();
				
				var w = $('#modal_inner').width() / 2;
				var wt = $('#msg_container').width() / 2;
				var _left = w - wt;
				 
				
				var _friendname;
				var msg ="";
					msg	+= 'Hi username,\n\n\n';
					msg += 'I\'m printing a photo album using images and wall post from my FB timeline.\n\n';
					msg += 'I would like to add your content to my album as well, so my album will have the perspective from you and me.\n\n\n';
					msg += 'Thanks a lot,\n\n\n';
					msg += 'You';
				$('#txtmsg').val(msg);	
				$('#f_name').keypress(function(event){
					if (event.which == 13){
						$.ajax({
							url		: 'main/get_fb_names',
							type	: 'post',
							data	: {' first_name':$('#f_name').val()},
							success	: function(res){
								var _obj = $.parseJSON(res);
								if (_obj.status == 0){
									var friends = _obj.data.split(';');
									$('#sel_friends').empty();
									$.each(friends,function(i,elem){
										var _arr = elem.split(':');
										var imgUrl = 'https://graph.facebook.com/'+_arr[0]+'/picture';
										$('#sel_friends').append('<li id="'+_arr[0]+'"><img src="'+imgUrl+'" width="20">'+_arr[1]+'<input type="checkbox" id="'+_arr[0]+'" class="float_right"/></li>');
										$('#sel_friends').fadeIn('slow');															
									});
									
									var _val;
									$.cookie('_friendid',null);
									$("ul#sel_friends li").each(function() {
										$(this).live('click',function(){
											var _fbid = $(this).attr('id');
											console.log(_fbid);
											 
											_friendname = $(this).text();
											$('#f_name').val($(this).text());
											$.cookie('_friendid',$(this).attr('id'));
											$('input[type="checkbox"]#'+_fbid).toggle(
												function(e){																			 
													$(this).attr('checked', 'true');
													e.preventDefault(); 
												},function(e){    
													$(this).attr('checked', 'false');
													e.preventDefault(); 
											});//end of $('input[type="checkbox"]
											 
											var _owner;	
											FB.api($.cookie('hardcover_fbid'),
												function(res){
													_owner = res.name;
													console.log(res.name);
													var msg1 = "";
														msg1 += 'Hi '+_friendname+',\n\n\n';
														msg1 += 'I\'m printing a photo album using images and wall post from my FB timeline.\n\n';
														msg1 += 'I would like to add your content to my album as well, so my album will have the perspective from you and me.\n\n\n';
														msg1 += 'Thanks a lot,\n\n\n';
														msg1 += res.name;
													$('#txtmsg').val(msg1);
												}
											);//end of FB.api
										}).animate({'opacity' : 1}).hover(
											function() {
												$(this).animate({'opacity' : .8});
											}, function() {
												$(this).animate({'opacity' : 1});
										});//end of $(this).live('click'
										
									});//end of $("ul#sel_friends li")
									
									$("ul#sel_friends").mouseleave(function(e) {
										$('#sel_friends').fadeOut('slow');
									});
								}//end of if _obj.status
								
							}//end of success
							
						});//end of $.ajax
						event.preventDefault();
						
					}//end of if event.which
				}).focus(function(){
					$('#sel_friends').fadeOut('slow');
				}).click(function(){
					$('#sel_friends').fadeOut('slow');
				});//end of $('#f_name')
				
				$('#msg_container').css({opacity:1,'margin-left':_left});								   																															
				
			    $('#submit_form').click(function(event){		
					var _userid = $.trim($.cookie('_friendid'));
					$('#sel_friends').fadeOut('slow');
					post_to_timeline( _userid );	
					$.ajax({
						url 	: 'main/friends_being_requested_for_fbdata',
						type	: 'post',
						data	: {'friends_fbid':_userid},
						sucess	: function(){},
						error	: function(){}
					});
					event.preventDefault();
			    });//end of $('#submit_form')
			    
				$('#cancel_form').live('click',function(){
					$('#modal_bckgrnd,#modal_container').animate({opacity:0},'slow','linear',function(){$(this).remove();});
					$('#sel_friends').fadeOut('slow');
					return false;
				});
			}//end of success 
		});//end of $.ajax 
	}//end of function sendMessage 
		
	function friendSelector(){
		$.ajax({
			url 	: 	'main/fb_message',
			data 	:	{'ret':'friends'},
			type	:	'post',
			success	:	function(res){
				var _obj = $.parseJSON(res);
				$('#modal_inner').append(_obj.data);
				modal_position();
				var w = $('#modal_inner').width() / 2;
				var wt = $('#msg_container_bottom').width() / 2;
				var _left = w - wt;
				$('#msg_container_bottom').css({opacity:1,'margin-left':_left});
			}
		});
	}//end of function friendSelector
	
	function post_to_timeline(_userid){
		var _infoid = $.cookie('hardcover_book_info_id');						   	   	
		var _token = $.cookie('hardcover_token');
		checkPermissions();		   
		var _msg = $('#txtmsg').val();
		//var _fb = $.base64Decode(_userid);
		
		FB.api(_userid+'/feed?access_token='+_token, 'post',
		{
			message	:	$('#txtmsg').val(),
			url		:	'hardcover.me',
			link	: 	'http://dev.hardcover.me/main/share_url?book_info_id='+_infoid+'&rated='+_userid,
			picture :	'https://dev.hardcover.me/images/slide2/HardCover_logo.png'
		},function(res){
			console.log(res);
			$('#modal_bckgrnd,#modal_container').animate({opacity:0},'slow','linear',function(){$(this).remove();});
		});//end of FB.api
		 
	}//end of function post_to_timeline
	
	function checkPermissions() {
		FB.api('me/permissions', function(res) {
			var perms = ['user_photos','friends_photos','read_stream','publish_stream','publish_actions'];
			$.each(perms, function(i, perm) {
				if(!res || !res.data[0] || !(perm in res.data[0])) {
					logIn();
				}
			});
		});
	}
	
	function logIn(){
		FB.login(function(response) { 
			if (response.authResponse){  
				var _userid = $('#fname').val();
				post_to_timeline(_userid);						
			}
		}, {scope:'user_photos,friends_photos,read_stream,publish_stream,publish_actions'});
	}
 
	
	$.fn.extend({
		makeRequest: function(params){
			$.ajax({
				url			: params._url,
				type		: 'POST',
				cache		:false,
				dataType	: 'json',
				data		: params.postParam,
				beforeSend	: function(){},
				error		: params.onErrorFn,
				success		: params.onSuccessFn ,
				complete	: function(){}
			});
		}
	});//end of $.fn.extend
	
	function modal_position(){
		var h = $('#modal_container').height() / 2;
		var ht = $('#modal_inner').height() / 2;
		var _top = h - ht;
		$('#modal_inner').css({opacity:1,'margin-top':_top}).fadeIn();
	}
	
	function printBook(){
	 	$.ajax({
	 		url		:'album/fb_message',
	 		data	: {'ret':'print'},
	 		type	: 'post',
	 		success	: function(res){
				var _obj = $.parseJSON(res);
			 	$('#modal_inner').append(_obj.data).fadeIn('slow');
				modal_position();
				var w = $('#modal_inner').width() / 2;
				var wt = $('#msg_container_bottom').width() / 2;
				var _left = w - wt;
				$('#msg_container').css({opacity:1,'margin-left':_left});
			}//end of success
		});//end of $.ajax
	}//end of function printBook
	
	//PageFlip
	function myPageflip(width,height){
		// Dimensions of the whole book
		var _containerw = $('#edit_main_book').innerWidth();
		var _containerh = $('#edit_main_book').innerHeight();
		
		var BOOK_WIDTH = width;
		var BOOK_HEIGHT = height;
		
		// Dimensions of one page in the book
		var PAGE_WIDTH = Math.abs((width/2)-10);
		var MARGIN_LEFT = Math.abs((width/2));
		var PAGE_HEIGHT = height - 10;
		
	    // Vertical spacing between the top edge of the book and the papers
		var PAGE_Y = ( BOOK_HEIGHT - PAGE_HEIGHT ) / 2;
		
		// The canvas size equals to the book dimensions + this padding
		var CANVAS_PADDING = 20;
		
		var page = 0;
		var pageE = 0;
		var n = 0;
		var selPage = 0;
		var curr_page = "";
		
		// Get the HTML5 canvas tag element and store it to a static variable, this way it is easier to re call the canvas.
		var canvas = document.getElementById("pageflip-canvas");
		
		// Declare a variable to handle the 2D where we can draw a shape directly to the browser.
		var context = canvas.getContext("2d");
		
		// The mouse x and y coordinates, declaring it to 0 as origin;
		var mouse = { x: 0, y: 0 };
		
		// Declare a variable array, this will later be used to hold all the elements of a page.
		var flips = [];
		/*
		 * I used jquery on this one coz it is easier to manipulate.
		 * These will be the backbone of the layout based on the function paramerter width and height. It does compute the necessary page width and margins of the
		 * book layout and this is also the base css layout for animating the pageflip.
		*/
		$('#book').css({width:width,height:height});
		$('#pages section._left').css({height:PAGE_HEIGHT});
		$('#pages section._right').css({width:PAGE_WIDTH,height:PAGE_HEIGHT,left:MARGIN_LEFT});
		$('#pages section div.canvas_container').css({width:PAGE_WIDTH-22,height:PAGE_HEIGHT-20});
		
		$('section').filter(':first').css({width:PAGE_WIDTH,left:'10px','z-index':'1'});
		
		// Get the book element tag. this is also equal to jquery $(div).attr('id');
		var book = document.getElementById("book");
		
		// List of all the page elements in the DOM
		var pages = book.getElementsByTagName("section");
		
		// Lenght of the page left and right...
		var p_len = pages.length;
		
		/*
		 * Organize the depth of our pages and create the flip definitions
		 * In order for us to have 2 page book layout, this is where I specify the flow.
		*/
		for(var i = 0, len = pages.length; i < len; i++) {
			// the page will be positioned left
			if (i%2 == 0){
				// Give a higher z-index so that it will always shown top on the right page.
				// The base width = 0, and will progress as you animate to left and always shown atop the right page
				pages[i].style.zIndex = i+100;
			}else{
				// the page will be positioned right
				pages[i].style.zIndex = len - i;
				flips.push({
					progress	: 1, // Current progress of the flip (left -1 to right +1)
					target		: 1, // The target value towards which progress is always moving
					page		: pages[i], // The page DOM element related to this flip
					pageE		: pages[i+1],
					dragging	: false
				});
			}
		}
		
		// Resize the canvas to match the book size
		canvas.width = BOOK_WIDTH + ( CANVAS_PADDING * 2 );
		canvas.height = BOOK_HEIGHT + ( CANVAS_PADDING * 2 );
		
		// Offset the canvas so that it's padding is evenly spread around the book
		canvas.style.top = -CANVAS_PADDING + "px";
		canvas.style.left = -CANVAS_PADDING + "px";
		
		// Render the page flip 10 times a second
		var _loop = setInterval( render, 1000 / 10 );
		 
		var _lastPage = 0;
		var _ic = 0;
		var _rl;
		var _lr;
		var _pp;
		var _lclick;
		var _rclick;
		var _lastnum = 0;
		
		//This is the event of the previous button
		//On mouse down set the mouse x coorditaes equal to the page of the current width.
		$('div#fold_left p').on('mousedown',function(e){
			//Make sure that the left mouse button is clicked	
			if (e.which == 1){
				_lclick = true;
				_rclick = false;
				mouse.x = PAGE_WIDTH;
				//On the event that previous button is clicked let page minus 1 until it reaches 0. This is the equivalent value on the array.
				page -= 1;
				page < 0  ? page = 0 : page;
				//We are on the current page let the page property drag.
				flips[page].dragging = true;
				selPage = page;
				if (flips[selPage].dragging){
					//Let the canvas to have a higher index on the other page so that the animation is visible.
					canvas.style.zIndex = 100; //parseInt(pages[selPage].style.zIndex)+100;
				}
			}
		}).on('mouseup',function(e){
			//Make sure that the left mouse button is clicked
			if (e.which == 1){
				for(var i = 0; i < flips.length; i++) {
					if (flips[i].dragging){
						//flips[i].target = 1;
						//Remove current highlight on the pagination list.
						$('#pagination ul li.current').removeClass('current');
						//Store the current id of the page.
						var _p2 = $(flips[i].page).attr('id');
						//Make the current list on the pagination highlighted and remove the the previous style.
						$('li#'+_p2).addClass('current').prev().addClass('current');
					}
					flips[i].dragging = false;
					if (flips[i].dragging == false) canvas.style.zIndex = 0;					
				}
			}
		}).on('mouseenter',function(){
			$(document).off('mousemove',mouseMoveHandler).off('mousedown',mouseDownHandler).off( "mouseup", mouseUpHandler);
		}).on('mouseleave',function(){
			$(document).on('mousemove',mouseMoveHandler);
		});//end of $('div#fold_left p')
		
		//This is the event of the next button
		//On mouse down set the mouse x coorditaes equal to the page of the current width.
		//Let the page width be negative where the page is going. 
		//Page flip set up -1,0,1 meaning left center right
		$('div#fold_right p').on('mousedown',function(e){
			if (e.which == 1 ){
				_lclick = false;
				_rclick = true;
				mouse.x = - (PAGE_WIDTH);
				//let the array higher than 9 to be equal to it since we have 20 pages where the original array containes only 10 - from 0 to 9,
				page > 8 ? page = 9 : page;
				flips[page].dragging = true;
				selPage = page;
				if (flips[selPage].dragging){
					canvas.style.zIndex = 100; //parseInt(pages[selPage].style.zIndex)+100;
				}
				page += 1;
			}
		}).on('mouseup',function(e){
			if (e.which == 1 ){
				for(var i = 0; i < flips.length; i++) {
					if (flips[i].dragging){
						//flips[i+1].target = 1;
						page += 1;
						$('#pagination ul li.current').removeClass('current');
						var _p2 = $(flips[i+1].page).attr('id');
						$('li#'+_p2).addClass('current').prev().addClass('current');
						if (selPage != page ){
							page = Math.max( page - 1, 0 );
						}
					}
					flips[i].dragging = false;
					if (flips[i].dragging == false) canvas.style.zIndex = 0;					
				}
			}
		}).on('mouseenter',function(){
			$(document).off('mousemove',mouseMoveHandler).off('mousedown',mouseDownHandler).off( "mouseup", mouseUpHandler);
		}).on('mouseleave',function(){
			$(document).on('mousemove',mouseMoveHandler);
		});//end of $('div#fold_right p')
	
		//This is the pagination list when click by page number and the equivalent page is flip.
		$('div#pagination ul li').each(function(index, element) {
			//On mousedown event get the page number being clicked
			$(this).on('mousedown',function(e){
				//Get the value of the click page + 1.
				var i = index+1;
				//Get the page if even.
				i%2 == 0 ? i = i - 1 : i;
				//Divide it by 2 to be equal to the odd page and equal to the array. e.g. 1 and 2 = 0, 3 and 4 = 1 ...
				_ic = Math.round(i / 2);
				//If the current click is higher than last click then it means next.
				if (_lastPage == 0 || _ic > _lastPage){
					//We are going to left -1,0,1
					mouse.x = -(PAGE_WIDTH);
					//let the page equal to the click page number - 1. Since the page list has no 0 value and always starts at 1 that is why we minus it by 1 to be equal
					//to the array.
					page = _ic - 1;
					(_ic -1) >= 9 ? page = 9 : page;
					//Set all page before the current page to be drag = true so that all pages before it is below the current because we are using z-index.
					for(var x = 0; x < page; x++){
						flips[x].dragging = true;
					}
					selPage = page;
					if (flips[selPage].dragging){
						canvas.style.zIndex = 100;
					}
					_rl = true;
				}else{
					//We are going right.
					mouse.x = PAGE_WIDTH;
					page = _ic - 1;
					page < 0  ? page = 0 : page;
					for(var x = (_lastPage-1) ; x >= page ; x--){
						flips[x].dragging = true;
					}
					selPage = page;
					if (flips[selPage].dragging){
						canvas.style.zIndex = 100;
					}
					_lr = true;
				}
			}).on('mouseup',function(e){
				if ( $('p#_curr').hasClass('_current') ){
					$('#book_cover').addClass('hideDiv').fadeOut();
					$('#book_content').fadeIn();
					$('#my_edit').fadeIn('slow');
					$('p#_curr').removeClass('_current');
				}
				
				if (_rl == true){
					//Right to left
					for(var i = 0; i < flips.length; i++) {
						if (flips[i].dragging){
								$('#pagination ul li.current').removeClass('current');
								var _pcurr = $(flips[i+1].page).attr('id');
								$('li#'+_pcurr).addClass('current').prev().addClass('current');
								if (selPage != page){
									page = Math.max( page - 1, 0 );
								}
						}
						flips[i].dragging = false;
						if (flips[i].dragging == false) canvas.style.zIndex = 0;
						_rl = false;
					}//end for
				}else if(_lr == true){
					//Left to right	
					for(var i = 0; i < flips.length; i++) {
						if (flips[i].dragging){
							$('#pagination ul li.current').removeClass('current');
							var _p2 = $(flips[page].page).attr('id');
							$('li#'+_p2).addClass('current').prev().addClass('current');
						}
						flips[i].dragging = false;
						if (flips[i].dragging == false) canvas.style.zIndex = 0;
						_lr = false;
					}//end for
					
				}//end if _rl
				_lastPage = _ic;
			}).on('mouseenter',function(){
				$(document).off('mousemove',mouseMoveHandler).off('mousedown',mouseDownHandler).off( "mouseup", mouseUpHandler);
			}).on('mouseleave',function(){
				//$(document).on('mousemove',mouseMoveHandler).on('mousedown',mouseDownHandler).on( "mouseup", mouseUpHandler);
			});//end of $(this).on
		}).on('mouseenter',function(){
			$(document).off('mousemove',mouseMoveHandler).off('mousedown',mouseDownHandler).off( "mouseup", mouseUpHandler);
		}).on('mouseleave',function(){
			$(document).on('mousemove',mouseMoveHandler);
		});//end of $('div#pagination ul li') 
		
		/*
		 * The functions below are for handling the mouse pointer upon dragging
		 *
		*/
		function mouseMoveHandler(event) {
			// Offset mouse position so that the top of the spine is 0,0
			mouse.x = event.clientX - book.offsetLeft - ( BOOK_WIDTH / 2 );
			mouse.y = event.clientY - book.offsetTop;
		}
		
		function mouseDownHandler(event) {
			var srcEl = event.srcElement ? event.srcElement : event.target;
			if (srcEl.tagName == "IMG" || srcEl.tagName == "A" || srcEl.tagName == "INPUT" || srcEl.type == "BUTTON" || srcEl.type == "SELECT" || srcEl.tagName == "TEXTAREA" || srcEl.tagName == "SELECT"){
				//Make img elements selectable...
				return false;
			}else{
				if (Math.abs(mouse.x) < PAGE_WIDTH) {			
					if (mouse.x < 0 && page - 1 >= 0) {
						// We are on the left side, drag the previous page
						flips[page - 1].dragging = true;
						selPage=page-1;		
						$.cookie('selPage',null);
						$.cookie('selPage',selPage);					
						$('section._left').each(function(index, element) {
							if (event.type == "mousedown"){
								if ($(element).width() > 156 && $(element).position().left < 335){
									var zdx = parseInt($(element).css('z-index'));
									if (zdx < 99){
										zdx = parseInt(zdx) + 100;
										$(element).css({'z-index':zdx});									
									}								
								}
							}
						});
					} else if (mouse.x > 0 && page + 1 < flips.length) {
						// We are on the right side, drag the current page
						flips[page].dragging = true;
						selPage=page;
						$.cookie('selPage',null);
						$.cookie('selPage',selPage);
						if (event.type == "mousedown"){
							$('section._left').each(function(index, element) {
								if ($(element).width() > 320 && $(element).position().left < 12 ){
									var zdx = $(element).css('z-index');
									if (zdx > 99){
										zdx = parseInt(zdx) - 100;
										$(element).css({'z-index':zdx});
									}
								}
							});
						}
					}//end of if mouse.x
					if (flips[selPage].dragging){
						canvas.style.zIndex = 100; //parseInt(pages[selPage].style.zIndex)+100;
						//pages[selPage].style.zIndex = parseInt(canvas.style.zIndex)+1;
						//console.log(pages[selPage].style.zIndex);
					}
				}//end of if Math.abs
				
			}//end of if srcEl.tagName
			
			event.preventDefault();// Prevents the text selection cursor from appearing when dragging
		}//end of function mouseDownHandler 
		
		function mouseUpHandler(event) {
			for(var i = 0; i < flips.length; i++) {
				// If this flip was being dragged we animate to its destination
				//$(pages[i]).removeClass('current');
				if(flips[i].dragging) {
					// Figure out which page we should go to next depending on the flip directions
					if(mouse.x < 0) {
						n=i;
						flips[i].target = -1;
						if (selPage == page) {
							//$(pages[page]).removeClass('current');
							page = Math.min( page + 1, flips.length );
							$('#pagination ul li.current').removeClass('current');
							var _p2 = $(flips[i+1].page).attr('id');
							$('li#'+_p2).addClass('current').prev().addClass('current');
						}
					} else {
						n=i;
						flips[i].target = 1;
						if (selPage != page ){
							//$(pages[page]).removeClass('current');
							page = Math.max( page - 1, 0 );
							$('#pagination ul li.current').removeClass('current');
							var _p2 = $(flips[i].page).attr('id');
							$('li#'+_p2).addClass('current').prev().addClass('current');
						}
					}
				}
				flips[i].dragging = false;
				if (flips[i].dragging == false) canvas.style.zIndex = 0;
			}//end of for
		}//end of function mouseUpHandler
		//end of functions handling mouse pointer
		
		function render() {
			context.clearRect( 0, 0, canvas.width, canvas.height );
			
			for (var i = 0; i < flips.length; i++) {
				var flip = flips[i];
				
				if( flip.dragging ) {
					flip.target = Math.max( Math.min( mouse.x / PAGE_WIDTH, 1 ), -1 );
				}
				flip.progress += ( flip.target - flip.progress ) * 0.2;
				
				// If the flip is being dragged or is somewhere in the middle of the book, render it
				if(flip.dragging || Math.abs( flip.progress ) < 0.997) {
					//flip.pageE.style.left = 355 + "px";
					drawFlip(flip);
				}
			}
		}//end of function render
		
		function drawFlip(flip) {
			// Strength of the fold is strongest in the middle of the book
			var strength = 1 - Math.abs( flip.progress );
			//from forum...
			if (strength < 0.01) strength = 0.01;
			
			// Width of the folded paper
			var foldWidth = ( PAGE_WIDTH * 0.5 ) * ( 1 - flip.progress );
			var foldW = (PAGE_WIDTH) * ( 1 + flip.progress );
			
			// X position of the folded paper
			var foldX = PAGE_WIDTH * flip.progress + foldWidth;
			
			// How far the page should outdent vertically due to perspective
			var verticalOutdent = 20 * strength;
			
			// The maximum width of the left and right side shadows
			var paperShadowWidth = ( PAGE_WIDTH * 0.5 ) * Math.max( Math.min( 1 - flip.progress, 0.5 ), 0 );
			var rightShadowWidth = ( PAGE_WIDTH * 0.5 ) * Math.max( Math.min( strength, 0.5 ), 0 );
			var leftShadowWidth = ( PAGE_WIDTH * 0.5 ) * Math.max( Math.min( strength, 0.5 ), 0 );
			
			// Change page element width to match the x position of the fold
			flip.page.style.width = Math.max(foldX, 0) + "px";
			
			//Customized pages for the left side. While dragged put it on the back of the page being flipped.
			//console.log(flip.pageE);
			flip.pageE.style.width = Math.max(foldWidth-4,0) + "px";
			flip.pageE.style.left = Math.max(foldW+12,0) + "px";
			flip.pageE.style.background = '#FAFAFA';
			
			//context.save();
			context.translate( CANVAS_PADDING + ( BOOK_WIDTH / 2 ), PAGE_Y + CANVAS_PADDING );
			
			// Draw a sharp shadow on the left side of the page
			context.strokeStyle = 'rgba(0,0,0,'+(0.05 * strength)+')';
			context.lineWidth = 30 * strength;
			context.beginPath();
			context.moveTo(foldX - foldWidth, -verticalOutdent * 0.5);
			context.lineTo(foldX - foldWidth, PAGE_HEIGHT + (verticalOutdent * 0.5));
			context.stroke();
			
			// Right side drop shadow
			var rightShadowGradient = context.createLinearGradient(foldX, 0, foldX + rightShadowWidth, 0);
			rightShadowGradient.addColorStop(0, 'rgba(0,0,0,'+(strength*0.2)+')');
			rightShadowGradient.addColorStop(0.8, 'rgba(0,0,0,0.0)');
			
			context.fillStyle = rightShadowGradient;
			context.beginPath();
			context.moveTo(foldX, 0);
			context.lineTo(foldX + rightShadowWidth, 0);
			context.lineTo(foldX + rightShadowWidth, PAGE_HEIGHT);
			context.lineTo(foldX, PAGE_HEIGHT);
			context.fill();
		
			// Left side drop shadow
			var leftShadowGradient = context.createLinearGradient(foldX - foldWidth - leftShadowWidth, 0, foldX - foldWidth, 0);
			leftShadowGradient.addColorStop(0, 'rgba(0,0,0,0.0)');
			leftShadowGradient.addColorStop(1, 'rgba(0,0,0,'+(strength*0.15)+')');
			
			context.fillStyle = leftShadowGradient;
			context.beginPath();
			context.moveTo(foldX - foldWidth - leftShadowWidth, 0);
			context.lineTo(foldX - foldWidth, 0);
			context.lineTo(foldX - foldWidth, PAGE_HEIGHT);
			context.lineTo(foldX - foldWidth - leftShadowWidth, PAGE_HEIGHT);
			context.fill();
			
			// Gradient applied to the folded paper (highlights & shadows)
			var foldGradient = context.createLinearGradient(foldX - paperShadowWidth, 0, foldX, 0);
			foldGradient.addColorStop(0.35, '#fafafa');
			foldGradient.addColorStop(0.73, '#f0f0f0');
			foldGradient.addColorStop(0.9, '#fafafa');
			foldGradient.addColorStop(1.0, '#f5f5f5');
			
			context.fillStyle = foldGradient;
			context.strokeStyle = 'rgba(0,0,0,0.06)';
			context.lineWidth = 0.5;
			
			// Draw the folded piece of paper
			context.beginPath();
			context.moveTo(foldX, 0);
			context.lineTo(foldX, PAGE_HEIGHT);
			context.quadraticCurveTo(foldX, PAGE_HEIGHT + (verticalOutdent * 2), foldX - foldWidth, PAGE_HEIGHT + verticalOutdent);
			context.lineTo(foldX - foldWidth, -verticalOutdent);
			context.quadraticCurveTo(foldX, -verticalOutdent * 2, foldX, 0);
			
			context.fill();
			context.stroke();
			context.restore();
		}//end of function drawFlip
	}//end of function myPageFlip
	
	/*
	 * FB type message box (something like a popup message)
	 *
	*/
	function loadPopup(){
		$("#backgroundPopup").css({"opacity": "0.3"});
		$("#backgroundPopup").fadeIn("fast", function(){
			$("#popupContact").fadeIn("slow");
		});
		//$("#popupContact").fadeIn("slow");
		//FB.Canvas.scrollTo(0,0);
	}
	
	function customLoad(){
		$("#backgroundPopup").css({"opacity": "0.3"});
		$("#backgroundPopup").fadeIn("fast", function(){
			$("#popup_load").fadeIn("slow");
		});
		//$("#popupContact").fadeIn("slow");
		//FB.Canvas.scrollTo(0,300);
	}
	
	function disablePopup(){
		$("#popupContact").fadeOut("slow", function(){
			$("#backgroundPopup").fadeOut("fast").remove();
			$("#popupContact").remove();
		});
		$("#popup_load").fadeOut("fast", function(){
			$("#backgroundPopup").fadeOut("fast").remove();
		});
	}
	
	function centerPopup(top){
		var windowWidth = document.documentElement.clientWidth;
		var windowHeight = document.documentElement.clientHeight;
		var popupHeight = $("#popupContact").height();
		var popupWidth = $("#popupContact").width();
		popupHeight >1000 ? popupHeight = 1000 : popupHeight; 
		$("#popupContact").css({"z-index":"99999","position": "absolute","top": top+"px","left": windowWidth/2-popupWidth/2});
		$("#popup_load").css({"z-index":"99999","position": "absolute","top": top+"px","left": windowWidth/2-popupWidth/2});
		$("#backgroundPopup").css({"height": windowHeight});
		FB.Canvas.scrollTo(0,top);
	}
	
	function showComMessage(message, type, title, eventBinding,top){
		$(body).append('<div div="backgroundPopup"></div>');
		$('#backgroundPopup').append('<div div="backgroundPopup"></div>');
		var messageStr = "<span class='" + type + "'>" + message + "</span>";
		if( undefined == title ){
			title = "";
		}
		//var buttonStr = '<input type="button" value="Ok" class="s_btn"/>';
		var messageBoxStr = '<a id="popupContactClose">Close</a><div id="popup_head"><h1>'+title+'</h1></div>  <div id="personal">'
					+ messageStr + '</div>';
			messageBoxStr;
		$("#popupContact").html( messageBoxStr);
		var mtop = (top == 0 || top == '' || top == undefined) ? 300 : top;
		centerPopup(mtop);
		loadPopup();
		if( eventBinding != true ){
			$(".c_btn, .s_btn, #popupContactClose").unbind('click');
			$(".c_btn, .s_btn, #popupContactClose").bind('click', disablePopup );
		}
	}
	
	function showLoading(param){    
		var messageBoxStr = "<p>"+param+"</p>";
		$("#popup_load").html( messageBoxStr);
		centerPopup(300);
		customLoad();
	}
	//end FB type message box
	
	(function($) {
		/**
		 * Show modal or pop-up screen
		 *
		 */
		$.fn.myModal = function (pop_name) {
			var _html = "";
			pop_name = $.trim(pop_name).replace(/\s */gi, "");
			this.each(function () {
				_html = "<div id=\"modal_container\""+ (pop_name.length > 0 ? " class=\""+ pop_name.toLowerCase() +"\"" : "") +">\n&nbsp;";
				//_html += "\n";
				_html += "<div id=\"modal_inner\" style=\"overflow:hidden\"></div>\n</div>\n";
				_html += "<div id=\"modal_bckgrnd\">";
				_html += "</div>";
				$("body").prepend(_html);
				$("#modal_container").css({"width":$(window).width(),"height":$(window).height()});
				$("#modal_bckgrnd").css({"width":$(window).width(),"height":$(window).height()});
				$("#modal_bckgrnd").css({"opacity":1}).animate({opacity:.7}, "slow", function() {
					$("#cancel").on("click", function() {
						$("#modal_container, #modal_bckgrnd").animate({opacity:0},"slow","linear",function(){$(this).remove();});
						return false;
					});
				});
			});
			return this;
		};
		
		/*$.fn.myModal.defaults = {
			inline			: false,
			sendMessage		: false,
			friendSelector	: false,
			deletePages		: false,
			printBook		: false
		};*/
		
	})(jQuery);
	
	/*$("a").animate({"opacity":1}).hover(function() {
		$(this).animate({"opacity":0.5});
	}, function() {
		$(this).animate({"opacity":1});
	});*/

	/* ========== Add Scroll on click on these links "Collaborative Books, Books Friends Created, Books I've Created" ==========*/
	$('.js-books-tables').on('click', function (e) {
		e.preventDefault();
		//var target = this.hash,
		var $target = $("#album_table");
		$('html, body', window.parent.document).stop().animate({
			'scrollTop': $target.offset().top
		}, 900, 'swing', function () {
			//window.location.hash = target;
		});

		return false;
		//alert($target.offset().top);
		//$target.css('border','1px solid #000');
	});
 	
 	/* ========== Cover Tab Scripts ========== */
 	function loadBookAndAuthorName() {

		var _obj = '';
		$.ajax({
			url : "cover/getCoverInfo/"+ $.cookie("hardcover_book_info_id") + "/front",
			success :  function(res){
				$("#cover_title").css({display:"block"});
				$("#cover_author").css({display:"block"});

				_obj = $.parseJSON(res);

				$("#cover_title").val(_obj.cover.book_name);
				$("#cover_author").val(_obj.cover.author);

			}
	});
}

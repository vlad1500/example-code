// JavaScript Document

var pagenum;
var xnum = 0;
var wd = (book_width/4) - 15;
var act_wd = book_width;
var hd = book_height;	
var html;
var htImg=0;
var imgPic = hd * .1;
__pagenum = '';

(function ($) {
	
	  $.fn.pageLayout = function (settings) {
	
		var opts =  $.extend({},$.fn.pageLayout.defaults, settings);
		xnum = opts.pindex;
		//if(opts.isObject) 		
		//var book = $.wowBook("#pages");		
		
		if (opts.isObject){			
			var isContinue = false
			var _layout;
			
			$('#pages').empty();
			//$('#pages').append("<div id='cover'></div><div class='pagefx'></div>");
			
//			if (opts.pagenum > 0){
//				book.removePages(opts.pagenum, -1);
//				book.updateBook(true);
//			}
			
			$.each(opts.object.book_pages,function(i,el){

				//if (opts.divId == el.fbdata.id){
					//isContinue = true;
				opts.divId == el.fbdata.id ? _layout = opts.layout : _layout = el.page_layout;
					
				if (_layout == 1){	
					
					if (el.fbdata.height > hd){
						
						if ( el.connection == 'statuses'){
							var _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>';
						}else{
							var _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="100%" class="editable"/>';
						}
							html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
							html +='<div id="div-'+el.fbdata.id+'" class="float_left img_layout1" style="height:100%">';
							html +=_msg;											
							html +='</div><!--End of Image-->';
							html +='<ul id="ul-'+el.fbdata.id+'" class="float_left comment_layout1">';																
							html +='</ul>';    										  													
							html +='</div>';   
						
						xnum++;
						pagenum = setPageDetails (xnum, _layout, pagenum, 1,  el.fbdata.id);
					}else{
						
						var imght = el.fbdata.height;
						if ( el.connection == 'statuses'){
							var _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>';
						}else{
							var _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="'+imght+'" class="editable"/>';
						}	
						
							html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
							html +='<div id="div-'+el.fbdata.id+'" class="float_left img_layout1" style="height:'+imght+'">';
							html +=_msg;											
							html +='</div><!--End of Image-->';
							html +='<ul id="ul-'+el.fbdata.id+'" class="float_left comment_layout1">';																
							html +='</ul>';    										  													
							html +='</div>';   							
						
						xnum++;
						pagenum = setPageDetails (xnum, _layout, pagenum, 1,  el.fbdata.id);					
						
					}					
					//if (book === undefined) {
					$('#pages').append(html);				
					//}else{
					//	book.insertPage(html,true);
					//	book.updateBook(true);
					//}
									
					if (el.comment.length != 0)					
					fill_layout(el.comment,el.fbdata.id,opts.pagenum,true,_layout);
			
				}else if (_layout == 2){	
				
					if (el.fbdata.height > hd){
						
						if ( el.connection == 'statuses'){
							var _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>';
						}else{
							var _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="100%" class="editable"/>';
						}
							html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
							html +='<ul id="ul-'+el.fbdata.id+'" class="float_left comment_layout2">';																
							html +='</ul>';
							html +='<div id="div-'+el.fbdata.id+'" class="float_right img_layout2" style="height:100%">';
							html +=_msg;											
							html +='</div><!--End of Image-->';					    										  													
							html +='</div>';  
						
						xnum++;
						pagenum = setPageDetails (xnum, _layout, pagenum, 1,  el.fbdata.id);
					}else{
						
						var imght = el.fbdata.height;
						if ( el.connection == 'statuses'){
							var _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>';
						}else{
							var _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="'+imght+'" class="editable"/>';
						}	
						
							html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
							html +='<ul id="ul-'+el.fbdata.id+'" class="float_left comment_layout2">';																
							html +='</ul>';    										  													
							html +='<div id="div-'+el.fbdata.id+'" class="float_right img_layout2" style="height:'+imght+'">';
							html +=_msg;											
							html +='</div><!--End of Image-->';
							html +='</div>'; 							
						
						xnum++;
						pagenum = setPageDetails (xnum, _layout, pagenum, 1,  el.fbdata.id);					
						
					}					//if (book === undefined) {
						$('#pages').append(html);				
					//}else{
					//	book.insertPage(html,true);
					//	book.updateBook(true);
					//}
			
					if (el.comment.length != 0)					
					fill_layout(el.comment,el.fbdata.id,opts.pagenum,true,_layout);
				
									
				}else if (_layout == 3){	
					
				}else if (_layout == 4){	
					
					if (el.fbdata.height > hd){
						
						if ( el.connection == 'statuses'){
							var _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>';
						}else{
							var _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="100%" class="editable"/>';
						}
							html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
							html +='<div id="div-'+el.fbdata.id+'" class="img_layout4" style="height:100%">';
							html +=_msg;																    										  				
							html +='<ul id="ul-'+el.fbdata.id+'" class="comment_layout4">';																
							html +='</ul>';									
							html +='</div><!--End of Image-->';						
							html +='</div>';   
						
						xnum++;
						pagenum = setPageDetails (xnum, _layout, pagenum, 1,  el.fbdata.id);
					}else{
						
						var imght = el.fbdata.height;
						
						if ( el.connection == 'statuses'){
							var _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>';
							
						}else{
							var _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="'+imght+'" class="editable"/>';
						}	
						
							html = '<div id="'+el.fbdata.id+'" class="pagefx">';																			  													
							html +='<div id="div-'+el.fbdata.id+'" class="img_layout4" style="height:'+imght+'">';
							html +=_msg;											
							html +='</div><!--End of Image-->';
							html +='<ul id="ul-'+el.fbdata.id+'" class="comment_layout4">';																
							html +='</ul>';					
							html +='</div>';   
						
						xnum++;
						pagenum = setPageDetails (xnum, _layout, pagenum, 1,  el.fbdata.id);					
						
					}					//if (book === undefined) {
						$('#pages').append(html);				
					//}else{
					//	book.insertPage(html,true);
					//	book.updateBook(true);
					//}
					

					htImg = hd; //$('#div-'+el.fbdata.id).height()

					//var __id = _msg.attr('id');
					////console.log($('#div-'+el.fbdata.id).height());
					
					if (el.comment.length != 0)											
						fill_layout(el.comment,el.fbdata.id,opts.pagenum,true,_layout,htImg);
					
				
				}else{	
				
					if (opts.pagenum > 0){
						book.removePages(opts.pagenum, -1);
						book.updateBook(true);
					}
						
				}
				
			 // }//end of divId
			  
			});//End of each object
			
		}else{
			
			if (opts.layout == 1){	
		
			if (opts.fbdata.height > hd){
				
				if ( opts.connection == 'statuses'){
				 	var _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>';
				}else{
					var _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" height="100%" class="editable"/>';
				}
					html = '<div id="'+opts.fbdata.id+'" class="pagefx">';															
					html +='<div id="div-'+opts.fbdata.id+'" class="float_left img_layout1" style="height:100%">';
					html +=_msg;											
					html +='</div><!--End of Image-->';
					html +='<ul id="ul-'+opts.fbdata.id+'" class="float_left comment_layout1">';																
					html +='</ul>';    										  													
					html +='</div>';   
				
				xnum++;
				pagenum = setPageDetails (xnum, opts.layout, pagenum, 1,  opts.fbdata.id);
			}else{
				
				var imght = opts.fbdata.height;
				if ( opts.connection == 'statuses'){
				 	var _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>';
				}else{
					var _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" height="'+imght+'" class="editable"/>';
				}	
				
					html = '<div id="'+opts.fbdata.id+'" class="pagefx">';															
					html +='<div id="div-'+opts.fbdata.id+'" class="float_left img_layout1" style="height:'+imght+'">';
					html +=_msg;											
					html +='</div><!--End of Image-->';
					html +='<ul id="ul-'+opts.fbdata.id+'" class="float_left comment_layout1">';																
					html +='</ul>';    										  													
					html +='</div>';   							
				
				xnum++;
				pagenum = setPageDetails (xnum, opts.layout, pagenum, 1,  opts.fbdata.id);					
				
			}				
			
			$('#pages').append(html);				
	
			fill_layout(opts.comment,opts.fbdata.id,opts.pagenum,false,opts.layout);
	
		}else if (opts.fbdata == 2){	
		
			if (opts.fbdata.height > hd){
				
				if ( opts.connection == 'statuses'){
				 	var _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>';
				}else{
					var _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" height="100%" class="editable"/>';
				}
				var html = '<div id="'+opts.fbdata.id+'" class="pagefx">';															
					html +='<ul id="ul-'+opts.fbdata.id+'" class="float_left comment_layout2">';																
					html +='</ul>';
					html +='<div id="div-'+opts.fbdata.id+'" class="float_right img_layout2" style="height:100%">';
					html +=_msg;											
					html +='</div><!--End of Image-->';					    										  													
					html +='</div>';  
				
				xnum++;
				pagenum = setPageDetails (xnum, opts.layout, pagenum, 1,  opts.fbdata.id);
			}else{
				
				var imght = opts.fbdata.height;
				if ( opts.connection == 'statuses'){
				 	var _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>';
				}else{
					var _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" height="'+imght+'" class="editable"/>';
				}	
				
				var html = '<div id="'+opts.fbdata.id+'" class="pagefx">';															
					html +='<ul id="ul-'+opts.fbdata.id+'" class="float_left comment_layout2">';																
					html +='</ul>';    										  													
					html +='<div id="div-'+opts.fbdata.id+'" class="float_right img_layout2" style="height:'+imght+'">';
					html +=_msg;											
					html +='</div><!--End of Image-->';
					html +='</div>'; 							
				
				xnum++;
				pagenum = setPageDetails (xnum, opts.layout, pagenum, 1,  opts.fbdata.id);					
				
			}				
			
			$('#pages').append(html);
	
			fill_layout(opts.comment,opts.fbdata.id,opts.pagenum,false,opts.layout);
		
							
		}else if (opts.layout == 3){	

			if (opts.pagenum > 0){
			}
			
			
		}else if (opts.layout == 4){	
			
			if (opts.fbdata.height > hd){
				
				if ( opts.connection == 'statuses'){
				 	var _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>';
				}else{
					var _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" height="100%" class="editable"/>';
				}
				var html = '<div id="'+opts.fbdata.id+'" class="pagefx">';															
					html +='<div id="div-'+opts.fbdata.id+'" class="img_layout4" style="height:100%">';
					html +=_msg;											
					html +='</div><!--End of Image-->';					    										  				
					html +='<ul id="ul-'+opts.fbdata.id+'" class="comment_layout4">';																
					html +='</ul>';														
					html +='</div>';   
				
				xnum++;
				pagenum = setPageDetails (xnum, opts.layout, pagenum, 1,  opts.fbdata.id);
			}else{
				
				var imght = opts.fbdata.height;
				if ( opts.connection == 'statuses'){
				 	var _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>';
				}else{
					var _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" height="'+imght+'" class="editable"/>';
				}	
				
				var html = '<div id="'+opts.fbdata.id+'" class="pagefx">';																			  													
					html +='<div id="div-'+opts.fbdata.id+'" class="img_layout4" style="height:'+imght+'">';
					html +=_msg;											
					html +='</div><!--End of Image-->';
					html +='<ul id="ul-'+opts.fbdata.id+'" class="comment_layout4">';																
					html +='</ul>';					
					html +='</div>';   
				xnum++;
				pagenum = setPageDetails (xnum, opts.layout, pagenum, 1,  opts.fbdata.id);					
				
			}				
			
			$('#pages').append(html);
			
			htImg = hd;
	
			fill_layout(opts.comment,opts.fbdata.id,opts.pagenum,false,opts.layout,htImg);
		
		
		}else{	
		
			if (opts.pagenum > 0){
				//book.removePages(opts.pagenum, -1);
				//book.updateBook(true);
			}
				
		}	
		
	  	}//end of opts.isObject
		
						
		
		//if(opts.isObject) {		
//	
//			var _back = '<div class="pagefx"></div>';		
//			
//			_back += '<div id="back_cover"></div>';
//
//			$('#pages').append(_back);
//			
//			$('#pages').wowBook({
//				 height : book_height
//				,width  : book_width
//				,centeredWhenClosed : false
//				,hardcovers : true
//				,turnPageDuration : 500
//				//,numberedPages : [2,-3]
//				,flipSound     	  : false
//				,transparentPages : true
//				,updateBrowserURL : true
//				,pageNumbers	  :	false
//				,controls : {
//						zoomIn    : '#zoomin',
//						zoomOut   : '#zoomout',
//						next      : '#fold_right',
//						back      : '#fold_left',
//						first     : '#first',
//						last      : '#last',
//						slideShow : '#slideshow'
//					}
//				,onShowPage : function(){
//						
//					}	
//			}).css({'display':'none', 'margin':'auto'}).fadeIn(1000);			
//			$.wowBook("#pages").showPage(opts.pagenum,true);
//			
//		}	
//		
//		$('#button_container').width($('#pages').width()).css({ 'left' : $('#pages').offset().left - 20 });
//									
//		$('div.wowbook-page').each(function(index, element) { 									
//			$(this).live('mouseenter',function(){ 
//				//var curr_id = $(this).attr('id');										
//				curr_id = $(this).children().get(0).id;
//				$.cookie('div_',curr_id);
//				$(this).hasClass('wowbook-right') ?  cls = '_right' : cls = '_left'; //$('#'+curr_id).attr('class');
//				var _this = $(this);
//				////console.log(cls);
//				$('#'+curr_id).addClass('current'); 
//				$('.'+cls+'_icon').fadeIn('slow');			
//				$('.'+cls+'_icon').hover(function(){
//					$(this).css({'opacity':1}).stop(true);		
//				}).click(function(){	
//						var _id = $(this).children('div').get(0).id;	
//						var _w	= $('#page-layout_option').width();	
//						var _w2 = _w / 2;				
//					if (cls == "_left"){
//						$('#page-layout_option').css({left: $('#'+_id).offset().left - _w2 }).fadeIn('slow');
//					}else{
//						$('#page-layout_option').css({left: ($('#'+_id).offset().left - _w) - (_w2 - 40)  }).fadeIn('slow');
//					}
//				});							
//			}).live('mouseleave',function(){ 				
//				curr_id = $(this).children().get(0).id;
//				$('#'+curr_id).removeClass('current'); 										
//				$('.'+cls+'_icon').fadeOut('slow');					
//			}).live('mousedown',function(){
//				$('#page-layout_option').fadeOut('slow');
//			});
//		});
		
		__pagenum = pagenum;
		console.log(__pagenum);
	  
		return this;
	  };	  
	 
	 function fill_layout(comment,fbdataid,_page,isBook,layout,imgContainer){
										
		var ht = 0;
		var ht_ = 0;
		var htn_ = 0;
		var arr = [];
		var arr_ = [];
		var is_nxt_page = false;
		var is_col_nxt = false;	
		var p_layout = 1;	
		
		//if (isBook){ var book = $.wowBook('#pages'); }
		
		var _wd = wd-8;	
		var cmt_cont;

		$.each(comment,function(x,cmt){

			var _nme = cmt.comment_obj.from.name;
			var _pic = 'https://graph.facebook.com/'+ cmt.comment_obj.from.id +'/picture?type=small';
				

				if (layout == 4){						
					$('ul#ul-'+fbdataid).append('<li id="comment_'+x+cmt.book_comment_id+'_'+x+'" style="width:'+(act_wd-10)+'px;min-height:40px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;width:15%;"><img src="'+ _pic +'" class="float_left"></div><p style="font-size:'+font_size+'px" class="comment_by">'+_nme+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ cmt.comment_obj.message +'</p></li>');																																																	
					ht = ht + parseInt($('li#comment_'+x+cmt.book_comment_id+'_'+x).height()) + 10.5;
					ht = ht + imgContainer;																
				}else{
							
					$('ul#ul-'+fbdataid).append('<li id="comment_'+x+cmt.book_comment_id+'_'+x+'" style="width:'+_wd+'px;min-height:40px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ _pic +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+_nme+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ cmt.comment_obj.message +'</p></li>');								
                          
					ht = ht + $('li#comment_'+x+cmt.book_comment_id+'_'+x).height() + 10.5;						

				}
				  
				pagenum = setPageDetails (xnum, layout, pagenum, 2,  cmt.book_comment_id);

				if (ht >= hd){													
					var _newMsg = cmt.comment_obj.message.replace(";", " ");	
					$('#ul-'+fbdataid+' li#comment_'+x+cmt.book_comment_id+'_'+x).remove();													
					arr.push({ 'data' :  _pic + ';' + _nme + ';' + _newMsg + ';' + cmt.book_comment_id });	
					is_nxt_page = true;													
				}//End of ht > hd
			
		});	//End of each el.comment
		
		if (is_nxt_page){ 
					   
			var html = '<div id="nxt-'+fbdataid+'" class="pagefx">';															
			html +='<ul id="ul-'+fbdataid+'_1" class="float_left comment_layout1" style="width:'+_wd+'px">';																
			html +='</ul>';
			html +='<ul id="ul-'+fbdataid+'_2" class="float_left comment_layout1" style="width:'+_wd+'px;margin:0 0 0 10px;">';																
			html +='</ul>';						    										  													
			html +='</div>';											
			
			$('#pages').append(html);
			
			xnum++;
			
			$.each(arr,function(xx,msg){
				
				var _msg = msg.data.split(';');						
				$('ul#ul-'+fbdataid+'_1').append('<li id="comment_'+xx+'_1'+_msg[3]+'_1" style="width:'+_wd+'px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ _msg[0] +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+_msg[1]+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ _msg[2] +'</p></li>');																						
				
				pagenum = setPageDetails (xnum, layout, pagenum, 2,  _msg[3]);
				
				ht_ = ht_ + $('li#comment_'+xx+'_1'+_msg[3]+'_1').height() + 10.5;
				////console.log($('li#comment_'+xx+'_1'+msg.book_comment_id+'_'+x+'_1').height());
				if (ht_ > hd){													
					$('#ul-'+fbdataid+'_1'+' li#comment_'+xx+'_1'+_msg[3]+'_1').remove();
					arr_.push({ 'data' :  _msg[0] + ';' + _msg[1] + ';' + _msg[2] +';'+ _msg[3] });
					is_col_nxt = true;
				}//end of ht_ > hd	
				
			});//End of each arr																
			
			if (is_col_nxt){
				
				var arrn_ = [];
				var is_coln_nxt = false;
				
				$.each(arr_ ,function(xxx,msg2){	
								
					var gmsg = msg2.data.split(';');
					
					$('ul#ul-'+fbdataid+'_2').append('<li id="comment_'+xxx+'_2'+ gmsg[3] +'_2" style="width:'+_wd+'px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ gmsg[0] +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+gmsg[1]+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ gmsg[2] +'</p></li>');					
					
					pagenum = setPageDetails (xnum, p_layout, pagenum, 2,  gmsg[3]);
					
					htn_ = htn_ + $('#comment_'+xxx+'_2'+ gmsg[3] +'_2').height() + 10.5;
					if (htn_ > hd){													
						$('ul#ul-'+fbdataid+'_2'+' li#comment_'+xxx+'_2'+gmsg[3]+'_2').remove();
						arrn_.push({ 'data' :  gmsg[0] + ';' + gmsg[1] + ';' + gmsg[2] +';'+ gmsg[3]});
						is_coln_nxt = true;
					}//end of ht_ > hd																												
						
				});//End of each arr_
				
				if (is_coln_nxt){

					var arr_n = [];
					var ht_n = 0;
					var is_nn_col = false;
					
					var html = '<div id="next-'+fbdataid+'_1" class="pagefx">';															
					html +='<ul id="ul-'+fbdataid+'_1A" class="float_left comment_layout1" style="width:'+_wd+'px">';																
					html +='</ul>';
					html +='<ul id="ul-'+fbdataid+'_2A" class="float_left comment_layout1" style="width:'+_wd+'px;margin:0 0 0 10px;">';																
					html +='</ul>';						    										  													
					html +='</div>';											

					$('#pages').append(html);	
					
					xnum++;
					
					$.each(arrn_ ,function(ix,msg_){														
						var gmsg = msg_.data.split(';');																												
						$('ul#ul-'+fbdataid+'_1A').append('<li id="comment_'+ix+'_1A'+ gmsg[3] +'_1A" style="width:'+_wd+'px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ gmsg[0] +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+gmsg[1]+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ gmsg[2] +'</p></li>');										
						
						pagenum = setPageDetails (xnum, layout, pagenum, 2,  gmsg[3]);
						
						ht_n = ht_n + $('li#comment_'+ix+'_1A'+ gmsg[3] +'_1A').height() + 10.5;
						
						if (ht_n > hd){
							$('ul#ul-'+fbdataid+'_1A'+' li#comment_'+ix+'_1A'+ gmsg[3] +'_1A').remove();
							arr_n.push({ 'data' :  gmsg[0] + ';' + gmsg[1] + ';' + gmsg[2] + ';' + gmsg[3]});
							is_nn_col = true;	
						}//End of ht_n > hd
					});//End of arrn_
					
					
					if (is_nn_col){
						var h_ = 0;
						$.each(arr_n , function(ix,msg_){
							var gmsg = msg_.data.split(';');
							$('#ul-'+fbdataid+'_2A').append('<li id="comment_'+ix+'_2A'+ gmsg[3] +'_2A" style="width:'+_wd+'px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ gmsg[0] +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+gmsg[1]+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ gmsg[2] +'</p></li>');	
							h_ = h_ + $('#comment_'+ix+'_2A'+ gmsg[3] +'_2A').height()+10;
							
							pagenum = setPageDetails (xnum, layout, pagenum, 2,  gmsg[3]);
							
							if (h_ > hd){															
								$('#comment_'+ix+'_2A'+ gmsg[3] +'_2A').remove();
							}//End of h_ < hd
																					
						});//End of each arr_n
						
					}//end of is_nn_col
																	
				}//End of is_coln_nxt
				
			}//End of is_col_nxt		
															
		}//End of is_nxt_page	 
	 
	 }//End of fill_layout
	 
	 //Layout_3 
	 function fill_layout_3(_obj,_id,_page){		 
		var book = $.wowBook("#pages");
		var _isStart = false;
		
		$.each(_obj.book_pages,function(i,el){										
			var ht = 0;
			var ht_ = 0;
			var htn_ = 0;
			var arr = [];
			var arr_ = [];
			var is_nxt_page = false;
			var is_col_nxt = false;	
			var wd = book_width/4;
			var hd = book_width/2;					
														
		if (el.fbdata.id  == _id){
			_isStart = true;	
			
				var html ='<div id="div-'+el.fbdata.id+'" class="img_layout3" style="height:100%">';
				    html += '  <div id="col_layout3_left" class="float_left">';
					html +='   <div id="div-A" class="img_layout3">';
					html +='    <img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="100%" class="editable"/>';											
					html +='   </div><!--End of Image-->';	
					html +='  </div>'; 				    										  				
					html +='  <ul id="'+el.book_info_id+'_'+i+'" class="float_left comment_layout3_left">';																
					html +='  </ul>';														   
					html +=' </div><!---End of col_layout3-left-->';
					html += ' <div id="col_layout3_right" class="float_right">';
					html +='  <ul id="'+el.book_info_id+'_'+i+'" class="float_left comment_layout3_left">';																
					html +='  </ul>';
					html +='  <div id="div-B" class="img_layout3_down">';																			
					html +='   <img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="100%" class="editable"/>';											
					html +='  </div><!--End of Image-->';					    										  																		
					html +=' </div>';   					
					html +='</div>';
								
					//$('#pages').append(html);
					book.insertPage(html,true);


				$.each(el.comment,function(x,cmt){
					////console.log(cmt);
				var _nme = cmt.comment_obj.from.name;
				var _pic = 'https://graph.facebook.com/'+ cmt.comment_obj.from.id +'/picture?type=small';
															
				$('#'+el.book_info_id+'_'+i).append('<li id="comment_'+x+cmt.book_comment_id+'_'+i+'" style="width:'+wd+'px"><img src="'+ _pic +'" class="float_left"><p class="comment_by">'+_nme+'</p><p class="float_left fbcomment">'+ cmt.comment_obj.message +'</p></li>');																																											
					ht = ht + $('li#comment_'+x+cmt.book_comment_id+'_'+i).height() + 10.5;												
					
					if (ht >= hd){													
						////console.log('li#comment_'+x+el.book_info_id+'_'+i+'  '+ht);
						$('#'+el.book_info_id+'_'+i+' li#comment_'+x+cmt.book_comment_id+'_'+i).remove();													
						arr.push({ 'data' :  _pic + ';' + _nme + ';' + cmt.comment_obj.message +';'+ cmt.book_comment_id});	
						is_nxt_page = true;								
					}//End of ht > hd
				
			});	//End of each el.comment
			
			if (is_nxt_page){ 
													   
				var html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
				html +='<ul id="'+el.book_info_id+'_'+i+'_1" class="float_left comment_layout1">';																
				html +='</ul>';
				html +='<ul id="'+el.book_info_id+'_'+i+'_2" class="float_left comment_layout1">';																
				html +='</ul>';						    										  													
				html +='</div>';											
				book.insertPage(html,true);
				
				
				$.each(arr,function(xx,msg){
					var _msg = msg.data.split(';');						
					$('#'+el.book_info_id+'_'+i+'_1').append('<li id="comment_'+xx+'_1'+ _msg[3] +'_'+i+'_1" style="width:'+wd+'px"><img src="'+ _msg[0] +'" class="float_left"><p class="comment_by">'+_msg[1]+'</p><p class="float_left fbcomment">'+ _msg[2] +'</p></li>');																						
					
					ht_ = ht_ + $('li#comment_'+xx+'_1'+_msg[3]+'_'+i+'_1').height() + 10.5;
					////console.log($('li#comment_'+xx+'_1'+el.book_info_id+'_'+i+'_1').height());
					if (ht_ > hd){													
						$('#'+el.book_info_id+'_'+i+'_1'+' li#comment_'+xx+'_1'+_msg[3]+'_'+i+'_1').remove();
						arr_.push({ 'data' :  _msg[0] + ';' + _msg[1] + ';' + _msg[2] +';'+ _msg[3] });
						is_col_nxt = true;
					}//end of ht_ > hd																	
				
				if (is_col_nxt){
					
					var arrn_ = [];
					var is_coln_nxt = false;
					
					$.each(arr_ ,function(xxx,msg2){
						var gmsg = msg2.data.split(';');
						$('#'+el.book_info_id+'_'+i+'_2').append('<li id="comment_'+xxx+'_2'+ gmsg[3] +'_'+i+'_2" style="width:'+wd+'px"><img src="'+ gmsg[0] +'" class="float_left"><p class="comment_by">'+gmsg[1]+'</p><p class="float_left fbcomment">'+ gmsg[2] +'</p></li>');					
						
						htn_ = htn_ + $('#comment_'+xxx+'_2'+gmsg[3]+'_'+i+'_2').height() + 10.5;
						if (htn_ > hd){													
							$('#'+el.book_info_id+'_'+i+'_2'+' li#comment_'+xxx+'_2'+gmsg[3]+'_'+i+'_2').remove();
							arrn_.push({ 'data' :  gmsg[0] + ';' + gmsg[1] + ';' + gmsg[2] +';'+ gmsg[3]});
							is_coln_nxt = true;
						}//end of ht_ > hd																												
							
					});//End of each arr_
					
					if (is_coln_nxt){
						
						var arr_n = [];
						var ht_n = 0;
						var is_nn_col = false;
						
						var html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
						html +='<ul id="'+el.book_info_id+'_'+i+'_1A" class="float_left comment_layout1">';																
						html +='</ul>';
						html +='<ul id="'+el.book_info_id+'_'+i+'_2A" class="float_left comment_layout1">';																
						html +='</ul>';						    										  													
						html +='</div>';											
						book.insertPage(html,true);
						
						$.each(arrn_ ,function(ix,msg_){														
							var gmsg = msg_.data.split(';');																												
							$('#'+el.book_info_id+'_'+i+'_1A').append('<li id="comment_'+ix+'_1A'+gmsg[3]+'_'+i+'_1A" style="width:'+wd+'px"><img src="'+ gmsg[0] +'" class="float_left"><p class="comment_by">'+gmsg[1]+'</p><p class="float_left fbcomment">'+ gmsg[2] +'</p></li>');										
							
							ht_n = ht_n + $('#comment_'+ix+'_1A'+gmsg[3]+'_'+i+'_1A').height() + 10.5;
							if (ht_n > hd){
								$('#'+el.book_info_id+'_'+i+'_1A'+' li#comment_'+ix+'_1A'+gmsg[3]+'_'+i+'_1A').remove();
								arr_n.push({ 'data' :  gmsg[0] + ';' + gmsg[1] + ';' + gmsg[2] +';'+ gmsg[3]});
								is_nn_col = true;	
							}//End of ht_n > hd
						});//End of arrn_
						
						if (is_nn_col){
							var h_ = 0;
							$.each(arr_n , function(ix,msg_){
								var gmsg = msg_.data.split(';');
								$('#'+el.book_info_id+'_'+i+'_2A').append('<li id="comment_'+ix+'_2A'+gmsg[3]+'_'+i+'_2A" style="width:'+wd+'px"><img src="'+ gmsg[0] +'" class="float_left"><p class="comment_by">'+gmsg[1]+'</p><p class="float_left fbcomment">'+ gmsg[2] +'</p></li>');	
								h_ = h_ + $('#comment_'+ix+'_2A'+gmsg[3]+'_'+i+'_2A').height()+10;
								if (h_ > hd){															
									$('#comment_'+ix+'_2A'+gmsg[3]+'_'+i+'_2A').remove();
								}//End of h_ < hd
																						
							});//End of each arr_n
						}//end of is_nn_col
																		
					}//End of is_coln_nxt
					
				}//End of is_col_nxt
				
				});//End of each arr		
																
			}//End of is_nxt_page
			
		}else{
			if (_isStart){
			if (el.fbdata.height > hd){		
				var html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
					html +='<div id="div-'+el.fbdata.id+'" class="float_left img_layout1" style="height:100%">';
					html +='<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" height="100%" class="editable"/>';											
					html +='</div><!--End of Image-->';
					html +='<ul id="'+el.book_info_id+'_'+i+'" class="float_left comment_layout1">';																
					html +='</ul>';    										  													
					html +='</div>';   
					book.insertPage(html,true);
			
			}else{
				
				var imght = el.fbdata.height;
				var html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
					html +='<div id="div-'+el.fbdata.id+'" class="float_left img_layout1" height="'+imght+'">';
					html +='<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" class="editable"/>';											
					html +='</div><!--End of Image-->';
					html +='<ul id="'+el.book_info_id+'_'+i+'" class="float_left comment_layout1">';																
					html +='</ul>';    										  													
					html +='</div>';   
					book.insertPage(html,true);										
				
			}
	
						$.each(el.comment,function(x,cmt){
					////console.log(cmt);
				var _nme = cmt.comment_obj.from.name;
				var _pic = 'https://graph.facebook.com/'+ cmt.comment_obj.from.id +'/picture?type=small';
															
				$('#'+el.book_info_id+'_'+i).append('<li id="comment_'+x+cmt.book_comment_id+'_'+i+'" style="width:'+wd+'px"><img src="'+ _pic +'" class="float_left"><p class="comment_by">'+_nme+'</p><p class="float_left fbcomment">'+ cmt.comment_obj.message +'</p></li>');																																											
					ht = ht + $('li#comment_'+x+cmt.book_comment_id+'_'+i).height() + 10.5;												
					
					if (ht >= hd){													
						////console.log('li#comment_'+x+el.book_info_id+'_'+i+'  '+ht);
						$('#'+el.book_info_id+'_'+i+' li#comment_'+x+cmt.book_comment_id+'_'+i).remove();													
						arr.push({ 'data' :  _pic + ';' + _nme + ';' + cmt.comment_obj.message +';'+ cmt.book_comment_id});	
						is_nxt_page = true;								
					}//End of ht > hd
				
			});	//End of each el.comment
			
			if (is_nxt_page){ 
													   
				var html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
				html +='<ul id="'+el.book_info_id+'_'+i+'_1" class="float_left comment_layout1">';																
				html +='</ul>';
				html +='<ul id="'+el.book_info_id+'_'+i+'_2" class="float_left comment_layout1">';																
				html +='</ul>';						    										  													
				html +='</div>';											
				book.insertPage(html,true);
				
				
				$.each(arr,function(xx,msg){
					var _msg = msg.data.split(';');						
					$('#'+el.book_info_id+'_'+i+'_1').append('<li id="comment_'+xx+'_1'+ _msg[3] +'_'+i+'_1" style="width:'+wd+'px"><img src="'+ _msg[0] +'" class="float_left"><p class="comment_by">'+_msg[1]+'</p><p class="float_left fbcomment">'+ _msg[2] +'</p></li>');																						
					
					ht_ = ht_ + $('li#comment_'+xx+'_1'+_msg[3]+'_'+i+'_1').height() + 10.5;
					////console.log($('li#comment_'+xx+'_1'+el.book_info_id+'_'+i+'_1').height());
					if (ht_ > hd){													
						$('#'+el.book_info_id+'_'+i+'_1'+' li#comment_'+xx+'_1'+_msg[3]+'_'+i+'_1').remove();
						arr_.push({ 'data' :  _msg[0] + ';' + _msg[1] + ';' + _msg[2] +';'+ _msg[3] });
						is_col_nxt = true;
					}//end of ht_ > hd																	
				
				if (is_col_nxt){
					
					var arrn_ = [];
					var is_coln_nxt = false;
					
					$.each(arr_ ,function(xxx,msg2){
						var gmsg = msg2.data.split(';');
						$('#'+el.book_info_id+'_'+i+'_2').append('<li id="comment_'+xxx+'_2'+ gmsg[3] +'_'+i+'_2" style="width:'+wd+'px"><img src="'+ gmsg[0] +'" class="float_left"><p class="comment_by">'+gmsg[1]+'</p><p class="float_left fbcomment">'+ gmsg[2] +'</p></li>');					
						
						htn_ = htn_ + $('#comment_'+xxx+'_2'+gmsg[3]+'_'+i+'_2').height() + 10.5;
						if (htn_ > hd){													
							$('#'+el.book_info_id+'_'+i+'_2'+' li#comment_'+xxx+'_2'+gmsg[3]+'_'+i+'_2').remove();
							arrn_.push({ 'data' :  gmsg[0] + ';' + gmsg[1] + ';' + gmsg[2] +';'+ gmsg[3]});
							is_coln_nxt = true;
						}//end of ht_ > hd																												
							
					});//End of each arr_
					
					if (is_coln_nxt){
						
						var arr_n = [];
						var ht_n = 0;
						var is_nn_col = false;
						
						var html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
						html +='<ul id="'+el.book_info_id+'_'+i+'_1A" class="float_left comment_layout1">';																
						html +='</ul>';
						html +='<ul id="'+el.book_info_id+'_'+i+'_2A" class="float_left comment_layout1">';																
						html +='</ul>';						    										  													
						html +='</div>';											
						book.insertPage(html,true);
						
						$.each(arrn_ ,function(ix,msg_){														
							var gmsg = msg_.data.split(';');																												
							$('#'+el.book_info_id+'_'+i+'_1A').append('<li id="comment_'+ix+'_1A'+gmsg[3]+'_'+i+'_1A" style="width:'+wd+'px"><img src="'+ gmsg[0] +'" class="float_left"><p class="comment_by">'+gmsg[1]+'</p><p class="float_left fbcomment">'+ gmsg[2] +'</p></li>');										
							
							ht_n = ht_n + $('#comment_'+ix+'_1A'+gmsg[3]+'_'+i+'_1A').height() + 10.5;

							if (ht_n > hd){
								$('#'+el.book_info_id+'_'+i+'_1A'+' li#comment_'+ix+'_1A'+gmsg[3]+'_'+i+'_1A').remove();
								arr_n.push({ 'data' :  gmsg[0] + ';' + gmsg[1] + ';' + gmsg[2] +';'+ gmsg[3]});
								is_nn_col = true;	
							}//End of ht_n > hd
						});//End of arrn_
						
						if (is_nn_col){
							var h_ = 0;
							$.each(arr_n , function(ix,msg_){
								var gmsg = msg_.data.split(';');
								$('#'+el.book_info_id+'_'+i+'_2A').append('<li id="comment_'+ix+'_2A'+gmsg[3]+'_'+i+'_2A" style="width:'+wd+'px"><img src="'+ gmsg[0] +'" class="float_left"><p class="comment_by">'+gmsg[1]+'</p><p class="float_left fbcomment">'+ gmsg[2] +'</p></li>');	
								h_ = h_ + $('#comment_'+ix+'_2A'+gmsg[3]+'_'+i+'_2A').height()+10;
								if (h_ > hd){															
									$('#comment_'+ix+'_2A'+gmsg[3]+'_'+i+'_2A').remove();
								}//End of h_ < hd
																						
							});//End of each arr_n
						}//end of is_nn_col
																		
					}//End of is_coln_nxt
					
				}//End of is_col_nxt
				
				});//End of each arr		
																
			}//End of is_nxt_page
			
		  }//End of _isStart
			
		}//end of el.fbdata.id == _id
		
		})//End of each._obj.book_pages
		
		var _back = '<div class="pagefx"></div>';		
		book.insertPage(_back,true);
		_back = '<div id="back_cover"></div>';
		book.insertPage(_back,true);			
		book.updateBook(true);		
		book.updateBook();
		book.showPage(_page,true);	 		 
	 }//End of fill_layout_3
	 
	 function setPageDetails (_pnum, _playout, _retval, id_type, _id) {
		 
//		_pnum => page number 
//		_playout => page layout
//		_retval => global variable, always make sure this is blank on intial load or on a new change
//		id_type => 1 for fbdata_id, 2 for comment_id 
//		_id => ID value
//		
		
		var pre_ID = '';
		if (id_type == 1) // for fbdata id
			pre_ID = 'fbid_';
		else if (id_type == 2) // for comment id
			pre_ID = 'cid_';
			
		if (__pagenum.length != 0) 
			_retval = __pagenum + ',' + pre_ID + _id + ':' + _pnum + ':' + _playout;
		else 
			_retval = pre_ID + _id + ':' + _pnum + ':' + _playout;
			
		__pagenum = _retval;	
		return _retval;
	}
	  
	 $.fn.pageLayout.defaults = {
		divId  		: 0 ,
		pagenum 	: 0 ,
		comment 	: {},
		fbdata  	: {},
		layout  	: 1,
		connection 	: 'photos',
		isObject	: false,
		object  	: {},
 		pindex  	: 1
	  };
	  

})(jQuery);		
		
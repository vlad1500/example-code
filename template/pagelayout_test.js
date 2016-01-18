// JavaScript Document

var pagenum;
var xnum = 0;
var wd = 400;
var act_wd = book_width;
var hd = 300;	
var html;
var htImg=0;
var imgPic = hd * .1;
__pagenum = '';
font_size = 10;

(function ($) {
	
	  $.fn.pageLayout = function (settings) {
	
		var opts =  $.extend({},$.fn.pageLayout.defaults, settings);
		xnum = opts.pindex;
		
		//$('#pages').empty();
		//$('#pages').append("<div id='cover'></div><div class='pagefx'></div>");

		$.each(opts.object.book_pages,function(i,el){	
			var ht = 0;
			var ht_ = 0;
			var htn_ = 0;
			var arr = [];
			var arr_ = [];
			var is_nxt_page = false;
			var is_col_nxt = false;	
			var layout = 1; 
			var isMain = false;
			var _wd = wd-8;	
			var cmt_cont;
			var imgHT = 0;
			var _msg;
			var holder_=0;
			var _heightOf = 0;

			var html ='<div id="'+el.fbdata.id+'" class="pagefx">';
				html +='   <div id="div-'+el.fbdata.id+'" style="width:100%; max-height:'+hd+'px; box-sizing:border-box; position:relative; overflow:hidden;">';																								
				html +='   </div>';							
				html +='</div>';										
				$('#pages').append(html);	
				
				el.connection == 'statuses' ? _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>' : _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" class="editable"/>';
				
				$('#div-'+el.fbdata.id).append(_msg);
				
				_heightOf = $('#div-'+el.fbdata.id).height() * .80;																	
				
				if(el.connection != 'statuses' && el.comment.length > 0){ 
					$('#img-'+el.fbdata.id).imgscale({ scale : 'fill', lessenTo : _heightOf, center:true });
					var html ='<ul id="ul-'+el.fbdata.id+'" class="comment_layout4"></ul>';
						$('#'+el.fbdata.id).append(html);
				}else{
					$('#img-'+el.fbdata.id).imgscale({ scale : 'fit', center:true });	
				}
					
			//Comments		
			if (el.comment.length > 0){		 														

			$.each(el.comment,function(x,cmt){
	
				var _nme = cmt.comment_obj.from.name;
				var _pic = 'https://graph.facebook.com/'+ cmt.comment_obj.from.id +'/picture?type=small';
					
								
				$('ul#ul-'+el.fbdata.id).append('<li id="comment_'+x+cmt.book_comment_id+'_'+x+'" style="min-height:40px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ _pic +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+_nme+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ cmt.comment_obj.message +'</p></li>');								
							  
				ht = ht + $('li#comment_'+x+cmt.book_comment_id+'_'+x).height() + 10.5;						
					  
				pagenum = setPageDetails (xnum, layout, pagenum, 2,  cmt.book_comment_id);
	
					if ((ht + _heightOf) >= hd){													
						var _newMsg = cmt.comment_obj.message.replace(";", " ");	
						$('#ul-'+el.fbdata.id+' li#comment_'+x+cmt.book_comment_id+'_'+x).remove();													
						arr.push({ 'data' :  _pic + ';' + _nme + ';' + _newMsg + ';' + cmt.book_comment_id });	
						is_nxt_page = true;													
					}//End of ht > hd
				
			});	//End of each el.comment
			
			if (is_nxt_page){ 
						   
				var html = '<div id="nxt-'+el.fbdata.id+'" class="pagefx">';															
				html +='<ul id="ul-'+el.fbdata.id+'_1" class="float_left comment_layout1" style="width:48%">';																
				html +='</ul>';
				html +='<ul id="ul-'+el.fbdata.id+'_2" class="float_left comment_layout1" style="width:48%; margin:0 0 0 10px;">';																
				html +='</ul>';						    										  													
				html +='</div>';											
				
				$('#pages').append(html);
				
				xnum++;
				
				$.each(arr,function(xx,msg){
					
					var _msg = msg.data.split(';');						
					$('ul#ul-'+el.fbdata.id+'_1').append('<li id="comment_'+xx+'_1'+_msg[3]+'_1" ><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ _msg[0] +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+_msg[1]+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ _msg[2] +'</p></li>');																						
					
					pagenum = setPageDetails (xnum, layout, pagenum, 2,  _msg[3]);
					
					ht_ = ht_ + $('li#comment_'+xx+'_1'+_msg[3]+'_1').height() + 10.5;
					//////console.log($('li#comment_'+xx+'_1'+msg.book_comment_id+'_'+x+'_1').height());
					if (ht_ > hd){													
						$('#ul-'+el.fbdata.id+'_1'+' li#comment_'+xx+'_1'+_msg[3]+'_1').remove();
						arr_.push({ 'data' :  _msg[0] + ';' + _msg[1] + ';' + _msg[2] +';'+ _msg[3] });
						is_col_nxt = true;
					}//end of ht_ > hd	
					
				});//End of each arr																
				
				if (is_col_nxt){
					
					var arrn_ = [];
					var is_coln_nxt = false;
					
					$.each(arr_ ,function(xxx,msg2){	
									
						var gmsg = msg2.data.split(';');
						
						$('ul#ul-'+el.fbdata.id+'_2').append('<li id="comment_'+xxx+'_2'+ gmsg[3] +'_2" ><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ gmsg[0] +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+gmsg[1]+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ gmsg[2] +'</p></li>');					
						
						pagenum = setPageDetails (xnum, layout, pagenum, 2,  gmsg[3]);
						
						htn_ = htn_ + $('#comment_'+xxx+'_2'+ gmsg[3] +'_2').height() + 10.5;
						if (htn_ > hd){													
							$('ul#ul-'+el.fbdata.id+'_2'+' li#comment_'+xxx+'_2'+gmsg[3]+'_2').remove();
							arrn_.push({ 'data' :  gmsg[0] + ';' + gmsg[1] + ';' + gmsg[2] +';'+ gmsg[3]});
							is_coln_nxt = true;
						}//end of ht_ > hd																												
							
					});//End of each arr_
					
					if (is_coln_nxt){
	
						var arr_n = [];
						var ht_n = 0;
						var is_nn_col = false;
						
						var html = '<div id="next-'+el.fbdata.id+'_1" class="pagefx">';															
						html +='<ul id="ul-'+el.fbdata.id+'_1A" class="float_left comment_layout1" style="width:48%">';																
						html +='</ul>';
						html +='<ul id="ul-'+el.fbdata.id+'_2A" class="float_left comment_layout1" style="width:48%; margin:0 0 0 10px;">';																
						html +='</ul>';						    										  													
						html +='</div>';											
	
						$('#pages').append(html);	
						
						xnum++;
						
						$.each(arrn_ ,function(ix,msg_){														
							var gmsg = msg_.data.split(';');																												
							$('ul#ul-'+el.fbdata.id+'_1A').append('<li id="comment_'+ix+'_1A'+ gmsg[3] +'_1A" ><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ gmsg[0] +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+gmsg[1]+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ gmsg[2] +'</p></li>');										
							
							pagenum = setPageDetails (xnum, layout, pagenum, 2,  gmsg[3]);
							
							ht_n = ht_n + $('li#comment_'+ix+'_1A'+ gmsg[3] +'_1A').height() + 10.5;
							
							if (ht_n > hd){
								$('ul#ul-'+el.fbdata.id+'_1A'+' li#comment_'+ix+'_1A'+ gmsg[3] +'_1A').remove();
								arr_n.push({ 'data' :  gmsg[0] + ';' + gmsg[1] + ';' + gmsg[2] + ';' + gmsg[3]});
								is_nn_col = true;	
							}//End of ht_n > hd
						});//End of arrn_
						
						
						if (is_nn_col){
							var h_ = 0;
							$.each(arr_n , function(ix,msg_){
								var gmsg = msg_.data.split(';');
								$('#ul-'+el.fbdata.id+'_2A').append('<li id="comment_'+ix+'_2A'+ gmsg[3] +'_2A" ><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ gmsg[0] +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+gmsg[1]+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ gmsg[2] +'</p></li>');	
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
			 	
			}//End of if el.connection

			
		  });//End of each object
		  	
		
			var _back = '<div class="pagefx"></div>';		
			
			_back += '<div id="back_cover"></div>';

			$('#pages').append(_back);
	  
		return this;
	  };	  
	 
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
 		pindex  	: 0
	  };
	  

})(jQuery);		
		
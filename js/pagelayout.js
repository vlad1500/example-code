// JavaScript Document

var pagenum;
var xnum = 0;
var wd = Math.floor((book_width/4) - 15);
var act_wd = book_width;
var pg_wd = Math.floor((book_width/2) - 21);
var hd = book_height;	
var html;
var htImg=0;
var imgPic = hd * .1;
var	__pagenum = '';
var _msg;
var _heightOf;
var npage;

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
			$('#pages').append("<div id='cover'></div><div class='pagefx'></div>");
						
			$.each(opts.object.book_pages,function(i,el){
				
				if (opts.divId == el.fbdata.id){
					arrObj[i] = el.fbdata.id +':'+ opts.layout;
					 _layout = opts.layout
				}else{
					var _getLayout = arrObj[i].split(':');					
					_layout = _getLayout[1];
				}
				
//				if ( _layout == 0 ){
//					var html ='<div id="'+el.fbdata.id+'" class="pagefx">';
//						html +='   <div id="div-'+el.fbdata.id+'" style="width:100%; max-height:'+hd+'px; box-sizing:border-box; overflow:hidden;">';																								
//						html +='   </div>';							
//						html +='</div>';										
//						$('#pages').append(html);	
//						
//						el.connection == 'statuses' ? _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>' : _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" class="editable"/>';
//						
//						$('#div-'+el.fbdata.id).append(_msg);
//						
//						if(el.connection != 'statuses' && el.comment.length > 0){ 
//							_heightOf = $('#div-'+el.fbdata.id).height() * .80;		
//							var _parent = '#div-'+el.fbdata.id;										
//							$('#img-'+el.fbdata.id).imgscale({ scale : 'fit', lessenTo : _heightOf, center:true });
//							var html ='<ul id="ul-'+el.fbdata.id+'" class="comment_layout4"></ul>';
//								$('#'+el.fbdata.id).append(html);
//						}else{
//							_heightOf = $('#div-'+el.fbdata.id).height() * .95;		
//							var _parent = '#div-'+el.fbdata.id;					
//							$('#img-'+el.fbdata.id).imgscale({ scale : 'fit', center:true });	
//						}
//						
//					xnum++;
//					pagenum = setPageDetails (xnum, 0, pagenum, 1,  el.fbdata.id);					
//				}else 
					
					if (_layout == 1){	
					
					if (el.fbdata.height > hd){
						
						if ( el.connection == 'statuses'){
							var _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>';
						}else{
							var _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" width="100%" class="editable"/>';
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
							var _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" width="100%" class="editable"/>';
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
				
					var html ='<div id="div-'+el.fbdata.id+'" class="img_layout3" style="height:100%">';
						html += '  <div id="col_layout3_left" class="float_left">';
						html +='   <div id="div-A" class="img_layout3">';
						html +='    <img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" width="100%" class="editable"/>';											
						html +='   </div><!--End of Image-->';	
						html +='  <ul id="'+el.book_info_id+'_'+i+'" class="float_left comment_layout3_left">';																
						html +='  </ul>';														   						
						html +='  </div>'; 				    										  				
						html +=' </div><!---End of col_layout3-left-->';
						html += ' <div id="col_layout3_right" class="float_right">';
						html +='  <ul id="'+el.book_info_id+'_'+i+'" class="float_left comment_layout3_left">';																
						html +='  </ul>';
						html +='  <div id="div-B" class="img_layout3_down">';																			
						html +='   <img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" width="100%" class="editable"/>';											
						html +='  </div><!--End of Image-->';					    										  																		
						html +=' </div>';   					
						html +='</div>';	
									
						$('#pages').append(html);						
						xnum++;
						pagenum = setPageDetails (xnum, _layout, pagenum, 1,  el.fbdata.id);
						
						if (el.comment.length != 0)											
						fill_layout(el.comment,el.fbdata.id,opts.pagenum,true,_layout);
					
				}else if (_layout == 4){	
					
					var _shapeofimg;
			
					if( el.fbdata.width > el.fbdata.height )
						_shapeofimg = 'width="100%"'; // wide image
					else if( el.fbdata.width < el.fbdata.height )
						_shapeofimg = 'height="100%"'; // tall image
					else if( el.fbdata.width == el.fbdata.height )
						_shapeofimg = 'width="100%" height="100%"'; // sqaure image
						
					var _shapeofimg;			
					
					if( el.fbdata.width > el.fbdata.height ){
						el.fbdata.width  < pg_wd ? _shapeofimg = el.fbdata.width :  _shapeofimg = 'width="100%"';
						// wide image
					}else if( el.fbdata.width < el.fbdata.height ){
						el.fbdata.height < hd ? _shapeofimg = el.fbdata.height : _shapeofimg = 'height="100%"'; 
						// tall image
					}else if( el.fbdata.width == el.fbdata.height ){
						var _newwd;
						var _newht;
						el.fbdata.width  < pg_wd ? _newwd = el.fbdata.width : _newwd = '"width:100%"';
						el.fbdata.height  < hd ? _newht = el.fbdata.height : _newht = '"height:100%"';
						_shapeofimg = _newwd +' ' + _newht; 
						// sqaure image
						
					}						
			
					
					var html = '<div id="'+el.fbdata.id+'" class="pagefx">';															
							html +='   <div id="div-'+el.fbdata.id+'" style="width:'+pg_wd+'px; max-height:'+hd+'px; overflow:hidden;">';																								
							html +='   </div>';							
							html +='</div>';  
						
						$('#pages').append(html);
						
						el.connection == 'statuses' ? _msg = '<p id="msg-'+el.fbdata.id+'">'+el.fbdata.message+'</p>' : _msg = '<img src="'+el.fbdata.source+'" id="img-'+el.fbdata.id+'" class="editable" '+ _shapeofimg +'/>';
								
						if(el.connection !== 'statuses' && el.comment.length > 0){ 
							_heightOf = hd * .78;	
							$('#div-'+el.fbdata.id).css({'max-height':_heightOf});						
							if( $('#img-'+el.fbdata.id).attr('src') != 'undefined' ){
								$('#img-'+el.fbdata.id).onImagesLoaded(function(_img_){ 
									$(_img_).fadeIn(3000)//.imgscale({ scale : 'fit', lessenTo : _heightOf, center:true });		
								});
							}
							
							$('#div-'+el.fbdata.id).append(_msg);
							
							var html ='<ul id="ul-'+el.fbdata.id+'" class="comment_layout4"></ul>';
								$('#'+el.fbdata.id).append(html);
								
						}else if(el.connection === 'statuses' && el.comment.length > 0){
							
							_heightOf = hd * .78;
							$('#msg-'+el.fbdata.id).css({'height':_heightOf});
							
							$('#div-'+el.fbdata.id).append(_msg);
							
							var html ='<ul id="ul-'+el.fbdata.id+'" class="comment_layout4"></ul>';
								$('#'+el.fbdata.id).append(html);
							
						}else{
								_heightOf = hd * .96;		
		
								$('#div-'+el.fbdata.id).css({'max-height':_heightOf});
								
								$('#div-'+el.fbdata.id).append(_msg);
								
								if( $('#img-'+el.fbdata.id).attr('src') !== undefined ){
									$('#img-'+el.fbdata.id).onImagesLoaded(function(_img_){ 
										$(_img_).fadeIn(3000)//.imgscale({ scale : 'fit', lessenTo : _heightOf, center:true });		
									});
								}else if (el.connection === 'statuses'){
									$('#msg-'+el.fbdata.id).css({'height':_heightOf});
								}				
								
							//});
						}		
						
						if (el.connection !== 'statuses'){
							var _wofimg = $('#img-'+el.fbdata.id).width();		
							var _hofimg = $('#img-'+el.fbdata.id).height();		
							var _wofdiv = $('#img-'+el.fbdata.id).offsetParent().width();
							
							if( _shapeofimg == 'width="100%"' || _shapeofimg == 'height="100%"' ){					
								var _marlef = Math.floor(100*_wofimg/_wofdiv);	
								_shapeofimg == 'width="100%"' ? _marlef = 0 : _marlef;											
							}else if( _wofimg === _hofimg ){	
								var _marlef = Math.floor(100*_wofimg/_wofdiv);						
							}
							
							$('#img-'+el.fbdata.id).css({ 'margin-left' : _marlef });
						}						
					
					xnum++;
					
					if (opts.divId == el.fbdata.id)
					npage = xnum;
					
					pagenum = setPageDetails (xnum, _layout, pagenum, 1,  el.fbdata.id);

					//htImg = hd; //$('#div-'+el.fbdata.id).height()
					
					if (el.comment.length != 0)											
						fill_layout(el.comment,el.fbdata.id,opts.pagenum,true,_layout,_heightOf);
					
				
				}else{	
				
					//fill_default_layout(opts.object);
						
				}
				
			 // }//end of divId
			  
			});//End of each object
			
		}else{
												
			if (opts.layout == 1){	
		
			if (opts.fbdata.height > hd){
				
				if ( opts.connection == 'statuses'){
				 	var _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>';
				}else{
					var _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" width="100%" class="editable"/>';
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
						
			if (opts.comment.length != 0)	
			fill_layout(opts.comment,opts.fbdata.id,opts.pagenum,false,opts.layout);
	
		}else if (opts.fbdata == 2){	
		
			if (opts.fbdata.height > hd){
				
				if ( opts.connection == 'statuses'){
				 	var _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>';
				}else{
					var _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" width="100%" class="editable"/>';
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
			if (opts.comment.length != 0)	
			fill_layout(opts.comment,opts.fbdata.id,opts.pagenum,false,opts.layout);
		
							
		}else if (opts.layout == 3){	

			var html ='<div id="div-'+opts.fbdata.id+'" class="img_layout3" style="height:100%">';
					html += '  <div id="col_layout3_left" class="float_left">';
					html +='   <div id="div-A" class="img_layout3">';
					html +='    <img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" width="100%" class="editable"/>';											
					html +='   </div><!--End of Image-->';	
					html +='   <ul id="'+opts.book_info_id+'" class="float_left comment_layout3_left">';																
					html +='   </ul>';														   						
					html +='  </div>'; 				    												    										  																		
					html +=' </div>';   					
								
					$('#pages').append(html);						
					xnum++;
					pagenum = setPageDetails (xnum, opts.layout, pagenum, 1,  opts.fbdata.id);
					
					if (opts.comment.length != 0)											
					fill_layout(opts.comment,opts.fbdata.id,opts.pagenum,false,opts.layout);			
			
		}else if (opts.layout == 4){	
		
			 
//			 console.log(opts.fbdata.height);
//			 console.log(opts.fbdata.width);

			var _shapeofimg;			
			
			if( opts.fbdata.width > opts.fbdata.height ){
				opts.fbdata.width  < pg_wd ? _shapeofimg = opts.fbdata.width :  _shapeofimg = 'width="100%"';
				// wide image
			}else if( opts.fbdata.width < opts.fbdata.height ){
				opts.fbdata.height < hd ? _shapeofimg = opts.fbdata.height : _shapeofimg = 'height="100%"'; 
				// tall image
			}else if( opts.fbdata.width == opts.fbdata.height ){
				var _newwd;
				var _newht;
				opts.fbdata.width  < pg_wd ? _newwd = opts.fbdata.width : _newwd = '"width:100%"';
				opts.fbdata.height  < hd ? _newht = opts.fbdata.height : _newht = '"height:100%"';
				_shapeofimg = _newwd +' ' + _newht; 
				// sqaure image
				
		    }
	
			
			var html = '<div id="'+opts.fbdata.id+'" class="pagefx">';															
					html +='   <div id="div-'+opts.fbdata.id+'" style="width:'+pg_wd+'px; max-height:'+hd+'px; overflow:hidden;">';																								
					html +='   </div>';							
					html +='</div>';  
				
				$('#pages').append(html);
				
				opts.connection == 'statuses' ? _msg = '<p id="msg-'+opts.fbdata.id+'">'+opts.fbdata.message+'</p>' : _msg = '<img src="'+opts.fbdata.source+'" id="img-'+opts.fbdata.id+'" class="editable" '+ _shapeofimg +'/>';
						
				if(opts.connection !== 'statuses' && opts.comment.length > 0){ 
					_heightOf = Math.floor(hd * .78);	
					$('#div-'+opts.fbdata.id).css({'max-height':_heightOf});						
					if( $('#img-'+opts.fbdata.id).attr('src') != 'undefined' ){
						$('#img-'+opts.fbdata.id).onImagesLoaded(function(_img_){ 
							$(_img_).fadeIn(3000)//.imgscale({ scale : 'fit', lessenTo : _heightOf, center:true });		
						});
					}
					
					$('#div-'+opts.fbdata.id).append(_msg);
					
					var html ='<ul id="ul-'+opts.fbdata.id+'" class="comment_layout4"></ul>';
						$('#'+opts.fbdata.id).append(html);
						
				}else if(opts.connection === 'statuses' && opts.comment.length > 0){
					
					_heightOf = Math.floor(hd * .78);
					$('#msg-'+opts.fbdata.id).css({'height':_heightOf});
					
					$('#div-'+opts.fbdata.id).append(_msg);
					
					var html ='<ul id="ul-'+opts.fbdata.id+'" class="comment_layout4"></ul>';
						$('#'+opts.fbdata.id).append(html);
					
				}else{
						_heightOf = Math.floor(hd * .96);		

						$('#div-'+opts.fbdata.id).css({'max-height':_heightOf});
						
						$('#div-'+opts.fbdata.id).append(_msg);
						
						if( $('#img-'+opts.fbdata.id).attr('src') !== undefined ){
							$('#img-'+opts.fbdata.id).onImagesLoaded(function(_img_){ 
								$(_img_).fadeIn(3000)//.imgscale({ scale : 'fit', lessenTo : _heightOf, center:true });		
							});							
						}else if (opts.connection === 'statuses'){
							$('#msg-'+opts.fbdata.id).css({'height':_heightOf});
						}				
						
					//});
				}		
				
				if (opts.connection !== 'statuses'){
					var _wofimg = $('#img-'+opts.fbdata.id).width();		
					var _hofimg = $('#img-'+opts.fbdata.id).height();		
					var _wofdiv = $('#img-'+opts.fbdata.id).offsetParent().width();
					
					if( _shapeofimg == 'width="100%"' || _shapeofimg == 'height="100%"' ){					
						var _marlef = Math.floor(100*_wofimg/_wofdiv);	
						_shapeofimg == 'width="100%"' ? _marlef = 0 : _marlef;											
					}else if( _wofimg === _hofimg ){	
						var _marlef = Math.floor(100*_wofimg/_wofdiv);						
					}
					
					$('#img-'+opts.fbdata.id).css({ 'margin-left' : _marlef });
				}
				
				
//				var _imgNewWidth = $('#img-'+opts.fbdata.id).width();
//				var _parentWidth = $('#div-'+opts.fbdata.id).width();
//				
//				if( _imgNewWidth < _parentWidth ) {
//					var _nwd = Math.floor(_imgNewWidth / 2 );
//					var _nht = Math.floor(_parentWidth / 2 );
//					var _marginLeft;
//                    _marginLeft = Math.floor( _nht - _nwd  ) + 'px';
//                    $('#img-'+opts.fbdata.id).css( 'margin-left', _marginLeft );
//                }							 
			
			xnum++;
			pagenum = setPageDetails (xnum, 4, pagenum, 1,  opts.fbdata.id);	
			
			if (opts.comment.length != 0)	
			fill_layout(opts.comment,opts.fbdata.id,opts.pagenum,false,opts.layout,_heightOf);
		
		
		}else{	
			
			//fill_default_layout(opts.object);
				
		}	
		
	  	}//end of opts.isObject
		
						
		
		if(opts.isObject) {		

			var _back = '<div class="pagefx"></div>';		
			
			_back += '<div id="back_cover"></div>';

			$('#pages').append(_back);
			
			$('#pages').wowBook({
				 height : book_height
				,width  : book_width
				,centeredWhenClosed : false
				,hardcovers : true
				,turnPageDuration : 500
				//,numberedPages : [2,-3]
				,flipSound     	  : false
				,transparentPages : true
				,updateBrowserURL : true
				,pageNumbers	  :	false
				,controls : {
						zoomIn    : '#zoomin',
						zoomOut   : '#zoomout',
						next      : '#fold_right',
						back      : '#fold_left',
						first     : '#first',
						last      : '#last',
						slideShow : '#slideshow'
					}
				,hardPages		  : true
			}).css({'display':'none', 'margin':'auto'}).fadeIn(1000);	
					
			$.wowBook("#pages").showPage(opts.pagenum,true);
			//$.wowBook("#pages").gotoPage(opts.divId);
			var _book = $.wowBook("#pages");
			
			$('.paginate ul').empty();
			
			var _get_i;
			
			$(_book.pages).each(function(i,el){
				if ( i == 0 )
				{
					//$('.paginate ul').append('<li id="'+ i +'">'+ i +'</li>');
				}
				else if( i <= 20 )
				{
					var n = i%2;
					switch (n) {																		
						case 0:			
							//$('#pagination').append('<li id="'+ i +'">'+ i +'</li>');
							break;						
						default:	
							var ii = i + 1;
							var _page_list = '<div id="'+ i +'" class="paginate-left-page">'+ i +'</div>';
								_page_list += '<div id="'+ ii +'" class="paginate-right-page">'+ ii +'</div>'
							$('.paginate ul').append('<li>'+ _page_list +'</li>');
							break;
	
					}
				}	
				_get_i = i;			
			});	
			
			$('.paginate ul').append('<li><div id="elips" class="paginate-left-page">...</div><div class="paginate-right-page" id="'+_get_i+'">'+ _get_i +'</div></li>');
			
			$('.paginate ul li div').each(function(i) {															
					$(this).live('click',function(){																													
						var _id = $(this).attr('id');
						$('.paginate ul li div').removeClass('active');
						if( _id !== 'elips'){																		
							$(this).addClass('active');		
							////console.log(_book.currentPage);
							//console.log(_book.isOnPage(_id))			
							_book.gotoPage(_id);
							//_book.updateBrowserURL(true);
						}else{
							return false
						}

					})
			});															

			$.ajax({
				url			: 	'/main/save_pagenum',
				data		:	{'pagenum' : pagenum},
				type		: 'POST',
				success 	: function(res){  
								ret_object = $.parseJSON(res); 	
								//console.log(ret_object);							
							},
				complete 	: function(){},
				error 		: function(){}
			});			
			
		
		$('#button_container').width($('#pages').width()).css({ 'left' : $('#pages').offset().left - 20 });
									
		$('div.wowbook-page').each(function(index, element) { 									
			$(this).live('mouseenter',function(){ 
				//var curr_id = $(this).attr('id');										
				curr_id = $(this).children().get(0).id;
				$.cookie('div_',curr_id);
				$(this).hasClass('wowbook-right') ?  cls = '_right' : cls = '_left'; //$('#'+curr_id).attr('class');
				var _this = $(this);
				//////console.log(cls);
				$('#'+curr_id).addClass('current'); 
				$('.'+cls+'_icon').fadeIn('slow');			
				$('.'+cls+'_icon').hover(function(){
					$(this).css({'opacity':1}).stop(true);		
				}).click(function(){	
						var _id = $(this).children('div').get(0).id;	
						var _w	= $('#page-layout_option').width();	
						var _w2 = _w / 2;				
					if (cls == "_left"){
						$('#page-layout_option').css({left: $('#'+_id).offset().left - _w2 }).fadeIn('slow');
					}else{
						$('#page-layout_option').css({left: ($('#'+_id).offset().left - _w) - (_w2 - 40)  }).fadeIn('slow');
					}
				});							
			}).live('mouseleave',function(){ 				
				curr_id = $(this).children().get(0).id;
				$('#'+curr_id).removeClass('current'); 										
				$('.'+cls+'_icon').fadeOut('slow');					
			}).live('mousedown',function(){
				$('#page-layout_option').fadeOut('slow');
			});
		});
		
		$('img.editable').each(function(index, element) {
			$(this).live('click',function(){
				//console.log($(this));
				$(this).myEditor();
			})
		});
		
		}
		
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
		var imgHT = 0;
		var _msg;
		var holder_=0;
		var _heightOf = 0;		
		
		//if (isBook){ var book = $.wowBook('#pages'); }
		
		var _wd = wd-8;	
		var cmt_cont;

		$.each(comment,function(x,cmt){
			
			var _nme = cmt.comment_obj.from.name;
			var _pic = 'https://graph.facebook.com/'+ cmt.comment_obj.from.id +'/picture?type=small';
				

				if (layout == 4){						
					$('ul#ul-'+fbdataid).append('<li id="comment_'+x+cmt.book_comment_id+'_'+x+'" style="width:auto;min-height:35px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;width:15%;"><img src="'+ _pic +'" class="float_left"></div><p style="font-size:'+font_size+'px" class="comment_by">'+_nme+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ cmt.comment_obj.message +'</p></li>');																																																	
					ht = Math.floor(ht + parseInt($('li#comment_'+x+cmt.book_comment_id+'_'+x).height()) + 10.5);
					ht = Math.floor(ht + imgContainer);																
				}else{
							
					$('ul#ul-'+fbdataid).append('<li id="comment_'+x+cmt.book_comment_id+'_'+x+'" style="width:'+_wd+'px;min-height:35px;"><div class="pic_cn float_left" style="height:'+imgPic+'px;"><img src="'+ _pic +'" class="float_left"></div><p class="comment_by" style="font-size:'+font_size+'px">'+_nme+'</p><p class="float_left fbcomment" style="font-size:'+font_size+'px">'+ cmt.comment_obj.message +'</p></li>');								
                          
					ht = Math.floor(ht + $('li#comment_'+x+cmt.book_comment_id+'_'+x).height() + 10.5);						

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
				
				ht_ = Math.floor(ht_ + $('li#comment_'+xx+'_1'+_msg[3]+'_1').height() + 10.5);

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
					
					pagenum = setPageDetails (xnum, layout, pagenum, 2,  gmsg[3]);
					
					htn_ = Math.floor(htn_ + $('#comment_'+xxx+'_2'+ gmsg[3] +'_2').height() + 10.5);
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
						
						ht_n = Math.floor(ht_n + $('li#comment_'+ix+'_1A'+ gmsg[3] +'_1A').height() + 10.5);
						
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
							h_ = Math.floor(h_ + $('#comment_'+ix+'_2A'+ gmsg[3] +'_2A').height()+10.5);
							
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
	 
	 function setPageDetails (_pnum, _playout, _retval, id_type, _id) {		
		var pre_ID = '';
		if (id_type == 1) // for fbdata id
			pre_ID = 'fbid_';
		else if (id_type == 2) // for comment id
			pre_ID = 'cid_';
		
		if (__pagenum != 'undefined') {	
			if (__pagenum.length != 0) 
				_retval = __pagenum + ',' + pre_ID + _id + ':' + _pnum + ':' + _playout;
			else 
				_retval = pre_ID + _id + ':' + _pnum + ':' + _playout;
		}
		
		__pagenum = _retval;	
		
		return _retval;
	}
	  
	 $.fn.pageLayout.defaults = {
		divId  		: 0 ,
		pagenum 	: 0 ,
		comment 	: {},
		fbdata  	: {},
		layout  	: 4,
		connection 	: 'photos',
		isObject	: false,
		object  	: {},
 		pindex  	: 0
	  };
	  

})(jQuery);		
		

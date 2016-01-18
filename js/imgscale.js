// JavaScript Document
(function($) {
    $.fn.imgscale = function(params) {

        params = $.extend( {parent: false, scale: 'fill', center: true, fade: 0, lessenTo: 0}, params);
        
        var _parentHeight, _parentWidth, _imgHeight, _imgWidth, _imgNewWidth, _imgNewHeight, _marginLeft, _marginTop;
		
		this.each( function() {
            var $img = $(this);
			
            var $parent = ( !params.parent ? $img.parent() : $img.parents( params.parent ) );
			var $height = params.lessenTo;
			
            $parent.css({opacity: 0, overflow: 'hidden'});
            if( $parent.length > 0 ) {
                console.log($img);
				$img.removeAttr('height').removeAttr('width');
//				var _src = $img.attr('src');
//				var fileNameIndex = _src.lastIndexOf("/") + 1;
//				var filename = _src.substr(fileNameIndex);
//				$.loadImage(filename,_src);
//				$height > 0 ?  _scaleImage( $img, $parent, false, $height ) :  _scaleImage( $img, $parent, false );                  
                if ($img.complete) {
					$height > 0 ?  _scaleImage( $img, $parent, false, $height ) :  _scaleImage( $img, $parent, false );                  
                } else {
                    $img.load( function() {						
						$height > 0 ?  _scaleImage( $img, $parent, true, $height ) :  _scaleImage( $img, $parent, true )
                    })
                }
            }

		});
			
	
//		function try_($img){
//									
//			if ( $img.complete && $img.naturalWidth !== 0 ) { 
//				var $parent = ( !params.parent ? $img.parent() : $img.parents( params.parent ) );
//				var $height = params.lessenTo;
//				
//				$parent.css({opacity: 0, overflow: 'hidden'});
//				
//				if( $parent.length > 0 ) {				
//					//$(this).removeAttr('height').removeAttr('width');	
//					$img.attr('src',params.source);							
//					$height > 0 ?  _scaleImage( $img, $parent, true, $height ) :  _scaleImage( $img, $parent, true );
//				}
//				console.log('loaded');
//			}else{
//				
//				$img.load( function() {
//					console.log('trying');
//					setTimeout(try_($(this)), 200);					
//					return;
//				});
//			}	
//		}
		
        function _scaleImage( $img, $parent, _loadedImg, _height ) {
			_height != undefined ?  _parentHeight = _height :  _parentHeight = $parent.height();           

            _parentWidth = $parent.width();
            $img.height() < 30 ? _imgHeight = _parentHeight : _imgHeight = $img.height();
            $img.width() < 30 ? _imgWidth = $parent.width() : _imgWidth = $img.width();		
//            _imgHeight = $img.height();
//            _imgWidth = $img.width();				

//			console.log($img);
//			console.log( _imgHeight);
//			console.log($img.width());
//			console.log(_imgWidth);			
			
            _getParentShape();

            function _getParentShape() {
                if( _parentWidth > _parentHeight )
                    _getImageShape( 'w' ); // wide parent
                else if( _parentWidth < _parentHeight )
                    _getImageShape( 't' ); // tall parent
                else if( _parentWidth == _parentHeight )
                    _getImageShape( 's' ); // square parent
            }

            function _getImageShape( _parentShape ) {
                if( _imgWidth > _imgHeight )
                    _compareShapes( _parentShape, 'w' ) // wide image
                else if( _imgWidth < _imgHeight )
                    _compareShapes( _parentShape, 't' ) // tall image
                else if( _imgWidth == _imgHeight )
                    _compareShapes( _parentShape, 's' ) // sqaure image
            }

            function _compareShapes( _parentShape, _imgShape ) {
                if( _parentShape == 'w' && _imgShape == 'w' )
                    _calulateScale();
                else if( _parentShape == 'w' && _imgShape == 't' )
                    _reiszeImage( 'w' );
                else if( _parentShape == 'w' && _imgShape == 's' )
                    _reiszeImage( 'w' );
                else if( _parentShape == 't' && _imgShape == 'w' )
                    _reiszeImage( 'w' );
                else if( _parentShape == 't' && _imgShape == 't' )
                    _calulateScale();
                else if( _parentShape == 't' && _imgShape == 's' )
                    _reiszeImage( 't' );
                else if( _parentShape == 's' && _imgShape == 'w' )
                    _reiszeImage( 't' );
                else if( _parentShape == 's' && _imgShape == 't' )
                    _reiszeImage( 'w' );
                else if( _parentShape == 's' && _imgShape == 's' )
                    _reiszeImage( 'w' );
            }

            function _calulateScale() {
                if( (_imgWidth * _parentHeight / _imgWidth ) >= _parentWidth )
                    _reiszeImage( 't' );
                else
                    _reiszeImage( 'w' );
            }

            function _reiszeImage( _scale ) {

                switch( _scale ) {
                    case 't':
                      if( params.scale == 'fit' )
                        $img.attr( 'width', _parentWidth );
                      else
                        $img.attr( 'height', _parentHeight );
                        break;
                    case 'w':
                        if( params.scale == 'fit' )
                          $img.attr( 'height', _parentHeight );
                        else
                          $img.attr( 'width', _parentWidth );
                        break;
                }
                if( params.center )
                  _repositionImage();
                else
                  _showImage();
            }

            function _repositionImage() {
                _imgNewWidth = $img.width();
                _imgNewHeight = $img.height();		
				
				
						
				//$img.height() < 30 ? _imgNewHeight = _parentHeight: _imgNewHeight = $img.height();
				//$img.width() < 30 ? _imgNewWidth = $parent.width() : _imgNewWidth = $img.height();
				
                if( _imgNewHeight > _parentHeight ) {
					//console.log('height :'+_imgNewHeight+' - parentHeight:'+_parentHeight);
                    _marginTop = '-' + ( Math.floor( ( _imgNewHeight - _parentHeight ) / 2 ) ) + 'px';
                    $img.css( 'margin-top', _marginTop );
                }

                if( _imgNewWidth > _parentWidth ) {
					//console.log('width :'+_imgNewWidth+' - parentWidth:'+_parentWidth);
                    _marginLeft = '-' + ( Math.floor( ( _imgNewWidth - _parentWidth ) / 2 ) ) + 'px';
                    $img.css( 'margin-left', _marginLeft );
                }
				
				if( _imgNewWidth < _parentWidth ) {
					var _nwd = Math.floor(_imgNewWidth / 2 );
					var _nht = Math.floor(_parentWidth / 2 );
                    _marginLeft = Math.floor( (_nht - _nwd) / 2  ) + 'px';
					console.log(_nwd);
                    $img.css( 'margin-left', _marginLeft );
                }
				
                _showImage();
            }
            
            function _showImage(){
              if( params.fade > 0 && _loadedImg )
                $parent.animate({opacity : 1}, params.fade);
              else
                $parent.css('opacity', 1);
            }
        }

    };
})(jQuery);
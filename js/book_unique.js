var APIURL = '/'; 
var fimg = '';

//Called when a photo is successfully retrieved

function onPhotoURISuccess(imageURI) {
  requestCrossDomain(APIURL+'edit_album/get_book_pages_uni', function(res) { });
}

function requestCrossDomain( site, callback ) {  
	
    if ( !site ) {  
        alert('No site was passed.');   
        return false;  
    }  

    $.getJSON( site+"?c=sri"+$.now(), function(data){ imagesDisplay(data); } );
}  


function imagesDisplay(data) 
{  
    var parentBookDiv = $(".book-flip-url");
    book_info = data.book_info;
	book_pages = data.book_pages;
	if(book_pages === null || book_pages == "" || $.cookie("hardcover_fbid") == ""){	    
        console.log("error: book_pages is "+book_pages+".");
        var errEle = '<div style="position:absolute; top:45%; left:45%;"><h3>You are not permitted to view this book.</h3></div>';
        $(".ajaxLoaderDiv").hide();
        parentBookDiv.append(errEle);        
	} else {
	    var newDime = getNewDimension(); //added for screen detection
        console.log(newDime);
        newDime = newDime.split("~");
        var bgWidth = (newDime[0]*1);
        var bgHeight = (newDime[1]*1);
        var smWidth = bgWidth / 10;
        var smHeight = bgHeight / 10;
        var temp = new Array();
        var data_len=book_pages.length;
        var xtra_page=0;	
        if(data_len%2==1){
       	    xtra_page=2;
        }else{
	       	xtra_page=1;
        }
        data_len=data_len+xtra_page;	
        var bc = book_info.back_cover_page;
        var fc = book_info.front_cover_page;
/*--------------------*/
/*josh mods for share button*/
/*--------------------*/     
        var l = window.location;
        var base_url = l.protocol + "//" + l.host; //added this for a more dynamic base url.
        console.log(base_url);
        var ImgContainerDiv = '<div id="ImgContainerDiv" style="display:none;"></div>';
        parentBookDiv.append(ImgContainerDiv);
        //var base_url = window.base_url?window.base_url:'https://dev.hardcover.me';        
        for(var i=0;i<=data_len; i++) {
            var shareEle = '<div id="sharebuttons" page_number="'+(i+1)+'" style="width:205px; position:absolute; z-index:9998; bottom:-60px; left:50%; margin-left: -6%;"><span class="st_facebook" displayText=""><a href="javascript:void(0);" class="fb-share"><img src="'+base_url+'/images/facebook.png" alt="Facebook"/></a></span><span class="st_twitter" displayText=""><a href="javascript:void(0);" class="twitter-share"><img src="'+base_url+'/images/twitter.png" alt="Twitter"/></a></span><span class="st_pinterest" displayText=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="'+base_url+'/images/pinterest.png" alt="Pinterest"/></a></span><span class="st_googleplus" displayText=""></span><span class="st_email" displayText=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="'+base_url+'/images/mail.png" alt="mail"/></a></span></div>';
            temp[i] = new Object();
            if(i==0){
                temp[i].title = 	'Cover';
                temp[i].src = 	    base_url + '/timthumb.php?src='+fc+'&h='+bgHeight+'&w='+bgWidth+'&zc=2';
                temp[i].thumb = 	base_url + '/timthumb.php?src='+fc+'&h='+smHeight+'&w='+smWidth+'&zc=2';
		      //temp[i].htmlContent = "Ananda Prithvi";        
                temp[i].htmlContent = shareEle;
            } else if(i<=data_len-xtra_page){
                if(i > 20){
                    var imgElement = '<img class="pageImg'+(i+1)+'" src="'+base_url + '/timthumb.php?src='+book_pages[i-1].image_url+'&h='+bgHeight+'&w='+bgWidth+'&zc=2" />';
                    $("#ImgContainerDiv").append(imgElement);
                    temp[i].src = 	"";    
                } else {
                    temp[i].src = 	base_url + '/timthumb.php?src='+book_pages[i-1].image_url+'&h='+bgHeight+'&w='+bgWidth+'&zc=2';    
                }                   
                temp[i].thumb = 	base_url + '/timthumb.php?src='+book_pages[i-1].image_url+'&h='+smHeight+'&w='+smWidth+'&zc=2';
                temp[i].title = 	'cc '+i;
                temp[i].fb_username = 	book_info.author_name;
                temp[i].front_cover = 	book_info.front_cover_page;          
                temp[i].htmlContent = shareEle; 
            } else if(i==data_len){			  
                temp[i].title = 	'Back Cover';
                temp[i].src = 	 	base_url +  '/timthumb.php?src='+bc+'&h='+bgHeight+'&w='+bgWidth+'&zc=2';
                temp[i].thumb = 	base_url +  '/timthumb.php?src='+bc+'&h='+smHeight+'&w='+smWidth+'&zc=2';		
            }
		
        }
        if(i%2 == 1){
            temp[i] = new Object();
            temp[i].src =       '/images/preloader.jpg';
            temp[i].thumb = 	'/images/preloader.jpg';
            temp[i].title = 	'last';
        }
        var page = JSON.stringify(temp);
        if(getUrlVars()){
            var start = getUrlVars()["page"];
        }else{
            var start = 0;
        }
        $("#container").flipBook({
            pages: jQuery.parseJSON(page),
            lightBox:false,
            pageWidth:bgWidth,
            pageHeight:bgHeight,
            thumbnailWidth:smWidth,
            thumbnailHeight:smHeight,
            webgl:false,
            pageHardness:2.5,
            coverHardness:8,
            pageMaterial:'phong',
			
			startPage:start
        });        
    }
}

// Read a page's GET URL variables and return them as an associative array.
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function getCurrentImg(page_number) {
	return $($('.pn'+page_number+' img')[0]).attr('src');
}

//josh mods for detect window resolution and change image size
    
            var selectors, lastTarget;
                selectors = {
                crops : 'input[name=crop]',
                crop  : 'input[name=crop]:checked'
            };
            function round() {
                return $('input[name=round]:checked').length === 1;
            }            
            /**
             * Reduce a numerator and denominator to it's smallest, integer ratio using Euclid's Algorithm
             */
            function reduceRatio(numerator, denominator) {
                var gcd, temp, divisor;
                        // from: http://pages.pacificcoast.net/~cazelais/euclid.html
                gcd = function (a, b) { 
                    if (b === 0) return a;
                    return gcd(b, a % b);
                }
                        // take care of some simple cases
                if (!isInteger(numerator) || !isInteger(denominator)) return '? : ?';
                if (numerator === denominator) return '1 : 1';
                        // make sure numerator is always the larger number
                if (+numerator < +denominator) {
                    temp        = numerator;
                    numerator   = denominator;
                    denominator = temp;
                }
                divisor = gcd(+numerator, +denominator);
                return 'undefined' === typeof temp ? (numerator / divisor) + ' : ' + (denominator / divisor) : (denominator / divisor) + ' : ' + (numerator / divisor);
            };
            function ratio2css(numerator, denominator) {
                var width, height;
                if (+numerator > +denominator) {
                    width  = 200;
                    height = solve(width, undefined, numerator, denominator);
                }
                else {
                    height = 200;
                    width  = solve(undefined, height, numerator, denominator);
                }
                return {
                    width      : width + 'px',
                    height     : height + 'px',
                    lineHeight : height + 'px'
                };
            }
            /**
             * Determine whether a value is an integer (ie. only numbers)
             */
            function isInteger(value) {
                return /^[0-9]+$/.test(value);
            };
                /**
             * Solve for the 4th value
             * @param int num2 Numerator from the right side of the equation
             * @param int den2 Denominator from the right side of the equation
             * @param int num1 Numerator from the left side of the equation
             * @param int den1 Denominator from the left side of the equation
             * @return int
             */
            function solve(width, height, numerator, denominator) {
                var value;
                        // solve for width
                if ('undefined' !== typeof width) {
                    value = round() ? Math.round(width / (numerator / denominator)) : width / (numerator / denominator);
                }
                // solve for height
                else if ('undefined' !== typeof height) {
                    value = round() ? Math.round(height * (numerator / denominator)) : height * (numerator / denominator);
                }
                        return value;
            }
            /**
             * Handle a keyup event
             */
            function getNewDimension() {
                var x1, y1, x2, y2, x1v, y1v, x2v, y2v, ratio;                        
                var wW = $(document).width();
                var wH = $(document).height();
                x1 = wW;                
                y1 = wH;
                x2 = x1 / 2;
                y2 = y1 - 220;
                x1v = x1;
                console.log(x1v);
                y1v = y1;
                console.log(y1v);
                x2v = x2;
                y2v = y2;
                //showStatus(x1v,y1v);
                        // display new ratio
                ratio = reduceRatio(x1v, y1v);                
                //resizeSample();
                //switch (evt.target) {
//                    case x1[0]:
//                        if (!isInteger(x1v) || !isInteger(y1v) || !isInteger(y2v)) return;
//                        x2.val(solve(undefined, y2v, x1v, y1v));                        
//                        break;
//                    case y1[0]:
//                        if (!isInteger(y1v) || !isInteger(x1v) || !isInteger(x2v)) return;
//                        y2.val(solve(x2v, undefined, x1v, y1v));                        
//                        break;
//                    case x2[0]:
//                        if (!isInteger(x2v) || !isInteger(x1v) || !isInteger(y1v)) return;
//                        y2.val(solve(x2v, undefined, x1v, y1v));                        
//                        break;
//                    case y2[0]:
//                        if (!isInteger(y2v) || !isInteger(x1v) || !isInteger(y1v)) return;
//                        x2.val(solve(undefined, y2v, x1v, y1v));                        
//                        break;
//                }
                x2 = x2;//solve(undefined, y2v, x1v, y1v);
                y2 = y2;//solve(x2v, undefined, x1v, y1v);
                return x2+"~"+y2;
            };                                    
            function resizeSample() {
                var img, imgRatio, width, height, boxRatio, imgW, imgH, css;               
                img = $('#visual-ratio img');
                imgRatio = img.width() / img.height();
                width  = $('#visual-ratio').width();
                height = $('#visual-ratio').height();
                boxRatio = width / height;
                function cropToWidth() {
                    img.css({ width  : width + 'px', height : 'auto' });
                    img.css({ top  : 0 - Math.round((img.height() - height) / 2) + 'px', left : 0 });
                }
                function cropToHeight() {
                    img.css({ width  : 'auto', height : height + 'px' });
                    img.css({ top  : 0, left : 0 - Math.round((img.width() - width) / 2) + 'px' });
                }
                function boxToWidth() {
                    img.css({ width  : width + 'px', height : 'auto' });
                    img.css({ top  : Math.round((height - img.height()) / 2) + 'px', left : 0 });
                }
                function boxToHeight() {
                    img.css({ width  : 'auto', height : height + 'px' });
                    img.css({ top  : 0, left : Math.round((width - img.width()) / 2) + 'px' });
                }
                if ('crop' === $(selectors.crop).val()) {
                    if (imgRatio > boxRatio) {
                        cropToHeight();
                    }
                    else {
                        cropToWidth();
                    }
                }
                else { // box
                    if (imgRatio > boxRatio) {
                        boxToWidth();
                    }
                    else {
                        boxToHeight();
                    }
                }
            }      
            function showStatus(width,height){
                statusDiv = '<div class="statusDiv" style="position:absolute; top:0; left:0; height:30px; width:100%; background:#000; color:#fff; text-align:center; font-weight:bold; padding:5px;">Test ~ Screen Width: '+width+' | Screen Height: '+height+'</div>';
                $(".statusDiv").remove();
                $("body").prepend(statusDiv);
            }
//end josh
		var cpage  = 0;
		var dwidth = 0;
		var dheight= 0;
		var dimgwidth = 0;

var APIURL = 'http://dev.hardcover.me/flipbook/';


//Called when a photo is successfully retrieved
function onPhotoURISuccess(imageURI) {
  requestCrossDomain(APIURL+'json.php', function(res) { });
}

function requestCrossDomain( site, callback ) {  
    // If no url was passed, exit.  
    if ( !site ) {  
        alert('No site was passed.');  
        return false;  
    }  
    // Take the provided url, and add it to a YQL query. Make sure you encode it!  
    var yql = 'http://query.yahooapis.com/v1/public/yql?q=' + encodeURIComponent('select * from json where url="' + site + '"') + '&format=json&callback=?';  
    $.getJSON( yql, function(data){ imagesDisplay(data.query.results.json.book_pages); } );  
} 


function imagesDisplay(data)
{
	
	if(data.length > 0)
		jQuery("#paginations ul").append("<li><a href='javascript:void(0)' onclick='backwardclick();'>«</a></li>");
		
	for(i=1,j=0;j<data.length;i++,j+=2)
	{ 
	   if(data[j] == '')
		{
			i--;
			continue;
		}
  var str = '<div class="pages" id="page-'+i+'">';
	str += '<div class="page page-left">';
	str += '<img class="bookimg" alt="" src="'+APIURL+'timthumb.php?src='+data[j].image_url+'&h='+dheight+'&w='+dimgwidth+'&zc=1"/>';
	str += '</div>';
	str += '<div class="page page-right">';
	if((j+1) >= data.length)
		str += ' ';
	else
		str += '<img class="bookimg" alt="" src="'+APIURL+'timthumb.php?src='+data[j+1].image_url+'&h='+dheight+'&w='+dimgwidth+'&zc=1"/>';
	str += '</div>';
	str += '</div>';
	$("#bookbg").append(str);
	
	jQuery("#paginations ul").append("<li class='pag'><a href='javascript:void(0)' onclick='pageLoad("+i+")'>["+(j+1)+"-"+(j+2)+"]</a></li>");
	
	}

	if(data.length > 0)
		jQuery("#paginations ul").append("<li><a href='javascript:void(0)' onclick='forwardclick();'>»</a></li>");

	
	initializebook();
}
		
		jQuery(document).ready(function(){
			
			//initializebook();
			loadBook();
			tei = '';
			onPhotoURISuccess(tei);
			
		});
		
		jQuery(window).resize(function() {
			loadBook();
			resizeImages();
			//imagecrop();
		});
		
		
		
		function resizeImages()
		{
			jQuery(".pages.show .booking").each(function(){
				resizeImg(jQuery(this));
			});
			
			jQuery(".bookimg").each(function(){
				resizeImg(jQuery(this));
			});
		}
		
		function resizeImg(img)
		{
			var imgval = img.attr("src");
			img.attr("src", "");
			temp = imgval.split("&h");
			imgval = temp[0]+"&h="+dheight+"&w="+dimgwidth;
			img.attr("src", imgval);
		}
		
		
		function loadBook()
		{
			dwidth = jQuery("#bookbg").width();
			dimgwidth = dwidth*0.40;
			dheight = parseInt((dwidth/3)*2);//jQuery(window).height();
			
			var temp = jQuery(window).height();
			
			if(dheight > temp)
			{
				dheight = temp - 60;
			}
			
			jQuery(".pagesc").css("height",dheight+"px");
		}
		
		function initializebook()
		{
			
			jQuery("#forward").unbind("click");
			jQuery("#forward").bind("click", function(){
				forwardclick();
			});
			
			jQuery("#backward").unbind("click");
			jQuery("#backward").bind("click", function(){
				backwardclick()
			});
			
			jQuery("#forward").trigger("click");
		}
		
		
		
		
		function pagescount()
		{
			flag = true;
			var i = 0;
			var leftpage = 0;
			var totcount = jQuery(".pages").length;
			var temppage = jQuery(".pageborder").length;
			var temp = totcount-temppage;
			for(i=0;i<temp;i++)
				{
					jQuery(".pagesc").append('<div class="pageborder"></div>')
				}
			i = 0;
			jQuery(".pages").each(function(){
				$(".pageborder:eq("+i+")").removeClass("pagesl").removeClass("pagesr").addClass("pagesl");
				v = 10 - (i*(12/totcount));
						
				$(".pageborder:eq("+i+")").css({"right":"","left":v+"px"});
				if(flag == false)
					{
						$(".pageborder:eq("+i+")").removeClass("pagesl").removeClass("pagesr").addClass("pagesr");
						v = 10 - (i*(12/totcount));
						$(".pageborder:eq("+i+")").css({"left":"","right":v+"px"});
					}
				if($(this).hasClass("show"))
				{
					flag= false;
				}
				i++;
			});	
		}
		
		//editor 
		global_i = 0;
		function editorfunc()
		{
			$(".phtlabel textarea").each(function () {
				global_i++;

				$(this).wysiwyg({
					plugins: {
						autoload: true,
						i18n: { lang: "en" },
						rmFormat: { rmMsWordMarkup: true }
					},

					controls: {
						
						colorpicker: {
							groupIndex: 11,
							visible: true,
							css: {
								"color": function (cssValue, Wysiwyg) {
									var document = Wysiwyg.innerDocument(),
										defaultTextareaColor = $(document.body).css("color");

									if (cssValue !== defaultTextareaColor) {
										return true;
									}

									return false;
								}
							},
							exec: function() {
								if ($.wysiwyg.controls.colorpicker) {
									$.wysiwyg.controls.colorpicker.init(this);
								}
							},
							tooltip: "Colorpicker"
						}
					},

					initialContent: function() {
						var $get = [];
						var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split("&amp;");

						for (var i = 0; i < hashes.length; i++) {
							var hash = hashes[i].split('=');
							$get.push(hash[0]);
							$get[hash[0]] = hash[1];
						}

						if ($get.text) {
							return unescape($get.text.replace(/\+/g, "%20"));
						}
						return "<p>Initial Content " + global_i + "</p>";
					}
				});
			});
		}
		
		//paginations
		function pageLoad(page){
			cpage = page;
			jQuery(".pages").removeClass("show").addClass("hide");
			jQuery("#page-"+cpage).addClass("show");
			pageArrows();
		}
		
		//right side click
		function forwardclick()
		{
			cpage++;
			jQuery(".pages").removeClass("show").addClass("hide");
			jQuery("#page-"+cpage).addClass("show");
			pageArrows();
		}
		
		//left side click
		function backwardclick()
		{
			cpage--;
			jQuery(".pages").removeClass("show").addClass("hide");
			jQuery("#page-"+cpage).addClass("show");
			pageArrows();
		}
		
		function addLabel()
		{
			var str  = "<div class='phtlabel'>";
				str += "<div class='box'>";
				str += "<a class='move' href='javascript:void(0)'> </a>";
				str += "<textarea></textarea>";
				str += "</div>";
				str += "</div>";
			jQuery(".show .page").append(str);
			jQuery(".phtlabel").draggable();
			//jQuery('.phtlabel textarea').wysiwyg();
			editorfunc();
		}
		
		//page display status
		function pageArrows()
		{
			pagescount();
			var pagecount = jQuery(".pages").length;
			if(cpage <= 1)
				{
					jQuery("#backward").css("display","none");
					//jQuery("#backward").css("display","none");
				}
			else
				{
					jQuery("#backward").css("display","block");
				}
			if(cpage >= pagecount)
				{
					jQuery("#forward").css("display","none");
				}
			else
				{
					jQuery("#forward").css("display","block");
				}
			
			
			//paginations
			
			var len = jQuery("#paginations li.pag").length;
			var temp = cpage / 8;
			jQuery("#paginations li.pag").css("display","none");
			
			startcou = parseInt(temp)*8;
			
			if(cpage % 8 <= 2)
				{
					startcou = startcou - 2;
					if(startcou <= 0)
						startcou = 0;
				}
			if(cpage % 8 >= 6)
				{
					startcou = startcou - 8;
					lastcou = startcou + 8;
					if(lastcou >= len)
						startcou = len - 8;
				}
			
			lastcou = startcou + 8;
			console.log(startcou+" "+lastcou);
			
			for(temp = startcou;temp<lastcou;temp++)
			{
				if(temp<len)
					jQuery("#paginations li.pag:eq("+temp+")").css("display","inline");
			}
			
			//jQuery("#paginations li:first").css("display","inline");
			//jQuery("#paginations li:last").css("display","inline");
		}
		
		function addCrop()
		{
			//alert("crop start");
			imagecrop();
		}
		
		function imagecrop()
		{

			      // Create variables (in this scope) to hold the API and image size
			      var jcrop_api, boundx, boundy;
			      
			      $('.bookimg').Jcrop({
			        onChange: updatePreview,
			        onSelect: updatePreview,
			        aspectRatio: 1
			      },function(){
			        // Use the API to get the real image size
			        var bounds = this.getBounds();
			        boundx = bounds[0];
			        boundy = bounds[1];
			        // Store the API in the jcrop_api variable
			        jcrop_api = this;
			      });

			      function updatePreview(c)
			      {
			        if (parseInt(c.w) > 0)
			        {
			          var rx = 100 / c.w;
			          var ry = 100 / c.h;

			          $('#preview').css({
			            width: Math.round(rx * boundx) + 'px',
			            height: Math.round(ry * boundy) + 'px',
			            marginLeft: '-' + Math.round(rx * c.x) + 'px',
			            marginTop: '-' + Math.round(ry * c.y) + 'px'
			          });
			        }
			      };

			 
		}
		
		function fullscreen()
		{
			jQuery(document).toggleFullScreen(true);
		}
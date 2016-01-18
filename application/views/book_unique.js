var APIURL = '/'; 
//https://dev.hardcover.me
var pictureSource; 
var destinationType; 
var cropzoom;


//Called when a photo is successfully retrieved
function onPhotoURISuccess(imageURI) {
  requestCrossDomain(APIURL+'edit_album/get_book_pages_uni', function(res) { });
}

function requestCrossDomain( site, callback ) {  
	
    // If no url was passed, exit.  
    if ( !site ) {  
        alert('No site was passed.');   
        return false;  
    }  
    // Take the provided url, and add it to a YQL query. Make sure you encode it!  
  //  var yql = 'http://query.yahooapis.com/v1/public/yql?q=' + encodeURIComponent('select * from json where url="' + site + '"') + '&format=json&callback=?';  
 //   $.getJSON( yql, function(data){ console.log(data); imagesDisplay(data.query.results.json.book_pages); } );
    $.getJSON( site+"?c=sri"+$.now(), function(data){ imagesDisplay(data.book_pages); } );
}  

var fimg = '';
function imagesDisplay(data)
{
	st = '[';
	st += '{src:"'+data[0].image_url+'",thumb:"'+data[0].image_url+'", title:"cover"},';
	for(i=1,j=0;j<data.length;i++,j++)
	{  
		st += '{src:"'+data[j].image_url+'",thumb:"'+data[j].image_url+'", title:" page "'+i+'},';
	} 
	st += "]";
	 $("#container").flipBook({
            css:"/css/example7.css",
            pages: st,
            lightBox:false,
            webgl:false,
            flipType:"2d"
        });
}

function imagesDisplay2(data)
{ 
	
	if(data.length > 0)
		jQuery("#paginations ul").html("<li><a href='javascript:void(0)' onclick='backwardclick();'>Prev</a></li>");
		
        var temp = 0;
        if($("#bookbg").find(".pages").length > 0)
		temp = $("#bookbg").find(".pages").length;
	temp++;
	//$("#bookbg").find(".pages").remove();
	//$("#bookbg").find(".pagesc").html("");

	if(data.length > 1)
		jQuery("#paginations ul").append("<li class='pag' id='pp_1'><a href='javascript:void(0)' onclick='pageLoad(1)'>Cover</a></li>");
	
	for(i=2,j=0;j<data.length;i++,j+=2)
	{ 
	   if(data[j] == '')
		{
			i--;
			continue;
		}
	if(fimg == "") {
		fimg = data[j].image_url;
		bookcover();
	}

  var str = '<div class="pages" id="page-'+i+'">';
  if((j) >= data.length){
	str += '<div class=" page-left">';
}else{
	str += '<div class="page page-left">';
}
	str += '<img class="bookimg" alt="" src="'+APIURL+'timthumb.php?src='+data[j].image_url+'&w='+dimgwidth+'&zc=1"/>';
	str += '</div>';
 if((j+1) >= data.length){
	str += '<div class=" page-right">';
}else{
	str += '<div class=" page page-right">';
}
	if((j+1) >= data.length)
		str += ' ';
	else
		str += '<img class="bookimg" alt="" src="'+APIURL+'timthumb.php?src='+data[j+1].image_url+'&w='+dimgwidth+'&zc=1"/>';
	str += '</div>';
	str += '</div>';
	$("#bookbg").append(str);
	
	jQuery("#paginations ul").append("<li class='pag' id='pp_"+i+"'><a href='javascript:void(0)' onclick='pageLoad("+i+")'>["+(j+1)+"- "+(j+2)+"]</a></li>");
	
	}
	
	bcoverpage(i);
	
	jQuery("#paginations ul").append("<li class='pag' id='pp_"+i+"'><a href='javascript:void(0)' onclick='pageLoad("+i+")'>Cover</a></li>");

	if(data.length > 0)
		jQuery("#paginations ul").append("<li><a href='javascript:void(0)' onclick='forwardclick();'>Next</a></li>");
//loadcover();
	//initPagination();
	initializebook();
	
}

function bcoverpage(val)
{
	var str = '<div id="page-'+val+'" class="pages hide show">';
	    str += '<div class="page page-left" style="background:#84ACCC;">';
	    str += '<div class="bg_solid">'; 
	    str += '<p  class="top_p" style="margin-bottom:26px;text-align: center;height:52px;padding-top: 15px;"><span style="font-weight:bold;font-size:40px;">&nbsp;</span> </p>';
	    str += '<div class="cover_image_f">';
	    str += '<img  id="back_img"  src="" />'; 
	    str += '</div>';
	    str += '<p class="bottom_p" style="margin-bottom:6px;text-align: center;"><span style="font-weight:bold">&nbsp;</span> </p>';
	    str += '</div>';
	    str += '</div>';
	    str += '<div class="page page-right" style="background:none"></div>';
	    str += '</div>';
	$("#bookbg").append(str);
	if(bcover != "")
	{
		jQuery('#back_img').attr('src','/timthumb.php?src='+fcover+'&w='+dimgwidth+'&h='+(dheight-117)+'&cc=84ACCC');
	}
}

function bookcover()
{
	if(fcover == "")
	{
		jQuery('#front_img').attr('src','/timthumb.php?src='+fimg+'&w='+dimgwidth+'&h='+(dheight-117)+'&cc=84ACCC');
	}
	else
	{
		jQuery('#front_img').attr('src','/timthumb.php?src='+fcover+'&w='+dimgwidth+'&h='+(dheight-117)+'&cc=84ACCC');
	}
	
}


var cpage  = 0;
var dwidth = 0;
var dheight= 0;
var dimgwidth = 0;

jQuery(document).ready(function(){
	
	//initializebook();
	loadBook();
	tei = '';
	onPhotoURISuccess(tei);
	$("#app_loader, #app_loader1").fadeOut("slow");
	
	
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
	
	dimgwidth = dwidth*0.48;
	//console.log(dwidth+" "+dimgwidth);	
	dheight = parseInt((dwidth/3)*2);//jQuery(window).height();
	
	var temp = jQuery(window).height();
	
	if(dheight > temp)
	{
		if($("body#preview").length > 0)
			dheight = temp - 300;
		else
			dheight = temp - 140;
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
	jQuery("#app_loader, #app_loader1").css("display","none");
	
	//jQuery("#forward").trigger("click");
	selectimage();
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
	var r = ((page)*2)-1;
	var q =(page)*2;
	jQuery(".pages").removeClass("show").addClass("hide");
	jQuery("#page-"+cpage).addClass("show");
	jQuery('#paginations li a.select_paginate').removeAttr('style'); 	    
	jQuery('#paginations li a').removeClass('select_paginate');
	jQuery('#pos_val').val(page);
	jQuery('#pos_url').val(r+'-'+q);
	
	 
	jQuery('#pp_'+page+' a').addClass('select_paginate');
	jQuery('#pp_'+page+' a').css('color','#363636');
	jQuery('#pp_'+page+' a').css('cursor','default');
	jQuery('#pp_'+page+' a').css('text-decoration','none');
	
	pageArrows();
}

//right side click
function forwardclick()
{
	cpage++;
	 var l = cpage*2-1;
	  var r = cpage*2;
var pagecount = jQuery(".pages").length;
	if(cpage > pagecount)
		{
		cpage = pagecount;
		return true;
		}

	jQuery('#pos_val').val(cpage);
	jQuery('#pos_url').val(l+'-'+r);
	jQuery(".pages").removeClass("show").addClass("hide");
	jQuery("#page-"+cpage).addClass("show");
	pageArrows();
}

//left side click
function backwardclick()
{
	cpage--;
	 var l = cpage*2-1;
	  var r = cpage*2;
if(cpage < 1)
	{
		cpage = 0;
		return true;
	}
	jQuery('#pos_val').val(cpage);
	jQuery('#pos_url').val(l+'-'+r);
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

var pheight = 0;
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
	if(cpage < 1)
	{
		cpage = 0;
		return true;
	}
	if(cpage > pagecount)
		{
		cpage = pagecount;
		return true;
		}
	//console.log(cpage+" "+pagecount);
	t = (parseInt(cpage)-1)*2;
	if(cpage == 1) {
		location.href = "#";
		if(pheight == 0) {
			pheight = jQuery("#bookbg").height();
			jQuery("#bookbg").height(pheight+10);
		}
	
		jQuery(".pagesc").css("visibility","hidden");
		jQuery("#bookbg").css({"border-right":"5px solid #84ACCC","border-left":"5px solid #FFFFFF","border-top":"0px solid #FFFFFF","border-bottom":"0px solid #FFFFFF","background":"#FFFFFF"});
		jQuery(".book_icons:eq(0)").css("visibility","hidden");
	}
	else if(cpage == pagecount)
	{
		if(pheight == 0) {
			pheight = jQuery("#bookbg").height();
			jQuery("#bookbg").height(pheight+10);
		}
		jQuery(".pagesc").css("visibility","hidden");
		jQuery("#bookbg").css({"border-left":"5px solid #84ACCC","border-right":"5px solid #FFFFFF","border-top":"0px solid #FFFFFF","border-bottom":"0px solid #FFFFFF","background":"#FFFFFF"});
		jQuery(".book_icons:eq(0)").css("visibility","hidden");
	}
	else {
		if(pheight != 0) {
			pheight = jQuery("#bookbg").height();
			jQuery("#bookbg").height(pheight-10);
			pheight = 0;
		}
		location.href="#"+(t-1)+"-"+t;
		jQuery(".pagesc").css("visibility","visible");
		jQuery("#bookbg").css({"border":"5px solid #84ACCC","background":"#F1F6F9"});
		jQuery(".book_icons:eq(0)").css("visibility","visible");
	}
	
	//paginations
	jQuery("li.pag").removeClass("active");
	jQuery("li.pag:eq("+(cpage-1)+")").addClass("active");
var len = jQuery("#paginations").find("li.pag").length;

			var temp = cpage % 8;
			i = 0;
			sideval = 0;
			
			start = 0;
			end = 7;

			if(cpage != 1)
			{
				var sel = 0;
				j = 1;
				jQuery("#paginations li.show").each(function(){
					if(jQuery(this).hasClass("active"))
					  sel = j;
					j++;
				});
				
				if( sel < 3)
				{
					start = cpage - 5;
					if(start < 0)
						start = 0;
				    	end = start + 7;
				}
				if(sel > 6)
				{
					end = cpage + 5;
					if(end >= len)
						end= len;
					start = end - 7;
				}
			}
						
			jQuery("li.pag").removeClass("show").addClass("hide");
			jQuery("#paginations li.pag").each(function(){
				if(start <= i && i <= end)
				 jQuery(this).removeClass("hide").addClass("show");
				i++;
				
			});

			jQuery("#pagination li.show").each(function(){
				idv = this.id;
				if(idv == cpage)
					{
						sideval = i;
					}
				i++;
			});
			
			jQuery("#pagination li.pag").removeClass("show").addClass("hide");
			
			startcou = parseInt(temp)*8;
			
			if(cpage % 8 <= 2)
				{
					startcou = startcou - 2;
					if(startcou <= 0)
						startcou = 0;
				}
			if(cpage % 8 >= 6)
				{
					startcou = cpage - 3;
					if(startcou <= 0)
						startcou = 0;
				}
			
			lastcou = startcou + 8;
			if(lastcou >= len) {
				startcou = len - 8;
				lastcou = startcou + 8;
			}
			
			//console.log(startcou+" "+lastcou);
			
			for(temp = startcou;temp<lastcou;temp++)
			{
				if(temp<len)
					jQuery("#pagination li.pag:eq("+temp+")").removeClass("hide").addClass("show");
			}
	
	//jQuery("#paginations li:first").css("display","inline");
	//jQuery("#paginations li:last").css("display","inline");
}

function addCrop(val)
{
	//alert("crop start");
	//jQuery("#cropfrm").css("display","inline");
	//jQuery("#align-centerw").css("display","none");
	jQuery("."+val+"save").css("display","inline");
	jQuery("."+val).css("display","none");
	imagecrop(val);
}
function saveCrop(val)
{
	jQuery("."+val+"save").css("display","none");
	jQuery("."+val).css("display","inline");
	cropzoom.send('/resize_and_crop.php','POST',{},function(rta){
                $('#dialog').find('img').remove();
                var img = $('<img />').attr('src','/' + rta);
                $('#dialog').append(img);
                $('#dialog').dialog('destroy');
                $('#dialog').dialog({
                  modal:true
                });
            });
}

function restoreCrop(val)
{
	jQuery("."+val+"save").css("display","none");
	jQuery("."+val).css("display","inline");
	cropzoom.restore();
}

function imagecrop(val)
{
	//var imgv = "https://dev.hardcover.me/flipbook/timthumb.php?src=https://sphotos-b.xx.fbcdn.net/hphotos-snc6/5931_1022365337966_7743914_n.jpg&h=393&w=481.20000000000005";
	   var imgv = jQuery("#bookbg .show .page-"+val).find("img").attr("src");

	   cropzoom = $("#bookbg .show .page-"+val).cropzoom({
            width: dimgwidth,
            height: dheight,
            bgColor: '#EEE',
            enableRotation:true,
            enableZoom:true,
            zoomSteps:10,
            rotationSteps:10,
            selector:{        
              centered:true,
              borderColor:'blue',
              borderColorHover:'red'
            },
            image:{
                source: imgv,
                width: dimgwidth,
                height: dheight,
                minZoom:10,
                maxZoom:150
            }
        });

	     /* // Create variables (in this scope) to hold the API and image size
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

	 */
}



function updateCoords(c)
{
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
};

function checkCoords()
{
	if (parseInt($('#w').val())) return true;
	alert('Please select a crop region then press submit.');
	return false;
};

function fullscreen()
{
	jQuery("#main_edit").toggleFullScreen(true);
}

function selectimage()
{
	var temp = window.location.href;
	var url = temp.split("#");
	if(url.length >= 2)
	{
		selimg = url[1];
		url = selimg.split("-");
		if(url.length >= 2) {
			selimg = url[0];

		selimg = (parseInt(selimg)+1)/2;
		pageLoad(selimg+1);
		jQuery('#bookbg_cover_front').hide();
		jQuery('#book_cont').css('visibility','visible');
		}
		else
			jQuery("#forward").trigger("click");
	}
	else
	{
		jQuery("#forward").trigger("click");
	}
}

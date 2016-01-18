var APIURL = '/';
//http://dev.hardcover.me
var pictureSource;
var destinationType;
var cropzoom;
var descriptions = new Array();
var titles = new Array();
var page_ids = new Array();
var thisUrl = "";
//Called when a photo is successfully retrieved
function onPhotoURISuccess(imageDomain, imageURI) {
    requestCrossDomain(imageDomain, APIURL + 'edit_album/get_book_pages_share', function (res) {});
}

function requestCrossDomain(imageDomain, site, callback) {

    // If no url was passed, exit.
    if (!site) {
        alert('No site was passed.');
        return false;
    }
    // Take the provided url, and add it to a YQL query. Make sure you encode it!
    //  var yql = 'http://query.yahooapis.com/v1/public/yql?q=' + encodeURIComponent('select * from json where url="' + site + '"') + '&format=json&callback=?';
    //   $.getJSON( yql, function(data){ console.log(data); imagesDisplay(data.query.results.json.book_pages); } );
    $.getJSON(site + "?c=sri" + $.now(), function (data) {
        imagesDisplay(imageDomain, data.book_pages);
    });
}

function imagesDisplay(imageDomain, data) {
    if (data && data.length > 0) {
        thisUrl = imageDomain;
        $("#insertContentHere").html("");
        jQuery("#paginations ul").html("");
        //console.log(data);
        console.log("creating images dennis");
        wW = jQuery("#bookbg").width();
        if (wW >= 1920) {
            var bgWidth = 1920;
            var bgHeight = 1440;
            var smWidth = 640;
            var smHeight = 480;
        }
        if (wW < 1920) {
            var bgWidth = 1680;
            var bgHeight = 1050;
            var smWidth = 480;
            var smHeight = 320;
        }
        if (wW < 1680) {
            var bgWidth = 1440;
            var bgHeight = 900;
            var smWidth = 320;
            var smHeight = 240;
        }
        if (wW < 1440) {
            var bgWidth = 1366;
            var bgHeight = 768;
            var smWidth = 150;
            var smHeight = 150;
        }

        var tW = bgWidth / 2;
        var tH = (8 / 11) * tW;
        jQuery("#bookbg").height((tH + 70) + "px");
        jQuery(".pagesc").height((tH + 49) + "px");
        console.log("editor page width: " + bgWidth + " height: " + bgHeight);

        if (data.length > 0)
            jQuery("#paginations ul").append("<li><a href='javascript:void(0)' onclick='backwardclick();'>Prev</a></li>");

        var temp = 0;
        var urlToUse = imageDomain + '/' + bgWidth + "x" + bgHeight + "/";
        urlToUse = '/timthumb.php?src=' + imageDomain + "/";
        if ($("#bookbg").find(".pages").length > 0)
            temp = $("#bookbg").find(".pages").length;

        temp++;


        if (data.length > 1)
        //josh fix for 1st pages being blank
            var str = '<div class="pages" id="page-' + 1 + '">';

        if (data[0].image_url.indexOf('.fbcdn.net') != -1) {
            image_url = '/timthumb.php?src=' + data[0].image_url + '&h=' + tH + '&w=' + tW+ '&zc=2';
        } else {
            image_url = urlToUse + data[0].image_url + '&h=' + tH + '&w=' + tW+ '&zc=2';// + data[0].image_url;
        }

        str += '<div class="page page-left">';
        str += '<img class="bookimg" alt="" src="' + image_url + '"  />';
        str += '</div>';

        if (data[1]) {
            if (data[1].image_url.indexOf('.fbcdn.net') != -1) {
                image_url = '/timthumb.php?src=' + data[1].image_url + '&h=' + tH + '&w=' + tW+ '&zc=2';
            } else {
                image_url = urlToUse + data[1].image_url + '&h=' + tH + '&w=' + tW+ '&zc=2';// + data[1].image_url;
            }

            str += '<div class=" page page-right">';
            str += '<img class="bookimg" alt="" src="' + image_url + '" style="margin-top:20px;" />';
            str += '</div>';
        }

        str += '</div>';
        $("#insertContentHere").append(str);

        jQuery("#paginations ul").append("<li class='pag' id='pp_1'><a href='javascript:void(0)' onclick='pageLoad(1)'>[1-2]</a></li>");
        titles[0] = data[0].title;
        descriptions[0] = data[0].description;
        page_ids[0] = data[0].book_pages_id;
        titles[1] = data[1].title;
        descriptions[1] = data[1].description;
        page_ids[1] = data[1].book_pages_id;
        var flag = true;
        for (i = 2, j = 2; j < data.length; i++, j += 2) {
            if (data[j] == '') {
                i--;
                continue;
            }
            var str = '<div class="pages" id="page-' + i + '">';

            if ((j) >= data.length) {
                str += '<div class=" page-left">';
            } else {
                str += '<div class="page page-left">';
            }

            console.log('image url: ' + data[j].image_url);
            console.log('urlToUse: ' + urlToUse);

            if (data[j].image_url.indexOf('.fbcdn.net') != -1) {
                image_url = '/timthumb.php?src=' + data[j].image_url + '&h=' + tH + '&w=' + tW+ '&zc=2';
            } else {
                image_url = urlToUse + data[j].image_url + '&h=' + tH + '&w=' + tW+ '&zc=2';// + data[j].image_url;
            }
            titles[j] = data[j].title;
            descriptions[j] = data[j].description;
            page_ids[j] = data[j].book_pages_id;
            str += '<img class="bookimg" alt="" src="' + image_url + '" />';
            str += '</div>';

            if ((j + 1) >= data.length) {
                str += '<div class=" page-right">';
            } else {
                str += '<div class=" page page-right">';
            }

            if ((j + 1) >= data.length)
                str += ' ';
            else {

                if (data[j + 1].image_url.indexOf('.fbcdn.net') != -1) {
                    image_url = '/timthumb.php?src=' + data[j + 1].image_url + '&h=' + tH + '&w=' + tW+ '&zc=2';
                } else {
                    image_url = urlToUse + data[j + 1].image_url + '&h=' + tH + '&w=' + tW+ '&zc=2';// + data[j + 1].image_url;
                }

                str += '<img class="bookimg" alt="" src="' + image_url + '" />';
                titles[j+1] = data[j+1].title;
                descriptions[j+1] = data[j+1].description;
                page_ids[j+1] = data[j+1].book_pages_id;
            }
            str += '</div>';
            str += '</div>';
            $("#insertContentHere").append(str);

            jQuery("#paginations ul").append("<li class='pag' id='pp_" + i + "'><a href='javascript:void(0)' onclick='pageLoad(" + i + ")'>[" + (j + 1) + "- " + (j + 2) + "]</a></li>");


        }
        $(".bookimg").css({
            "max-width": tW + 'px',
            "max-height": tH + 'px',
            "margin-top": '10px',
            "display": 'table-cell',
            'vertical-align': 'middle',
            "margin": "auto",
            "position": "absolute",
            "top": "0",
            "bottom": "0",
            "left": "0",
            "right": "0"
        });
        if (data.length > 0)
            jQuery("#paginations ul").append("<li><a href='javascript:void(0)' onclick='forwardclick();'>Next</a></li>");

        initializebook();
    } else {
        alert("Book data is empty.");
    }
}
function checkImage (src, good, bad) {
    var img = new Image();
    img.onload = good;
    img.onerror = bad;
    img. src = src;
}

//checkImage( "foo.gif", function(){ alert("good"); }, function(){ alert("bad"); } );
var cpage = 0;
var dwidth = 0;
var dheight = 0;
var dimgwidth = 0;
/*
jQuery(document).ready(function(){

	//initializebook();
	loadBook();
	tei = '';
	onPhotoURISuccess(tei);
});
*/

jQuery(window).resize(function () {
    loadBook();
    resizeImages();
    //imagecrop();
});



function resizeImages() {
    jQuery(".pages.show .booking").each(function () {
        resizeImg(jQuery(this));
    });

    jQuery(".bookimg").each(function () {
        resizeImg(jQuery(this));
    });
}

function resizeImg(img) {
    wW = jQuery("#bookbg").width();
    //    wW = wW / 2;
    if (wW >= 1920) {
        var bgWidth = 1920;
        var bgHeight = 1440;
        var smWidth = 640;
        var smHeight = 480;
    }
    if (wW < 1920) {
        var bgWidth = 1680;
        var bgHeight = 1050;
        var smWidth = 480;
        var smHeight = 320;
    }
    if (wW < 1680) {
        var bgWidth = 1440;
        var bgHeight = 900;
        var smWidth = 320;
        var smHeight = 240;
    }
    if (wW < 1440) {
        var bgWidth = 1366;
        var bgHeight = 768;
        var smWidth = 150;
        var smHeight = 150;
    }

    var urlToUse = thisUrl + '/' + bgWidth + "x" + bgHeight + "/";

    //console.log('urlToUse: '+urlToUse);
    var imgval = img.src;
    //console.log(typeof imgval);
    var tW = bgWidth / 2;
    var tH = (tW / 11) * 8;
    if (typeof imgval == "string") {
        img.attr("src", "");
        temp = imgval.split("/");
        urlToUse = '/timthumb.php?src=' + thisUrl + "/";
        imgval = urlToUse + temp[5] + '&h=' + tH + '&w=' + tW;// + temp[5];
        img.attr("width", "auto");
        img.attr("height", "auto");
        img.css("max-width", tW + "px");
        img.css("max-height", tH + "px");
        img.attr("src", imgval);
    }
}


function loadBook() {
    dwidth = jQuery("#bookbg").width();
    dimgwidth = dwidth * 0.40;
    dheight = parseInt((dimgwidth / 11) * 8); //jQuery(window).height();
    console.log("editor page width: " + dimgwidth + " height: " + dheight);

    var temp = jQuery(window).height();

    if (dheight > temp) {
        if ($("body#preview").length > 0)
            dheight = temp - 220;
        else
            dheight = temp - 60;
    }

    jQuery(".pagesc").css("height", dheight + "px");
}

function initializebook() {
    jQuery("#forward").unbind("click").bind("click", function () {
        forwardclick();
    });
    jQuery("#backward").unbind("click").bind("click", function () {
        backwardclick()
    });

    //jQuery("#forward").trigger("click");
    pageLoad(1);
    jQuery("#app_loader").css("display", "none");
}




function pagescount() {
    flag = true;
    var i = 0;
    var leftpage = 0;
    var totcount = jQuery(".pages").length;
    var temppage = jQuery(".pageborder").length;
    var temp = totcount - temppage;
    for (i = 0; i < temp; i++) {
        jQuery(".pagesc").append('<div class="pageborder"></div>')
    }
    i = 0;
    jQuery(".pages").each(function () {
        $(".pageborder:eq(" + i + ")").removeClass("pagesl").removeClass("pagesr").addClass("pagesl");
        v = 10 - (i * (12 / totcount));

        $(".pageborder:eq(" + i + ")").css({
            "right": "",
            "left": v + "px"
        });
        if (flag == false) {
            $(".pageborder:eq(" + i + ")").removeClass("pagesl").removeClass("pagesr").addClass("pagesr");
            v = 10 - (i * (12 / totcount));
            $(".pageborder:eq(" + i + ")").css({
                "left": "",
                "right": v + "px"
            });
        }
        if ($(this).hasClass("show")) {
            flag = false;
        }
        i++;
    });
}

//editor
global_i = 0;

function editorfunc() {
    $(".phtlabel textarea").each(function () {
        global_i++;

        $(this).wysiwyg({
            plugins: {
                autoload: true,
                i18n: {
                    lang: "en"
                },
                rmFormat: {
                    rmMsWordMarkup: true
                }
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
                    exec: function () {
                        if ($.wysiwyg.controls.colorpicker) {
                            $.wysiwyg.controls.colorpicker.init(this);
                        }
                    },
                    tooltip: "Colorpicker"
                }
            },

            initialContent: function () {
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
function pageLoad(page) {

    cpage = page;
    var r = (page - 1) * 2;
    var q = (page - 1) * 2 - 1;
    jQuery(".pages").removeClass("show").addClass("hide");
    jQuery("#page-" + cpage).removeClass("hide").addClass("show");
    jQuery('#paginations li a.select_paginate').removeAttr('style');
    jQuery('.addthis_toolbox').attr('addthis:url', jQuery('#url').val() + '#' + q + '-' + r);
    jQuery('#paginations li a').removeClass('select_paginate');


    jQuery('#pp_' + page + ' a').addClass('select_paginate');
    jQuery('#pp_' + page + ' a').css('color', '#363636');
    jQuery('#pp_' + page + ' a').css('cursor', 'default');
    jQuery('#pp_' + page + ' a').css('text-decoration', 'none');
    showInfo(cpage);
    pageArrows();
}

//right side click
function forwardclick() {
    cpage++;
    jQuery(".pages").removeClass("show").addClass("hide");
    jQuery("#page-" + cpage).removeClass("hide").addClass("show");
    showInfo(cpage+1);
    pageArrows();
}

//left side click
function backwardclick() {
    cpage--;
    jQuery(".pages").removeClass("show").addClass("hide");
    jQuery("#page-" + cpage).removeClass("hide").addClass("show");
    showInfo(cpage-1);
    pageArrows();
}
function showInfo(cPage){
    cPage--;
    var thisTitleLeft = $("#left-page-info #title");
    var thisDescLeft = $("#left-page-info #description");
    var thisTitleRight = $("#right-page-info #title");
    var thisDescRight = $("#right-page-info #description");

    thisTitleLeft.val(titles[cPage]);
    thisTitleRight.val(titles[(cPage*1)+1]);

    thisDescLeft.val(descriptions[cPage]);
    thisDescRight.val(descriptions[(cPage*1)+1]);

    $("#left-page-info #book_pages_id").val(page_ids[cPage]);
    $("#right-page-info #book_pages_id").val(page_ids[(cPage*1)+1]);
    //console.log(cPage);
    //console.log(titles);
    //console.log(descriptions);
}
function addLabel() {
    var str = "<div class='phtlabel'>";
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
function pageArrows() {
    pagescount();
    var pagecount = jQuery(".pages").length;
    if (cpage <= 1) {
        jQuery("#backward").css("display", "none");
        //jQuery("#backward").css("display","none");
    } else {
        jQuery("#backward").css("display", "block");
    }
    if (cpage >= pagecount) {
        jQuery("#forward").css("display", "none");
    } else {
        jQuery("#forward").css("display", "block");
    }
    doPagination();
}
//josh mod for pagination
function doPagination() {
    $("li.pag").removeClass("active");
    $("li#pp_" + cpage).addClass("active");
    var pTotal = $("#paginations").find("li.pag").length;
    //console.log(pTotal);
    var cMax = 0;
    for (var i = 0; i <= pTotal; i++) {
        if (cpage > cMax && cpage <= cMax + 10)
            $("li#pp_" + i).removeClass("hide").addClass("show");
        else
            $("li#pp_" + i).removeClass("show").addClass("hide");
        if (i == (cMax + 10))
            cMax += 10;
    }
}

function test() {
    //paginations
    jQuery("li.pag").removeClass("active");
    jQuery("li.pag:eq(" + (cpage - 1) + ")").addClass("active");

    var len = jQuery("#paginations").find("li.pag").length;

    var temp = cpage % 8;
    i = 0;
    sideval = 0;

    start = 0;
    end = 7;

    if (cpage != 1) {
        var sel = 0;
        j = 1;
        jQuery("#paginations li.show").each(function () {
            if (jQuery(this).hasClass("active"))
                sel = j;
            j++;
        });

        if (sel < 3) {
            start = cpage - 5;
            if (start < 0)
                start = 0;
            end = start + 7;
        }
        if (sel > 6) {
            end = cpage + 5;
            if (end >= len)
                end = len;
            start = end - 7;
        }
    }

    jQuery("li.pag").removeClass("show").addClass("hide");
    jQuery("#paginations li.pag").each(function () {
        if (start <= i && i <= end)
            jQuery(this).removeClass("hide").addClass("show");
        i++;

    });

    jQuery("#pagination li.show").each(function () {
        idv = this.id;
        if (idv == cpage) {
            sideval = i;
        }
        i++;
    });

    jQuery("#pagination li.pag").removeClass("show").addClass("hide");

    startcou = parseInt(temp) * 8;

    if (cpage % 8 <= 2) {
        startcou = startcou - 2;
        if (startcou <= 0)
            startcou = 0;
    }
    if (cpage % 8 >= 6) {
        startcou = cpage - 3;
        if (startcou <= 0)
            startcou = 0;
    }

    lastcou = startcou + 8;
    if (lastcou >= len) {
        startcou = len - 8;
        lastcou = startcou + 8;
    }

    console.log(startcou + " " + lastcou);

    for (temp = startcou; temp < lastcou; temp++) {
        console.log(temp);
        if (temp < len)
            jQuery("#pagination li.pag:eq(" + temp + ")").removeClass("hide").addClass("show");
    } //jQuery("#paginations li:first").css("display","inline");
    //jQuery("#paginations li:last").css("display","inline");
}

function addCrop(val) {
    //alert("crop start");
    //jQuery("#cropfrm").css("display","inline");
    //jQuery("#align-centerw").css("display","none");
    jQuery("." + val + "save").css("display", "inline");
    jQuery("." + val).css("display", "none");
    imagecrop(val);
}

function saveCrop(val) {
    jQuery("." + val + "save").css("display", "none");
    jQuery("." + val).css("display", "inline");
    cropzoom.send('/resize_and_crop.php', 'POST', {}, function (rta) {
        $('#dialog').find('img').remove();
        var img = $('<img />').attr('src', '/' + rta);
        $('#dialog').append(img);
        $('#dialog').dialog('destroy');
        $('#dialog').dialog({
            modal: true
        });
    });
}

function restoreCrop(val) {
    jQuery("." + val + "save").css("display", "none");
    jQuery("." + val).css("display", "inline");
    cropzoom.restore();
}

function imagecrop(val) {
    //var imgv = "http://dev.hardcover.me/flipbook/timthumb.php?src=https://sphotos-b.xx.fbcdn.net/hphotos-snc6/5931_1022365337966_7743914_n.jpg&h=393&w=481.20000000000005";
    var imgv = jQuery("#bookbg .show .page-" + val).find("img").attr("src");

    cropzoom = $("#bookbg .show .page-" + val).cropzoom({
        width: dimgwidth,
        height: dheight,
        bgColor: '#EEE',
        enableRotation: true,
        enableZoom: true,
        zoomSteps: 10,
        rotationSteps: 10,
        selector: {
            centered: true,
            borderColor: 'blue',
            borderColorHover: 'red'
        },
        image: {
            source: imgv,
            width: dimgwidth,
            height: dheight,
            minZoom: 10,
            maxZoom: 150
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

function updateCoords(c) {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
};

function checkCoords() {
    if (parseInt($('#w').val())) return true;
    alert('Please select a crop region then press submit.');
    return false;
};

function fullscreen() {
    jQuery("#main_edit").toggleFullScreen(true);
}
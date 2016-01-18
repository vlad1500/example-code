var thisURL = 'dev.hardcover.me';
var l = window.location;
var base_url = l.protocol + "//" + l.host;
var hardcover = l.hash;
hardcover = hardcover.replace("#","");
if (!window.jQuery) {
    // jQuery is not loaded
    var js = document.createElement("script");
    js.type = "text/javascript";
    js.src = "//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js";
    document.head.appendChild(js);
}

document.onreadystatechange = function () {
    var state = document.readyState;
    if (state == 'interactive') {
        doNothing();
    } else if (state == 'complete') {
        if(hardcover.indexOf("hardcover=") != -1)
            goInit();
    }
};
    $(document).ready(function(){
        $(".hardCover").on("click",function(){
            setTimeout(function () {
                l = window.location;
                base_url = l.protocol + "//" + l.host;
                hardcover = l.hash;
                hardcover = hardcover.replace("#","");
                goInit();
                setTimeout(function () {
                    if(hardcover.indexOf("hardcover=") != -1){
                        jQuery("body").goHardcover();
                    }
                }, 10);
            }, 10);
        });
    });
function goInit() {
    if($("#brandScript").length == 0){
        var js = document.createElement("script");
        js.type = "text/javascript";
        js.src = l.protocol+"//"+thisURL+"/min/index.php?g=brandJS";
        $(js).attr("id","brandScript");
        document.body.appendChild(js);
    }
    //jQuery.noConflict();
    (function init($, window, document, undefined) {
        $.fn.goHardcover = function (options) {
            //entry point
            return this.each(function () {
                var goHardcover = new Main();
                goHardcover.init(options, this);
            });
        };
        $.fn.goHardcover.options = {
            addJS:true,
            popUp:true,
            ifDev:false
        };
        var Main = function (){

        };
        Main.prototype = {
            init:function(options,elem){
                var self = this;
                self.elem = elem;
                self.$elem = $(elem);
                self.options = {};
                self.options = $.extend({}, $.fn.goHardcover.options, options);
                self.options.main = self;
                self.start();
            },
            start:function (){
                this.createWrap();
                if(this.options.addJS)
                    this.addJSfiles();
                if(this.options.popUp)
                    this.popBook();
            },
            createWrap:function () {
                var self = this;
                $(".hardcover-wrapper").each(function(){
                    $(this).remove();
                });
                self.wrapper = $(document.createElement('div'))
                    .addClass('hardcover-wrapper')
                    .attr("id","hardCoverCon")
                ;
                self.wrapper.appendTo(self.$elem);
                self.thisStyle = $(document.createElement('link'))
                    .attr('rel','stylesheet')
                    .attr("href",l.protocol+"//"+thisURL+"/min/index.php?g=brandCSS")
                ;
                self.thisStyle.appendTo(self.wrapper);
            },
            addJSfiles:function () {
                var self = this;
                function setMT(metaName, name, value) {
                    var t = 'meta['+metaName+'="'+name+'"]';
                    var mt = $(t);
                    if (mt.length === 0) {
                        t = '<meta '+metaName+'="'+name+'" />';
                        mt = $(t).appendTo('head');
                    }
                    mt.attr('content', value);
                }
                setMT('property', 'og:title', 'Hardcover - BRAND TEST');
                setMT('property', 'og:type', 'website');
            },
            popBook:function () {
                var self = this;
                var wW=($(window).width()*.98), wH=($(window).height()*.89);
                if(hardcover.indexOf("hardcover=") != -1){
                    var hBook = hardcover.split("=")[1].split(":");
                    console.log(hBook);
                    $(".openThisLink").each(function(){
                        $(this).remove();
                    });
                    var hLinkHref = l.protocol+"//"+thisURL+"/books/"+hBook[0]+"/"+hBook[1]+"#"+hBook[2]+"?iframe=true";
                    var oLink = $(document.createElement( 'a' ))
                            .attr("href",hLinkHref)
                            .attr("rel","prettyPhoto[iframes]")
                            .addClass("openThisLink")
                            .css("display","hidden")
                    ;
                    this.wrapper.append(oLink);
                    //console.log($(".openThisLink"));
                    setTimeout(function () {
                        $(".openThisLink").prettyPhoto({
                            slideshow:false,
                            show_title: false,
                            allow_resize: false,
                            default_width: wW,
		    	            default_height: wH,
                            modal: false
                        });
                        $(".pp_pic_holder").each(function(){
                            $(this).remove();
                        });
                        $(".pp_overlay").each(function(){
                            $(this).remove();
                        });
                        $(".openThisLink").click();
                        location.hash = hardcover;
                    }, 10);
                }
            }
        };
    })(jQuery, window, document);
    $(document).ready(function(){
        setTimeout(function () {
            if(hardcover.indexOf("hardcover=") != -1){
                //jQuery("body").html("");
                jQuery("body").goHardcover();
            }
        }, 10);
    });
}
function doNothing(){

}
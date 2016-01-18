
(function init($, window, document, undefined) {
    /**
     * Plugin constructor method
     * @param options Object containing options, overrides default options
     * @return {*}
     */
    $.fn.flipBook = function (options) {
        //entry point
        return this.each(function () {
            var flipBook = new Main();
            flipBook.init(options, this);
        });
    };

//    $.fn.flipBook.goToPage = function (page) {
//        if(this.FlipBook.Book)
//            this.FlipBook.Book.goToPage(page);
//    };

    // DEFAULT OPTIONS
    $.fn.flipBook.options = {
        //stylesheet for the plugin, default:included in the html
        css:"",

        //pdf file  - not supported
        pdf:"",

        //array of page objects - this must be passed to plugin constructor
        // {
        // src:"page url",
        // thumb:"thumb url",
        // title:"page title for table of contents"
        // }
        pages:[],

        assets:{
            preloader:"images/preloader.jpg",
            left:"images/left.png",
            overlay:"images/overlay.jpg"
        },

        //page that will be displayed when the book starts
        startPage:1,

        //book default settings
        pageWidth:1000,
        pageHeight:1414,
        thumbnailWidth:100,
        thumbnailHeight:141,

        //menu buttons
        currentPage:true,
        btnNext:true,
        btnPrev:true,
        btnZoomIn:true,
        btnZoomOut:true,
        btnToc:true,
        btnThumbs:true,
        btnShare:true,
        btnExpand:true,

        //flip animation type; can be "2d" or "3d"
        flipType:'3d',

        zoom:.8,
        zoomMin:.7,
        zoomMax:6,

        //flip animation parameters
        time1:500,
        transition1:'easeInQuad',
        time2:600,
        transition2:'easeOutQuad',

        //social share buttons -  if value is "" the button will not be displayed
        social:[
            {name:"facebook", icon:"icon-facebook", url:"http://codecanyon.net"},
            {name:"twitter", icon:"icon-twitter", url:"http://codecanyon.net"},
            {name:"googleplus", icon:"icon-googleplus", url:"http://codecanyon.net"},
            {name:"linkedin", icon:"icon-linkedin", url:"http://codecanyon.net"},
            {name:"youtube", icon:"icon-youtube", url:"http://codecanyon.net"}
        ],
//        facebook:"http://codecanyon.net",
//        twitter:"http://codecanyon.net",
//        googleplus:"http://codecanyon.net",
//        linkedin:"http://codecanyon.net",


        //lightbox settings
        lightBox : false,
        lightboxTransparent:true,
        lightboxPadding : 0,
        lightboxMargin  : 20,

        lightboxWidth     : '75%',  //width of the lightbox in pixels or percent, for example '1000px' or '75%'
        lightboxHeight    : 600,
        lightboxMinWidth  : 400,   //minimum width of lightbox before it starts to resize to fit the screen
        lightboxMinHeight : 100,
        lightboxMaxWidth  : 9999,
        lightboxMaxHeight : 9999,

        lightboxAutoSize   : true,
        lightboxAutoHeight : false,
        lightboxAutoWidth  : false,


        //WebGL settings

        webgl:false,

        //web gl 3d settings
        cameraDistance:2500,

        pan:0,
        panMax:5,
        panMin:-5,
        tilt:0,
        tiltMax:0,
        tiltMin:-60,

        //book
        bookX:0,
        bookY:0,
        bookZ:0,

        //pages
        pageMaterial:'phong',                     // page material, 'phong', 'lambert' or 'basic'
        pageShadow:false,
        pageHardness:1,
        coverHardness:4,
        pageSegmentsW:10,
        pageSegmentsH:3,
        pageShininess:25,
        pageFlipDuration:2,

        //point light
        pointLight:false,                            // point light enabled
        pointLightX:0,                              // point light x position
        pointLightY:0,                              // point light y position
        pointLightZ:2000,                           // point light z position
        pointLightColor:0xffffff,                   // point light color
        pointLightIntensity:0.1,                    // point light intensity

        //directional light
        directionalLight:false,                     // directional light enabled
        directionalLightX:0,                        // directional light x position
        directionalLightY:0,                        // directional light y position
        directionalLightZ:1000,                     // directional light z position
        directionalLightColor:0xffffff,             // directional light color
        directionalLightIntensity:0.3,              // directional light intensity

        //ambient light
        ambientLight:true,                          // ambient light enabled
        ambientLightColor:0xcccccc,                 // ambient light color
        ambientLightIntensity:0.2,                  // ambient light intensity

        //spot light
        spotLight:true,                             // spot light enabled
        spotLightX:0,                               // spot light x position
        spotLightY:0,                               // spot light y position
        spotLightZ:5000,                            // spot light z position
        spotLightColor:0xffffff,                    // spot light color
        spotLightIntensity:0.2,                     // spot light intensity
        spotLightShadowCameraNear:0.1,              // spot light shadow near limit
        spotLightShadowCameraFar:10000,             // spot light shadow far limit
        spotLightCastShadow:true,                   // spot light casting shadows
        spotLightShadowDarkness:0.5                 // spot light shadow darkness

    };

    /**
     *
     * @constructor
     */
    var Main = function (){

    };
    /**
     * Object prototype
     * @type {Object}
     */
    Main.prototype = {

        init:function(options,elem){
            /**
             * local variables
             */
            var self = this;
            self.elem = elem;
            self.$elem = $(elem);
            self.options = {};

            //stats for debug
//            var
//                stats,
//                createStats = function () {
//                    stats = new Stats();
//                    stats.domElement.style.position = 'absolute';
//                    stats.domElement.style.top = '0px';
//                    self.$elem.append($(stats.domElement));
//                }();
//
//
//            function animate() {
//                requestAnimationFrame(animate);
//                stats.update();
//            }
//            animate();

            var dummyStyle = document.createElement('div').style,
                vendor = (function () {
                    var vendors = 't,webkitT,MozT,msT,OT'.split(','),
                        t,
                        i = 0,
                        l = vendors.length;

                    for (; i < l; i++) {
                        t = vendors[i] + 'ransform';
                        if (t in dummyStyle) {
                            return vendors[i].substr(0, vendors[i].length - 1);
                        }
                    }
                    return false;
                })(),
                prefixStyle = function (style) {
                    if (vendor === '') return style;

                    style = style.charAt(0).toUpperCase() + style.substr(1);
                    return vendor + style;
                },

                isAndroid = (/android/gi).test(navigator.appVersion),
                isIDevice = (/iphone|ipad/gi).test(navigator.appVersion),
                isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),
                has3d = prefixStyle('perspective') in dummyStyle,
                hasTouch = 'ontouchstart' in window && !isTouchPad,
                RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize',
                CLICK_EV = hasTouch ? 'touchend' : 'click',
                START_EV = hasTouch ? 'touchstart' : 'mousedown',
                MOVE_EV = hasTouch ? 'touchmove' : 'mousemove',
                END_EV = hasTouch ? 'touchend' : 'mouseup',
                CANCEL_EV = hasTouch ? 'touchcancel' : 'mouseup',
                transform = prefixStyle('transform'),
                perspective = prefixStyle('perspective'),
                transition = prefixStyle('transition'),
                transitionProperty = prefixStyle('transitionProperty'),
                transitionDuration = prefixStyle('transitionDuration'),
                transformOrigin = prefixStyle('transformOrigin'),
                transformStyle = prefixStyle('transformStyle'),
                transitionTimingFunction = prefixStyle('transitionTimingFunction'),
                transitionDelay = prefixStyle('transitionDelay'),
                backfaceVisibility = prefixStyle('backfaceVisibility')
                ;

            /**
             * Global variables
             */
            self.has3d = has3d;
            self.hasWebGl  = Detector.webgl;
            self.hasTouch = hasTouch;
            self.RESIZE_EV = RESIZE_EV;
            self.CLICK_EV = CLICK_EV;
            self.START_EV = START_EV;
            self.MOVE_EV = MOVE_EV;
            self.END_EV = END_EV;
            self.CANCEL_EV = CANCEL_EV;
            self.transform = transform;
            self.transitionProperty = transitionProperty;
            self.transitionDuration = transitionDuration;
            self.transformOrigin = transformOrigin;
            self.transitionTimingFunction = transitionTimingFunction;
            self.transitionDelay = transitionDelay;
            self.perspective = perspective;
            self.transformStyle = transformStyle;
            self.transition = transition;
            self.backfaceVisibility = backfaceVisibility;

            //default options are overridden by options object passed to plugin constructor
            self.options = $.extend({}, $.fn.flipBook.options, options);
            self.options.main = self;
            self.p = false;

            self.options.css == "" ? self.start() : self.loadCSS(self.options.css);
        },


        /**
         * start everything, after we have options
         */
        start:function (){
            this.started = true;
            this.createBook();
            this.Book.updateVisiblePages();
            this.createMenu();
            if(this.options.currentPage){
                this.createCurrentPage();
                this.updateCurrentPage();
            }
            this.createToc();
            this.createThumbs();
            if(this.options.btnShare)
                this.createShareButtons();
            this.resize();
        },

        loadCSS:function(url){

            $('#flipBookCSS').remove();
            var self = this;
            //append css to head tag
            $('<link rel="stylesheet" type="text/css" href="'+url+'" id="flipBookCSS" />').appendTo("head");
            //wait for css to load
            $.ajax({
                url:url,
                success:function(data){
                    //css is loaded
                    //start the app
                    self.start();
                }
            })
        },

        reloadCSS:function(url){
            $('#flipBookCSS').remove();


            //append css to head tag
            $('<link rel="stylesheet" type="text/css" href="'+url+'" id="flipBookCSS" />').appendTo("head");
            var self = this;
            //wait for css to load
            $.ajax({
                url:url,
                success:function(data){
                    //css is loaded

                    self.resize();
                }
            })
        },

        /**
         * create the book
         */
        createBook : function () {
            var self = this;

            if(self.options.pages.length % 2 != 0)
                alert('Number of pages must be even (2,4,6...)');
            self.wrapper = $(document.createElement('div'))
                .addClass('main-wrapper')
            ;
            self.bookLayer = $(document.createElement('div'))
                .addClass('flipbook-bookLayer')
                .appendTo(self.wrapper)
            ;
            self.bookLayer[0].style[self.transformOrigin] = '100% 100%';

            self.book = $(document.createElement('div'))
                .addClass('book')
                .appendTo(self.bookLayer)
            ;

            //if lightbox
            if(self.options.lightBox){
                self.lightbox = new FLIPBOOK.Lightbox(this, self.wrapper,self.options);
                if(self.options.lightboxTransparent == true){
                    self.wrapper.css('background','none');
                    self.bookLayer.css('background','none');
                    self.book.css('background','none');
                }
            }
            else{
                self.wrapper.appendTo(self.$elem);
            }


            self.options.onTurnPageComplete = self.onTurnPageComplete;
            if(!self.has3d)
                self.options.flipType = '2d';
            //WebGL mode
            if(self.options.webgl && self.hasWebGl){
//                if(self.options.webgl && self.hasWebGl){
                var bookOptions = self.options;
                bookOptions.pagesArr = self.options.pages;
                bookOptions.scroll = self.scroll;
                bookOptions.parent = self;
                self.Book = new FLIPBOOK.BookWebGL(self.book[0], bookOptions);
                self.webglMode = true;
            }else{
                self.Book = new FLIPBOOK.Book(self.book[0], self.options);

                self.scroll = new iScroll(self.bookLayer[0], {
//                bounce:false,
                    wheelAction:'zoom',
                    zoom:true,
                    zoomMin:self.options.zoomMin,
                    zoomMax:self.options.zoomMax,
                    keepInCenterH:true,
                    keepInCenterV:true,
                    bounce:false
                });
                self.webglMode = false;
            }
//            self.currentPage = $(document.createElement('div'))
//                .attr('id','currentPage');
//            self.updateCurrentPage();
            self.Book.goToPage(Number(self.options.startPage)-1);

            $(window).resize(function () {
                self.resize();
            });
        },

        /**
         * create menu
         */
        createMenu:function(){
            var self = this;
            this.menuWrapper = $(document.createElement('div'))
                .addClass('flipbook-menuWrapper')
                .appendTo(this.wrapper)
            ;
            this.menu = $(document.createElement('div'))
                    .addClass('flipbook-menu')
                    .addClass('skin-color-bg')
                    .appendTo(this.menuWrapper)
                ;
            if(this.options.lightboxTransparent){
//                this.menu.css('background','none');
//                this.menu.css('border','none');

            }

//            var btnFirst = $(document.createElement('a'))
//                .appendTo(menu)
//                .bind(this.CLICK_EV, function(){
//                    self.Book.firstPage();
//                })
//                .addClass('flipbook-menu-btn')
//                .addClass('first');
            if(self.options.btnPrev)
            {
                var btnPrev = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.Book.prevPage();
                    })
                    .addClass('icon-arrow-left')
//                    .addClass('icon-arrow-left-2')
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('skin-color')
//                    .addClass('prev')
                ;
            }
            if(self.options.btnNext)
            {
                var btnNext = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.Book.nextPage();
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-arrow-right')
                        .addClass('skin-color')
//                    .addClass('icon-arrow-right-2')
            ;
            }
            if(self.options.btnZoomIn)
            {
                var btnZoomIn = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.zoomIn();
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
//                    .addClass('icon-plus')
                    .addClass('icon-zoom-in')
                        .addClass('skin-color')
                    ;
            }
            if(self.options.btnZoomOut)
            {
                var btnZoomOut = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.zoomOut();
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-zoom-out')
//                    .addClass('icon-minus')
                        .addClass('skin-color')
                    ;
            }
//            var btnLast = $(document.createElement('a'))
//                .attr('aria-hidden', 'true')
//                .appendTo(menu)
//                .bind(this.CLICK_EV, function(){
//                    self.Book.lastPage();
//                })
//                .addClass('flipbook-menu-btn')
//                .addClass('last');
            if(self.options.btnToc)
            {
                var btnToc = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.toggleToc();
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-list')
                    .addClass('skin-color')
                    ;
            }
            if(self.options.btnThumbs)
            {
                var btnThumbs = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.toggleThumbs();
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-layout')
                    .addClass('skin-color')
                ;
            }
            if(self.options.btnShare)
            {
                this.btnShare = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.toggleShare();
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-share')
                    .addClass('skin-color')
                ;
            }

            if (THREEx.FullScreen.available() && self.options.btnExpand){
                var btnExpand = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){


                        if (THREEx.FullScreen.available()) {
                            if (THREEx.FullScreen.activated()) {
                                THREEx.FullScreen.cancel();
                                $(this)
                                    .removeClass('icon-resize-shrink')
                                    .addClass('icon-resize-enlarge')
                                ;
                            }
                            else {
                                THREEx.FullScreen.request(self.wrapper[0]);
                                $(this)
                                    .removeClass('icon-resize-enlarge')
                                    .addClass('icon-resize-shrink')
                                ;
                            }
                        }
//                        $(this).addClass('icon-resize-enlarge');
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-resize-enlarge')
                    .addClass('skin-color')
                    ;
            }
        },

        createShareButtons:function(){
            var self = this;
            this.shareButtons = $(document.createElement('span'))
                .appendTo(this.bookLayer)
                .addClass('flipbook-shareButtons')
                .addClass('skin-color-bg')
                .addClass('invisible')
                .addClass('transition')
            ;

            var i;
            for (i = 0; i<self.options.social.length; i++){
                createButton(self.options.social[i]);
            }
            function createButton(social){
                var btn = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .appendTo(self.shareButtons)
                        .addClass('flipbook-shareBtn')
                        .addClass(social.icon)
                        .addClass('icon-general')
                        .addClass('skin-color')
                        .bind(self.CLICK_EV, function(e){
                            window.open(social.url,"_self")
                        })
                    ;
            }
        },

        zoomOut:function(){
            if(!this.webglMode){
                var newZoom = this.scroll.scale / 1.5 < this.scroll.options.zoomMin ? this.scroll.options.zoomMin : this.scroll.scale / 1.5;
                this.scroll.zoom(this.bookLayer.width()/2,this.bookLayer.height()/2,newZoom,400);
            }else
                this.Book.zoomTo(-2);
        },
        zoomIn:function(){
            if(!this.webglMode){
                var newZoom = this.scroll.scale * 1.5 > this.scroll.options.zoomMax ? this.scroll.options.zoomMax : this.scroll.scale * 1.5;
                this.scroll.zoom(this.bookLayer.width()/2,this.bookLayer.height()/2,newZoom,400);
            }else
                this.Book.zoomTo(2);
        },

        toggleShare:function(){
            this.shareButtons.toggleClass('invisible');
        },
        /**
         * create current page indicator
         */
        createCurrentPage : function(){
            var self = this;
            this.currentPage =  $(document.createElement('input'))
                .addClass('flipbook-currentPage')
                .attr('type', 'text')
                .addClass('skin-color')
                .appendTo(this.menuWrapper)
                .keyup(function (e) {
                    if (e.keyCode == 13) {
                        var value = parseInt($(this).val())-1;
                        self.updateCurrentPage();
                        self.Book.goToPage(value);
                    }
                })
                .focus(function(e){
                    $(this).val("");
                })
                .focusout(function(e){
                    var value = parseInt($(this).val())-1;
                    self.updateCurrentPage();
                    self.Book.goToPage(value);
                })
            ;
        },

        createToc:function(){
            var self = this;
            this.tocHolder =  $(document.createElement('div'))
                .addClass('flipbook-tocHolder')
                .addClass('invisible')
                .appendTo(this.wrapper)
//                .hide();
            ;
            this.toc =  $(document.createElement('div'))
                .addClass('.flipbook-toc')
                .appendTo(this.tocHolder)
            ;
            self.tocScroll = new iScroll(self.tocHolder[0],{bounce:false});

            //tiile
            var title = $(document.createElement('span'))
                .addClass('flipbook-tocTitle')
                .addClass('skin-color-bg')
                .addClass('skin-color')
                .appendTo(this.toc)
            ;
//            title.text(this.options.tocTitle);
//            var btnToc = $(document.createElement('span'))
//                .attr('aria-hidden', 'true')
//                .appendTo(title)
//                .css('float','left')
//                .addClass('icon-list')
//                .addClass('icon-social')
//                ;

             var btnClose = $(document.createElement('span'))
                .attr('aria-hidden', 'true')
                .appendTo(title)
                .css('float','right')
                .css('position','absolute')
                .css('top','0px')
                .css('right','0px')
                .css('cursor','pointer')
                .css('font-size','.8em')
                .addClass('icon-cross')
                .addClass('icon-general')
                 .addClass('skin- color')
                 .bind(self.START_EV, function(e){
                     self.toggleToc();
                 });


            for(var i = 0; i<this.options.pages.length; i++)
            {
                if(this.options.pages[i].title == "")
                    continue;
                if(typeof  this.options.pages[i].title === "undefined")
                    continue;

                var tocItem = $(document.createElement('a'))
                    .attr('class', 'flipbook-tocItem')
                    .addClass('skin-color-bg')
                    .addClass('skin-color')
                    .attr('title', String(i+1))
                    .appendTo(this.toc)
//                    .unbind(self.CLICK_EV)
                    .bind(self.CLICK_EV, function(e){

                        if(!self.tocScroll.moved ){
                            var clickedPage = Number($(this).attr('title'))-1;
                            if(self.Book.goingToPage != clickedPage)
                                self.Book.goToPage(clickedPage);
//                            console.log(e,this);
                        }
                    })
                ;
                $(document.createElement('span'))
                    .appendTo(tocItem)
                    .text(this.options.pages[i].title);
                $(document.createElement('span'))
                    .appendTo(tocItem)
                    .attr('class', 'right')
                    .text(i+1);
            }

            self.tocScroll.refresh();
        },

        toggleToc:function(){
//            this.tocHolder[0].classList.toggle('invisible');
            this.tocHolder.toggleClass('invisible');

            this.tocScroll.refresh();
        },

        /**
         * update current page indicator
         */
        updateCurrentPage : function(){
            if(typeof this.currentPage === 'undefined')
                return;
            var text, rightIndex = this.Book.rightIndex, pagesLength = this.webglMode ? this.Book.pages.length*2 : this.Book.pages.length;
            if (rightIndex == 0) {
                text = "1 / " + String(pagesLength);
            }
            else if (rightIndex == pagesLength) {
                text = String(pagesLength) + " / " + String(pagesLength);
            }
            else {
                text = String(rightIndex) + "," + String(rightIndex + 1) + " / " + String(pagesLength);
            }
            if(this.p && this.options.pages.length != 24 && this.options.pages.length != 8)
                this.Book.rightIndex = 0;
            this.currentPage.attr('value',text);
            this.currentPage.attr('size', this.currentPage.val().length);
        },

        /**
         * page turn is completed, update what is needed
         */
        turnPageComplete:function () {
            //this == FLIPBOOK.Book

            this.animating = false;
            this.updateCurrentPage();
        },

        /**
         * update book size
         */
        resize:function(){
            var blw = this.bookLayer.width(),
                blh = this.bookLayer.height(),
                bw = this.book.width(),
                bh = this.book.height(),
                menuW = this.menuWrapper.width();
            var self = this;
            if(blw == 0 || blh == 0 || bw == 0 || bh == 0){
                setTimeout(function(){
                    self.resize();
                }, 1000);
                return;
            }

            if(blw/blh >= bw/bh)
                this.fitToHeight(true);
            else
                this.fitToWidth(true);

            //center the menu
//            this.menuWrapper.css('left',String(blw/2 - menuW / 2)+'px');
            if(this.options.btnShare){
                var sharrBtnX = this.btnShare.offset().left;
                var bookLayerX = this.bookLayer.offset().left;
                this.shareButtons.css('left',String(sharrBtnX-bookLayerX)+'px');
            }
        },

        /**
         * fit book to screen height
         * @param resize
         */
        fitToHeight:function (resize) {
            var x= this.bookLayer.height();
            var y= this.book.height();
            if(resize) this.ratio = x/y;
            this.fit(this.ratio, resize);
            this.thumbsVertical();
        },

        /**
         * fit book to screen width
         * @param resize
         */
        fitToWidth:function (resize) {
            var x= this.bookLayer.width();
            var y= this.book.width();
            if(resize) this.ratio = x/y;
            this.fit(this.ratio, resize);
//            this.thumbsHorizontal();
            this.thumbsVertical();
        },

        /**
         * resize book by zooming it with iscroll
         * @param r
         * @param resize
         */
        fit:function(r, resize) {
            if(!this.webglMode){
                r = resize ? this.ratio : this.scroll.scale;
                if (resize){

                    this.scroll.options.zoomMin = r *this.options.zoomMin;
                    this.scroll.options.zoomMax = r *this.options.zoomMax;
                }
                this.scroll.zoom(this.bookLayer.width()/2,this.bookLayer.height()/2,r *this.options.zoom,0);
            }
        },

        /**
         * create thumbs
         */
        createThumbs : function () {
            var self = this,point1,point2;
            self.thumbsCreated = true;
            //create thumb holder - parent for thumb container
            self.thumbHolder = $(document.createElement('div'))
                .addClass('flipbook-thumbHolder')
                .addClass('invisible')
                .appendTo(self.bookLayer)
                .css('position', 'absolute')
                .css('display', 'none')
            ;
            //create thumb container - parent for thumbs
            self.thumbsContainer = $(document.createElement('div')).
                appendTo(self.thumbHolder)
                .addClass('flipbook-thumbContainer')
                .css('margin', '0px')
                .css('padding', '0px')
                .css('position', 'relative')
            ;
            //scroll for thumb container
            self.thumbScroll = new iScroll(self.thumbHolder[0],{bounce:false});
            self.thumbs = [];
            for (var i = 0; i < self.options.pages.length; i++) {
                var imgUrl = self.options.pages[i].thumb;
                var thumbsLoaded = 0;
                var thumb = new FLIPBOOK.Thumb($, self.Book, imgUrl, self.options.thumbnailWidth, self.options.thumbnailHeight, i);
                thumb.image.style[self.transform] = 'translateZ(0)';
                self.thumbs.push(thumb);
                $(thumb.image)
                    .attr('title', i + 1)
                    .appendTo(self.thumbsContainer)
                    .bind(self.CLICK_EV, function(e){
                        if(!self.thumbScroll.moved)
                        {
                            var clickedPage = Number($(this).attr('title'))-1;
                            if(self.Book.goingToPage != clickedPage)
                                self.Book.goToPage(clickedPage);
                        }
                    });
                thumb.loadImage();
            }
        },

        /**
         * toggle thumbs
         */
        toggleThumbs : function () {

            if (!this.thumbsCreated)
                this.createThumbs();
            this.thumbHolder.css('display','block');
            this.thumbHolder.toggleClass('invisible');
//            this.thumbHolder.fadeToggle();
            var self = this;
            this.thumbsVertical();
//            (this.bookLayer.width() > this.bookLayer.height()) ? this.thumbsVertical() : this.thumbsHorizontal();
//            setTimeout(
//                self.thumbScroll.refresh(), 2000
//            );

        },

        /**
         * thumbs vertical view
         */
        thumbsVertical:function(){
            if (!this.thumbsCreated)
                return;
            var w = this.options.thumbnailWidth,
                h = this.options.thumbnailHeight * this.thumbs.length;
            this.thumbHolder
                .css('width', String(w) + 'px')
                .css('height', '100%')
                .css('bottom', 'auto')
                .css('left', 'auto')
                .css('top', '0px')
                .css('right', '0px');
            this.thumbsContainer
                .css('height', String(h) + 'px')
                .css('width', String(w) + 'px');
            for(var i=0;i<this.thumbs.length;i++)
            {
                var thumb = this.thumbs[i].image;
                thumb.style.top = String(i*this.options.thumbnailHeight)+'px';
                thumb.style.left = '0px';
            }
            this.thumbScroll.hScroll = false;
            this.thumbScroll.vScroll = true;
            this.thumbScroll.refresh();
        },

        /**
         * thumbs horizontal view
         */
        thumbsHorizontal:function(){
            if (!this.thumbsCreated)
                return;
            var w = this.options.thumbnailWidth* this.thumbs.length,
                h = this.options.thumbnailHeight ;
            this.thumbHolder
                .css('width', '100%')
                .css('height', String(h) + 'px')
                .css('left', '0px')
                .css('right', 'auto')
                .css('top', 'auto')
                .css('bottom', '0px')
            ;
            this.thumbsContainer
                .css('height', String(h) + 'px')
                .css('width', String(w) + 'px')
            ;
            for(var i=0;i<this.thumbs.length;i++)
            {
                var thumb = this.thumbs[i].image;
                thumb.style.top = '0px';
                thumb.style.left = String(i*this.options.thumbnailWidth)+'px';
            }
            this.thumbScroll.hScroll = true;
            this.thumbScroll.vScroll = false;
            this.thumbScroll.refresh();
        }   ,

        /**
         * toggle full screen
         */
        toggleExpand : function() {
            if (THREEx.FullScreen.available()) {
                if (THREEx.FullScreen.activated()) {
                    THREEx.FullScreen.cancel();
                }
                else {
                    THREEx.FullScreen.request();
                }
            }
        },
        lightboxStart:function(){
//            this.resize();
            this.reloadCSS(this.options.css);
//            this.Book.render(true);
//            this.resize();
        },
        lightboxEnd:function(){
//            this.Book.render(false);

            if (THREEx.FullScreen.available()) {
                if (THREEx.FullScreen.activated()) {
                    THREEx.FullScreen.cancel();
                }
            }
        }
    };

        //easign functions
    $.extend($.easing,
        {
            def: 'easeOutQuad',
            swing: function (x, t, b, c, d) {
                //alert($.easing.default);
                return $.easing[$.easing.def](x, t, b, c, d);
            },
            easeInQuad: function (x, t, b, c, d) {
                return c*(t/=d)*t + b;
            },
            easeOutQuad: function (x, t, b, c, d) {
                return -c *(t/=d)*(t-2) + b;
            },
            easeInOutQuad: function (x, t, b, c, d) {
                if ((t/=d/2) < 1) return c/2*t*t + b;
                return -c/2 * ((--t)*(t-2) - 1) + b;
            },
            easeInCubic: function (x, t, b, c, d) {
                return c*(t/=d)*t*t + b;
            },
            easeOutCubic: function (x, t, b, c, d) {
                return c*((t=t/d-1)*t*t + 1) + b;
            },
            easeInOutCubic: function (x, t, b, c, d) {
                if ((t/=d/2) < 1) return c/2*t*t*t + b;
                return c/2*((t-=2)*t*t + 2) + b;
            },
            easeInQuart: function (x, t, b, c, d) {
                return c*(t/=d)*t*t*t + b;
            },
            easeOutQuart: function (x, t, b, c, d) {
                return -c * ((t=t/d-1)*t*t*t - 1) + b;
            },
            easeInOutQuart: function (x, t, b, c, d) {
                if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
                return -c/2 * ((t-=2)*t*t*t - 2) + b;
            },
            easeInQuint: function (x, t, b, c, d) {
                return c*(t/=d)*t*t*t*t + b;
            },
            easeOutQuint: function (x, t, b, c, d) {
                return c*((t=t/d-1)*t*t*t*t + 1) + b;
            },
            easeInOutQuint: function (x, t, b, c, d) {
                if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
                return c/2*((t-=2)*t*t*t*t + 2) + b;
            },
            easeInSine: function (x, t, b, c, d) {
                return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
            },
            easeOutSine: function (x, t, b, c, d) {
                return c * Math.sin(t/d * (Math.PI/2)) + b;
            },
            easeInOutSine: function (x, t, b, c, d) {
                return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
            },
            easeInExpo: function (x, t, b, c, d) {
                return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
            },
            easeOutExpo: function (x, t, b, c, d) {
                return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
            },
            easeInOutExpo: function (x, t, b, c, d) {
                if (t==0) return b;
                if (t==d) return b+c;
                if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
                return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
            },
            easeInCirc: function (x, t, b, c, d) {
                return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
            },
            easeOutCirc: function (x, t, b, c, d) {
                return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
            },
            easeInOutCirc: function (x, t, b, c, d) {
                if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
                return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
            },
            easeInElastic: function (x, t, b, c, d) {
                var s=1.70158;var p=0;var a=c;
                if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
                if (a < Math.abs(c)) { a=c; var s=p/4; }
                else var s = p/(2*Math.PI) * Math.asin (c/a);
                return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
            },
            easeOutElastic: function (x, t, b, c, d) {
                var s=1.70158;var p=0;var a=c;
                if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
                if (a < Math.abs(c)) { a=c; var s=p/4; }
                else var s = p/(2*Math.PI) * Math.asin (c/a);
                return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
            },
            easeInOutElastic: function (x, t, b, c, d) {
                var s=1.70158;var p=0;var a=c;
                if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
                if (a < Math.abs(c)) { a=c; var s=p/4; }
                else var s = p/(2*Math.PI) * Math.asin (c/a);
                if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
                return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
            },
            easeInBack: function (x, t, b, c, d, s) {
                if (s == undefined) s = 1.70158;
                return c*(t/=d)*t*((s+1)*t - s) + b;
            },
            easeOutBack: function (x, t, b, c, d, s) {
                if (s == undefined) s = 1.70158;
                return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
            },
            easeInOutBack: function (x, t, b, c, d, s) {
                if (s == undefined) s = 1.70158;
                if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
                return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
            },
            easeInBounce: function (x, t, b, c, d) {
                return c - $.easing.easeOutBounce (x, d-t, 0, c, d) + b;
            },
            easeOutBounce: function (x, t, b, c, d) {
                if ((t/=d) < (1/2.75)) {
                    return c*(7.5625*t*t) + b;
                } else if (t < (2/2.75)) {
                    return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
                } else if (t < (2.5/2.75)) {
                    return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
                } else {
                    return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
                }
            },
            easeInOutBounce: function (x, t, b, c, d) {
                if (t < d/2) return $.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
                return $.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
            }
        });

})(jQuery, window, document)


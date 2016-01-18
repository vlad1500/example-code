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
    
    $.fn.shelfBook = function (options) {
        //entry point   
        return this.each(function () {
            var shelfBook = new Shelf();
            shelfBook.init(options, this);
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
            preloader:"/images/preloader.jpg",
            left:"/images/left.png",
            overlay:"/images/overlay.jpg"
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
        btnViews:false,
        btnHelp:true,
        btnToc:true,
        btnThumbs:true,
        btnShare:false,
        btnExpand:true,
        btnAddPhoto:false,
        btnTopTen:false,
        
        viewBook:true,
        viewTimeline:false,
        viewSlideshow:false,
        isFramed:false,
        isMobile:false,
        currentView:"Bookflip",
        bookName:"",

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
        //  facebook:"http://codecanyon.net",
        //  twitter:"http://codecanyon.net",
        //  googleplus:"http://codecanyon.net",
        //  linkedin:"http://codecanyon.net",


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
            this.createMainWrap();
            if(this.options.viewBook){
                this.createBook();
                this.Book.updateVisiblePages();
                this.createTop10();
            }
            this.createMenuWrapper();
            this.createLeftMenu();
            this.createHelp();
            this.createNextTwenty();
            if(this.options.currentPage){
                this.createCurrentPage();
                this.updateCurrentPage();
                this.createMenu();
                this.createToc();
                this.createThumbs();
            }
            if(this.options.btnShare) {
                this.createShareButtons();
                this.resize();
            }
            if(this.options.viewTimeline) {
                //this.initTimeline();
            }       
            if(this.options.currentPage)
                this.changeView(this.options.currentView);
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
        createMainWrap : function() {
            var self = this;
            self.wrapper = $(document.createElement('div'))
                .addClass('main-wrapper')
            ;
        },
        /**
         * create the book
         */
        createBook : function () {
            var self = this;
          if(self.options.pages){
            if(self.options.pages.length % 2 != 0)
                alert('Number of pages must be even (2,4,6...)');
            self.bookLayer = $(document.createElement('div'))
                .addClass('flipbook-bookLayer')
                .addClass('bookflipLayer')
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
                self.Book = new FLIPBOOK.Book(self.book[0], self.options, self);
                
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
            self.Book.goToPage(Number(self.options.startPage));
            var FLheight = self.bookLayer.height();
            var nFLhegith = FLheight - 45;
            self.bookLayer.css("height",nFLhegith+"px");
            $(window).resize(function () {
                self.resize();
            });
          } else {
            alert('Pages can not be empty.');
          }
        },
        createTimeline : function () {
            var self = this;

            if(self.options.pages.length % 2 != 0)
                alert('Number of pages must be even (2,4,6...)'); 
                           
            self.timeLayer = $(document.createElement('div'))
                .addClass('flipbook-bookLayer')
                .addClass('timelineLayer')                
                .appendTo(this.wrapper)
            ;
            self.timeLayer[0].style[self.transformOrigin] = '100% 100%';
            
            self.timeline = $(document.createElement('div'))
                .addClass('timeline')
                .appendTo(self.timeLayer)
            ;            
            
            self.wrapper.appendTo(self.$elem);

            self.options.onTurnPageComplete = self.onTurnPageComplete;
            self.Timeline = new FLIPBOOK.Timeline(self.timeline[0], self.options);
            self.webglMode = false;
            self.Timeline.goToPage(Number(self.options.startPage));
            var FLheight = self.timeLayer.height();
            var nFLhegith = FLheight - 45;
            self.timeLayer.css("height",nFLhegith+"px");
        },
        createTimelineV2 : function () {
            var self = this;

            if(self.options.pages.length % 2 != 0)
                alert('Number of pages must be even (2,4,6...)');

            self.timeLayer = $(document.createElement('div'))
                .addClass('flipbook-bookLayer')
                .addClass('timelineLayer')
                .appendTo(this.wrapper)
            ;
            self.timeLayer[0].style[self.transformOrigin] = '100% 100%';

            self.timeline = $(document.createElement('div'))
                .addClass('timeline')
                .attr("id","hardcover-timeline")
                .appendTo(self.timeLayer)
            ;

            self.wrapper.appendTo(self.$elem);

            self.options.onTurnPageComplete = self.onTurnPageComplete;
            self.Timeline = new FLIPBOOK.TimelineV2(self.timeline[0], self.options);
            self.webglMode = false;
            self.Timeline.goToPage(Number(self.options.startPage));
            var FLheight = self.timeLayer.height();
            var nFLhegith = FLheight - 45;
            if(this.options.isMobile == true)
                self.timeLayer.css("height","100%");
            else
                self.timeLayer.css("height",nFLhegith+"px");
        },

        createSlideshow : function () {
            var self = this;
            this.slideshowV2 = false;
            if(self.options.pages.length % 2 != 0)
                alert('Number of pages must be even (2,4,6...)');
            
            self.slideLayer = $(document.createElement('div'))
                .addClass('flipbook-bookLayer')
                .addClass('slideshowLayer')
                .appendTo(this.wrapper)
            ;
            self.slideLayer[0].style[self.transformOrigin] = '100% 100%';
            
            self.slideshow = $(document.createElement('div'))
                .addClass('slideshow')
                .appendTo(self.slideLayer)
            ;            
            
            //if lightbox
            if(self.options.lightBox){
                self.lightbox = new FLIPBOOK.Lightbox(this, self.wrapper,self.options);
                if(self.options.lightboxTransparent == true){                    
                    self.slideLayer.css('background','none');
                    self.slideshow.css('background','none');
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
                self.Slideshow = new FLIPBOOK.BookWebGL(self.book[0], bookOptions);
                self.webglMode = true;
            }else{                
                self.Slideshow = new FLIPBOOK.Slideshow(self.slideshow[0], self.options);
                
                self.scroll = new iScroll(self.slideLayer[0], {
//                bounce:false,
                    //wheelAction:'zoom',
                    zoom:true,
                    zoomMin:self.options.zoomMin,
                    zoomMax:self.options.zoomMax,
                    keepInCenterH:true,
                    keepInCenterV:true,
                    bounce:false
                });
                self.webglMode = false;
            }
            if (THREEx.FullScreen.available() && self.options.btnExpand){
                var btnExpand = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(self.slideshow)
                    .bind(this.CLICK_EV, function(){


                        if ($(document).fullScreen() != null) {
                            if ($(document).fullScreen() == true) {
                                $(document).fullScreen(false);
                                $(this)
                                    .removeClass('icon-resize-shrink')
                                    .addClass('icon-resize-enlarge')
                                ;
                            }
                            else {
                                $(document).fullScreen(true);
                                $(this)
                                    .removeClass('icon-resize-enlarge')
                                    .addClass('icon-resize-shrink')
                                ;
                            }
                        }
//                        $(this).addClass('icon-resize-enlarge');
                    })
                    //.addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-resize-enlarge')
                    .addClass('slideshow-enlarge')
                    ;
            }
            self.Slideshow.goToPage(Number(self.options.startPage));
            var FLheight = self.slideLayer.height();
            var nFLhegith = FLheight - 45;            
            self.slideLayer.css("height",nFLhegith+"px");
            $(window).resize(function () {
                self.resize();
            });
        },
        createSlideshowV2 : function () {
            var self = this;
            this.slideshowV2 = true;
            if(self.options.pages.length % 2 != 0)
                alert('Number of pages must be even (2,4,6...)');

            self.slideLayer = $(document.createElement('div'))
                .addClass('flipbook-bookLayer')
                .addClass('slideshowLayer')
                .appendTo(this.wrapper)
            ;
            self.slideLayer[0].style[self.transformOrigin] = '100% 100%';

            self.slideshow = $(document.createElement('div'))
                .addClass('slideshow')
                .attr("id","hardcover-slideshow")
                .appendTo(self.slideLayer)
            ;

            self.wrapper.appendTo(self.$elem);

            self.options.onTurnPageComplete = self.onTurnPageComplete;
            self.Slideshow = new FLIPBOOK.SlideshowV2(self.slideshow[0], self.options);
            self.webglMode = false;
            var FLheight = self.slideLayer.height();
            var nFLhegith = FLheight - 45;
            if(this.options.isMobile == true)
                self.slideLayer.css("height","100%");
            else
                self.slideLayer.css("height",nFLhegith+"px");
        },
        /*
        Create left menu
        */
        createLeftMenu:function() {
            var self = this;
            this.leftMenu = $(document.createElement('div'))
                .addClass('flipbook-leftMenu')
                .addClass('col-md-3')
                .appendTo(this.menuWrapper)
            ;

            var smallLogo = $(document.createElement('div'))
                    .appendTo(this.leftMenu)
                    .addClass('branding-small')
            ;

            if(self.options.btnAddPhoto)
            {
                var btnAddPhoto = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.leftMenu)
                    .bind(this.CLICK_EV, function(){
                        //self.toggleAddPhoto();
                        $(".share_here").click();
                    })
                    .text("Add Photo")
                    .addClass('flipbook-menu-btn') 
                    .addClass('addPhoto')                   
                    .addClass('skin-color')
                    ;                
            }

            if(self.options.btnTopTen)
            {
                var btnTopTen = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.leftMenu)
                    .bind(this.CLICK_EV, function(){
                        //self.toggleTopTen();
                        var userName = $("body").attr("user_name");
                        var top10Url = "/books/"+userName+"/top_10";
                        window.location.href = top10Url;
                    })
                    .text("Top 10")
                    .addClass('flipbook-menu-btn') 
                    .addClass('topTen')                   
                    .addClass('skin-color')
                    ;                
            }
        },

        createAddPhoto:function() {
            var self = this;
            self.addPhotoCreated = true;
            this.photoHolder = $(document.createElement('div'))
                .appendTo(this.menuWrapper)
                .addClass('flipbook-addPhotoHolder')
                .addClass('flipbook-addPhoto')
                .addClass('skin-color-bg')
                .addClass('invisible')
                .addClass('transition')
            ;
            addPhotoItem('Add photos to this book');

            function addPhotoItem(itemTitle){
                var item = $(document.createElement('span'))
                        .appendTo(self.photoHolder)
                        .addClass('flipbook-addPhotoItem')
                        .addClass('skin-color')
                        .text(itemTitle)
                ;
            }
            var arrowDown = $(document.createElement('div'))
                .appendTo(this.photoHolder)
                .addClass('arrow-down')
                .css({'position':'absolute', 'bottom':'-10px', 'right':'0'})
            ;
        },

        toggleAddPhoto : function () {
         if(this.options.btnAddPhoto){
            if (!this.addPhotoCreated)
                this.createAddPhoto();
                var viewParent = $(".addPhotoOPen");
                var addPhotoPos = $(".addPhoto").position();
                //var newLeft = position.left+"px";            
                this.photoHolder.css({'display':'block', 'left': parseInt(addPhotoPos.left) - parseInt(36) +'px', 'width':'8%', 'height':'65px', 'max-height':'none', 'z-index':'9'});
                this.photoHolder.toggleClass('invisible');
         }                        
        },

        createTop10 : function () {
            var self = this;

            if(self.options.pages.length % 2 != 0)
                alert('Number of pages must be even (2,4,6...)'); 
                           
            self.top10Layer = $(document.createElement('div'))
                .addClass('flipbook-bookLayer')
                .addClass('top10Layer')                
                .appendTo(this.wrapper)
            ;
            self.top10Layer[0].style[self.transformOrigin] = '100% 100%';
        },

        /**
         * create current page indicator
         */
        createCurrentPage : function(){
            var self = this, pagesLength = this.webglMode ? this.Book.pages.length*2 : this.Book.pages.length;
            var currenStart = self.options.startPage;
            this.currentPageCon =  $(document.createElement('div'))
                .addClass('flipbook-currentPage')
                .addClass('col-md-6')
                .appendTo(this.menuWrapper)                
            ;
            this.paginationCon =  $(document.createElement('div'))
                .addClass('flipbook-paginationCon')
                .appendTo(this.currentPageCon)                
            ;
          if(this.slideshowV2 == false){
            var btnSNext = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(".slideshow")
                    .bind(this.CLICK_EV, function(){
                        self.Slideshow.nextPage();
                    })
                    //.addClass('icon-general')
                    //.addClass('icon-arrow-right-big')
                    .addClass('icon-arrow-right-big')
            ;
            var btnSPrev = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .prependTo(".slideshow")
                    .bind(this.CLICK_EV, function(){
                        self.Slideshow.prevPage();
                    })
                    //.addClass('icon-arrow-left-big')
                    //.addClass('icon-general')
                    .addClass('icon-arrow-left-big')
                ;
          }
        },
        /*
        Create Pagination
        */
        changePagination:function(){
            var curView = this.currentView;
            var self = this;
            var currenStart = self.options.startPage;
            var mCount = 0;
            console.log("creating pagination for: "+curView);
            //if(!thumbsHtml)
                //thumbsHtml = $(".thumb_con").html();
            //console.log(thumbsHtml);
            this.paginationCon.html("");
            if(curView == "Bookflip"){
                var pagesLength = this.webglMode ? this.Book.pages.length*2 : this.Book.pages.length;
                if(this.Book.rightIndex) currenStart = this.Book.rightIndex;
                if(self.options.btnPrev){
                    var btnPrev = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .prependTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){
                            self.Book.prevPage();
                        })
                        //.addClass('icon-arrow-left')                    
                        //.addClass('icon-general')
                        .addClass('fa')
                        .addClass('fa-angle-double-left')
                        .addClass('fa-2x')
                        .addClass('skin-color')
                    ;
                }
                for(var x=1;x < (pagesLength-2);x++){
                    var pagebtn =  $(document.createElement('span'))
                        .addClass('page-num')                
                        .addClass('btn-'+x)
                        .addClass('skin-color')                      
                        .addClass('pagination')
                        .addClass('MenuItem')
                        .attr("id",curView+"-MenuItem-"+x)
                        .text(x+"-"+(x+1)) 
                        .attr('title', String(x))             
                        .appendTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){                    
                            var clickedPage = Number($(this).attr('title'));
                            //self.gotoTimeline();
                            if(self.Book.goingToPage != clickedPage) {
                                if (!self.Book.animating) {
                                    self.Book.goToPage(clickedPage);
                                    $(this).addClass('page-current');
                                    $(this).siblings().removeClass('page-current');
                                    $('#page-front-cover').removeClass('page-cover');
                                    $('#page-back-cover').removeClass('page-cover');                                                                
                                }
                            }                    
                        })                
                    ;
                    if (currenStart == x || currenStart == (x+1)){
                        pagebtn.addClass('page-current');
                        pagebtn.siblings().removeClass('page-current');
                    }
                    x++;
                    mCount++;
                }
                this.Book.doPagination();
                if(self.options.btnNext){
                    var btnNext = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .appendTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){
                            self.Book.nextPage();
                        })                    
                        //.addClass('icon-general')
                        //.addClass('icon-arrow-right')
                        .addClass('fa')
                        .addClass('fa-angle-double-right')
                        .addClass('fa-2x')
                        .addClass('skin-color')
                    ;
                } 
                var btnFront = $(document.createElement('span'))
                    .addClass('page-num')
                    .addClass('skin-color')                      
                    .addClass('pagination')
                    .text("Front") 
                    .attr('title', String(0))             
                    .prependTo(this.paginationCon)
                    .bind(this.CLICK_EV, function(){                    
                        var clickedPage = Number($(this).attr('title'));
                        if(self.Book.goingToPage != clickedPage) {
                            if (!self.Book.animating) {
                                self.Book.goToPage(clickedPage);
                                $(this).addClass('page-current');
                                $(this).siblings().removeClass('page-current');
                                $('#page-front-cover').removeClass('page-cover');
                                $('#page-back-cover').removeClass('page-cover');
                            }
                        }
                    })
                ;            
                var btnFront = $(document.createElement('span'))
                    .addClass('page-num')
                    .addClass('skin-color')                      
                    .addClass('pagination')
                    .text("Back") 
                    .attr('title', String(pagesLength))             
                    .appendTo(this.paginationCon)
                    .bind(this.CLICK_EV, function(){                    
                        var clickedPage = Number($(this).attr('title'));
                        if(self.Book.goingToPage != clickedPage) {
                            if (!self.Book.animating) {
                                self.Book.goToPage(clickedPage);
                                $(this).addClass('page-current');
                                $(this).siblings().removeClass('page-current');
                                $('#page-front-cover').removeClass('page-cover');
                                $('#page-back-cover').removeClass('page-cover');                                
                            }
                        }
                    })
                ;
                $(".normalThumbs").show();
                $(".slideThumbs").hide();
            }
            if(curView == "Timeline"){
                var pagesLength = this.Timeline.timesCount;
                if(this.Timeline.rightIndex) currenStart = this.Timeline.rightIndex;
                if(self.options.btnPrev){
                    var btnPrev = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .prependTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){                            
                            self.Timeline.prevPage();
                            $("#hardcover-timeline .nav-previous").click();
                        })
                        .addClass('fa')
                        .addClass('fa-angle-double-left')
                        .addClass('fa-2x')
                        .addClass('skin-color')
                    ;
                }
                $("#hardcover-timeline .nav-previous").on("click",function(){
                    self.Timeline.prevPage();
                });
                var cCount = 1;
                $(".timenav .marker").each(function(){
                    var thisID = $(this).attr("id");
                    var pagebtn =  $(document.createElement('span'))
                        .addClass('page-num')
                        .addClass('btn-'+cCount)
                        .addClass('skin-color')
                        .addClass('pagination')
                        .addClass('MenuItem')
                        .attr("id",curView+"-MenuItem-"+mCount)
                        .text(cCount)
                        .attr("goid",thisID)
                        .attr('title', String(cCount))
                        .appendTo(self.paginationCon)
                        .bind("click", function(){
                            var clickedPage = Number($(this).attr('title'));
                            var clickedID = $(this).attr("goid");
                            console.log("clicked time page: "+clickedID);
                            $(".timenav #"+clickedID+" .flag").click();
                            self.Timeline.goToPage(clickedPage);
                            $(this).addClass('page-current');
                            $(this).siblings().removeClass('page-current');
                            $('#page-front-cover').removeClass('page-cover');
                            $('#page-back-cover').removeClass('page-cover');
                        })
                    ;
                    $(".timenav #"+thisID+" .flag").attr("title",String(cCount));
                    $(".timenav #"+thisID+" .flag").on("click",function(){
                        var clickedPage = Number($(this).attr('title'));
                        self.Timeline.goToPage(clickedPage);
                        $('.btn-'+clickedPage).addClass('page-current');
                        $('.btn-'+clickedPage).siblings().removeClass('page-current');
                        $('#page-front-cover').removeClass('page-cover');
                        $('#page-back-cover').removeClass('page-cover');
                    });
                    if (currenStart == cCount || currenStart == (cCount+1)){
                        pagebtn.addClass('page-current');
                        pagebtn.siblings().removeClass('page-current');
                        $(".timenav #"+thisID+" .flag").click();
                    }
                    cCount++;
                    mCount++;
                });
                if(self.options.btnNext){
                    var btnNext = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .appendTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){                            
                            self.Timeline.nextPage();
                            $("#hardcover-timeline .nav-next").click();
                        })                    
                        .addClass('fa')
                        .addClass('fa-angle-double-right')
                        .addClass('fa-2x')
                        .addClass('skin-color')
                    ;
                }
                $("#hardcover-timeline .nav-next").on("click",function(){
                    self.Timeline.nextPage();
                });
                $(".normalThumbs").show();
                $(".slideThumbs").hide();
            }
            if(curView == "Slideshow"){
              this.slidePlay = false;
              if(this.slideshowV2){
                if(this.Slideshow.rightIndex) currenStart = this.Slideshow.rightIndex;
                if(self.options.btnPrev){
                    var btnPrev = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .prependTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){
                            //$(".galleria-image-nav-left").click();
                            var thisIndex = parseInt( location.hash.substr(11), 10 ) - 1;
                            if(thisIndex < 0)
                                thisIndex = gallery.getDataLength();
                            gallery.show(thisIndex);
                        })
                        .addClass('fa')
                        .addClass('fa-angle-double-left')
                        .addClass('fa-2x')
                        .addClass('skin-color')
                    ;
                }
                this.thumbPlay = $(document.createElement('div'))
                    .addClass('thumbPlay')
                    .appendTo(this.paginationCon)
                    .css({overflow: "hidden", position: "relative", visibility: "visible", display: "table-cell", width: "41px", height: "35px"})
                ;
                this.thumbCon = $(document.createElement('div'))
                    .addClass('thumb_con')
                    .addClass('galleryThumbCon')
                    .appendTo(this.paginationCon)
                ;
                var imgPlay = $(document.createElement('img'))
                    .addClass('imgPlay')
                    .attr("src","/images/thumbPlay.png")
                    .css({display: "block", opacity: 1, "min-width": "0px", "min-height": "0px", "max-width": "none", "max-height": "none", "-webkit-transform": "translate3d(0, 0, 0)", width: "41px", height: "35px"})
                    .bind(this.CLICK_EV, function(){
                        console.log("play clicked: "+self.slidePlay);
                        if(self.slidePlay)
                            self.slidePlay = false;
                        else
                            self.slidePlay = true;
                        self.playSlide(3000);
                    })
                    .appendTo(this.thumbPlay)
                ;

                //$(".thumb_con").html(thumbsHtml);
                //Galleria.ready(function(options) {
                    //this.createGThumbs();
                //});
                if(self.options.btnNext){
                    var btnNext = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .appendTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){
                            var thisIndex = parseInt( location.hash.substr(11), 10 ) + 1;
                            if(thisIndex >=  gallery.getDataLength())
                                thisIndex = 0;
                            gallery.show(thisIndex);
                            //$(".galleria-image-nav-right").click();
                        })
                        .addClass('fa')
                        .addClass('fa-angle-double-right')
                        .addClass('fa-2x')
                        .addClass('skin-color')
                    ;
                }
                var btnFront = $(document.createElement('span'))
                    .addClass('page-num')
                    .addClass('skin-color')
                    .addClass('pagination')
                    .text("Start")
                    .attr('title', String(0))
                    .prependTo(this.paginationCon)
                    .bind(this.CLICK_EV, function(){
                        gallery.show( 0 );
                    })
                ;
                var btnFront = $(document.createElement('span'))
                    .addClass('page-num')
                    .addClass('skin-color')
                    .addClass('pagination')
                    .text("End")
                    .attr('title', Number(pagesLength)-1)
                    .appendTo(this.paginationCon)
                    .bind(this.CLICK_EV, function(){
                        gallery.show( gallery.getDataLength() );
                    })
                ;
                $(".normalThumbs").hide();
                $(".slideThumbs").show();
              }else {
                var pagesLength = this.Slideshow.slides.length;
                if(this.Slideshow.rightIndex) currenStart = this.Slideshow.rightIndex;
                if(self.options.btnPrev){
                    var btnPrev = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .prependTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){
                            self.Slideshow.prevPage();
                        })
                        .addClass('fa')
                        .addClass('fa-angle-double-left')
                        .addClass('fa-2x')
                        .addClass('skin-color')
                    ;
                }
                for(var x=1;x < (pagesLength);x++){
                    var imgUrl = this.thumbs[x].imageSrc;
                    //console.log(this.thumbs[x-1]);
                    var pThumb = $(document.createElement('img'))
                                .attr("src",imgUrl)
                                .attr("height","35px")
                                ;
                    var pagebtn =  $(document.createElement('span'))
                        .addClass('page-num')
                        .addClass('btn-'+x)
                        .addClass('skin-color')
                        .addClass('pagination')
                        .append(pThumb)
                        .attr('title', String(x))
                        .appendTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){
                            var clickedPage = Number($(this).attr('title'));
                            self.Slideshow.goToPage(clickedPage);
                            $(this).addClass('page-current');
                            $(this).siblings().removeClass('page-current');
                            $('#page-front-cover').removeClass('page-cover');
                            $('#page-back-cover').removeClass('page-cover');
                        })
                    ;
                    if (currenStart == x || currenStart == (x+1)){
                        pagebtn.addClass('page-current');
                        pagebtn.siblings().removeClass('page-current');
                    }
                }
                if(self.options.btnNext){
                    var btnNext = $(document.createElement('span'))
                        .attr('aria-hidden', 'true')
                        .appendTo(this.paginationCon)
                        .bind(this.CLICK_EV, function(){
                            self.Slideshow.nextPage();
                        })
                        .addClass('fa')
                        .addClass('fa-angle-double-right')
                        .addClass('fa-2x')
                        .addClass('skin-color')
                    ;
                }
                var btnFront = $(document.createElement('span'))
                    .addClass('page-num')
                    .addClass('skin-color')
                    .addClass('pagination')
                    .text("Start")
                    .attr('title', String(0))
                    .prependTo(this.paginationCon)
                    .bind(this.CLICK_EV, function(){
                        var clickedPage = Number($(this).attr('title'));
                        if(self.Slideshow.goingToPage != clickedPage) {
                                self.Slideshow.goToPage(clickedPage);
                                $(this).addClass('page-current');
                                $(this).siblings().removeClass('page-current');
                                $('#page-front-cover').removeClass('page-cover');
                                $('#page-back-cover').removeClass('page-cover');
                        }
                    })
                ;
                var btnFront = $(document.createElement('span'))
                    .addClass('page-num')
                    .addClass('skin-color')                      
                    .addClass('pagination')
                    .text("End") 
                    .attr('title', Number(pagesLength)-1)             
                    .appendTo(this.paginationCon)
                    .bind(this.CLICK_EV, function(){                    
                        var clickedPage = Number($(this).attr('title'));
                            self.Slideshow.goToPage(clickedPage);
                            $(this).addClass('page-current');
                            $(this).siblings().removeClass('page-current');
                            $('#page-front-cover').removeClass('page-cover');
                            $('#page-back-cover').removeClass('page-cover');                     
                    })
                ;
              }
            }            
        },
        // josh - play/stop slide
        playSlide : function(thisInterval){
            var self = this;
            console.log("slide state: "+this.slidePlay);
            if(this.slidePlay ==  true){
                console.log("playing slide");
                setTimeout(function () {
                    var thisIndex = parseInt( location.hash.substr(11), 10 ) + 1;
                    if(thisIndex >=  gallery.getDataLength())
                        thisIndex = 0;
                    gallery.show(thisIndex);
                    self.playSlide(thisInterval);
                }, thisInterval)
            } else {
                console.log("stoping slide");
            }
        },
        stopSlide : function(){
            this.slidePlay = false;
        },
        // create menu wrapper 
        createMenuWrapper:function(){
            var self = this;
            this.menuWrapper = $(document.createElement('div'))
                .addClass('flipbook-menuWrapper')
                .addClass('skin-color-bg')
                .appendTo(this.wrapper)
            ;
        },
        /**
         * create menu
         */
        createMenu:function(){
            var self = this;            
            this.menu = $(document.createElement('div'))
                    .addClass('flipbook-menu')
                    .addClass('col-md-3')
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
            
            // Commenting the ZoomIn button
            /*
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
                    .addClass('icon-plus')
                    .addClass('icon-zoom-in')
                        .addClass('skin-color')
                    ;
            }*/

            // Commenting the ZoomOut button
            /*
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
                    .addClass('icon-minus')
                    .addClass('skin-color')
                    ;
            }*/

//            var btnLast = $(document.createElement('a'))
//                .attr('aria-hidden', 'true')
//                .appendTo(menu)
//                .bind(this.CLICK_EV, function(){
//                    self.Book.lastPage();
//                })
//                .addClass('flipbook-menu-btn')
//                .addClass('last');
            if(self.options.btnHelp)
            {
                var btnHelp = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.toggleHelp();
                        self.toggleAddPhoto();
                        self.toggleNext();
                        self.toggleTopTen();
                        self.toggleViews();
                        /*setTimeout(function () {
                            $(".flipbook-helpBg").removeClass("invisible").addClass("invisible");
                            $(".flipbook-topTenHolder").removeClass("invisible").addClass("invisible");
                            $(".flipbook-addPhotoHolder").removeClass("invisible").addClass("invisible");
                            $(".flipbook-nextTwentyHolder").removeClass("invisible").addClass("invisible");
                            $(".flipbook-helpHolder").removeClass("invisible").addClass("invisible");
                            $('.flipbook-viewHolder').removeClass("invisible").addClass("invisible");                  
                        }, 3000);*/
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('fa')
                    .addClass('fa-question')
                    .addClass('skin-color')
                    ;
            }                
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
                var btnThumbs1 = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.toggleThumbs();
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-layout')
                    .addClass('skin-color')
                    .addClass('normalThumbs')
                ;
                var btnThumbs2 = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.Slideshow.toggleThumbs();
                    })
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general')
                    .addClass('icon-layout')
                    .addClass('skin-color')
                    .addClass('slideThumbs')
                    .css("display","none")
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


                        if ($(document).fullScreen() != null) {
                            if ($(document).fullScreen() == true) {
                                $(document).fullScreen(false);
                                $(this)
                                    .removeClass('icon-resize-shrink')
                                    .addClass('icon-resize-enlarge')
                                ;
                            }
                            else {
                                $(document).fullScreen(true);
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
                    ;
            }
            if(self.options.btnViews)
            {
                var btnToc = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.menu)
                    .bind(this.CLICK_EV, function(){
                        self.toggleViewsThumbs();
                    })
                    .hover(function() {
                        self.toggleViewsThumbs();
                    })
                    .text("Change View")
                    .addClass('flipbook-menu-btn')
                    .addClass('icon-general') 
                    .addClass('changeView')
                    .addClass('skin-color')
                    .css("line-height",2)
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

        createTopTen : function () {
            var self = this;
            self.topTenCreated = true;
            self.topTenHolder = $(document.createElement('div'))
                .appendTo(this.menuWrapper)
                .addClass('flipbook-topTenHolder')
                .addClass('flipbook-topTen')
                .addClass('skin-color-bg')
                .addClass('invisible')
                .addClass('transition')
            ;
            self.itemContainer = $(document.createElement('div'))
                .appendTo(self.topTenHolder)
                .addClass('row')
            ;
            self.secondItem = $(document.createElement('div'))
                .appendTo(self.itemContainer)
                .addClass('col-sm-12')
                .addClass('skin-color')
                .addClass('text-left')
            ;
            var secondItemCont = new Array("1) HardCover for brands", "2) How to create a book", "3) How to embed");

            var i;
            for (i = 0; i<secondItemCont.length; i++){
                createSecondItemCont(secondItemCont[i]);
            }
            function createSecondItemCont(itemLabel){
                var items = $(document.createElement('p'))
                        .appendTo(self.secondItem)
                        .text(itemLabel);
                ;
            }
            var arrowDown = $(document.createElement('div'))
                .appendTo(this.topTenHolder)
                .addClass('arrow-down')
                .css({'position':'absolute', 'bottom':'-10px', 'left':'0'})
            ;
            /*
            self.top10Layer[0].style[self.transformOrigin] = '100% 100%';
            
            $(window).resize(function () {
                self.resize();
            });*/
        },

        toggleTopTen : function () {
            if (!this.topTenCreated)
                this.createTopTen();
                var viewParent = $(".topTenOpen");
                var topTenPos = $(".topTen").position();           
                this.topTenHolder.css({'display':'block', 'left':parseInt(topTenPos.left) + parseInt(24) +'px', 'width':'15%', 'height':'65px', 'max-height':'none', 'z-index':'9'});
                this.topTenHolder.toggleClass('invisible');                        
        },

        createNextTwenty : function () {
            var self = this;
            self.nextTwentyCreated = true;
            self.nextTwentyHolder = $(document.createElement('div'))
                .appendTo(this.menuWrapper)
                .addClass('flipbook-nextTwentyHolder')
                .addClass('flipbook-next')
                .addClass('skin-color-bg')
                .addClass('invisible')
                .addClass('transition')
            ;
            var nextContent = $(document.createElement('i'))
                .appendTo(this.nextTwentyHolder)
                .addClass('skin-color')
                .text('View next 20 pages.')
            ;
            var nextContent = $(document.createElement('div'))
                .appendTo(this.nextTwentyHolder)
                .addClass('arrow-down')
                .css({'position':'absolute', 'bottom':'-10px', 'left':'42%'})
            ;
        },

        toggleNext : function () {
            if (!this.nextTwentyCreated)
                this.createNextTwenty();
                var viewParent = $(".nextOpen");
                var pagerPos = $('.flipbook-paginationCon').position();
                var nextTwentyPos = $('.fa-angle-double-right').position();           
                this.nextTwentyHolder.css({'display':'block', 'left':parseInt((pagerPos.left + nextTwentyPos.left) + 65) +'px', 'width':'130px', 'height':'65px', 'max-height':'none', 'z-index':'9'});
                this.nextTwentyHolder.toggleClass('invisible');                        
        },

        createHelp : function () {
            var self = this;
            self.helpCreated = true;
            self.helpHolder = $(document.createElement('div'))
                .appendTo(this.menuWrapper)
                .addClass('flipbook-helpHolder')
                .addClass('flipbook-help')
                .addClass('skin-color-bg')
                .addClass('invisible')                
                .addClass('transition')          
                .css({'display':'block', 'right':'5px', 'width':'30px', 'height':'30px', 'max-height':'none', 'bottom':'80px', 'z-index':'99'})      
            ;
            var xIcon = $(document.createElement('i'))
                .appendTo(this.helpHolder)
                .addClass('skin-color')
                .addClass('fa')
                .addClass('fa-times')
                .bind(self.CLICK_EV, function(e){
                    self.toggleAddPhoto();
                    self.toggleNext();
                    self.toggleTopTen();
                    self.toggleViews();
                    self.toggleHelp();
                })
            ;
            this.helpBg = $(document.createElement('div'))
                .appendTo(this.menuWrapper)
                .addClass('flipbook-helpBg')
                .addClass('invisible')
                .addClass('transition')
                .css({'position':'absolute', 'width':'100%', 'bottom':'0', 'height':'75px', 'background':'rgba(0,0,0,0.5)', 'z-index':'9'})
            ;
        },

        toggleHelp : function () {
            var self = this;
            if (!this.helpCreated)
                this.createHelp();
            var viewParent = $(".topTenOpen");
            var position = viewParent.position();             
            this.helpHolder.toggleClass('invisible');
            this.helpBg.toggleClass('invisible');            
        },

        zoomOut:function(){
            if(!this.webglMode){
                var newZoom = this.scroll.scale / 1.5 < this.scroll.options.zoomMin ? this.scroll.options.zoomMin : this.scroll.scale / 1.5;
                this.scroll.zoom(this.bookLayer.width()/2,this.bookLayer.height()/2,newZoom,400);
            }else
                this.Book.zoomTo(-2);
                this.Slideshow.zoomTo(-2);
        },
        zoomIn:function(){
            if(!this.webglMode){
                var newZoom = this.scroll.scale * 1.5 > this.scroll.options.zoomMax ? this.scroll.options.zoomMax : this.scroll.scale * 1.5;
                this.scroll.zoom(this.bookLayer.width()/2,this.bookLayer.height()/2,newZoom,400);
            }else
                this.Book.zoomTo(2);
                this.Slideshow.zoomTo(2);
        },

        toggleShare:function(){
            this.shareButtons.css('display','block');
            this.shareButtons.toggleClass('invisible');
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
                                self.Slideshow.goToPage(clickedPage);
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
            //if(typeof this.currentPage === 'undefined')
//                return;
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
//            if(this.p && this.options.pages.length != 24 && this.options.pages.length != 8)
//                this.Book.rightIndex = 0;
            //this.currentPage.attr('value',text);
            //this.currentPage.attr('size', this.currentPage.val().length);
/*--------------------*/
/*josh mods for share button*/
/*--------------------*/
            $(".ajaxLoaderDiv").hide();
            var self = this;
            var right_page = this.Book.rightIndex;
            var maxPages = this.options.pages.length;
            var left_page = right_page - 1;
            var fb_GO = 1;
            var tw_GO = 1;
            var pn_GO = 1;
            var em_GO = 1;
            var img_GO = 1;
            var desc = $('meta[name="description"]').attr('content');
            //load images only on current page. -2 pages left +2 pages right
            var l = window.location;
            var base_url = l.protocol + "//" + l.host;
            $('.pn'+right_page+' img[src="/images/right.png"').remove();
            $('.js-pageEnd img[src="/images/left.png"').remove();
            $(".pn"+right_page).addClass("right");
            //console.log("right: "+right_page);
            //var ImgContainerDiv = $("#ImgContainerDiv").html();
            var range = getRange(right_page,maxPages);
            $(".flipbook-thumbContainer").html("");
            self.thumbs = [];
            var thisMax = (range+20 <= this.options.pages.length) ? range+20 : this.options.pages.length;
            console.log(thisMax);
            for(var i=range;i<thisMax;i++){
              //console.log(i);
              //console.log(this.options.pages[i].originalSrc);
              if(this.options.pages[i].originalSrc){
                var thisOrigSrc = this.options.pages[i].originalSrc;

                if(self.options.pages[i].title != "last"){
                  var thisImageLoader = new Image();
                  thisImageLoader.src = thisOrigSrc;
                  $(thisImageLoader).attr("title",(i-1));
                  $(thisImageLoader).attr("thisOrigSrc",thisOrigSrc);
                  $(thisImageLoader).load(function () {
                    var curCount = $(this).attr("title");
                    var thisSrc = $(this).attr("thisOrigSrc");
                    $('.pn'+curCount+' img[src="/images/preloader.jpg"]')
                        .attr("src",thisSrc)
                        .addClass("preload"+curCount)
                        ;
                    var nWidth = this.naturalWidth;
                    var nHeight = this.naturalHeight;
                    var pH = $(".preload"+curCount).parent().height();
                    var pW = $(".preload"+curCount).parent().width();
                    //console.log(pW+" ~ "+pH+" ~ "+nWidth+" ~ "+nHeight);
                    $(".preload"+curCount).css({"max-width":String(self.options.pageWidth) + 'px',"max-height":String(self.options.pageHeight) + 'px',"display":'table-cell','vertical-align':'middle',"margin":"auto","position":"absolute","top":"0","bottom":"0","left":"0","right":"0" });
                    if(nWidth > nHeight){
                        $(".preload"+curCount).css({"height":"auto"});
                        var oH = $(".preload"+curCount).height();
                        var nH = (pH - oH) / 2;
                    }else if(nHeight > nWidth){
                        $(".preload"+curCount).css("width","auto");
                        var oW = $(".preload"+curCount).width();
                        var nW = (pW - oW) / 2;
                    }
                  });
                }
                var imgUrl = self.options.pages[i].originalThumb;
                if(imgUrl){
                    var thumb = new FLIPBOOK.Thumb($, self.Book, imgUrl, self.options.thumbnailWidth, self.options.thumbnailHeight, i);
                    thumb.image.style[self.transform] = 'translateZ(0)';
                    self.thumbs.push(thumb);
                    $(thumb.image)
                        .attr('title', i + 1)
                        .appendTo(self.thumbsContainer)
                        .bind(self.CLICK_EV, function(e){
                            if(!self.thumbScroll.moved){
                                var clickedPage = Number($(this).attr('title'))-1;
                                if(self.Book.goingToPage != clickedPage)
                                    self.Book.goToPage(clickedPage);
                                //self.Slideshow.goToPage(clickedPage);
                            }
                        });
                    thumb.loadImage();
                }
              } else {
                console.log("this: "+i);
              }
            }
    jQuery('#sharebuttons .info-share').unbind("click").on("click",function(event){
        var page_number = ($(this).parent().parent().attr('page_number')) - 1;
        event.preventDefault();
        event.stopPropagation();
        $(".infoContainer").each(function(){
            $(this).remove();
        });
        var title = "";
        var desc = "";
        title = pageGlobal[page_number].title;
        desc = pageGlobal[page_number].description;
        if(!title) title = "";
        if(!desc) desc = "";
        if(typeof title === "undefined") title = "";
        if(typeof desc === "undefined") desc = "";
        if((title == "" && desc == "")) title = "No description.";
        var thisContainer = $(document.createElement('div'))
            .prependTo($(this).parent())
            .unbind("click")
            .on(self.CLICK_EV, function(event){
                event.preventDefault();
                event.stopPropagation();
                $(".infoContainer").each(function(){
                    $(this).remove();
                });
            })
            .addClass('infoContainer')
        ;
        var thisTitle = $(document.createElement('div'))
            .appendTo(thisContainer)
            .html("<h2>"+title+"</h2>")
            .addClass('infoTitle')
        ;
        var thisDesc = $(document.createElement('div'))
            .appendTo(thisContainer)
            .html("<p>"+desc+"</p>")
            .addClass('infoDesc')
        ;
        var infoClose = $(document.createElement('div'))
            .appendTo(thisContainer)
            .html("<span>Close</span>")
            .unbind("click")
            .on(self.CLICK_EV, function(event){
                event.preventDefault();
                event.stopPropagation();
                $(".infoContainer").each(function(){
                    $(this).remove();
                });
            })
            .addClass('infoClose')
        ;
    });
            jQuery('.flipbook-page .fb-shareFront').unbind("click").on("click",function(event){
                var page_number = $(this).parent().parent().attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var page_image = getCurrentImg(page_number);
                console.log(page_image);
                var title = document.title;                
                var caption = window.location.href;
                caption = caption.split("/");
                caption = caption[2];
                if(fb_GO == 1){
                    fb_GO = 0;
                    app_id = $("#pp_header").attr("app_id");	
                    FB.init({appId: app_id, xfbml: true, cookie: true});
                    FB.ui({
                        method: 'feed',
                        name: title,
                        link: window.location.href,
                        picture: page_image,
                        caption: caption,
                        description: desc
                    },
                        function(response) {
                            fb_GO = 1;
                            if (response && response.post_id) {
                                alert('Post was published.');
                            } else {
                                alert('Post was not published.');
                            }
                        }
                    );                                      
                }             
            });             
            jQuery('.flipbook-page .fb-share').unbind("click").on("click",function(event){
                var currentElement = $(this).parent().parent();
                var page_number = currentElement.attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var page_image = l.protocol+getCurrentImg(page_number);
                //console.log(page_image);
                var capt = page_image;
                var title = document.title;
                if(fb_GO == 1){                    
                    fb_GO = 0;
                    app_id = $("#pp_header").attr("app_id");	
                    //FB.init({appId: app_id, xfbml: true, cookie: true});
                    //FB.init({appId: app_id, status: true, cookie: true, xfbml: true, channelURL : "https://dev.hardcover.me/channel.html", oauth:true});
                    FB.login(function(response) {
                        fb_GO = 1;
                        if (response.authResponse) {
                            var access_token = FB.getAuthResponse()['accessToken'];                            
                            FB.api("/me/picture?width=50&height=50&access_token="+access_token,  function(response) {                                
                                var user_pic = response.data.url;
                                var book_image = getCurrentImg(page_number);
                                book_image = book_image.replace("&h=1080&w=1485&zc=2", "&h=48&w=90&zc=2");
                                $(".createdPopDiv").remove();
                                var bookDiv = '<div class="bookDiv"><img src="'+book_image+'" /><h3>'+title+'</h3></div>';
                                var popDiv = '<div id="dialog" class="createdPopDiv" title="Facebook Post"><div class="popPic"><img src="'+user_pic+'" /></div><div class="popMsg"><textarea  name="fb_message" placeholder="Say something about this..." id="fb_message" style="display:block;width:100%;height:40px;"></textarea>'+bookDiv+'</div></div>';                                
                                currentElement.append(popDiv);
                                $("#dialog").dialog({
				                    modal: true,
				                    resizable: false,
				                    buttons: {
                                        "Post": function() {                                            
                                            var msg = $("#fb_message").val()+'\r\n'+"- "+window.location.href;                                            
                                            FB.api('/me/photos?access_token='+access_token, 'post', { url: page_image, message: msg, access_token: access_token }, function(response) {
                                                if (!response || response.error) {
                                                    alert('Error occured: ' + JSON.stringify(response.error));
                                                } else {
                                                    alert('Image posted on your wall.');
                                                }
                                            });                                    
                                            $(this).dialog("close");
                                        },
                                        "Cancel": function() {                                            
                                            $(this).dialog("close");
                                        }
                                    }
                                }); 
                                $(".ui-dialog").each(function(){
                                    $(this).css("z-index","9999"); 
                                    $(this).css("width","400px");                        
                                });   
                                $(".ui-dialog-buttonset button:nth-child(2)").addClass("cancel-button");                     
                            });                            
                        } else {
                            alert('User cancelled login or did not fully authorize.');
                        }
                    }, {scope: 'publish_stream'});                                      
                }             
            }); 
            jQuery('.flipbook-page .twitter-share').unbind("click").click(function(event){
                var page_number = $(this).parent().parent().attr('page_number');
                event.preventDefault();
                event.stopPropagation();                
                var title = document.title;
                var message = "A HardCover book";
                var link = 'http://twitter.com/intent/tweet?url='+getCurrentImg(page_number)+'&text='+title+'. '+message+' '+encodeURI(getCurrentImg(page_number))+' '+encodeURI(window.location.href)+'&hashtags=hardcover';
                if(tw_GO == 1){
                    tw_GO = 0;                    
                    newWindow = window.open(link,'_blank','width=700,height=260');
                    newWindow.focus();                   
                    $(newWindow.document).ready(function(){  
                        setTimeout(function () {                            
                            tw_GO = 1;   
                        }, 1000);                            
                    });
                }
            });
            jQuery('.flipbook-page .pinterest-share').unbind("click").click(function(event){
                var page_number = $(this).parent().parent().attr('page_number');
                event.preventDefault();
                event.stopPropagation();
	           var title = document.title;
	           var message = "A HardCover book";
	           var link = '//www.pinterest.com/pin/create/button/?url='+encodeURI(window.location.href)+'&media='+encodeURI(getCurrentImg(page_number))+'&description='+title+'. '+message;
	           if(pn_GO == 1){
                    pn_GO = 0;
                    newWindow = window.open(link,'_blank','width=700,height=260');                    
                    newWindow.focus();
                    $(newWindow.document).ready(function(){  
                        setTimeout(function () {                            
                            pn_GO = 1;   
                        }, 1000);                            
                    });
                }
            }); 
            jQuery('.flipbook-page .email-share').unbind("click").click(function(event){
                var page_number = $(this).parent().parent().attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var link = 'mailto:?subject=HardCover&amp;body=Check out my life in HardCover.'+encodeURI(window.location.href);
                if(em_GO == 1){
                    em_GO = 0;
                    newWindow = window.open(link,'_parent','width=700,height=260');                    
                    newWindow.focus();
                    $(newWindow.document).ready(function(){  
                        setTimeout(function () {                            
                            em_GO = 1;   
                        }, 1000);                            
                    });                    
                }
            });
            function getCurrentImg(page_number) {
                return $($('.pn'+page_number+' img')[0]).attr('src');
            }
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
            var curView = this.currentView;
            if(curView == "Slideshow")
                this.thumbsHorizontal();
            else
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
            var curView = this.currentView;
            if(curView == "Slideshow")
                this.thumbsHorizontal();
            else
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
         * create views
         */
        createViews : function () {
            var self = this;
            self.viewsCreated = true;
            this.viewHolder = $(document.createElement('div'))
                .appendTo(this.menuWrapper)
                .addClass('flipbook-viewHolder')
                .addClass('flipbook-changeView')
                .addClass('skin-color-bg')
                .addClass('invisible')
                .addClass('transition')
            ;
            var i;
            //var changeViewItem = new Array("Book Flip", "Timeline", "Slideshow");
            var changeViewItem = [
                ["1) Bookflip", "bookflip-ico.jpg"],
                ["2) Timeline", "timeline-ico.jpg"],
                ["3) Slideshow", "slideshow-ico.jpg"]
            ];

            for(i=0;i<changeViewItem.length;i++) {
                var jsTitle = changeViewItem[i][0].replace(" ","_");
                this.btnCont = $(document.createElement('div'))
                    .appendTo(this.viewHolder)
                    .addClass('flipbook-changeView-item')
                    .addClass('flipbook-changeView-'+jsTitle)
                ;

                var btnBookflip = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.btnCont)
                    .addClass('flipbook-viewBtn')
                    .text(changeViewItem[i][0])
                    .addClass('icon-general')
                    .addClass('skin-color')
                    .attr("title",changeViewItem[i][0])
                    .bind(self.CLICK_EV, function(e){
                        var changeTo = $(this).attr("title").substr(3);
                        var jsTitle = changeTo.replace(" ","_");                        
                        $(".flipbook-changeView-"+jsTitle).siblings().removeClass('active-view');
                        $(".flipbook-changeView-"+jsTitle).addClass('active-view');
                        self.changeView(changeTo);
                    })
                ;

            }
            var arrowDown = $(document.createElement('div'))
                .appendTo(this.viewHolder)
                .addClass('arrow-down')
                .css({'position':'absolute', 'bottom':'-10px', 'right':'80px'})
            ;

            $(".flipbook-changeView > .flipbook-changeView-item")
                .first()
                .addClass("active-view")
            ;
        },
        /**
         * toggle views
         */
        toggleViews : function () {
            if (!this.viewsCreated)
                this.createViews();
            //this.viewHolder.css('display','block');
            var viewParent = $(".changeView");
            var viewParentParent = viewParent.parent();
            var positionVP = viewParent.position();
            var positionVPP = viewParentParent.position();
            var newLeft = ((positionVPP.left+positionVP.left) - 56) +"px";
            this.viewHolder.css({'display':'block', 'left':newLeft, 'max-height':'none', 'z-index':'9'});
            this.viewHolder.toggleClass('invisible');                        
        },
        /**
         * create views thumbs
         */
        createViewsThumbs : function () {
            var self = this;
            self.viewsCreatedThumbs = true;
            this.viewHolderThumbs = $(document.createElement('div'))
                .appendTo(this.menuWrapper)
                .addClass('flipbook-viewHolderThumb')
                .addClass('flipbook-changeView')
                .addClass('skin-color-bg')
                .addClass('invisible')
                .addClass('transition')
            ;
            var i;
            var changeViewItem = [
                ["Bookflip", "bookflip-ico.jpg"],
                ["Timeline", "timeline-ico.jpg"],
                ["Slideshow", "slideshow-ico.jpg"]
            ];

            for(i=0;i<changeViewItem.length;i++) {
                var jsTitle = changeViewItem[i][0].replace(" ","_");
                this.btnCont = $(document.createElement('div'))
                    .appendTo(this.viewHolderThumbs)
                    .addClass('flipbook-changeView-item')
                    .addClass('flipbook-changeView-'+jsTitle)
                ;

                var btnBookflip = $(document.createElement('span'))
                    .attr('aria-hidden', 'true')
                    .appendTo(this.btnCont)
                    .addClass('flipbook-viewBtn')
                    .text(changeViewItem[i][0])
                    .addClass('icon-general')
                    .addClass('skin-color')
                    .attr("title",changeViewItem[i][0])
                    .bind(self.CLICK_EV, function(e){
                        var changeTo = $(this).attr("title");
                        var jsTitle = changeTo.replace(" ","_");                        
                        $(".flipbook-changeView-"+jsTitle).siblings().removeClass('active-view');
                        $(".flipbook-changeView-"+jsTitle).addClass('active-view');
                        self.changeView(changeTo);
                    })
                ;

                var viewThumbs = $(document.createElement('img'))
                    .attr('src', 'https://dev.hardcover.me/images/'+changeViewItem[i][1])
                    .attr('title', changeViewItem[i][0])
                    .appendTo(this.btnCont)
                    .bind(self.CLICK_EV, function(e){
                        var changeTo = $(this).attr("title");
                        var jsTitle = changeTo.replace(" ","_");                        
                        $(".flipbook-changeView-"+jsTitle).siblings().removeClass('active-view');
                        $(".flipbook-changeView-"+jsTitle).addClass('active-view');
                        self.changeView(changeTo);
                    })
                ;

            }
            var arrowDown = $(document.createElement('div'))
                .appendTo(this.viewHolder)
                .addClass('arrow-down')
                .css({'position':'absolute', 'bottom':'-10px', 'right':'80px'})
            ;

            $(".flipbook-changeView > .flipbook-changeView-item")
                .first()
                .addClass("active-view")
            ;
        },
        /**
         * toggle views
         */
        toggleViewsThumbs : function () {
            if (!this.viewsCreatedThumbs)
                this.createViewsThumbs();
            //this.viewHolder.css('display','block');
            var viewParent = $(".changeView");
            var viewParentParent = viewParent.parent();
            var positionVP = viewParent.position();
            var positionVPP = viewParentParent.position();
            var newLeft = ((positionVPP.left+positionVP.left) - 56) +"px";
            this.viewHolderThumbs.css({'display':'block', 'left':newLeft, 'bottom':'44px;', 'max-height':'none', 'z-index':'9'});
            //this.viewHolder.toggleClass('invisible');
            var checkClass = this.viewHolderThumbs.hasClass('invisible');

            if(checkClass) {
                //alert("Change view call out is hidden");
                this.viewHolderThumbs.removeClass('invisible');
            }
            $('.flipbook-viewHolderThumb').mouseenter( function() {
                $(this).removeClass('invisible');
            });
            $('.flipbook-viewHolderThumb').mouseleave( function() {
                $(this).addClass('invisible');
            });
        },

        /**
         * toggle views
         */
        changeView : function (vTitle) {
            var self = this;
            var vClass = vTitle.toLowerCase();
            if(vTitle == "Timeline"){
                //this.gotoTimeline();
            }
            self.currentView = vTitle;
            if (!this.thumbsCreated)
                this.createThumbs();
            if(vTitle == "Slideshow"){
                $(".main-wrapper").css("background","#000");
                /*$(".flipbook-helpBg").css("margin-bottom","52px");
                $(".flipbook-topTen").css("margin-bottom","52px");                
                $(".flipbook-addPhoto").css("margin-bottom","52px");
                $(".flipbook-nextTwentyHolder").css("margin-bottom","52px");
                $(".flipbook-helpHolder").css("margin-bottom","52px");
                $('.flipbook-viewHolder').css("margin-bottom","52px");*/
                var gallery = Galleria.get(0);
                if(gallery)
                    gallery.destroy();
                $(".slideshowLayer").remove();
                if(this.options.viewSlideshow) {
                   this.createSlideshowV2();
                   this.thumbsHorizontal();
                   $(".buttonsFrontPage").hide();
                   $("#share_here_container").hide();
                   this.changePagination();
                   var right_page = this.Slideshow.rightIndex;
                }
                //this.thumbHolder.css("display","block");
                //this.thumbHolder.removeClass('invisible');
            } else if(vTitle == "Bookflip"){
                $(".main-wrapper").css("background","url('/images/patterns/retina_wood.png')");
                $(".flipbook-helpBg").css("margin-bottom","44px");
                $(".flipbook-topTen").css("margin-bottom","0px");
                $(".flipbook-addPhoto").css("margin-bottom","0px");
                $(".flipbook-nextTwentyHolder").css("margin-bottom","0px");
                $(".flipbook-helpHolder").css("margin-bottom","0px");
                $('.flipbook-viewHolder').css("margin-bottom","0px");
                this.thumbsVertical();
                this.thumbHolder.css("display","none");
                this.thumbHolder.addClass('invisible');
                $(".buttonsFrontPage").show();
                $("#share_here_container").show();
                this.changePagination();
                var right_page = this.Book.rightIndex;
            } else if(vTitle == "Timeline"){
                $(".main-wrapper").css("background","url('/images/patterns/retina_wood.png')");
                $(".timelineLayer").remove();
              if(this.options.viewTimeline){
                    this.createTimelineV2();
                $(".flipbook-helpBg").css("margin-bottom","44px");
                $(".flipbook-topTen").css("margin-bottom","0px");
                $(".flipbook-addPhoto").css("margin-bottom","0px");
                $(".flipbook-nextTwentyHolder").css("margin-bottom","0px");
                $(".flipbook-helpHolder").css("margin-bottom","0px");
                $('.flipbook-viewHolder').css("margin-bottom","0px");
                this.thumbsVertical();
                this.thumbHolder.css("display","none");
                this.thumbHolder.addClass('invisible');
                $(".buttonsFrontPage").hide();
                $("#share_here_container").hide();
                $(".timenav").waitUntilExists(function(){
                    console.log("finished loading nav");
                    self.changePagination();
                });
                var right_page = this.Timeline.rightIndex;
              }
            }
            if(right_page == "" || isNaN(right_page))
                right_page = 0;
            location.hash = vTitle+"~"+right_page;
            console.log("is iframed: "+this.options.isFramed);
            if(this.options.isFramed){
                var pHash = new String(window.parent.location);
                pHash = pHash.split("#");
                var hBook = pHash[1].split("=")[1].split(":");
                window.parent.location = pHash[0]+"#hardcover="+hBook[0]+":"+hBook[1]+":"+vTitle+"~"+right_page;
                //hardcover=stash:step-by-step_guide:Slideshow~2
                console.log(hBook);
            }
            $(".likeCon .fb-like > span").each(function(){
                $(this).css({"width":"78px","height":"20px"});
            });
            console.log("changing view to: "+vTitle);
            $("#container").attr("current_view",vTitle);
            $("."+vClass+"Layer").show();
            $("."+vClass+"Layer").siblings().hide();
            this.menuWrapper.show();
            if (this.viewsCreated) self.viewHolder.toggleClass('invisible');
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
                .appendTo(self.menuWrapper)
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
                if(imgUrl){
                    var thumb = new FLIPBOOK.Thumb($, self.Book, imgUrl, self.options.thumbnailWidth, self.options.thumbnailHeight, i);
                    thumb.image.style[self.transform] = 'translateZ(0)';
                    self.thumbs.push(thumb);
                    $(thumb.image)
                        .attr('title', i + 1)
                        .appendTo(self.thumbsContainer)
                        .bind(self.CLICK_EV, function(e){
                            if(!self.thumbScroll.moved){
                                var clickedPage = Number($(this).attr('title'))-1;
                                if(self.Book.goingToPage != clickedPage)
                                    self.Book.goToPage(clickedPage);
                                //self.Slideshow.goToPage(clickedPage);
                            }
                        });
                    thumb.loadImage();    
                }                
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
            var curView = this.currentView;
            if(curView == "Slideshow")
                this.thumbsHorizontal();
            else
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
            var cHeight = Number(this.bookLayer.height())-40;
            this.thumbHolder
                .css('width', String(w) + 'px')
                .css('height', cHeight+'px')
                .css('bottom', 'auto')
                .css('left', 'auto')
                .css('top', '-'+cHeight+'px')
                .css('z-index', '8')
                .css('overflow', 'hidden')
                .css('right', '0px');
            this.thumbsContainer
                .css('height', String(h) + 'px')
                .css('width', String(w) + 'px')
                .css('margin', '0px')
                ;
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
            var wW = $(document).width();
            var valCenter = (wW - w)/2;
            this.thumbHolder
                .css('width', '100%')
                .css('height', String(h) + 'px')
                .css('left', "auto")
                .css('right', 'auto')
                .css('top', 'auto')
                .css('bottom', '45px')
                .css('overflow', 'visible')
            ;
            this.thumbsContainer
                .css('height', String(h) + 'px')
                .css('width', String(w) + 'px')
                .css('margin', '0px auto')
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
        },

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
        },
        initTimeline:function(){
            var cHash = location.hash;
            cHash = cHash.replace("#","");
            var cDate = $(".created_dates .created_date"+cHash).html();
            var cPage = cDate;
            $('.timelineLight').timeline({
                openTriggerClass : '.read_more',
                hideControles : true,
                startItem : cPage
            });   
        },
        gotoTimeline:function(){
            var right_page = this.Book.rightIndex;
            var left_page = right_page - 1;
            var cDate = $(".created_dates .created_date"+left_page).html();
            var cPage = cDate;
            //console.log(cPage);
            //$('.timelineLight').timeline('goTo', cPage);
        }
    };


//josh shelf add

    $.fn.shelfBook.options = {
        css:"",

        pdf:"",
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
    var Shelf = function (){

    };
    /**
     * Object prototype
     * @type {Object}
     */
    Shelf.prototype = {

        init:function(options,elem){
            /**
             * local variables
             */
            var self = this;
            self.elem = elem;
            self.$elem = $(elem);
            self.options = {};


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
                self.Book = new FLIPBOOK.top10Book(self.book[0], self.options);

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
                .addClass('skin-color-bg')
                .appendTo(this.wrapper)
            ;
            this.menu = $(document.createElement('div'))
                    .addClass('flipbook-menu')
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
                .css('width', 'auto')
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
//end josh top 10    
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


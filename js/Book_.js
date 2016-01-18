var FLIPBOOK = FLIPBOOK || {};

/**
 *
 * @param el  container for the book
 * @param options
 * @constructor
 */
FLIPBOOK.Book = function (el, options) {
    /**
     * local variables
     */
    var self = this, i,main = options.main ;
    this.main = options.main;
    this.hasTouch = main.hasTouch;
    this.perspective = main.perspective;
    this.transform = main.transform;
    this.transformOrigin = main.transformOrigin;
    this.transformStyle = main.transformStyle;
    this.transition = main.transition;
    this.transitionDuration = main.transitionDuration;
    this.transitionDelay = main.transitionDelay;
    this.transitionProperty = main.transitionProperty;
    this.backfaceVisibility = main.backfaceVisibility;

    this.wrapper = typeof el == 'object' ? el : document.getElementById(el);
    jQuery(this.wrapper).addClass('flipbook-book');

    // Default options
    this.options = {
        //A4
        onTurnPageComplete:null,
        //2d or 3d
        flipType:'2d',
        shadow1opacity:.7, // black overlay for 3d flip
        shadow2opacity:.7 // gradient overlay
    };

    // User defined options
    for (i in options) this.options[i] = options[i];
    this.pages = [];
    this.pageWidth = this.options.pageWidth;
    this.pageHeight = this.options.pageHeight;
    this.animating = false;
    this.rightIndex = 0;
    this.onTurnPageComplete = this.options.onTurnPageComplete;

    var s = this.wrapper.style;
    s.width = String(2 * this.pageWidth) + 'px';
    s.height = '100%';//String(this.pageHeight) + 'px';

    this.flipType = this.options.flipType;
    this.shadow1opacity = this.options.shadow1opacity;
    this.shadow2opacity = this.options.shadow2opacity;

    //add bitmap pages
    var point1, point2;
    var maxPages = self.options.pages.length;
    //book shadow
    //left
    this.shadowL = document.createElement('div');
    jQuery(this.shadowL).addClass('flipbook-shadowLeft')
        .css("z-index",String(100 - maxPages))
        .css("width",String(this.pageWidth) + 'px')
        .css("height", String(this.pageHeight) + 'px');
//    this.shadowL.style = this.wrapper.style;
//    this.shadowL.style.width = String(this.pageWidth) + 'px';
//    this.shadowL.style.height = String(this.pageHeight) + 'px';
    this.wrapper.appendChild(this.shadowL);
    this.shadowLVisible =true;
    //right
    this.shadowR = document.createElement('div');
    jQuery(this.shadowR).addClass('flipbook-shadowRight')
        .css("z-index",String(100 - maxPages))
        .css("width",String(this.pageWidth) + 'px')
        .css("height", String(this.pageHeight) + 'px');
//    this.shadowR.style = this.wrapper.style;
//    this.shadowR.style.width = String(this.pageWidth) + 'px';
//    this.shadowR.style.height = String(this.pageHeight) + 'px';
    this.wrapper.appendChild(this.shadowR);
    this.shadowRVisible =true;


    this.shadowRight();
    for ( i = 0; i < maxPages; i++) {
        this.addPage(i,maxPages);
        jQuery(this.pages[i].wrapper)
            .attr('title', i + 1)
            .bind(self.main.CLICK_EV, function(e){
                var x, x2, y, y2, z, z2;
                x = self.main.scroll.x;
                x2 = self.xOnMouseDown;
                y = self.main.scroll.y;
                y2 = self.yOnMouseDown;
                z = self.zoomOnMouseUp;
                z2 = self.zoomOnMouseDown;
                //console.log(e.target.className+" ~ "+self.main.scroll.moved+" ~ "+self.main.scroll.animating+" ~ "+self.main.scroll.zoomed+" ~ "+(self.zoomOnMouseDown+" ~ "+self.main.scroll.scale));
                function isClose(x1,x2){
                    //console.log("is close: "+(Math.abs(x1-x2) < 10));
                   return (Math.abs(x1-x2) < 10);
                }
                if(self.main.scroll.moved || self.main.scroll.zoomed || (self.zoomOnMouseDown != self.main.scroll.scale))
                    return;
                //console.log("passed 1");
                if(e.target.className == "flipbook-page-link")
                    return;
                //console.log("passed Cliked");
                if(isClose(x,x2) && isClose(y,y2) && z === z2 ){
                    //console.log("page Cliked");
                    var clickedPage = Number(jQuery(this).attr('title'))-1;
                    if(clickedPage == self.rightIndex){
                        self.nextPage();
                    }
                    else{
                        self.prevPage();
                    }
                }
            })
            .bind(self.main.START_EV, function(e){
                self.zoomOnMouseDown = self.main.scroll.scale;
                self.xOnMouseDown = self.main.scroll.x;
                self.yOnMouseDown = self.main.scroll.y;
            })
            .bind(self.main.END_EV, function(e){
                self.zoomOnMouseUp = self.main.scroll.scale;
                self.xOnMouseUp = self.main.scroll.x;
                self.yOnMouseUp = self.main.scroll.y;
            })
        ;
    }
    this.addLike(maxPages);
    this.pages[0].loadPage();
    this.pages[1].loadPage();
    if(this.pages.length > 2)
    this.pages[2].loadPage();

    this.updateVisiblePages();

    //disable page scrolling
    jQuery(this.wrapper).on('DOMMouseScroll',function(e){e.preventDefault();});
    jQuery(this.wrapper).on('mousewheel',function(e){e.preventDefault();});
};

FLIPBOOK.Book.prototype.constructor = FLIPBOOK.Book;

FLIPBOOK.Book.prototype = {
    /**
     * add new page to book
     * @param i
     */
    addPage:function(i,maxPages){
        //console.log("pl: "+this.pages.length);
        var page = new FLIPBOOK.Page(this.options.pages[i], this.pageWidth, this.pageHeight,this.pages.length,this,maxPages);
//        var page = new FLIPBOOK.Page(this.options.pages[i].src, this.options.pages[i].htmlContent, this.pageWidth, this.pageHeight, this.pages.length,this);
        this.wrapper.appendChild(page.wrapper);
        this.pages.push(page);
    },
    addLike:function(maxPages){
        var like = new FLIPBOOK.Like(this.pageWidth, this.pageHeight,this,maxPages);
        this.wrapper.appendChild(like.wrapper);
    },

    // i - page number, 0-based 0,1,2,... pages.length-1
    goToPage:function (i) {
        if (i < 0 || i > this.pages.length)
            return;
        if (this.animating)
            return;
        if(isNaN(i))
            return;
        this.goingToPage = i;
        //convert target page to right index 0,2,4, ... pages.length
        i = (i % 2 == 1) ? i + 1 : i;

        if(i == 0 ){
            this.rightIndex == this.pages.length ? this.shadowNone() : this.shadowRight();
        }else if(i == this.pages.length){
            this.rightIndex == 0 ? this.shadowNone() : this.shadowLeft();
        }

        var pl, pr, plNew, prNew;
        //if going left or right
        if (i < this.rightIndex)
        //flip left
        {
            pl = this.pages[this.rightIndex - 1];
            pr = this.pages[i];
            if (i > 0) {
                plNew = this.pages[i - 1];
                if(this.flipType == '2d')
                plNew.expand();
                plNew.show();
            }
            if(this.flipType == '2d')
            pr.contract();
            this.animatePages(pl, pr);

        }
        //flip right
        else if (i > this.rightIndex) {
            pl = this.pages[i - 1];
            pr = this.pages[this.rightIndex];
            if (i < this.pages.length) {
                prNew = this.pages[i];
                if(this.flipType == '2d')
                prNew.expand();
                prNew.show();
            }
            if(this.flipType == '2d')
            pl.contract();
            this.animatePages(pr, pl);
        }

        this.rightIndex = i;
            var paginationPage = i, pagesLength = this.pages.length;
            if(!(paginationPage % 2)) paginationPage--;
            if(paginationPage < 0) paginationPage = 0;
            if(paginationPage == (pagesLength - 1)) paginationPage = pagesLength;
            // for pagination update    

            var currentPage = $(".flipbook-currentPage span[title='"+paginationPage+"']");
            currentPage.addClass('page-current');
            currentPage.siblings().removeClass('page-current');
            var current_view = $("#container").attr("current_view");
            location.hash = current_view+"~"+paginationPage;
            if(this.options.isFramed){
                var pHash = new String(window.parent.location);
                console.log("parent location: "+pHash);
                pHash = pHash.split("#");
                var hBook = pHash[1].split("=")[1].split(":");
                window.parent.location = pHash[0]+"#hardcover="+hBook[0]+":"+hBook[1]+":"+current_view+"~"+paginationPage;
                //hardcover=stash:step-by-step_guide:Slideshow~2
                console.log(hBook);
            }
//        if(this.main.p && this.pages[0].imageSrc != "images/Art-1.jpg")
//            this.rightIndex = 0;
    },
    /**
     * page flip animation
     * @param first
     * @param second
     */
    animatePages:function (first, second) {
        var index = this.rightIndex;
        $(".pn"+index+" #sharebuttons").hide();
        $(".pn"+(index+1)+" #sharebuttons").hide();
        this.animating = true;
        var self = this,
            time1 = self.options.time1,
            time2 = self.options.time2,
            transition1 = self.options.transition1,
            transition2 = self.options.transition2
            ;

        first.show();
        jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');
        jQuery(second.wrapper).css("background","none");
        jQuery(first.wrapper).css("background","none");
        //FIRST START
        if(this.flipType == '3d') {

            second.show();
            jQuery(second.wrapper).css('visibility', 'hidden');

            jQuery(first.wrapper).css('visibility', 'visible');
            jQuery(first.wrapper).css("text-indent", '0px');
            jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');

            var angle = (first.index < second.index)  ? "-90" : "90";

            jQuery(first.overlay).animate({opacity:self.shadow1opacity},{duration:time1,easing:transition1});

            jQuery(first.wrapper).animate(
                {
                    textIndent: angle
                },
                {
                    step: function(now,fx) {
                            jQuery(this).css(self.transform,'rotateY('+Math.round(now)+'deg)');
//                            console.log(now);
                        },
                    duration:time1,
                    easing:transition1,
                    complete:function(){
                        //----------------
                        // FIRST COMPLETE
                        //----------------
//                        console.log("complete");
//                        console.log("angle : "+angle);
                        first.hide();
                        first.hideVisibility();
                        jQuery(second.wrapper).css('visibility', 'visible');
                        //shadow
                        jQuery(second.overlay).css('opacity',self.shadow1opacity);
                        jQuery(second.overlay).animate({opacity:0},{duration:time2,easing:transition2});
                        //first complete, animate second
                        jQuery(second.wrapper).css(self.transform,'rotateY('+angle+'deg)');

                        //second initial ange
                        jQuery(second.wrapper).css("text-indent", String(-angle)+'px');
                        jQuery(second.wrapper).animate(
                            {
                                textIndent: 0
                            },
                            {
                                step: function(now,fx) {
                                        jQuery(this).css(self.transform,'rotateY('+Math.round(now)+'deg)');
//                                        console.log(now);
                                    },
                                complete:function(){
                                    jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');
                                    jQuery(first.wrapper).css('visibility','visible');
                                    jQuery(second.wrapper).css(self.transform,'rotateY(0deg)');
                                    jQuery(second.wrapper).css('visibility','visible');
                                },
                                duration:time2,
                                easing:transition2
                            }
                        );
                    }
                }
            );
        }
        else {
            jQuery(first.wrapper).animate({width:0}, time1, transition1,
                //on complete
                function() {
                    second.show();
                    jQuery(second.wrapper).animate({width:second.width}, time2, transition2);
                });

        }

        //BOTH COMPLETE
        setTimeout(function () {
            console.log("timeout! both");
            if (self.onTurnPageComplete)
                self.onTurnPageComplete.call(self);
            self.main.updateCurrentPage();
            self.animating = false;
            self.updateVisiblePages();
            first.overlay.style.opacity = '0';
            jQuery(first.wrapper).css("background","#fff");
            jQuery(second.wrapper).css("background","#fff");
            jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');
            jQuery(second.wrapper).css(self.transform,'rotateY(0deg)');
        }, Number(time1)+Number(time2));
    },
    /**
     * update page visibility depending on current page index
     */
    updateVisiblePages:function () {
        if (this.animating)
            return;
        for (var i = 0; i < this.pages.length; i++) {
            if ((i < (this.rightIndex - 1)) || (i > (this.rightIndex))) {
                if(this.flipType == '2d')
                    this.pages[i].contract();
                this.pages[i].hide();
            }
            else {
                if(this.flipType == '2d')
                    this.pages[i].expand();
                this.pages[i].show();
            }
            if (this.rightIndex == 0) {
                if(this.flipType == '2d')
                    this.pages[1].contract();
                 this.pages[1].hide();
            }
        }

        var index =this.rightIndex, pages = this.pages;
        if(index > 2)
            pages[index -3].loadPage();
        if(index > 0)
            pages[index -2].loadPage();
        if(index > 0)
            pages[index -1].loadPage();
        if(index < pages.length)
            pages[index].loadPage();
        if(index < pages.length)
            pages[index +1].loadPage();
        if(index < pages.length-2)
            pages[index +2].loadPage();

        if(index > 0 && index < this.pages.length){
            this.shadowBoth();
        }else if(index == 0){
            this.shadowRight();
        }else{
            this.shadowLeft();
        }
        $(".pn"+index+" #sharebuttons").show();
        $(".pn"+(index+1)+" #sharebuttons").show();
        this.doPagination();
    },
    //josh for pagination
        doPagination:function(){
            var curView = "Bookflip";
            var curPage = this.rightIndex;
            var pTotal = $(".flipbook-paginationCon").find(".MenuItem").length;
            console.log(pTotal + " ~ " + curPage);
            var cMax = 0;
            var cCount = 1;
            var rCount = 0;
            curPage = curPage + 1;
            for(var i=1;i<=pTotal;i++){
              //console.log("#"+curView+"-MenuItem-"+cCount);
              if($("#"+curView+"-MenuItem-"+cCount)){
                if(curPage <= 0) curPage = 1;
                //console.log(cMax + " ~ " + curPage + " ~ " + cCount + " ~ " + i);
                if (curPage > cMax && curPage <= cMax+20)
                    $("#"+curView+"-MenuItem-"+cCount).show();
                else
                    $("#"+curView+"-MenuItem-"+cCount).hide();
                if(i == (rCount+10)){
                    cMax += 20;
                    rCount += 10;
                }
                cCount += 2;
              }
            }
        },
        //end josh
    /**
     * go to next page
     */
    nextPage:function () {
        if (this.rightIndex == this.pages.length || this.animating)
            return;
        var nextPage = this.rightIndex + 2;
        this.goToPage(nextPage);
    },
    /**
     * go to previous page
     */
    prevPage:function () {
        if (this.rightIndex == 0 || this.animating)
            return;
        var nextPage = this.rightIndex - 2;
        if(nextPage < 0) nextPage = 0;        
        this.goToPage(nextPage);
    },

    shadowRight:function(){
        if(this.shadowLVisible){
            this.shadowLVisible = false;
            this.shadowL.style.display = 'none';
        }
        if(!this.shadowRVisible){
            this.shadowRVisible = true;
            this.shadowR.style.display = 'block';
        }
    },
    shadowLeft:function(){
        if(this.shadowRVisible){
            this.shadowRVisible = false;
            this.shadowR.style.display = 'none';
        }
        if(!this.shadowLVisible){
            this.shadowLVisible = true;
            this.shadowL.style.display = 'block';
        }
    },
    shadowBoth:function(){
        if(!this.shadowRVisible){
            this.shadowRVisible = true;
            this.shadowR.style.display = 'block';
        }
        if(!this.shadowLVisible){
            this.shadowLVisible = true;
            this.shadowL.style.display = 'block';
        }
    },
    shadowNone:function(){
        if(this.shadowRVisible){
            this.shadowRVisible = false;
            this.shadowR.style.display = 'none';
        }
        if(this.shadowLVisible){
            this.shadowLVisible = false;
            this.shadowL.style.display = 'none';
        }
    }

};

FLIPBOOK.Timeline = function (el, options) {
    /**
     * local variables
     */
    var self = this, i,main = options.main ;
    this.main = options.main;
    this.hasTouch = main.hasTouch;
    this.perspective = main.perspective;
    this.transform = main.transform;
    this.transformOrigin = main.transformOrigin;
    this.transformStyle = main.transformStyle;
    this.transition = main.transition;
    this.transitionDuration = main.transitionDuration;
    this.transitionDelay = main.transitionDelay;
    this.transitionProperty = main.transitionProperty;
    this.backfaceVisibility = main.backfaceVisibility;

    this.wrapper = typeof el == 'object' ? el : document.getElementById(el);
    jQuery(this.wrapper).addClass('timelineLight');
    jQuery(this.wrapper).addClass('tl');
    
    this.options = {
        //A4
        onTurnPageComplete:null,
        //2d or 3d
        flipType:'2d',
        shadow1opacity:.7, // black overlay for 3d flip
        shadow2opacity:.7 // gradient overlay
    };
    // User defined options
    for (i in options){
        //console.log(options[i]);
        this.options[i] = options[i];    
    } 
    this.times = [];
    this.pageWidth = this.options.pageWidth;
    this.pageHeight = this.options.pageHeight;    
    this.rightIndex = 0;

    var s = this.wrapper.style;
    s.width = String(2 * this.pageWidth) + 'px';
    s.height = String(this.pageHeight) + 'px';
    
    for ( i = 0; i < self.options.pages.length; i++) {
        var maxPages = self.options.pages.length;
        this.addTime(i,maxPages);        
    }
};

FLIPBOOK.Timeline.prototype.constructor = FLIPBOOK.Timeline;

FLIPBOOK.Timeline.prototype = {
    addTime:function(i,maxPages){ 
        var thisArr = new Array();
        $(".created_dates span").each(function(){
            thisArr.push($(this).attr("title"));    
        });
        var ifSrc = false;
        if(typeof this.options.pages[thisArr[i]] !== "undefined") ifSrc = true;                
        if(ifSrc != false){
            var time = new FLIPBOOK.Time(this.options.pages[thisArr[i]], this.pageWidth, this.pageHeight,this.times.length,this,maxPages);
            //console.log(time);            
            if(time.twrapper){
                this.wrapper.appendChild(time.twrapper);
                this.wrapper.appendChild(time.tLoader);
                this.times.push(time);    
            }
        }        
    },
    goToPage:function (i) {
        if (i < 0 || i > this.times.length)
            return;        
        if(isNaN(i))
            return;
        this.goingToPage = i;
        console.log("timeline going to page: "+i);
        this.rightIndex = i;
            var paginationPage = i, timesLength = this.times.length;
            //if(!(paginationPage % 2)) paginationPage--;                        
            if(paginationPage < 0) paginationPage = 0;
            if(paginationPage == (timesLength+1)) paginationPage = timesLength+1;
            // for pagination update    
             
            var currentPage = $(".flipbook-currentPage span[title='"+paginationPage+"']");
            currentPage.addClass('page-current');
            currentPage.siblings().removeClass('page-current');
            var current_view = $("#container").attr("current_view");
            location.hash = current_view+"~"+paginationPage;
            if(this.options.isFramed){
                var pHash = new String(window.parent.location);
                pHash = pHash.split("#");
                var hBook = pHash[1].split("=")[1].split(":");
                window.parent.location = pHash[0]+"#hardcover="+hBook[0]+":"+hBook[1]+":"+current_view+"~"+paginationPage;
                //hardcover=stash:step-by-step_guide:Slideshow~2
                console.log(hBook);
            }
    },
    nextPage:function () {
        if (this.rightIndex == this.times.length)
            return;
        var nextPage = this.rightIndex + 1;        
        var cDate = $(".created_dates .created_date"+nextPage).html();
        var cPage = cDate;
        $('.timelineLight').timeline('goTo', cPage);
        this.goToPage(nextPage);
    },   
    prevPage:function () {
        console.log("prev: "+this.rightIndex);
        if (this.rightIndex == 0)
            return;
        var nextPage = this.rightIndex - 1;
        if(nextPage < 0) nextPage = 0;
        var cDate = $(".created_dates .created_date"+nextPage).html();                                    
        var cPage = cDate;                        
        $('.timelineLight').timeline('goTo', cPage);
        this.goToPage(nextPage);
    }
};

FLIPBOOK.Slideshow = function (el, options) {
    /**
     * local variables
     */
    var self = this, i,main = options.main ;
    this.main = options.main;
    this.hasTouch = main.hasTouch;
    this.perspective = main.perspective;
    this.transform = main.transform;
    this.transformOrigin = main.transformOrigin;
    this.transformStyle = main.transformStyle;
    this.transition = main.transition;
    this.transitionDuration = main.transitionDuration;
    this.transitionDelay = main.transitionDelay;
    this.transitionProperty = main.transitionProperty;
    this.backfaceVisibility = main.backfaceVisibility;

    this.wrapper = typeof el == 'object' ? el : document.getElementById(el);
    jQuery(this.wrapper).addClass('flipbook-book');
    jQuery(this.wrapper).css({"position":"absolute","top":"-90px"});
            
    // Default options
    this.options = {
        //A4
        onTurnPageComplete:null,
        //2d or 3d
        flipType:'2d',
        shadow1opacity:.7, // black overlay for 3d flip
        shadow2opacity:.7 // gradient overlay
    };    
    // User defined options
    for (i in options) this.options[i] = options[i];
    this.options['flipType'] = '2d';    
    this.slides = [];
    var wW = $(document).width();
    var wH = $(document).height();          
    var bgWidth = (wW*.9)-80;
    var bgHeight = ((8 / 11)*bgWidth);
    if(bgHeight > (wH-240)){
        bgWidth = (((wH-240)/8)*11)-80;
        bgHeight = (8/11)*bgWidth;
    }
    this.pageWidth = bgWidth;
    this.pageHeight = bgHeight;
    this.animating = false;
    this.rightIndex = 0;
    this.onTurnPageComplete = this.options.onTurnPageComplete;

    var s = this.wrapper.style;
    s.width = String(this.pageWidth) + 'px';
    s.height = String(this.pageHeight) + 'px';

    this.flipType = this.options.flipType;
    this.shadow1opacity = this.options.shadow1opacity;
    this.shadow2opacity = this.options.shadow2opacity;

    //add bitmap pages
    var point1, point2;
    //book shadow
    //left
    this.shadowL = document.createElement('div');
    jQuery(this.shadowL).addClass('flipbook-shadowLeft')
        .css("width",String(this.pageWidth) + 'px')
        .css("height", String(this.pageHeight) + 'px');
//    this.shadowL.style = this.wrapper.style;
//    this.shadowL.style.width = String(this.pageWidth) + 'px';
//    this.shadowL.style.height = String(this.pageHeight) + 'px';
    this.wrapper.appendChild(this.shadowL);
    this.shadowLVisible =true;
    //right
    this.shadowR = document.createElement('div');
    jQuery(this.shadowR).addClass('flipbook-shadowRight')
        .css("width",String(this.pageWidth) + 'px')
        .css("height", String(this.pageHeight) + 'px');
//    this.shadowR.style = this.wrapper.style;
//    this.shadowR.style.width = String(this.pageWidth) + 'px';
//    this.shadowR.style.height = String(this.pageHeight) + 'px';
    this.wrapper.appendChild(this.shadowR);
    this.shadowRVisible =true;


    this.shadowRight();
    var maxPages = self.options.pages.length;
    for ( i = 0; i < maxPages; i++) {
        this.addPage(i,maxPages);
    }
    this.addLike(maxPages);
    this.slides[0].loadPage();
    this.slides[1].loadPage();
    if(this.slides.length > 2)
    this.slides[2].loadPage();

    this.updateVisiblePages();

    //disable page scrolling
    jQuery(this.wrapper).on('DOMMouseScroll',function(e){e.preventDefault();});
    jQuery(this.wrapper).on('mousewheel',function(e){e.preventDefault();});
};

FLIPBOOK.Slideshow.prototype.constructor = FLIPBOOK.Book;

FLIPBOOK.Slideshow.prototype = {
    /**
     * add new page to book
     * @param i
     */
    addPage:function(i,maxPages){
        //console.log("pl: "+this.pages.length);
        if(typeof this.options.pages[i] !== "undefined")var ifSrc = this.options.pages[i].src;                       
        if(typeof ifSrc !== 'undefined' && ifSrc != "/images/preloader.jpg"){
            //console.log(ifSrc);
            var slide = new FLIPBOOK.Slide(this.options.pages[i], this.pageWidth, this.pageHeight,this.slides.length,this,maxPages);
            this.wrapper.appendChild(slide.wrapper);
            this.slides.push(slide);
        }
    },
    addLike:function(maxPages){
        var like = new FLIPBOOK.SLike(this.pageWidth, this.pageHeight,this,maxPages);
        this.wrapper.appendChild(like.wrapper);
    },
    // i - page number, 0-based 0,1,2,... pages.length-1
    goToPage:function (i) {
        console.log(this.animating);
        if (i < 0 || i > this.slides.length)
            return;
        if (this.animating)
            return;
        if(isNaN(i))
            return;
        this.goingToPage = i;
        //convert target page to right index 0,2,4, ... pages.length
        //i = (i % 2 == 1) ? i + 1 : i;

        var pl, pr, plNew, prNew;
        //if going left or right
        if (i < this.rightIndex)
        //flip left
        {
            pl = this.slides[this.rightIndex - 1];
            pr = this.slides[i];
            if (i > 0) {
                plNew = this.slides[i - 1];
                if(this.flipType == '2d')
                plNew.expand();
                plNew.show();
            }
            //if(this.flipType == '2d')
            //pr.contract();
            this.animatePages(pl, pr);

        }
        //flip right
        else if (i > this.rightIndex) {
            pl = this.slides[i];
            pr = this.slides[this.rightIndex];
            if (i < this.slides.length) {
                prNew = this.slides[i];
                if(this.flipType == '2d')
                prNew.expand();
                prNew.show();
            }
            //if(this.flipType == '2d')
            //pl.contract();
            this.animatePages(pr, pl);
        }
        console.log("slide going to page: "+i);
        this.rightIndex = i;
            var paginationPage = i, slidesLength = this.slides.length;
            //if(!(paginationPage % 2)) paginationPage--;                        
            if(paginationPage < 0) paginationPage = 0;
            if(paginationPage == (slidesLength+1)) paginationPage = slidesLength;
            // for pagination update    
             
            var currentPage = $(".flipbook-currentPage span[title='"+paginationPage+"']");
            currentPage.addClass('page-current');
            currentPage.siblings().removeClass('page-current');
            var current_view = $("#container").attr("current_view");
            location.hash = current_view+"~"+paginationPage;
            if(this.options.isFramed){
                var pHash = new String(window.parent.location);
                pHash = pHash.split("#");
                var hBook = pHash[1].split("=")[1].split(":");
                window.parent.location = pHash[0]+"#hardcover="+hBook[0]+":"+hBook[1]+":"+current_view+"~"+paginationPage;
                //hardcover=stash:step-by-step_guide:Slideshow~2
                console.log(hBook);
            }
    },
    /**
     * page flip animation
     * @param first
     * @param second
     */
    animatePages:function (first, second) {
        var index = this.rightIndex;  
        $(".pn"+index+" #sharebuttons").show();
        $(".pn"+(index+1)+" #sharebuttons").show();      
        this.animating = true;
        var self = this,
            time1 = self.options.time1,
            time2 = self.options.time2,
            transition1 = self.options.transition1,
            transition2 = self.options.transition2
            ;

        first.show();
        //jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');
        
        //BOTH COMPLETE
        setTimeout(function () {
            console.log("timeout! both");
            if (self.onTurnPageComplete)
                self.onTurnPageComplete.call(self);
            self.main.updateCurrentPage();
            self.animating = false;
            self.updateVisiblePages();
            first.overlay.style.opacity = '0';
            jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');
            jQuery(second.wrapper).css(self.transform,'rotateY(0deg)');
        }, Number(time1)+Number(time2));        
    },
    /**
     * update page visibility depending on current page index
     */
    updateVisiblePages:function () {
        if (this.animating)
            return;
        for (var i = 0; i < this.slides.length; i++) {
            if ((i < (this.rightIndex - 1)) || (i > (this.rightIndex))) {
                if(this.flipType == '2d')
                    this.slides[i].contract();
                this.slides[i].hide();
            }
            else {
                if(this.flipType == '2d')
                    this.slides[i].expand();
                this.slides[i].show();
            }
            if (this.rightIndex == 0) {
                if(this.flipType == '2d')
                    this.slides[1].contract();
                 this.slides[1].hide();
            }
        }

        var index =this.rightIndex, slides = this.slides;
        if(index > 2)
            slides[index -3].loadPage();
        if(index > 3)
            slides[index -2].loadPage();
        if(index > 2)
            slides[index -1].loadPage();
        if(index < slides.length)
            slides[index].loadPage();
        if(index < slides.length-1)
            slides[index +1].loadPage();
        if(index < slides.length-2)
            slides[index +2].loadPage();     
        $(".pn"+index+" #sharebuttons").show(); 
        $(".pn"+(index+1)+" #sharebuttons").show();   
    },
    /**
     * go to next page
     */
    nextPage:function () {
        if (this.rightIndex >= this.slides.length-1)
            return;
        var nextPage = this.rightIndex + 1;
        //console.log(this.rightIndex+" ~ next page is: "+nextPage);
        this.goToPage(nextPage);
    },
    /**
     * go to previous page
     */
    prevPage:function () {
        if (this.rightIndex == 0)
            return;
        var nextPage = this.rightIndex - 1;
        if(nextPage < 0) nextPage = 0;        
        this.goToPage(nextPage);
    },
    shadowRight:function(){
        if(this.shadowLVisible){
            this.shadowLVisible = false;
            this.shadowL.style.display = 'none';
        }
        if(!this.shadowRVisible){
            this.shadowRVisible = true;
            this.shadowR.style.display = 'block';
        }
    }
};

FLIPBOOK.top10Book = function (el, options) {
    /**
     * local variables
     */
    var self = this, i,main = options.main ;
    this.main = options.main;
    this.hasTouch = main.hasTouch;
    this.perspective = main.perspective;
    this.transform = main.transform;
    this.transformOrigin = main.transformOrigin;
    this.transformStyle = main.transformStyle;
    this.transition = main.transition;
    this.transitionDuration = main.transitionDuration;
    this.transitionDelay = main.transitionDelay;
    this.transitionProperty = main.transitionProperty;
    this.backfaceVisibility = main.backfaceVisibility;

    this.wrapper = typeof el == 'object' ? el : document.getElementById(el);
    jQuery(this.wrapper).addClass('flipbook-book');

    // Default options
    this.options = {
        //A4
        onTurnPageComplete:null,
        //2d or 3d
        flipType:'2d',
        shadow1opacity:.7, // black overlay for 3d flip
        shadow2opacity:.7 // gradient overlay
    };

    // User defined options
    for (i in options) this.options[i] = options[i];
    this.pages = [];
    this.pageWidth = this.options.pageWidth;
    this.pageHeight = this.options.pageHeight;
    this.animating = false;
    this.rightIndex = 0;
    this.onTurnPageComplete = this.options.onTurnPageComplete;

    var s = this.wrapper.style;
    s.width = String(2 * this.pageWidth) + 'px';
    s.height = String(this.pageHeight) + 'px';

    this.flipType = this.options.flipType;
    this.shadow1opacity = this.options.shadow1opacity;
    this.shadow2opacity = this.options.shadow2opacity;

    //add bitmap pages
    var point1, point2;

    //book shadow
    //left
    this.shadowL = document.createElement('div');
    jQuery(this.shadowL).addClass('flipbook-shadowLeft')
        .css("width",String(this.pageWidth) + 'px')
        .css("height", String(this.pageHeight) + 'px');
//    this.shadowL.style = this.wrapper.style;
//    this.shadowL.style.width = String(this.pageWidth) + 'px';
//    this.shadowL.style.height = String(this.pageHeight) + 'px';
    this.wrapper.appendChild(this.shadowL);
    this.shadowLVisible =true;
    //right
    this.shadowR = document.createElement('div');
    jQuery(this.shadowR).addClass('flipbook-shadowRight')
        .css("width",String(this.pageWidth) + 'px')
        .css("height", String(this.pageHeight) + 'px');
//    this.shadowR.style = this.wrapper.style;
//    this.shadowR.style.width = String(this.pageWidth) + 'px';
//    this.shadowR.style.height = String(this.pageHeight) + 'px';
    this.wrapper.appendChild(this.shadowR);
    this.shadowRVisible =true;


    this.shadowRight();

    for ( i = 0; i < self.options.pages.length; i++) {
        this.addPage(i);
        jQuery(this.pages[i].wrapper)
            .attr('title', i + 1)
            .bind(self.main.CLICK_EV, function(e){
                var x, x2, y, y2, z, z2;
                x = self.main.scroll.x;
                x2 = self.xOnMouseDown;
                y = self.main.scroll.y;
                y2 = self.yOnMouseDown;
                z = self.zoomOnMouseUp;
                z2 = self.zoomOnMouseDown;

                function isClose(x1,x2){
                   return (Math.abs(x1-x2) < 10);
                }
                if(self.main.scroll.moved || self.main.scroll.animating || self.main.scroll.zoomed || (self.zoomOnMouseDown != self.main.scroll.scale))
                    return;
                if(e.target.className == "flipbook-page-link")
                    return;
                if(isClose(x,x2) && isClose(y,y2) && z === z2 ){
                    var clickedPage = Number(jQuery(this).attr('title'))-1;
                    if(clickedPage == self.rightIndex){
                        self.nextPage();
                    }
                    else{
                        self.prevPage();
                    }
                }
            })
            .bind(self.main.START_EV, function(e){
                self.zoomOnMouseDown = self.main.scroll.scale;
                self.xOnMouseDown = self.main.scroll.x;
                self.yOnMouseDown = self.main.scroll.y;
            })
            .bind(self.main.END_EV, function(e){
                self.zoomOnMouseUp = self.main.scroll.scale;
                self.xOnMouseUp = self.main.scroll.x;
                self.yOnMouseUp = self.main.scroll.y;
            })
        ;
    }
    this.pages[0].loadPage();
    this.pages[1].loadPage();
    if(this.pages.length > 2)
    this.pages[2].loadPage();

    this.updateVisiblePages();

    //disable page scrolling
    jQuery(this.wrapper).on('DOMMouseScroll',function(e){e.preventDefault();});
    jQuery(this.wrapper).on('mousewheel',function(e){e.preventDefault();});
};

FLIPBOOK.top10Book.prototype.constructor = FLIPBOOK.top10Book;

FLIPBOOK.top10Book.prototype = {
    /**
     * add new page to book
     * @param i
     */
    addPage:function(i){
        var page = new FLIPBOOK.Page(this.options.pages[i], this.pageWidth, this.pageHeight,this.pages.length,this);
//        var page = new FLIPBOOK.Page(this.options.pages[i].src, this.options.pages[i].htmlContent, this.pageWidth, this.pageHeight, this.pages.length,this);
        this.wrapper.appendChild(page.wrapper);
        this.pages.push(page);
    },

    // i - page number, 0-based 0,1,2,... pages.length-1
    goToPage:function (i) {
        if (i < 0 || i > this.pages.length)
            return;
        if (this.animating)
            return;
        if(isNaN(i))
            return;
        this.goingToPage = i;
        //convert target page to right index 0,2,4, ... pages.length
        i = (i % 2 == 1) ? i + 1 : i;

        if(i == 0 ){
            this.rightIndex == this.pages.length ? this.shadowNone() : this.shadowRight();
        }else if(i == this.pages.length){
            this.rightIndex == 0 ? this.shadowNone() : this.shadowLeft();
        }

        var pl, pr, plNew, prNew;
        //if going left or right
        if (i < this.rightIndex)
        //flip left
        {
            pl = this.pages[this.rightIndex - 1];
            pr = this.pages[i];
            if (i > 0) {
                plNew = this.pages[i - 1];
                if(this.flipType == '2d')
                plNew.expand();
                plNew.show();
            }
            if(this.flipType == '2d')
            pr.contract();
            this.animatePages(pl, pr);

        }
        //flip right
        else if (i > this.rightIndex) {
            pl = this.pages[i - 1];
            pr = this.pages[this.rightIndex];
            if (i < this.pages.length) {
                prNew = this.pages[i];
                if(this.flipType == '2d')
                prNew.expand();
                prNew.show();
            }
            if(this.flipType == '2d')
            pl.contract();
            this.animatePages(pr, pl);
        }

        this.rightIndex = i;

//        if(this.main.p && this.pages[0].imageSrc != "images/Art-1.jpg")
//            this.rightIndex = 0;
    },
    /**
     * page flip animation
     * @param first
     * @param second
     */
    animatePages:function (first, second) {
        this.animating = true;
        var self = this,
            time1 = self.options.time1,
            time2 = self.options.time2,
            transition1 = self.options.transition1,
            transition2 = self.options.transition2
            ;

        first.show();
        jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');
        //FIRST START
        if(this.flipType == '3d') {

            second.show();
            jQuery(second.wrapper).css('visibility', 'hidden');

            jQuery(first.wrapper).css('visibility', 'visible');
            jQuery(first.wrapper).css("text-indent", '0px');
            jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');

            var angle = (first.index < second.index)  ? "-90" : "90";

            jQuery(first.overlay).animate({opacity:self.shadow1opacity},{duration:time1,easing:transition1});

            jQuery(first.wrapper).animate(
                {
                    textIndent: angle
                },
                {
                    step: function(now,fx) {
                            jQuery(this).css(self.transform,'rotateY('+Math.round(now)+'deg)');
//                            console.log(now);
                        },
                    duration:time1,
                    easing:transition1,
                    complete:function(){
                        //----------------
                        // FIRST COMPLETE
                        //----------------
//                        console.log("complete");
//                        console.log("angle : "+angle);
                        first.hide();
                        first.hideVisibility();
                        jQuery(second.wrapper).css('visibility', 'visible');
                        //shadow
                        jQuery(second.overlay).css('opacity',self.shadow1opacity);
                        jQuery(second.overlay).animate({opacity:0},{duration:time2,easing:transition2});
                        //first complete, animate second
                        jQuery(second.wrapper).css(self.transform,'rotateY('+angle+'deg)');

                        //second initial ange
                        jQuery(second.wrapper).css("text-indent", String(-angle)+'px');
                        jQuery(second.wrapper).animate(
                            {
                                textIndent: 0
                            },
                            {
                                step: function(now,fx) {
                                        jQuery(this).css(self.transform,'rotateY('+Math.round(now)+'deg)');
//                                        console.log(now);
                                    },
                                complete:function(){
                                    jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');
                                    jQuery(first.wrapper).css('visibility','visible');
                                    jQuery(second.wrapper).css(self.transform,'rotateY(0deg)');
                                    jQuery(second.wrapper).css('visibility','visible');
                                },
                                duration:time2,
                                easing:transition2
                            }
                        );
                    }
                }
            );
        }
        else {
            jQuery(first.wrapper).animate({width:0}, time1, transition1,
                //on complete
                function() {
                    second.show();
                    jQuery(second.wrapper).animate({width:second.width}, time2, transition2);
                });

        }

        //BOTH COMPLETE
        setTimeout(function () {
            console.log("timeout! both");
            if (self.onTurnPageComplete)
                self.onTurnPageComplete.call(self);
            self.main.updateCurrentPage();
            self.animating = false;
            self.updateVisiblePages();
            first.overlay.style.opacity = '0';
            jQuery(first.wrapper).css(self.transform,'rotateY(0deg)');
            jQuery(second.wrapper).css(self.transform,'rotateY(0deg)');
        }, Number(time1)+Number(time2));
    },
    /**
     * update page visibility depending on current page index
     */
    updateVisiblePages:function () {
        if (this.animating)
            return;
        for (var i = 0; i < this.pages.length; i++) {
            if ((i < (this.rightIndex - 1)) || (i > (this.rightIndex))) {
                if(this.flipType == '2d')
                    this.pages[i].contract();
                this.pages[i].hide();
            }
            else {
                if(this.flipType == '2d')
                    this.pages[i].expand();
                this.pages[i].show();
            }
            if (this.rightIndex == 0) {
                if(this.flipType == '2d')
                    this.pages[1].contract();
                 this.pages[1].hide();
            }
        }

        var index =this.rightIndex, pages = this.pages;
        if(index > 2)
            pages[index -3].loadPage();
        if(index > 0)
            pages[index -2].loadPage();
        if(index > 0)
            pages[index -1].loadPage();
        if(index < pages.length)
            pages[index].loadPage();
        if(index < pages.length)
            pages[index +1].loadPage();
        if(index < pages.length-2)
            pages[index +2].loadPage();

        if(index > 0 && index < this.pages.length){
            this.shadowBoth();
        }else if(index == 0){
            this.shadowRight();
        }else{
            this.shadowLeft();
        }
    },
    /**
     * go to next page
     */
    nextPage:function () {
        if (this.rightIndex == this.pages.length || this.animating)
            return;
        this.goToPage(this.rightIndex + 2);
    },
    /**
     * go to previous page
     */
    prevPage:function () {
        if (this.rightIndex == 0 || this.animating)
            return;
        this.goToPage(this.rightIndex - 2);
    },

    shadowRight:function(){
        if(this.shadowLVisible){
            this.shadowLVisible = false;
            this.shadowL.style.display = 'none';
        }
        if(!this.shadowRVisible){
            this.shadowRVisible = true;
            this.shadowR.style.display = 'block';
        }
    },
    shadowLeft:function(){
        if(this.shadowRVisible){
            this.shadowRVisible = false;
            this.shadowR.style.display = 'none';
        }
        if(!this.shadowLVisible){
            this.shadowLVisible = true;
            this.shadowL.style.display = 'block';
        }
    },
    shadowBoth:function(){
        if(!this.shadowRVisible){
            this.shadowRVisible = true;
            this.shadowR.style.display = 'block';
        }
        if(!this.shadowLVisible){
            this.shadowLVisible = true;
            this.shadowL.style.display = 'block';
        }
    },
    shadowNone:function(){
        if(this.shadowRVisible){
            this.shadowRVisible = false;
            this.shadowR.style.display = 'none';
        }
        if(this.shadowLVisible){
            this.shadowLVisible = false;
            this.shadowL.style.display = 'none';
        }
    }

};

FLIPBOOK.TimelineV2 = function (el, options) {
    /**
     * local variables
     */
    var self = this, i,main = options.main ;
    this.main = options.main;
    this.hasTouch = main.hasTouch;
    this.perspective = main.perspective;
    this.transform = main.transform;
    this.transformOrigin = main.transformOrigin;
    this.transformStyle = main.transformStyle;
    this.transition = main.transition;
    this.transitionDuration = main.transitionDuration;
    this.transitionDelay = main.transitionDelay;
    this.transitionProperty = main.transitionProperty;
    this.backfaceVisibility = main.backfaceVisibility;

    this.options = {
        onTurnPageComplete:null,
        shadow1opacity:.7, // black overlay for 3d flip
        shadow2opacity:.7 // gradient overlay
    };
    // User defined options
    for (i in options){
        //console.log(options[i]);
        this.options[i] = options[i];
    }
    this.timesCount = 0;
    this.pageWidth = this.options.pageWidth;
    this.pageHeight = this.options.pageHeight;
    this.rightIndex = 0;
    var maxPages = self.options.pages.length;
    var coverImg = "<img src='"+this.options.pages[0].src+"' />";
    var dataJSON = '{"timeline":{"headline":"","type":"default","text":"'+this.options.bookDesc+'","startDate":"'+this.options.bookDate+'","asset":{"media":"'+this.options.pages[0].src+'","thumbnail":"'+this.options.pages[0].thumb+'","credit":"","caption":""},"date": [';
    //console.log(dataJSON);
    for ( i = 1; i < maxPages; i++) {
      if(this.options.pages[i].src){
        var cDate = $(".created_dates .created_date"+i).html();
        dataJSON += '{"startDate":"'+cDate+'","endDate":"'+cDate+'","headline":"Page '+i+'","text":"","asset":{"media":"'+this.options.pages[i].src+'","thumbnail":"'+this.options.pages[i].thumb+'","credit":"","caption":""}}';
        if(this.options.pages[i+1])
            dataJSON += ",";
        this.timesCount++;
      }
    }
    dataJSON += ']}}';
    //console.log(dataJSON);
    if(this.options.isMobile == true){
        var wW=$(window).width();var wH=$(window).height();
        wH = wH+16;
    } else {
        var wW=$(document).width();var wH=$(document).height();
        wH = wH-30;
    }

    if(this.options.isMobile == false)

    console.log("doc height: "+wH);
	createStoryJS({
	    type:		'timeline',
		width:		wW,
		height:	    wH,
		source:		eval("(" + dataJSON + ")"),
		embed_id:	'hardcover-timeline',
		debug:		true
	});
    if(this.options.isMobile){
        $(".flipbook-menuWrapper").hide();
        $(".vco-navigation").waitUntilExists(function(){
            //$(this).hide();
//            $(".vco-container").css("height","100%");
//            $(".vco-feature").css("height","100%");
//            $(".vco-slider").css("height","100%");
//            $("img.media-image").waitUntilExists(function(){
//                var wH = $(document).height()+200;
//                $(this).css("max-height",wH-45);
//                $(".slider-item").css("height",wH);
//            });
        });
    }
};

FLIPBOOK.TimelineV2.prototype.constructor = FLIPBOOK.TimelineV2;

FLIPBOOK.TimelineV2.prototype = {
    goToPage:function (i) {
        if (i < 0 || i > this.timesCount)
            return;
        if(isNaN(i))
            return;
        this.goingToPage = i;
        console.log("timeline going to page: "+i);
        this.rightIndex = i;
            var paginationPage = i, timesLength = this.timesCount;
            //if(!(paginationPage % 2)) paginationPage--;
            if(paginationPage < 0) paginationPage = 0;
            if(paginationPage == (timesLength+1)) paginationPage = timesLength+1;
            // for pagination update

            var currentPage = $(".flipbook-currentPage span[title='"+paginationPage+"']");
            currentPage.addClass('page-current');
            currentPage.siblings().removeClass('page-current');
            var current_view = $("#container").attr("current_view");
            location.hash = current_view+"~"+paginationPage;
            if(this.options.isFramed){
                var pHash = new String(window.parent.location);
                pHash = pHash.split("#");
                var hBook = pHash[1].split("=")[1].split(":");
                window.parent.location = pHash[0]+"#hardcover="+hBook[0]+":"+hBook[1]+":"+current_view+"~"+paginationPage;
                //hardcover=stash:step-by-step_guide:Slideshow~2
                console.log(hBook);
            }
    },
    nextPage:function () {
        var self = this;
        if (this.rightIndex == this.timesCount)
            return;
        var nextPage = this.rightIndex + 1;
        this.goToPage(nextPage);
    },
    prevPage:function () {
        var self = this;
        console.log("prev: "+this.rightIndex);
        if (this.rightIndex == 0)
            return;
        var nextPage = this.rightIndex - 1;
        if(nextPage < 0) nextPage = 0;
        this.goToPage(nextPage);
    }
};

FLIPBOOK.SlideshowV2 = function (el, options) {
    /**
     * local variables
     */
    var self = this, i,main = options.main ;
    this.main = options.main;
    this.hasTouch = main.hasTouch;
    this.perspective = main.perspective;
    this.transform = main.transform;
    this.transformOrigin = main.transformOrigin;
    this.transformStyle = main.transformStyle;
    this.transition = main.transition;
    this.transitionDuration = main.transitionDuration;
    this.transitionDelay = main.transitionDelay;
    this.transitionProperty = main.transitionProperty;
    this.backfaceVisibility = main.backfaceVisibility;

    this.options = {
        onTurnPageComplete:null,
        shadow1opacity:.7, // black overlay for 3d flip
        shadow2opacity:.7 // gradient overlay
    };
    // User defined options
    for (i in options){
        //console.log(options[i]);
        this.options[i] = options[i];
    }
    this.timesCount = 0;
    this.pageWidth = this.options.pageWidth;
    this.pageHeight = this.options.pageHeight;
    this.rightIndex = 0;
    var l = window.location;
    var maxPages = self.options.pages.length;
    //var data = '<div id="galleria">';
//    data += '<a href="'+this.options.pages[0].src+'"><img src="'+this.options.pages[0].thumb+'", data-big="'+this.options.pages[0].src+'" data-title="'+this.options.pages[0].title+'" data-description="'+this.options.pages[0].title+'" /></a>';
//    //console.log(data);
//    for ( i = 1; i < maxPages; i++) {
//      if(typeof this.options.pages[i].src !== "undefined" && this.options.pages[i].src != ""){
//        var cDate = $(".created_dates .created_date"+i).html();
//        data += '<a href="'+this.options.pages[i].src+'"><img src="'+this.options.pages[i].thumb+'", data-big="'+this.options.pages[i].src+'" data-title="'+this.options.pages[i].title+'" data-description="'+this.options.pages[i].title+'" /></a>';
//        this.timesCount++;
//      } else if(this.options.pages[i].src == "") {
//        var cDate = $(".created_dates .created_date"+i).html();
//        data += '<a href="/images/preloader.jpg"><img src="'+this.options.pages[i].thumb+'", data-big="/images/preloader.jpg" data-title="'+this.options.pages[i].title+'" data-description="'+this.options.pages[i].title+'" /></a>';
//      }
//    }
//    data += '</div>';
    this.thumbArray = [];
    var dataJSON = [
        {
            thumb: this.options.pages[0].thumb,
            image: this.options.pages[0].norm,
            big: this.options.pages[0].src,
            title: this.options.pages[0].title,
            description: this.options.pages[0].title
        }
    ];
    this.thumbArray.push(0);
    var maxCount = 20;
    var curPage = parseInt( location.hash.substr(11), 10 );
    self.rightIndex = curPage;
    var range = self.getRange(curPage,maxPages);
    this.currentRange = range;
    //console.log(range);
    this.toBeLoaded = [];
    var arrCount = 0;
    this.toBeLoaded.push(new Array());
    for ( i = 1; i <= maxPages; i++) {
        var range = self.getRange(i,maxPages);
        var setNum = range/10;
     if(this.options.pages[i]){
      if(this.options.pages[i].title == "" && this.options.pages[i].description == ""){
            var thisTitle = "";
            var thisDesc = "No description.";
      }else{
            var thisTitle = this.options.pages[i].title;
            var thisDesc = this.options.pages[i].description;
      }
      if(typeof this.options.pages[i].src !== "undefined" && this.options.pages[i].src != ""){
        dataJSON.push({
            thumb: this.options.pages[i].thumb,
            image: this.options.pages[i].norm,
            big: this.options.pages[i].src,
            title: thisTitle,
            description: thisDesc
        });
        this.toBeLoaded[setNum].push({
            thumb: this.options.pages[i].originalThumb,
            image: this.options.pages[i].norm,
            big: this.options.pages[i].originalSrc,
            title: thisTitle,
            description: thisDesc
        });
        this.timesCount++;
        arrCount++;
        this.thumbArray.push(i);
      } else if(typeof this.options.pages[i].src !== "undefined" && this.options.pages[i].originalSrc != ""){
        //console.log(setNum+"~"+arrCount);
        dataJSON.push({
            thumb: this.options.pages[i].originalThumb,
            image: this.options.pages[i].norm,
            big: this.options.pages[i].originalSrc,
            title: thisTitle,
            description: thisDesc
        });
        this.toBeLoaded[setNum].push({
            thumb: this.options.pages[i].originalThumb,
            image: this.options.pages[i].norm,
            big: this.options.pages[i].originalSrc,
            title: thisTitle,
            description: thisDesc
        });
        arrCount++;
      }
     }
     if(arrCount == 10){
        this.toBeLoaded.push(new Array());
        arrCount = 0;
     }
    }
    var startSlide = curPage;
    if(startSlide == 0) startSlide = 1;
    //console.log(this.thumbArray);
    //console.log(toBeLoaded);
    $("#hardcover-slideshow").append('<div id="galleria"></div>');
    var thisHeight = $(document).height();
    Galleria.loadTheme('/js/themes/classic/galleria.classic.min.js');
    // Initialize Galleria
    Galleria.run('#galleria', {
        dataSource: dataJSON,
        dataSourceOrig: this.toBeLoaded,
        height: thisHeight-45,
        maxScaleRatio: 1,
        preload: 2,
        show: curPage,
        queue: false,
        responsive : true,
        thumbQuality: false,
        wait: true,
        imageCrop: false,
        thumbCrop: false,
        //dummy: '/images/preloader.jpg',
        thumbnails: "lazy",
        clickNext: true
    });
    this.thumbAlreadyLoaded = new Array();
    Galleria.ready(function(opt) {
        var thisHash = location.hash;
        var curView = "Slideshow";
        var vCount = (curView.length * 1)+2;
        var curPage = parseInt( thisHash.substr(vCount), 10 );
        if(curPage == "" || isNaN(curPage))
            curPage = 0;
        self.rightIndex = curPage;
        gallery = this;
        //console.log(curPage);
        //console.log(self.options.pages);
        //console.log(self.options.pages[curPage]);
        self.imageLoader = new Image();
        self.imageLoader.src = self.options.pages[curPage].norm;
        $(self.imageLoader).load(function () {
            //alert('main image loading complete.');
            gallery.show((curPage*1)+1);
            gallery.show((curPage*1));
        });
        this.lazyLoad( self.thumbArray, function() {
            console.log(self.thumbArray);
        });
        self.thumbAlreadyLoaded = self.thumbAlreadyLoaded.concat(self.thumbArray);
        if(curPage == "" || isNaN(curPage))
            curPage = 0;
        //console.log(curPage);
        //console.log(self.options.pages);
        $(".galleria-thumbnails").html(self.options.pages[curPage].htmlContent);
        self.goPaginationSlide(); 
    });
    var dontDoHash = 0;
    $(window).hashchange(function(){
        var thisHash = location.hash;
        var curView = "Slideshow";
        var vCount = (curView.length * 1)+2;
        var curPage = parseInt( thisHash.substr(vCount), 10 );
        if(curPage == "" || isNaN(curPage))
            curPage = 0;
        self.rightIndex = curPage;
        var maxCount = 20;
        var maxPages = self.options.pages.length;
        var curCount = self.timesCount;
        var range = self.getRange(curPage,maxPages);
        var setNum = range/10;
        thisHash = thisHash.replace("#","").split("~")[0];
        curViewed = thisHash;
        //console.log(curViewed);
        if (!dontDoHash && curViewed == "Slideshow"){
            dontDoHash = 1;
            //console.log(self.currentRange+"~"+range);
            //if(self.currentRange != range)
                self.goPaginationSlide();
            setTimeout(function () {
                dontDoHash = 0;
            }, 1000);
        }
    });
            var fb_GO = 1;
            var tw_GO = 1;
            var pn_GO = 1;
            var em_GO = 1;
            var img_GO = 1;
            var desc = $('meta[name="description"]').attr('content');
            jQuery('.galleria-thumbnails .fb-shareFront').unbind("click").live("click",function(event){
                var page_number = ($(this).parent().parent().attr('page_number'));
                event.preventDefault();
                event.stopPropagation();
                var page_image = getCurrentImg(page_number);
                //console.log(page_image);
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
            jQuery('.galleria-thumbnails .fb-share').unbind("click").live("click",function(event){
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
            jQuery('.galleria-thumbnails .twitter-share').unbind("click").live("click",function(event){
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
            jQuery('.galleria-thumbnails .pinterest-share').unbind("click").live("click",function(event){
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
            jQuery('.galleria-thumbnails .email-share').unbind("click").live("click",function(event){
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
                var thisSrc = self.options.pages[page_number-1].originalSrc;
                return thisSrc;
            }

    jQuery('#sharebuttons .info-share').unbind("click").live("click",function(event){
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
            .bind("click", function(event){
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
            .bind("click", function(event){
                event.preventDefault();
                event.stopPropagation();
                $(".infoContainer").each(function(){
                    $(this).remove();
                });
            })
            .addClass('infoClose')
        ;
    });
            //alert("slideshow instance started");
};

FLIPBOOK.SlideshowV2.prototype.constructor = FLIPBOOK.SlideshowV2;

FLIPBOOK.SlideshowV2.prototype = {
    goPaginationSlide : function() {
            //console.log(pages);
            var self = this;
            var curPage = parseInt( location.hash.substr(11), 10 );
            if(curPage == "" || isNaN(curPage))
                curPage = 0;
            var pTotal = $(".flipbook-paginationCon").find(".MenuItem").length;
            $(".galleria-thumbnails").html(self.options.pages[curPage].htmlContent);
            //console.log(pTotal + " ~ " + curPage);
            var cMax = 0;
            var thisCounter = 1;
            this.thumbArray = [];
            for(var i=0;i<=pTotal;i++){
              var range = self.getRange(i,pTotal);
              var setNum = range/10;
              //console.log("#"+curView+"-MenuItem-"+i);
              if($("#Slideshow-MenuItem-"+i)){
                //console.log(curPage + " ~ " + cMax + " ~ " + i + " ~ " + pTotal);
                if(curPage == 0) curPage = 1;
                if (curPage > cMax && curPage <= cMax+10){
                    //console.log(i);
                    var arrChk = this.thumbAlreadyLoaded.indexOf(i);
                    if(arrChk == -1)
                        this.thumbArray.push(i);
                    //console.log(this.thumbArray);
                }
                if(i == (cMax+10))
                    cMax += 10;
              }
            }
            var gallery = Galleria.get(0);
            if(this.thumbArray)
                gallery.lazyLoad( this.thumbArray, function() {
                    console.log(self.thumbArray);
                });
            this.thumbAlreadyLoaded = this.thumbAlreadyLoaded.concat(this.thumbArray);
            var cMax = 0;
            for(var i=0;i<=pTotal;i++){
              var range = self.getRange(i,pTotal);
              var setNum = range/10;
              //console.log("#"+curView+"-MenuItem-"+i);
              var thisThumbConImg = $("#Slideshow-MenuItem-"+i+" img");
                //console.log(thisThumbConImg);
                if(thisThumbConImg.length > 1){
                    var thisCount = thisThumbConImg.length;
                    var curCount = 1;
                    thisThumbConImg.each(function(){
                        //console.log($(this).parent().attr("id"));
                        if(curCount != thisCount)
                            $(this).remove();
                        curCount++;
                    });
                }
              if($("#Slideshow-MenuItem-"+i)){
                //console.log(curPage + " ~ " + cMax + " ~ " + i + " ~ " + pTotal);
                if(curPage == 0) curPage = 1;
                if (curPage > cMax && curPage <= cMax+10){
                    this.currentRange = range;
                    $("#Slideshow-MenuItem-"+i).css("display","table-cell");
                    $("#Slideshow-MenuItem-"+i).css("visibility","visible");
                    //console.log(setNum);
                    var thisSrc = $("#Slideshow-MenuItem-"+i+" img").attr("src");
                    if(thisSrc && thisSrc.indexOf("/images/load.gif") != -1){
//                        if(self.toBeLoaded[setNum].length != 0){
//                            console.log(self.toBeLoaded);
//                            //$(".MenuItem").each(function(){
//                                $(this).remove();
//                            });
//                            gallery.load(self.toBeLoaded[setNum]);
//                        }
                        var newThumb = self.options.pages[i].originalThumb;
                        $("#Slideshow-MenuItem-"+i+" img").attr("src",newThumb);
                    }
                }else
                    $("#Slideshow-MenuItem-"+i).hide();
                if(i == (cMax+10))
                    cMax += 10;
              }
            }
            var newSrc = self.options.pages[curPage].originalSrc;
            var bigSrc = $(".galleria-images .galleria-image:nth-child(1) img").attr("src");
            //console.log(bigSrc);
            if(bigSrc && bigSrc.indexOf("/images/loader_slide.gif") != -1){
                $(".galleria-images .galleria-image:nth-child(1) img").attr("src",newSrc);
            }
            var bigSrc = $(".galleria-images .galleria-image:nth-child(2) img").attr("src");
            //console.log(bigSrc);
            if(bigSrc && bigSrc.indexOf("/images/loader_slide.gif") != -1){
                $(".galleria-images .galleria-image:nth-child(2) img").attr("src",newSrc);
            }
            //console.log(self.options.pages[curPage].htmlContent);
    },
    toggleThumbs : function() {
        if (!this.thumbsCreated)
            this.createThumbs();
        this.thumbHolder.css('display','block');
        this.thumbHolder.toggleClass('invisible');
    },
    createThumbs : function() {
        if (this.thumbsCreated)
            return;
        this.thumbsCreated = true;
        var self = this;
        self.thumbHolder = $(document.createElement('div'))
            .addClass('gallery-thumbHolder')
            .addClass('invisible')
            .appendTo($("#galleria"))
            .css('position', 'absolute')
            .css('display', 'none')
        ;
        var maxPages = self.options.pages.length;
        var imgCount = 10;
        var winSize = self.thumbHolder.width()-220;
        var tW = winSize / ((imgCount*1));
        var tH = (tW / 11) * 8;
        for ( i = 0; i <= maxPages; i++) {
            if(this.options.pages[i]){
                var thumb = this.options.pages[i].originalThumb;
                if(thumb){
                    var ImgCon = $(document.createElement('div'))
                        .addClass('gallery-thumbHolder-container'+i)
                        .addClass('galleryThumbs')
                        .appendTo(self.thumbHolder)
                    ;
                    var thumbImg = $(document.createElement('img'))
                        .addClass('gallery-thumbHolder-image'+i)
                        .appendTo(ImgCon)
                        .attr("src",thumb)
                        .attr("title",i)
                        .bind("click", function(){
                            var thisIndex = $(this).attr("title");
                            gallery.show(thisIndex);
                        })
                        .css({"width":tW+"px","height":tH+"px"})
                    ;
                }
            }
        }
    },
    getRange : function(current,max) {
        var range = 0;
        for(var i=0;i<=max;i+=10){
            if(current > i && current <= i+10)
                range = i;
        }
        return range;
    }
};
FLIPBOOK.Page = function (options, width, height, index, book, maxPages) {


    this.wrapper = document.createElement('div');
    jQuery(this.wrapper).addClass('flipbook-page');
    this.s = this.wrapper.style;
    this.s.width = String(width) + 'px';
    this.s.height = String(height) + 'px';
    this.index = index;
    this.book = book;
    this.width = width;
    this.height = height;

    this.invisible = false;

    this.image = new Image();
    /**
     * lightweight preloader for the page - shows until the page is loaded
     */
    this.image.src = book.options.assets.preloader;
    this.imageSrc = options.src;
    this.wrapper.appendChild(this.image);

    this.imageLoader = new Image();

    //shadow only on left page
//    if (this.index % 2 != 0) {
//        this.shadow = new Image();
//        this.wrapper.appendChild(this.shadow);
//    }

    //black overlay that will be used for shadow in 3d flip
    this.overlay = new Image();
    this.overlay.src = book.options.assets.overlay;
    this.wrapper.appendChild(this.overlay);
    this.overlay.style.opacity = '0';

    this.expanded = true;



//    this.clickArea = document.createElement('div');
//    this.clickArea.classList.add('flipbook-page-clickArea');

    this.htmlContent = options.htmlContent;



    //left pages (indexes 1,3,5,...)
    if (this.index % 2 == 0) {
        this.s.zIndex = String(100 - this.index);
        this.s.left = '50%';
        this.right(this.image);
        this.right(this.overlay);
    }
    //right pages (indexes 0,2,4,...)
    else {

//        shadow on left page
        //console.log("page index:"+this.index+" max: "+maxPages);
        if(maxPages != (this.index+1)){
            this.shadow = new Image();
            this.wrapper.appendChild(this.shadow);
            this.shadow.src = book.options.assets.left;
            this.left(this.shadow);    
        }

        this.s.zIndex = String(100 + this.index);
        this.s.right = '50%';
        this.left(this.image);
        this.left(this.overlay);
    }

    if(typeof  this.htmlContent !== 'undefined'){
        this.htmlContainer = document.createElement('div');
        jQuery(this.htmlContainer).addClass('flipbook-page-htmlContainer');
        this.wrapper.appendChild(this.htmlContainer);
        this.index % 2 == 0 ? this.right(this.htmlContainer) : this.left(this.htmlContainer);
    }

//    this.wrapper.appendChild(this.clickArea);

    this.image.style[this.book.transform] = 'translateZ(0)';

    this.overlay.style[this.book.transform] = 'translateZ(0)';
    this.overlay.style['pointer-events'] = 'none';

    if(this.shadow){
        this.shadow.style[this.book.transform] = 'translateZ(0)';
        this.shadow.style['pointer-events'] = 'none';
    }

    //this.s.bottom = 'auto';
    //this.s.top = 'auto';

    if (this.book.flipType == '3d') {
        this.wrapper.style[this.book.transformOrigin] = (this.index % 2 != 0) ? '100% 50%' : '0% 50%';
    }

    //links

    if(options.links)
    {
        var self = this;
        for(var i= 0; i<options.links.length;i++){

            var link = options.links[i];



            function createLink(link){
                var l = document.createElement('div');
                self.wrapper.appendChild(l);
                l.classList.add("flipbook-page-link");
                l.style.position = 'absolute';
                l.style.left = String(link.x)+'px';
                l.style.top = String(link.y)+'px';
                l.style.width = String(link.width)+'px';
                l.style.height = String(link.height)+'px';
                l.style.backgroundColor = link.color;
                l.style.opacity = link.alpha;
                l.style.cursor = 'pointer';
                jQuery(l)
                    .click(function(e){
                        if(Number(link.page)>0 ){
                            book.goToPage(Number(link.page))
                        }else if(String(link.url) != ''){
                            window.open(link.url);
                        }
                    })
                    .mouseenter(function(){
                        l.style.backgroundColor = link.hoverColor;
                        l.style.opacity = link.hoverAlpha;
                    })
                    .mouseleave(function(){
                        l.style.backgroundColor = link.color;
                        l.style.opacity = link.alpha;
                    })

                ;
            }
            createLink(link);

        }
    }
/*--------------------*/
/*josh mods for share button*/
/*--------------------*/
    var page_number = (this.index * 1) + 1;    
    jQuery(this.wrapper).addClass("pn"+page_number);
//end josh 
};

/**
 * prototype
 * @type {Object}
 */
FLIPBOOK.Page.prototype = {
    loadPage:function () {
        if(this.loaded == true)
            return;
        this.loaded = true;
        var self = this;
        self.imageLoader.src =     this.imageSrc;
        jQuery(self.imageLoader).load(function () {
            self.image.src = self.imageSrc;
            var nWidth = self.imageLoader.naturalWidth;
            var nHeight = self.imageLoader.naturalHeight;
            //console.log(self.imageLoader);
//            console.log(nWidth);
            var pH = $(self.image).parent().height();
            var pW = $(self.image).parent().width();
            //console.log(pW+" ~ "+pH+" ~ "+nWidth+" ~ "+nHeight);
            $(self.image).css({"max-width":String(pW) + 'px',"max-height":String(pH) + 'px',"display":'table-cell','vertical-align':'middle',"margin":"auto","position":"absolute","top":"0","bottom":"0","left":"0","right":"0" });
            if(nWidth > nHeight){
                $(self.image).css({"height":"auto"});
                var oH = $(self.image).height();
                var nH = (pH - oH) / 2;
                if(nH < pH){
                    //console.log(nH+" ~ "+pH+" ~ "+oH);
                    //$(self.image).css({"margin-top":nH+"px"});
                }//else
                    //$(self.image).css("height",pH+"px");
            }else if(nHeight > nWidth){
                $(self.image).css("width","auto");
                var oW = $(self.image).width();
                var nW = (pW - oW) / 2;
                if(nW < pW){
                    console.log(nW+" ~ "+pW+" ~ "+oW);
                    //$(self.image).css({"margin-left":nW+"px"});
                }//else
                    //$(self.image).css("width",pW+"px");
            }
        });
        if(typeof  this.htmlContent !== 'undefined'){
            this.htmlContainer.innerHTML = this.htmlContent;
        }
    },

    flipView:function () {

    },
    /**
     * expand page to full width
     */
    expand:function () {
        if(!this.expanded)
            this.s.width = String(this.width) + 'px';
        this.expanded = true;
    },
    /**
     * contract page to width 0
     */
    contract:function () {
        if(this.expanded)
            this.s.width = '0px';
        this.expanded = false;
    },
    show:function () {
        if(this.hidden){
//            this.invisible = false;
//            this.s.visibility = 'visible';
            this.s.display = 'block';
        }
        this.hidden = false;
    },
    hide:function () {
        if(!this.hidden){
            this.s.display = 'none';
        }
//            this.s.visibility = 'hidden';
        this.hidden = true;
    },
    hideVisibility:function () {
        if(!this.invisible)
            this.s.visibility = 'hidden';
        this.invisible = true;
    },
    /**
     * init left page image
     * @param image
     */
    left:function (image) {
        var s = $(image);
        s.css({"width":String(this.width) + 'px',"height":String(this.height) + 'px',"position":"absolute","top":"0","right":"0" });
    },
    /**
     * init right page image
     * @param image
     */
    right:function (image) {
        var s = $(image);
        s.css({"width":String(this.width) + 'px',"height":String(this.height) + 'px',"position":"absolute","top":"0","right":"0" });
    }
};
FLIPBOOK.Like = function (width, height, book, maxPage) {
    this.wrapper = document.createElement('div');
    jQuery(this.wrapper).addClass('flipbook-page');
    jQuery(this.wrapper).addClass('likeCon');
    this.s = this.wrapper.style;
    this.s.width = String(width) + 'px';
    this.s.height = String(height) + 'px';
    this.index = (maxPage*1)+1;
    this.width = width;
    this.height = height;
    this.s.zIndex = String(100 - this.index);
    console.log(maxPage);
    this.invisible = false;

    var fbLike = $(".fbLikeParent .fb-like").clone();
    jQuery(this.wrapper).append(fbLike);
    this.s.left = '50%';
};
FLIPBOOK.Like.prototype = {

};
FLIPBOOK.SLike = function (width, height, book, maxPage) {
    this.wrapper = document.createElement('div');
    jQuery(this.wrapper).addClass('flipbook-page');
    jQuery(this.wrapper).addClass('likeCon');
    this.s = this.wrapper.style;
    this.s.width = String(width) + 'px';
    this.s.height = String(height) + 'px';
    this.index = (maxPage*1)+1;
    this.width = width;
    this.height = height;
    this.s.zIndex = String(100 - this.index);
    this.invisible = false;

    var fbLike = $(".fbLikeParent .fb-like").clone();
    jQuery(this.wrapper).append(fbLike);
    this.s.left = '0%';
};
FLIPBOOK.SLike.prototype = {

};
FLIPBOOK.Time = function (options, width, height, index, book, maxPages) {
    var ImgContainerDiv = $("#ImgContainerDiv");
    var insMe = ImgContainerDiv.find('.pageImg'+(index+1));
    var newSrc = options.src;        
    if(options.src == "") newSrc = insMe.attr("src");
 if(newSrc){
    this.twrapper = document.createElement('div');
    jQuery(this.twrapper).addClass('item');
    this.s = this.twrapper.style;    
    this.index = index;    
    var cDate = $(".created_dates .created_date"+index).html();
    var cPage = cDate;
    jQuery(this.twrapper).addClass('tn'+index); 
    jQuery(this.twrapper).attr("data-id",cPage)
    //console.log("src of page: "+index);   
    this.invisible = false;
    this.image = new Image();    
    this.image.src = newSrc;
    this.twrapper.appendChild(this.image);
    this.readMore = $(document.createElement('div'))
        .addClass('read_more')
        .attr("data-id",cPage)
        .appendTo(this.twrapper)
        .html("More")
        ;
    this.tLoader = document.createElement('div');
    jQuery(this.tLoader).addClass('item_open');
    jQuery(this.tLoader).attr("data-id",cPage)
    //this.itemOpen = $(document.createElement('div'))
//        .addClass('item_open_content')
//        .attr("data-id",index+"/"+index+"/2013")
//        .after(this.tLoader)
//        .html('<img class="imageInsideMore" src="'+options.src+'" alt="" />')
//        ;   
    
    var image2 = $(document.createElement('img'));    
    image2.attr("src",newSrc);
    image2.attr("originalSrc",options.originalSrc);
    $(this.tLoader).append(image2);
 } else {
    return false;   
 }
};

FLIPBOOK.Time.prototype = {
    
};

FLIPBOOK.Slide = function (options, width, height, index, book, maxPages) {


    this.wrapper = document.createElement('div');
    jQuery(this.wrapper).addClass('flipbook-page');
    this.s = this.wrapper.style;
    this.s.width = String(width) + 'px';
    this.s.height = String(height) + 'px';
    this.index = index;
    this.book = book;
    this.width = width;
    this.height = height;

    this.invisible = false;

    this.image = new Image();
    /**
     * lightweight preloader for the page - shows until the page is loaded
     */
    this.image.src = book.options.assets.preloader;
    this.image.className = "thisLoader";
    this.imageSrc = options.src;
    this.wrapper.appendChild(this.image);

    this.imageLoader = new Image();

    //shadow only on left page
//    if (this.index % 2 != 0) {
//        this.shadow = new Image();
//        this.wrapper.appendChild(this.shadow);
//    }

    //black overlay that will be used for shadow in 3d flip
    this.overlay = new Image();
    this.overlay.src = book.options.assets.overlay;
    this.wrapper.appendChild(this.overlay);
    this.overlay.style.opacity = '0';

    this.expanded = true;



//    this.clickArea = document.createElement('div');
//    this.clickArea.classList.add('flipbook-page-clickArea');

    this.htmlContent = options.htmlContent;



    //left pages (indexes 1,3,5,...)
    if (this.index % 2 == 12340) {
        this.s.zIndex = String(100 - this.index);
        this.s.left = '50%';
        this.right(this.image);
        this.right(this.overlay);
    }
    //right pages (indexes 0,2,4,...)
    else {

//        shadow on left page
        //console.log("page index:"+this.index+" max: "+maxPages);
        if(maxPages != (this.index+1)){
            this.shadow = new Image();
            this.wrapper.appendChild(this.shadow);
            //this.shadow.src = book.options.assets.left;                    
//            this.left(this.shadow);    
        }        

        this.s.zIndex = String(100 + this.index);
        this.s.left = '0%';
        this.left(this.image);
        this.left(this.overlay);
    }

    if(typeof  this.htmlContent !== 'undefined'){
        this.htmlContainer = document.createElement('div');
        jQuery(this.htmlContainer).addClass('flipbook-page-htmlContainer');
        this.wrapper.appendChild(this.htmlContainer);
        this.index % 2 == 0 ? this.right(this.htmlContainer) : this.left(this.htmlContainer);
    }

//    this.wrapper.appendChild(this.clickArea);

    this.image.style[this.book.transform] = 'translateZ(0)';

    this.overlay.style[this.book.transform] = 'translateZ(0)';
    this.overlay.style['pointer-events'] = 'none';

    if(this.shadow){
        this.shadow.style[this.book.transform] = 'translateZ(0)';
        this.shadow.style['pointer-events'] = 'none';
    }

    this.s.top = '0px';

    if (this.book.flipType == '3d') {
        this.wrapper.style[this.book.transformOrigin] = (this.index % 2 != 0) ? '100% 50%' : '0% 50%';
    }

    //links

    if(options.links)
    {
        var self = this;
        for(var i= 0; i<options.links.length;i++){

            var link = options.links[i];



            function createLink(link){
                var l = document.createElement('div');
                self.wrapper.appendChild(l);
                l.classList.add("flipbook-page-link");
                l.style.position = 'absolute';
                l.style.left = String(link.x)+'px';
                l.style.top = String(link.y)+'px';
                l.style.width = String(link.width)+'px';
                l.style.height = String(link.height)+'px';
                l.style.backgroundColor = link.color;
                l.style.opacity = link.alpha;
                l.style.cursor = 'pointer';
                jQuery(l)
                    .click(function(e){
                        if(Number(link.page)>0 ){
                            book.goToPage(Number(link.page))
                        }else if(String(link.url) != ''){
                            window.open(link.url);
                        }
                    })
                    .mouseenter(function(){
                        l.style.backgroundColor = link.hoverColor;
                        l.style.opacity = link.hoverAlpha;
                    })
                    .mouseleave(function(){
                        l.style.backgroundColor = link.color;
                        l.style.opacity = link.alpha;
                    })

                ;
            }
            createLink(link);

        }
    }
/*--------------------*/
/*josh mods for share button*/
/*--------------------*/
    var page_number = (this.index * 1) + 1;    
    jQuery(this.wrapper).addClass("pn"+page_number);
//end josh 
};

/**
 * prototype
 * @type {Object}
 */
FLIPBOOK.Slide.prototype = {
    loadPage:function () {
        if(this.loaded == true)
            return;
        this.loaded = true;
        var self = this;
        self.imageLoader.src =     this.imageSrc;
        jQuery(self.imageLoader).load(function () {
            self.image.src = self.imageSrc;
        });
        if(typeof  this.htmlContent !== 'undefined'){
            this.htmlContainer.innerHTML = this.htmlContent;
        }
    },

    flipView:function () {

    },
    /**
     * expand page to full width
     */
    expand:function () {
        if(!this.expanded)
            this.s.width = String(this.width) + 'px';
        this.expanded = true;
    },
    /**
     * contract page to width 0
     */
    contract:function () {
        if(this.expanded)
            this.s.width = '0px';
        this.expanded = false;
    },
    show:function () {
        if(this.hidden){
//            this.invisible = false;
//            this.s.visibility = 'visible';
            this.s.display = 'block';
        }
        this.hidden = false;
    },
    hide:function () {
        if(!this.hidden){
            this.s.display = 'none';
        }
//            this.s.visibility = 'hidden';
        this.hidden = true;
    },
    hideVisibility:function () {
        if(!this.invisible)
            this.s.visibility = 'hidden';
        this.invisible = true;
    },
    /**
     * init left page image
     * @param image
     */
    left:function (image) {
        var s= image.style;
        s.width = String(this.width) + 'px';
        s.height = String(this.height) + 'px';
        s.position = 'absolute';
        s.top = '0px';
        s.right = '0px';
    },
    /**
     * init right page image
     * @param image
     */
    right:function (image) {
        var s= image.style;
        s.width = String(this.width) + 'px';
        s.height = String(this.height) + 'px';
        s.position = 'absolute';
        s.top = '0px';
        s.left = '0px';
    }
};

FLIPBOOK.top10Page = function (options, width, height, index, book) {


    this.wrapper = document.createElement('div');
    jQuery(this.wrapper).addClass('flipbook-page');
    this.s = this.wrapper.style;
    this.s.width = String(width) + 'px';
    this.s.height = String(height) + 'px';
    this.index = index;
    this.book = book;
    this.width = width;
    this.height = height;

    this.invisible = false;

    this.image = new Image();
    /**
     * lightweight preloader for the page - shows until the page is loaded
     */
    this.image.src = book.options.assets.preloader;
    this.imageSrc = options.src;
    this.wrapper.appendChild(this.image);

    this.imageLoader = new Image();

    //shadow only on left page
//    if (this.index % 2 != 0) {
//        this.shadow = new Image();
//        this.wrapper.appendChild(this.shadow);
//    }

    //black overlay that will be used for shadow in 3d flip
    this.overlay = new Image();
    this.overlay.src = book.options.assets.overlay;
    this.wrapper.appendChild(this.overlay);
    this.overlay.style.opacity = '0';

    this.expanded = true;



//    this.clickArea = document.createElement('div');
//    this.clickArea.classList.add('flipbook-page-clickArea');

    this.htmlContent = options.htmlContent;



    //left pages (indexes 1,3,5,...)
    if (this.index % 2 == 0) {
        this.s.zIndex = String(100 - this.index);
        this.s.left = '50%';
        this.right(this.image);
        this.right(this.overlay);
    }
    //right pages (indexes 0,2,4,...)
    else {

//        shadow on left page
        this.shadow = new Image();
        this.wrapper.appendChild(this.shadow);
        this.shadow.src = book.options.assets.left;
        this.left(this.shadow);

        this.s.zIndex = String(100 + this.index);
        this.s.right = '50%';
        this.left(this.image);
        this.left(this.overlay);
    }

    if(typeof  this.htmlContent !== 'undefined'){
        this.htmlContainer = document.createElement('div');
        jQuery(this.htmlContainer).addClass('flipbook-page-htmlContainer');
        this.wrapper.appendChild(this.htmlContainer);
        this.index % 2 == 0 ? this.right(this.htmlContainer) : this.left(this.htmlContainer);
    }

//    this.wrapper.appendChild(this.clickArea);

    this.image.style[this.book.transform] = 'translateZ(0)';

    this.overlay.style[this.book.transform] = 'translateZ(0)';
    this.overlay.style['pointer-events'] = 'none';

    if(this.shadow){
        this.shadow.style[this.book.transform] = 'translateZ(0)';
        this.shadow.style['pointer-events'] = 'none';
    }

    this.s.top = '0px';

    if (this.book.flipType == '3d') {
        this.wrapper.style[this.book.transformOrigin] = (this.index % 2 != 0) ? '100% 50%' : '0% 50%';
    }

    //links

    if(options.links)
    {
        var self = this;
        for(var i= 0; i<options.links.length;i++){

            var link = options.links[i];



            function createLink(link){
                var l = document.createElement('div');
                self.wrapper.appendChild(l);
                l.classList.add("flipbook-page-link");
                l.style.position = 'absolute';
                l.style.left = String(link.x)+'px';
                l.style.top = String(link.y)+'px';
                l.style.width = String(link.width)+'px';
                l.style.height = String(link.height)+'px';
                l.style.backgroundColor = link.color;
                l.style.opacity = link.alpha;
                l.style.cursor = 'pointer';
                jQuery(l)
                    .click(function(e){
                        if(Number(link.page)>0 ){
                            book.goToPage(Number(link.page))
                        }else if(String(link.url) != ''){
                            window.open(link.url);
                        }
                    })
                    .mouseenter(function(){
                        l.style.backgroundColor = link.hoverColor;
                        l.style.opacity = link.hoverAlpha;
                    })
                    .mouseleave(function(){
                        l.style.backgroundColor = link.color;
                        l.style.opacity = link.alpha;
                    })

                ;
            }
            createLink(link);

        }
    }

};

/**
 * prototype
 * @type {Object}
 */
FLIPBOOK.top10Page.prototype = {
    loadPage:function () {
        if(this.loaded == true)
            return;
        this.loaded = true;
        var self = this;
        self.imageLoader.src =     this.imageSrc;
        jQuery(self.imageLoader).load(function () {
            self.image.src = self.imageSrc;
        });
        if(typeof  this.htmlContent !== 'undefined'){
            this.htmlContainer.innerHTML = this.htmlContent;
        }
    },

    flipView:function () {

    },
    /**
     * expand page to full width
     */
    expand:function () {
        if(!this.expanded)
            this.s.width = String(this.width) + 'px';
        this.expanded = true;
    },
    /**
     * contract page to width 0
     */
    contract:function () {
        if(this.expanded)
            this.s.width = '0px';
        this.expanded = false;
    },
    show:function () {
        if(this.hidden){
//            this.invisible = false;
//            this.s.visibility = 'visible';
            this.s.display = 'block';
        }
        this.hidden = false;
    },
    hide:function () {
        if(!this.hidden){
            this.s.display = 'none';
        }
//            this.s.visibility = 'hidden';
        this.hidden = true;
    },
    hideVisibility:function () {
        if(!this.invisible)
            this.s.visibility = 'hidden';
        this.invisible = true;
    },
    /**
     * init left page image
     * @param image
     */
    left:function (image) {
        var s= image.style;
        s.width = String(this.width) + 'px';
        s.height = String(this.height) + 'px';
        s.position = 'absolute';
        s.top = '0px';
        s.right = '0px';
    },
    /**
     * init right page image
     * @param image
     */
    right:function (image) {
        var s= image.style;
        s.width = String(this.width) + 'px';
        s.height = String(this.height) + 'px';
        s.position = 'absolute';
        s.top = '0px';
        s.left = '0px';
    }
};

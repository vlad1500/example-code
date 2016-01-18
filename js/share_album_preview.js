	$(document).ready(function()
	{		
			// quick fix since local have problem identifying mimetype.		
			$.ajaxSetup({beforeSend: function(xhr)
				{
				  if (xhr.overrideMimeType)
				  {
				   // xhr.overrideMimeType("application/json");
				  }
				  //$("#hc_book").prepend('<div style="padding:40px; background: #fff;"><h1><img src="/images/edit_album/ajax.gif" /> Processing... Please wait</h1><p>Hold on tight while hardcover prepares your album...</p></div>');
				  $("#app_loader").fadeIn("slow");
				  $("#hc_cover").hide();		  				  
				}
				
			});
					
		function isFit(html){
			var html = 	$('<div class="layout1"><div class="col">'+html+"</div><div style='clear:both;'></div></div>");
			$("#measurement").empty().append(html);
			return html.height() < 478;
		}
		
		
		var curr_page = 1;
		var max_page = null;
		
		// add the pagination numbers
		function paginate()
		{
			max_page = $('#flip .layout1').length;
			$("#page-numbers").empty();

			$('<a href="#">&laquo;</a>').click(function(){
				if(curr_page == 1)
				{
					p = max_page;
				} else {
					p = curr_page - 1;
				}
				
				$("#page-numbers a:eq("+p+")").click();
				return false;
			}).appendTo("#page-numbers");
			
			for(i=1;i<=max_page;i++)
			{
				var x = $('<a href="#" data-page="'+i+'">'+i+'</a>').click(function(){
					var to = $(this).data("page");
					if(curr_page != to){
						$("#page"+curr_page).addClass("hidden");
						$("#page"+to).removeClass("hidden");
						$("#page-numbers a:eq("+ (curr_page) +")").removeClass("selected");
						curr_page = to;
						$(this).addClass("selected");
					}
					return false;
				}).appendTo("#page-numbers");
				
				if(i == curr_page) {
					x.addClass("selected");
				}
			}
			
			$('<a href="#">&raquo;</a>').click(function(){
				if(curr_page == max_page)
				{
					p = 1;
				} else {
					p = curr_page + 1;
				}
				
				$("#page-numbers a:eq("+p+")").click();
				return false;
			}).appendTo("#page-numbers");		
			
		}
		
		function get_canvas(id)
		{
			var id = (id == undefined || id == null) ? curr_page : id;
			
			for(i=0; i<canvases.length; i++)
			{
				if(canvases[i].id == "c_" + id) {
					return canvases[i];
				}
			}
		}
		
		// redraw photo's canvas
		function redraw_canvas(c)
		{
			var filters = c.filters;
			var properties = c.properties;
			var transform = c.transform;
//			alert(c.id + ": " + image.src);
						
			var canvas_width = 400, canvas_height = 478;
			if(properties.scale_by == "width") {
				var w = canvas_width;
				var h = canvas_width * c.image_data.height / c.image_data.width;
			} else if(properties.scale_by == "height") {
				var w = canvas_height * c.image_data.width / c.image_data.height;
				var h = canvas_height;
			} else if(properties.scale_by == "none") {
				var w = c.image_data.width;
				var h = c.image_data.height;
			}
			
			w = w * transform.scale;
			h = h * transform.scale;

			if(properties.align == "center") {
				x = (canvas_width - w) / 2;
				y = (canvas_height - h) / 2;									
			} else if(properties.align == "none") {
				x = 0;
				y = 0;
			}
					
			var ctx = $("#"+c.id)[0].getContext("2d");
			ctx.clearRect(0,0,canvas_width,canvas_height);		
			ctx.drawImage(c.image_data, x, y, w, h);
			
			
			if(filters.color == "bw") {
				filter_bw(ctx, canvas_width, canvas_height);
			}
		}
		
		function filter_bw(ctx, w, h)
		{
			var imgd = ctx.getImageData(0, 0, w, h);
			var pix = imgd.data;
			for (var i = 0, n = pix.length; i < n; i += 4) {
				var grayscale = pix[i  ] * .3 + pix[i+1] * .59 + pix[i+2] * .11;
				pix[i  ] = grayscale; 	// red
				pix[i+1] = grayscale; 	// green
				pix[i+2] = grayscale; 	// blue
			}
			ctx.putImageData(imgd, 0, 0);			
		}
		
		var url = "/share_album/get_book_pages/" + album_id;
//		url = "/tools/edit_album/debug_url_proxy.php";
//		url = "/test.json";
		var cols = [];
		var pages_html = [];
		
		var isLoaded;
		if (album_id == null){
			load(load_data.res);
			isLoaded = true;
		} else {
			$.getJSON(url+"?c="+$.now(), function(res){
				load(res);
			});			
			isLoaded = false;			
		}
		
		function load(res)
		{
			console.log(res);
			// prepare layout.
			var book_pages = res.book_pages;
			var cols = [];
			// trim based on preview or not.
			var book_length = (book_pages.length > 11) ? 11 : book_pages.length;
			for(i=0;i<book_length;i++)
			{								
				var page = book_pages[i];
				if(page.image_url == null) {
					cols.push({ "type" : "status", "status" : page.message });
				} else {					
					cols.push({ "type" : "canvas", "image_url" : page.image_url });					
				}
				
				if(page.comment == undefined) {
					cols.push({ "type" : "comment", "html" : '<div class="col"><div class="no-comment">No comments has been made to this post.</div></div>' });					
				} else {
					
					var comments_html = "";
					for(j=0;j<page.comment.length;j++)
					{
						var comment = page.comment[j];
						var author = comment.from.name;
						var message = comment.message;
						var img = "http://graph.facebook.com/"+comment.from.id+"/picture/";
						_comments_html = '<div class="comment">'+
											'<img src="'+img+'" width="50" height="50" />'+
												 '<div class="comment-text">'+
												"<strong>" + author + "</strong> " + 
													message +
												'</div>'+
										  '</div>';
						if(isFit(comments_html + _comments_html))
						{
							comments_html += _comments_html;
						} else {						
							cols.push({ "type" : "comment", "html" : '<div class="col">'+comments_html+'</div>' });
							comments_html = _comments_html;						
						}										  
						
						if(j == page.comment.length - 1){
							cols.push({ "type" : "comment", "html" : '<div class="col">'+comments_html+'</div>' });
							
							if(cols.length % 2 == 1) // add a whitespace for non-even columns
								cols.push({ "type" : "comment", "html" : '<div class="col"></div>' });
						}									  					
						 
					}				
					
				}
				
			  }

				var html = "";
				var page_num = 1;
								
				for(j=0;j<cols.length;j++)
				{
					if(j==0){
						html += '<div id="page'+page_num+'" class="layout1">';
					} else if(j % 2 == 0){
						page_num++;
						html += '<div id="page'+page_num+'" class="layout1">';
					}
										
					var col = cols[j];
					if(col.type == "canvas") {
						html += '<canvas id="c_'+page_num+'" style="float: left;" width="400" height="478"></canvas>';
						
						if(isLoaded == false)
						canvases.push({ 
								"id" : "c_"+page_num, 
								"image_data" : null,
								"image_url" : col.image_url, 
								"transform" : {
									"scale" : 1, // 0.1 - 2 (x0.1 to x2)
									"rotate" : 0, // in degrees					
								},
								"filters" : { 
									"color" : "none", // none, bw
								},
								"properties" : {
									"align" : "center", // center, none
									"scale_by" : "width" // width, height, none
								} 								
							});
					} else if (col.type == "status" ) {
						html += '<div class="col-status"><div class="status"><span class="quote">&ldquo;</span>'+col.status+'<span class="quote">&rdquo;</span></div></div>';
					} else if (col.type == "comment" ) {
						html += cols[j].html;						
					}
										
					if(j==cols.length - 1 || j % 2 == 1){
						html += '<div style="clear: both;"></div>';
						html += '</div>';
					}
					
				}
				//pre-displayed in view (Marlo 12/9/2012) -> $("#hc_book").html('<div id="hc_cover"></div>' + html);
				$("#hc_book").append(html);
				$('#hc_book').wowBook({
				      height : 478,
				      width  : 1600,
				      hardcovers : true,
				      hardPages : true,
				      flipSoundPath : "/css/share_album_preview/sound/"
				    });
				
			    //$(".wowbook-origin").append('<div id="invited"></div>').append('<div id="thanks"></div>');					    
								
				// start setting up the images and canvas.				
				var image_width = 400;
				var image_height = 478;
				var page_count = canvases.length;
				for(j=0;j<page_count;j++)
				{
					//canvases[j].ctx = $("#"+canvases[j].id)[0].getContext("2d")
					
					var img = new Image();
					img.j = j;
					img.onload = function(){
																		
						var c = canvases[this.j];
						c.image_data = this;			
						redraw_canvas(c);
					};
					
					img.src =  "/tools/edit_album/load_fb_image.php?src=" + escape(canvases[j].image_url);
					//images.push(img);
				}
				$("div[class='wowbook-handle wowbook-right']").html("<img src=\"/images/flip-guide.png\" alt=\"Click to See Next Page\" title=\"Click to See Next Page\" />").css({"float":"right","padding":"200px 0","right":"20px","width":"80px"});
				/*$(".wowbook-left:first").clone(true).attr("class", "wowbook-page wowbook-hardpage wowbook-right").css({"z-index":"0","left":"800px","display":"none"}).appendTo(".wowbook-origin");
				$(".wowbook-right:first").clone(true).attr("class", "wowbook-page wowbook-hardpage wowbook-left").css({"z-index":(page_count + 2),"left":"0px"}).appendTo(".wowbook-origin");
				$(".wowbook-right #page1:last").attr("id", "page"+ (page_count + 1)).empty();
				$(".wowbook-left #hc_cover #cover_preview_page:last").addClass("back_cover").empty();
				$(".wowbook-left #hc_cover:last").attr("id", "page"+ (page_count + 2));*/
								
		}
				//Marlo starts edit here 12/09/2012
				$("ul#cover_friends_pic").ready(function() {
					loadCover();
				});
				//Marlo ends edit here 12/09/2012
				

	});

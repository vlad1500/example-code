 
<!--home-->
<div id="my_home" class="tab2_content"></div>
<!--edit-->
<!--<div id="my_edit" class="tab2_content">
<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/edit_album/layout.css" type="text/css" />
<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/edit_album/edit.css" type="text/css" />
<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/book.css" type="text/css" />-->
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/editor/jquery.wysiwyg.css"/>
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/editor/plugins/farbtastic/css/farbtastic.css"/>
<!--<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/lightbox/pop.css" type="text/css">
<link rel="stylesheet" href="<?php //echo $this->config->item("css_url"); ?>/groupeditstyle.css" type="text/css" />-->
 
<?php
	$front_cover = '';
	$back_cover = '';
	$temp = 0;
	$tempb = 0;

	if(is_array($cover_page_selected) and isset($cover_page_selected['book_page_front_image_url']) and $cover_page_selected['book_page_front_image_url'] !='') { 
	    $front_cover = $cover_page_selected['book_page_front_image_url'] ;    
	    $temp = 1;
	}
	if(is_array($cover_page_selected) and isset($cover_page_selected['book_page_back_image_url']) and $cover_page_selected['book_page_back_image_url'] !='') { 
	    $back_cover = $cover_page_selected['book_page_back_image_url'] ;    
	    $tempb = 1;
	}
?>



	<div id="book_content" class="book-edit">
		<div id="measurement"></div>			
		
		<div id="header_edit" class="section-header">
			<div class="row">
				<div class="col-lg-2 col-sm-2">
					<a id="book-view"><img  alt="Book-view" src="images/book-view-select.png"/></a>
					<a id="thumb-view"><img alt="Thumb-view" src="images/thumb-view.png"/></a>
				</div>
				<div class="col-lg-6 col-sm-6 text-center">
					<?php echo $_COOKIE['book_name']; ?>
				</div>
				<div class="col-lg-4 col-sm-4 text-right">
					<button id="previewer" class="btn btn-small btn-orange">Preview + Publish</button>
					<button id="design-cover" class="btn btn-small btn-orange">Design Cover</button>
				</div>
			</div>
		</div>
		 
		<div id="main_edit" class="book__inner">
       		<div id="book_summary" style="display:none;" class="section-header book-summary">
       			<span class="book-summary__item"># <?php echo count($book_pages); ?> Photos</span>
       			<span class="book-summary__item">Rearrange images by Drag and Drop</span>
       			<span class="book-summary__item"><?php echo $page_info_text; ?></span>
       			<span class="book-summary__item"><input type="checkbox" name="selectall" id="selectall"> Select All</span>
       			<select id="page_num_list" class="book-summary__item">
       				<option value=''>Move to page</option>
       				<option value=''>1</option>
       				<?php 
       					$p = $total_pages/100+1;  
       					for($i=2;$i<=$p;$i++) { 
       						echo "<option value=".(($i-1)*100).">".$i."</option>"; 
       					} ?>
       			</select>
       			<button onclick="jump_order();" class="btn btn-small">Go</button>
       			<button id="delete_album_data_d" class="btn btn-small">Delete Photos</button>
       			<button id="save_order" class="btn btn-small">Save</button>
       			<input type="hidden" id="bok_id" value="<?php echo $book_id; ?>" /></div>

 				<form id="alb_form_dat" >
		 			<div id="thumb_image_view" style="display:none;" class="gridster" > 
						<?php //print_r($testJ); ?>
		    			<ul id="gallery" data-key="<?php echo $pi;?>">
		      				<?php  $i=1;  foreach($book_pages as $key=>$val){ if(trim($val->image_url) != '') { ?>
		         			<li data-itemid="<?php echo $val->book_pages_id; ?>" id="datali_<?php echo $val->book_pages_id; ?>"   class="gs_w input" >
                                <div style="display:none;"><?php echo $val->sql_josh ?></div>
								<div><img width="150" height="150" src="/timthumb.php?src=<?php echo $val->image_url;  ?>&h=150&w=150&zc=1" /> 
								<div style="float:left;" class="table thumb_inner_wrapper">
									<input id="delete_alb<?php echo $val->book_pages_id; ?>" name="delete_alb[<?php echo $val->book_pages_id; ?>]"  value="<?php echo $val->book_pages_id ?>" type="checkbox" /> <span class="span_left"> Delete</span> <span class="span_right">p.<?php echo $i; ?></span></div> 
								</div>
							</li>
		      			<?php  } $i++; } ?>
		    			</ul>
		  			<div style="margin: 0px auto; clear: left; width: 791px; text-align: center;"><?php echo $pagination;  ?></div>
			</div>
   		</form>
 
	<div id="bookbg">
		<div class="pagesc">
			<div class="pageborder"></div>
		</div>
		<a class="pagebutton" id="backward" href="javascript:void(0)"><img alt="Back" src="images/left-arrow.png"/></a>
		<a class="pagebutton" id="forward" href="javascript:void(0)"><img alt="Forward" src="images/right-arrow.png"/></a>
	</div>

	<!-- ========== Share buttons for every page. book.css ========== -->
	<div class="section-share">
		<div class="row">
			<!--  
			<div class="col-lg-6 mod-share">
				<a target='_blank' href='http://www.facebook.com/sharer/sharer.php?u=<?php echo current_url(); ?>' class="fb-share"><img src="<?php echo $this->config->item('image_url'); ?>/facebook.png" alt="Facebook"/></a>
				<a target='_blank' href="http://twitter.com/intent/tweet?url=<?php echo current_url(); ?>&amp;text=HardCover&amp;hashtags=hardcover" class="twitter-share"><img src="<?php echo $this->config->item('image_url'); ?>/twitter.png" alt="Twitter"/></a>
				<a target='_blank' href='mailto:?subject=HardCover&amp;body=Check out my life in HardCover. <?php echo current_url(); ?>' class="email-share"><img src="<?php echo $this->config->item('image_url'); ?>/mail.png" alt="mail"/></a>
				<a target='_blank' href='http://pinterest.com/pin/create/button/?url=<?php echo current_url(); ?>&media=urldescription=HardCover' class="pinterest-share"><img src="<?php echo $this->config->item('image_url'); ?>/pinterest.png" alt="Pinterest"/></a>
			</div>
			<div class="col-lg-6 mod-share">
				<a class="fb-share"><img src="<?php echo $this->config->item('image_url'); ?>/facebook.png" alt="Facebook"/></a>
				
				<a class="twitter-share"><img src="<?php echo $this->config->item('image_url'); ?>/twitter.png" alt="Twitter"/></a>
				<a class="email-share"><img src="<?php echo $this->config->item('image_url'); ?>/mail.png" alt="mail"/></a>
				<a class="pinterest-share"><img src="<?php echo $this->config->item('image_url'); ?>/pinterest.png" alt="Pinterest"/></a>
				
			</div>
			-->
		</div>
	</div>
	<!-- ========== End Share buttons for every page. ========== -->
	
	 
	<div id="bookbg-addtext" class="c">
		<div class="row">
			<div class="col-lg-6">
				<div class="bookbg-add-crop clearfix">
					<ul> 
				    	<li><a id="add_text_ico" class="add-text" href="javascript:addLabel('right')">&nbsp;</a></li>
				      	<li>
							<a  class="right add-text crop_ico" id="align-centerw" href = "javascript:addCrop('right')">&nbsp;</a>
							<a class="rightsave add-text" style="display:none" id="align-centerrs" href = "javascript:saveCrop('right')">Save Crop</a>
							<a id="align-centerrr" style="display:none" class="rightsave add-text" href = "javascript:restoreCrop('right')">Restore</a>
				   		</li>

				   		<li><a id=" " class="add-text rotate_left" href="javascript:rotateimg('left','right')">&nbsp;</a></li>
				      	<li>
				     	<li><a id=" " class="add-text rotate_right" href="javascript:rotateimg('right','right')">&nbsp;</a></li>
				      	<li>
						<li><a id=" " class="add-text flipvertical" href="#">&nbsp;</a></li>
				      	<li>
						<li><a id=" " class="add-text fliphorizontal" href="#">&nbsp;</a></li>
				  	</ul>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="bookbg-add-crop clearfix">
			 		<ul>
			   			<li><a id="add_text_ico" class="add-text" href="javascript:addLabel('left')">&nbsp;</a></li>
			   			<li>
							<a class="left add-text crop_ico" id="align-centerw" href = "javascript:addCrop('left')">&nbsp;</a>
							<a class="leftsave add-text" style="display:none" id="align-centerls" href = "javascript:saveCrop('left')">Save Crop</a>
							<a id="align-centerlr" style="display:none" class="leftsave add-text" href = "javascript:restoreCrop('left')">Restore</a>
			   			</li>
			       		<li><a id=" " class="add-text rotate_left" href="javascript:rotateimg('left','left')">&nbsp;</a></li>
			     		<li><a id=" " class="add-text rotate_right" href="javascript:rotateimg('right','left')">&nbsp;</a></li>
					  	<li><a id=" " class="add-text flipvertical" href="#">&nbsp;</a></li>
					  	<li><a id=" " class="add-text fliphorizontal" href="#">&nbsp;</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div id="paginations" class="section-paginations">
		<ul class="mod-paginate clearfix">
			
		</ul>
	</div>
	<div id="flip" style="display:none">		
		<div id="hc_book"></div>
	</div>
					
	<br />
	<div id="paginate" style="text-align: center; font: normal 12px verdana;display:none">
		<div id="page-numbers"></div>
		<div style="clear:both;"></div>
	</div>

	<div id="canvas-tools" style="display:none">
		<div id="tool-align" class="toolbox">
			Image Size and Alignment:
			<button id="align-center">Center</button>
			<button id="scaleby-height">Scale by height</button>
			<button id="scaleby-width">Scale by width</button>
			<button id="properties-reset">Reset</button>
		</div>
		<div id="tool-filter" class="toolbox">
			Color filters:
			<button id="filter-bw">Black & White</button> 
			<button id="filter-reset">Reset</button> 
			Transform: 
			<button id="transform-scale-enlarge">Enlarge +</button> 
			<button id="transform-scale-shrink">Shrink -</button> 
			<button id="transform-reset">Reset</button>
		</div>

		<div class="toolbox">
			<button id="zoom_in">Zoom In</button> <button id="zoom_out">Zoom Out</button> 			
		</div>
	</div>		
	</div>	 	
	</div>
</div><!--End of my_edit-->
<!--coverdesign-->
<div id="my_cover" class="tab2_content"></div><!--End of my_cover-->
<!--albums-->
<div id="my_album" class="tab2_content"></div><!--End of my_album--> 
<input id="fb_username" type="hidden" value="<?=$fb_username;?>" name="fb_username" />
<input id="secured_book_info_id" type="hidden" value="<?= $encrypted_book_info_id;?>" name="secured_book_info_id" />


<script type="text/javascript" charset="utf-8">
head.js( 		'<?php echo $this->config->item("js_url"); ?>/jquery.pagination.js',
				'<?php echo $this->config->item("js_url"); ?>/editor/jquery.wysiwyg.js',
				'<?php echo $this->config->item("js_url"); ?>/editor/controls/wysiwyg.colorpicker.js',
				'<?php echo $this->config->item("js_url"); ?>/jquery.cropzoom.js',
				'<?php echo $this->config->item("js_url"); ?>/jquery.dragsort-0.5.1.min.js',
				'<?php echo $this->config->item("js_url"); ?>/book_share.js'				
				);


var gridster = '';
var add_friends_url = "add-friends-url";
var design_cover_url = "design-cover-url";	
var saveData = { "res" : {}, "canvases" : [] };
var view = 2;
	
head.ready(function() {
	var w = jQuery('#bookbg_cover_front').width();
	jQuery('#front_img').attr('src','/timthumb.php?src=<?php echo $front_cover;  ?>&w='+w+'&cc=84ACCC');
	jQuery('#back_img').attr('src','/timthumb.php?src=<?php echo $back_cover;  ?>&w='+w+'&cc=84ACCC');

	//initializebook();	
	onPhotoURISuccess('edit_album/get_book_pages?fbid=<?=$fbid;?>&book_info_id=<?=$book_id;?>');
	loadBook();
	
	$("#gallery").dragsort({
		dragSelector: "div",
		placeHolderTemplate: "<li class='placeHolder'><div></div></li>"
	});
	
	$("#book-view").click(function(){	   
	    $("#bookbg .pages").each(function(){
	       $(this).remove();   
	    });
        requestCrossDomain(APIURL+'edit_album/get_book_pages_share', function(res) { });
        $('#thumb_image_view').hide();
		$('#book_summary').hide();
	 	$('#bookbg-addtext').show();
		$('#canvas-toolser').show();
		$('#bookbg').show();
		$('#paginations').show();
		$('#thumb-view img').attr('src','images/thumb-view.png');
		$('#book-view img').attr('src','images/book-view-select.png');
	});

	$("#selectall").unbind("click");
 	$("#selectall").bind("click",function(event){
    	var v = $(this).attr("checked");
    	if(v == 'checked'){
    		$(".table input[type=checkbox]").attr("checked",v);
  		}else
      		$(".table input[type=checkbox]").removeAttr("checked");
 		}
 	);
	
	$("#thumb-view").click(function(){
	
	$('#book_summary').show();
	   	$('#thumb_image_view').show();
	    $('#bookbg-addtext').hide();
	    $('#canvas-toolser').hide();
	  
	  	$('#bookbg').hide();
		$('#paginations').hide();
		$('#thumb-view img').attr('src','images/thumb-view-select.png');
		$('#book-view img').attr('src','images/book-view.png');
	
	});
		
	$("button#add-friends").click(function(){
		/**
		 * FB Send Dialog Pop-up screen
		 *
		 */
		//Marlo edit starts here 12/06-07/2012
		sendFBMessage("<?php echo $this->config->item('base_url'); ?>", "<?php echo $fb_username; ?>", "");
		//Marlo edit ends here 12/06-07/2012
	});

	$("button#save_order").click(function(){
		saveOrder();
	});

	$("button#delete_album_data_d").click(function(){
		var dat = '';
		dat = $('form#alb_form_dat').serialize();
		if(dat=='') {
			alert('No photos selected to Delete.');
		    return false;
		}

		modalMessageBox("Delete Photos", "<span style='font-weight:normal; font-size:18px;'>Do you want to delete selected images </span> "+ "?" +"<div id='delete_container_modal'><p> <a data-dismiss='modal' href='#' class='btn btn-orange'>NO</a></p></div>");
		$("#delete_container_modal p").prepend("<input type=\"button\" id=\"yes\" class='btn btn-orange' value=\"YES\" />");

		$("#form_container #reason").css({"width":"430px","font-weight":"normal"});
		$("#form_container h6").css({"margin":"0"});
		
		$("#yes").click(function(e) {
		    var ids = '';
			$(".gs_w input").each( function(n, i) {
				if($(this).is(":checked")) {
					if(ids=='')	
				    	ids = $(this).val(); 
				 	else {
						ids = ids  + "," + $(this).val() ; 
					}
					var idv = $(this).val();
					console.log(idv); 
					$('#datali_'+idv).remove();
				}
	     	});
	
	       	var bid = $('#bok_id').val();
	        $.ajax({
				url		: 	"main/delete_book_pades_d",
				type	:	"post",
				data    :"ids="+ids+"&bid="+bid,
				success	:	function(res) {					 
						$("#cancel").click();
						$('#edit a').click();
						$('#thumb-view').click();
						}
			});
		});

		modalPosition();
	});
		
	$("button#design-cover").click(function(){
		/**
		 * Go to Cover tab
		 *
		 */
		$("#app_loader").fadeIn("slow");
		$.ajax({
			url		: 	"cover/design",
			type	:	"post",
			success	:	function(res) {
				var _obj = $.parseJSON(res);
		        $("ul.tabs2 li").removeClass("active"); //Remove any "active" class
		        $("ul.tabs2 li#coverdesign").addClass("active");
		        $(".tab2_content").hide(); //Hide all tab content
				$("#main_inner").html(_obj.data);
				$("#my_cover").fadeIn();
				$("#app_loader").fadeOut("slow");
			}
		});
	});		

	$("button#previewer").click(function(){
		var WinId = window.open('edit_album/preview/', 'edit_album_preview', 'width=' + screen.width +',height='+screen.height);		
	});

	function setHeight() {
		var $gph = $('#gallery .placeHolder');

		$gph.height(204);

		console.log('setHeight called '+ $gph.height());
	}

	function saveOrder() {
		var data = $("#gallery li").map(function() { 
			return $(this).data("itemid"); }).get();
			var page = $('#gallery').attr("data-key"); 
            $.each(data, function(index, val) {
                console.log(val);
            });
	        $.post("main/update_thumb_order", { "ids[]": data ,"page" : page }); 
	    
	}

	function jump_order() {
		// var data = $("#gallery li").map(function() { return $(this).data("itemid"); }).get();

			var page = $('#page_num_list').val();
			if(page=='')
				return false;
				var ids = '';
				$(".gs_w input").each( function(n, i) {
					if($(this).is(":checked")){
						if(ids=='')	
    						ids = $(this).val(); 
 						else {
	 						ids = ids  + "," + $(this).val() ; 
						}
						var idv = $(this).val();
						console.log(idv); 
						$('#datali_'+idv).remove();
					}
   				});
	        $.post("main/update_thumb_jump_order", { "ids": ids ,"page" : page }); 
	}

	function paginate(val){
		jQuery.ajax({
			type:"post",
			url: "main/edit_album",
			data :"page="+val,
			beforeSend: function ( xhr ) {
		}
		}).done(function ( res ) {		   
			var _obj = $.parseJSON(res);
			$('.ajax_loader').remove();
		
			if($("#main_inner").find("#my_edit").length == 0)
				$("#main_inner").append("<div class='tab2_content' id='my_edit'></div>");
			$("#main_inner .tab2_content").html("");
			$('#main_inner').html(_obj.data).css("display","block"); 
			$('#thumb-view').trigger('click');
		   
		}); 
	}
	
});

</script>

<!--
<script src="<?php echo $this->config->item("js_url"); ?>/book.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/script.js"></script> 
<script src="<?php echo $this->config->item("js_url"); ?>/script_unique.js"></script> 
<script src="<?php echo $this->config->item("js_url"); ?>/albumscript.js"></script>
--> 
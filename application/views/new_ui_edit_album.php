
<!--home-->
<div id="my_home" class="tab2_content"></div>
<!--edit-->
<div id="my_edit" class="tab2_content">
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/edit_album/layout.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/edit_album/edit.css" type="text/css" />
<!--  
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/book.css" type="text/css" /> 
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/groupeditstyle.css" type="text/css" />
-->
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/editor/jquery.wysiwyg.css"/>
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/editor/plugins/farbtastic/css/farbtastic.css"/>
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/lightbox/pop.css" type="text/css">
<style>
.edit-tab-info input, .edit-tab-info textarea, .edit-tab-info label {
    padding: 3px;
    width: 500px;
}

</style>

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
				<div class="col-sm-7">
					<h3 class="h4 section--header__title"><?php echo $_COOKIE['book_name']; ?></h3>
				</div>
				<div class="col-sm-5 text-right">
					<button id="add-pages" class="btn btn-small btn-orange" style="display: none;">Add Pages</button>
					<button id="js-previewer" class="btn btn-small btn-orange">Preview + Publish</button>
					<button id="save-page-info" class="btn btn-small btn-orange">Save</button>
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
       			<input type="hidden" id="bok_id" value="<?php echo $book_id; ?>" />
       		</div>

 			<form id="alb_form_dat" >
		 		<div id="thumb_image_view" style="display:none;" class="gridster" > 
					<?php //print_r($book_pages); ?>
		    		<ul id="gallery" data-key="<?php echo $pi;?>">
		      			<?php  $i=1;  foreach($book_pages as $key=>$val){ if(trim($val->image_url) != '') { ?>
		         		<li data-itemid="<?php echo $val->book_pages_id; ?>" id="datali_<?php echo $val->book_pages_id; ?>"   class="gs_w input" >
							<div>
								<img width="150" height="150" src="<?php echo $this->config->item('image_upload_'); ?>/150x150/<?php echo $val->image_url; ?>" />
								<div style="float:left;" class="table thumb_inner_wrapper">
									<input id="delete_alb<?php echo $val->book_pages_id; ?>" name="delete_alb[<?php echo $val->book_pages_id; ?>]"  value="<?php echo $val->book_pages_id ?>" type="checkbox" /> <span class="span_left"> Delete</span> <span class="span_right">p.<?php echo $i; ?></span>
								</div> 
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
				<a class="pagebutton" id="backward" href="javascript:void(0)"><img alt="Back" src="../images/left-arrow.png"/></a>
				<a class="pagebutton" id="forward" href="javascript:void(0)"><img alt="Forward" src="../images/right-arrow.png"/></a>
                <div id="insertContentHere"></div>
			</div>

	<!-- ========== Share buttons for every page. book.css ========== -->
	<!--
	<div class="section-share">
		<div class="row">
			<div class="col-sm-6 mod-share">
				<a class="fb-share"><img src="<?php echo $this->config->item('image_url'); ?>/facebook.png" alt="Facebook"/></a>  
				<a class="twitter-share"><img src="<?php echo $this->config->item('image_url'); ?>/twitter.png" alt="Twitter"/></a>
				<a class="email-share"><img src="<?php echo $this->config->item('image_url'); ?>/mail.png" alt="mail"/></a>
				<a class="pinterest-share"><img src="<?php echo $this->config->item('image_url'); ?>/pinterest.png" alt="Pinterest"/></a>
			</div>
			<div class="col-sm-6 mod-share">
				<a class="fb-share"><img src="<?php echo $this->config->item('image_url'); ?>/facebook.png" alt="Facebook"/></a>
				<a class="twitter-share"><img src="<?php echo $this->config->item('image_url'); ?>/twitter.png" alt="Twitter"/></a>
				<a class="email-share"><img src="<?php echo $this->config->item('image_url'); ?>/mail.png" alt="mail"/></a>
				<a class="pinterest-share"><img src="<?php echo $this->config->item('image_url'); ?>/pinterest.png" alt="Pinterest"/></a>
			</div>
		</div>
	</div>
	-->
	<!-- ========== End Share buttons for every page. ========== -->
	
	 
	<div id="bookbg-addtext" class="c">
        <div class="row">
			<div class="col-sm-6">
				<div class="bookbg-add-crop clearfix edit-tab-info">
                  <form id="left-page-info">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" />
                    <label for="description">Description</label>
                    <textarea name="description" id="description"></textarea>
                    <input type="hidden" name="book_pages_id" id="book_pages_id" />
                  </form>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="bookbg-add-crop clearfix edit-tab-info">
                  <form id="right-page-info">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" />
                    <label for="description">Description</label>
                    <textarea name="description" id="description"></textarea>
                    <input type="hidden" name="book_pages_id" id="book_pages_id" />
                  </form>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
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

			<div class="col-sm-6">
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
			<button id="filter-bw">Black &amp; White</button> 
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
function doInit(){
    var wW=$(document).width();
		var wH=$(document).height();
	if (wW >= 1920) {
        var bgWidth = 1920;
        var bgHeight = 1440;
    }
    if (wW < 1920) {
        var bgWidth = 1680;
        var bgHeight = 1050;
    }
    if (wW < 1680) {
        var bgWidth = 1440;
        var bgHeight = 900;
    }
    if (wW < 1440) {
        var bgWidth = 1366;
        var bgHeight = 768;
    }
	var smWidth=150;
	var smHeight=150;
	var w = jQuery('#bookbg_cover_front').width();

	jQuery('#front_img').attr('src','<?php echo $this->config->item('image_upload_'); ?>'+'/'+bgWidth+'x'+bgHeight+'/'+'<?php echo $front_cover;  ?>');
	jQuery('#back_img').attr('src','<?php echo $this->config->item('image_upload_'); ?>'+'/'+bgWidth+'x'+bgHeight+'/'+'<?php echo $back_cover;  ?>');

	//initializebook();
	onPhotoURISuccess('<?php echo $this->config->item('image_upload_'); ?>', 'edit_album/get_book_pages?fbid=<?=$fbid;?>&book_info_id=<?=$book_id;?>' );
	loadBook();
	dwidth = jQuery("#bookbg").width();
	dimgwidth = dwidth*0.40;
    dheight = (8/11)*dimgwidth;
    FB.Canvas.setSize({ height: dheight+100 });
}
head.ready(function() {
    doInit();
    // Callling Preview window
    $("#js-previewer").click(function(e){
        e.preventDefault();
        var WinId = window.open('edit_album/preview/', 'edit_album_preview', 'width=' + screen.width +',height='+screen.height);        
    });
    $("#save-page-info").click(function(){
        $.ajax({
            url: '<?=$this->config->item('base_url');?>/edit_album/savePageInfo',
            type: 'post',
            data: $('form#left-page-info').serialize(),
            success: function(data) {
                console.log(data);
                $.ajax({
                    url: '<?=$this->config->item('base_url');?>/edit_album/savePageInfo',
                    type: 'post',
                    data: $('form#right-page-info').serialize(),
                    success: function(data) {
                        console.log(data);
                        doInit();
                        alert("current page info saved.");
                    }
                });
            }
        });
    });
});

</script>

<!--
<script src="<?php echo $this->config->item("js_url"); ?>/book.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/script.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/script_unique.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/albumscript.js"></script>
-->
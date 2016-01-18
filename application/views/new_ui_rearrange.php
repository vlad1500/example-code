
<script type="text/javascript">

	// Calling Modal
	$("#js-add-pages").click( function (e) {
		e.preventDefault();
		$('#js-modal-newpage').modal();
	});

	// Callling Preview window
	$("#js-previewer").click(function(e){
		e.preventDefault();
		var WinId = window.open('edit_album/preview/', 'edit_album_preview', 'width=' + screen.width +',height='+screen.height);		
	});

</script>

<div id="my_edit" class="tab2_content">
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/edit_album/layout.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/edit_album/edit.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/editor/jquery.wysiwyg.css"/>
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/editor/plugins/farbtastic/css/farbtastic.css"/>
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/lightbox/pop.css" type="text/css">

	<div id="book_content" class="book-edit">
		
		<div id="header_edit" class="section-header">
			<div class="row">
				<div class="col-sm-6">
					<h3 class="h4 section--header__title"><?php echo $_COOKIE['book_name']; ?></h3>
				</div>
				<div class="col-sm-6 text-right">
					<a href="#" id="js-previewer" class="btn btn-small btn-orange">Preview &amp; Publish</a>
					<a href="#" id="js-add-pages" class="btn btn-small btn-orange">Add Pages</a>
					<a href="#" id="js-save" class="btn btn-small btn-orange">Save</a>
				</div>
			</div>
		</div>

		<div id="main_edit" class="book__inner">
       		<div id="book_summary" class="section-header book-summary">
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
       			<button onclick="jump_order();" class="btn btn-small btn-orange">Go</button>
       			<button id="delete_album_data_d" class="btn btn-small btn-orange">Delete Photos</button>
       			<input type="hidden" id="bok_id" value="<?php echo $book_id; ?>" />
       		</div>

 			<form id="alb_form_dat" >
		 		<div id="thumb_image_view" class="gridster" >
		    		<ul id="gallery" data-key="<?php echo $pi;?>" status="<?=$_POST['page'] ?>">
		      			<?php  
		      			$i=$pi;
		      			foreach($book_pages as $key=>$val){
		      				$image_url = $val->image_url;
		      				if ($image_url != ''){								
								if (strpos($image_url,'/uploads/')===false){
									$image_url = "/timthumb.php?src=$image_url&h=150&w=150&zc=1";
								}else{									
									$image_url = str_replace('/uploads/','/uploads/150x150/',$image_url);
		      					}
		      			?>
				         		<li data-itemid="<?php echo $val->book_pages_id; ?>" id="datali_<?php echo $val->book_pages_id; ?>"   class="gs_w input" >
									<div>
										<img width="150" height="150" src="<?=$image_url;?>" /> 
										<div style="float:left;" class="table thumb_inner_wrapper">
											<input id="delete_alb<?php echo $val->book_pages_id; ?>" name="delete_alb[<?php echo $val->book_pages_id; ?>]"  value="<?php echo $val->book_pages_id ?>" type="checkbox" /> <span class="span_left"> Delete</span> <span class="span_right">p.<?php echo $i; ?></span>
										</div> 
									</div>
								</li>
		      			<?php  
							} 
							$i++; 
		      			} 
		      			?>
		    		</ul>
		  			<div style="margin: 0px auto; clear: left; width: 791px; text-align: center;"><?php echo $pagination;  ?></div>
				</div>
   			</form>
	
		</div>	 	
	</div>
<script>
head.js(
    '<?php echo $this->config->item("js_url"); ?>/book_share.js',
    '<?php echo $this->config->item("js_url"); ?>/jquery.dragsort-0.5.1.min.js'
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
		dragEnd: saveOrder,
		placeHolderTemplate: "<li class='placeHolder'><div></div></li>",
		dragStart: setHeight
	});

 	$("#selectall").unbind("click").bind("click",function(event){
 	    console.log("test click");
    	var v = $(this).attr("checked");
    	if(v == 'checked'){
    		$(".table input[type=checkbox]").attr("checked",v);
  		}else
      		$(".table input[type=checkbox]").removeAttr("checked");
 		}
 	);

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
				url		: 	"/main/delete_book_pades_d",
				type	:	"post",
				data    :"ids="+ids+"&bid="+bid,
				success	:	function(res) {
						$("#js-modal-common .close").click();
						$('#edit a').click();
						$('#thumb-view').click();
						}
			});
		});

		modalPosition();
	});
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
			url: "main/new_ui_rearrange",
			data :"page="+val,
			beforeSend: function ( xhr ) {
		}
		}).done(function ( data ) {
            var _obj = $.parseJSON(data);
                $('#js-maincontent').html("");
	            $('#js-maincontent').html(_obj.data);
	            $('#js-maincontent').fadeIn('slow');
                $('.dropdown-menu').show();
	            $('#js-dropdown .dropdown-menu > li').removeClass('open');
	            $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                $('#js-my-books').parent().addClass('active');
                $('#js-dropdown').addClass('open');
    			$('#js-rearrange').addClass('active');
                FB.Canvas.setSize();
		});
	}
</script>
</div><!--End of my_edit-->


<input id="fb_username" type="hidden" value="<?=$fb_username;?>" name="fb_username" />
<input id="secured_book_info_id" type="hidden" value="<?= $encrypted_book_info_id;?>" name="secured_book_info_id" />

<!-- ===== LIGHTBOX FOR ADD NEW PAGES ===== -->
<div class="modal fade" id="js-modal-newpage">
    <div class="modal-dialog">
     	<div class="modal-content">
        	<div class="modal-header">
          		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
          		<h4 class="modal-title">Add New Page</h4>
        	</div>
	        <div class="modal-body">
	        	
	        	<h3 class="modal-body__title h4">Add pages to the “book name” ex. Cookie book of wisdom</h3>
	        	<p>Number of pages to add <input type="text" name="add" class="text-input-xs"/></p>
	        	<p>Where to add pages:</p>
	        	<ul class="list-unstyled">
	        		<li><input type="radio" name="where-to-add"/> Beginning of the book</li>
	        		<li><input type="radio" name="where-to-add"/> End of the book</li>
	        		<li><input type="radio" name="where-to-add"/> Before page number [p. 1] <input type="text" name="before-page-number" class="text-input-xs"/> [p.x]</li>
	        	</ul>

	        	<ul class="list-unstyled">
	        		<li>Beginning of chapter <select>
	        			<option>1</option>
	        			<option>2</option>
	        			<option>3</option>
	        		</select></li>
	        		<li>End of chapter <select>
	        			<option>1</option>
	        			<option>2</option>
	        			<option>3</option>
	        		</select></li>
	        	</ul>
	        	<p class="text-right"><button id="add-pages" class="btn btn-small btn-orange">Add Pages</button></p>

	        </div>
      	</div>
    </div>
</div>





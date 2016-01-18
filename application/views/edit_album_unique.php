<!--home-->
<div id="my_home" class="tab2_content"></div>
<!--edit-->
<div id="my_edit" class="tab2_content">
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/edit_album/layout.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/edit_album/edit.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/book.css" type="text/css" />

<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/editor/jquery.wysiwyg.css"/>
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/editor/plugins/farbtastic/css/farbtastic.css"/>
<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/src/jquery.gridster.css"/>

<script src="<?php echo $this->config->item("js_url"); ?>/src/jquery.gridster.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/src/jquery.collision.js"></script> 

<script src="<?php echo $this->config->item("js_url"); ?>/src/jquery.coords.js"></script> 
<script src="<?php echo $this->config->item("js_url"); ?>/src/jquery.draggable.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/src/jquery.gridster.extras.js"></script> 
 
<script src="<?php echo $this->config->item("js_url"); ?>/src/utils.js"></script> 

<script type="text/javascript" charset="utf-8">
	var add_friends_url = "add-friends-url";
	var design_cover_url = "design-cover-url";

	var saveData = { "res" : {}, "canvases" : [] };
	var view = 2;
	
	$(document).ready(function(){
		
		$("#book-view").click(function(){
			
			$('#thumb_image_view').hide();
			 $('#book_summary').hide();
			 $('#bookbg-addtext').show();
			     $('#canvas-toolser').show();
			$('#bookbg').show();
			$('#paginations').show();
			$('#thumb-view img').attr('src','images/thumb-view.png');
			$('#book-view img').attr('src','images/book-view-select.png');
			});
			
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
		$("button#delete_album_data_d").click(function(){
			  var dat = '';
			  dat = $('form#alb_form_dat').serialize();
			  if(dat=='')
			  {
			    alert('No photos selected to Delete.');
			    return false;
			}
			   
			var ids = '';
			 $(".gs_w input").each( function(n, i) {
				 if($(this).is(":checked"))
				if(ids=='')	
				    ids = $(this).val(); 
				 else{
					 ids = ids  + "," + $(this).val() ; 
				 }
				var idv = $(this).val();
				console.log(idv); 
				gridster.remove_widget( $('#datali_'+idv));
  //alert(n); alert(i);
});
      var bid = $('#bok_id').val();
        $.ajax({
				url		: 	"main/delete_book_pades_d",
				type	:	"post",
				data    :"ids="+ids+"&bid="+bid,
				success	:	function(res) {
					 
				}
			});
		


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
		
	});

</script>
	<div id="book_content">
		<div id="measurement"></div>			

		<div id="main_edit">
		
       <div id="book_summary" style="display:none;"><span class="span_left" style="font-weight:bold; margin-left: 12px; margin-top: 10px;">Phots<?php echo count($book_pages); ?></span><span class="span_left" style=" margin-left: 50pxpx;margin-top: 10px;"> Rearrange images by Drag and Drop</span><button  id="delete_album_data_d" style=" float:right;margin-top: 7px;">Delete Photos</button><input type="hidden" id="bok_id" value="<?php echo $book_id; ?>" /></div>
 <form id="alb_form_dat" >
		 <div id="thumb_image_view" style="display:none;" class="gridster" > 
		 
	 
		 
		<?php echo "TESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSST"; ?>
		 
		 <?php print_r($book_pages); ?>
		    <ul>
		      <?php $i = 1; $c = 1; $r = 1;  foreach($book_pages as $key=>$val){ if(trim($val->image_url) != '') { ?>
		         <li id="datali_<?php echo $val->fb_dataid ?>"  data-row="<?php echo $r; ?>" data-col="<?php echo $c; ?>" data-sizex="1" data-sizey="1">
				<img width="150" height="150" src="<?php echo $val->image_url;  ?>" /> 
				<p><input id="delete_alb<?php echo $val->fb_dataid ?>" name="delete_alb[<?php echo $val->fb_dataid ?>]"  value="<?php echo $val->fb_dataid ?>" type="checkbox" /> <span class="span_left"> Delete</span> <span class="span_right">p.<?php echo $i; ?></span></p> 
			</li>
		      <?php $c++; if($c==6) { $r++; $c = 1;} $i++; } } ?>
		    </ul>
		 
		</div>
   </form>
<script>
var gridster = '';
$(function(){ //DOM Ready 
 
gridster = $(".gridster ul").gridster({
widget_margins: [10, 10],
widget_base_dimensions: [150, 175],
 min_cols: 5
}).data('gridster');
 
});

$(function(){ //DOM Ready
 
//var gridster = $(".gridster ul").gridster().data('gridster');
 
});

</script>test
		<div id="bookbg">
		<div class="pagesc">
			<div class="pageborder"></div>
		</div>
		<a class="pagebutton" id="backward" href="javascript:void(0)">
			<img alt="Back" src="images/left-arrow.png"/>
		</a>
		<a class="pagebutton" id="forward" href="javascript:void(0)">
			<img alt="Forward" src="images/right-arrow.png"/>
		</a>
	</div>
	
	<!--
	<div id="bookbg-addtext">
		<div class="bookbg-add-crop" style="float:right;">
		  <ul>
		    <li><a class="add-text">Add Text</a></li>
		     <li><a class="add-text">crop</a></li>
		     <li><img src="images/forwards-backward.png" /></li>
		       <li><img src="images/next.png" /></li>
		      
		  </ul>
		
		
		</div>
		<div class="bookbg-add-crop" style="float:left;">
		 <ul>
		   <li><a class="add-text">Add Text</a></li>
		     <li><a class="add-text">crop</a></li>
		      <li><img src="images/forwards-backward.png" /></li>
		       <li><img src="images/next.png" /></li>
		  </ul>
		
		</div>
	
	
	</div>
	
	
	-->
	
	
	
	
	
	<div id="paginations">
		<ul>
			
		</ul>
	</div>
<!--
	<div id="canvas-toolser" style="clear:both">
	  <div id="tool-align3" class="toolbox">
	  	<button id="align-centerq" onclick="addLabel()">Add</button>
	  	<button id="align-centerw"  class="left" onclick="addCrop('left')">Left Crop</button>
		<button id="align-centerls" class="leftsave" style="display:none" onclick="saveCrop('left')">Left Save</button>			
		<button id="align-centerlr" class="leftsave" style="display:none" onclick="restoreCrop('left')">Left Restore</button>

		<button id="align-centerw"  class="right" onclick="addCrop('right')">Right Crop</button>
		<button id="align-centerrs" class="rightsave" style="display:none" onclick="saveCrop('right')">Right Save</button>
		<button id="align-centerrr" class="rightsave" style="display:none" onclick="restoreCrop('right')">Right Restore</button>
	  	
	  <!--	<button id="align-centerer" onclick="fullscreen()">Full Screen</button> -->
	 	
	  <!-- </div>	 
	</div>
	-->
		
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

<!-- resources -->
<script src="<?php echo $this->config->item("js_url"); ?>/edit_album/jquery.transit.min.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/edit_album/edit.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/jquery.Jcrop.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/fullscreen.js"></script>
<script src="<?php echo $this->config->item("js_url"); ?>/jquery.cropzoom.js"></script>


<script src="<?php echo $this->config->item("js_url"); ?>/book.js"></script>



<!--<script src="<?php echo $this->config->item("js_url"); ?>/sharescript.js" type="text/javascript"></script>-->

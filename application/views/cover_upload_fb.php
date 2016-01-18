<link href="//code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=$this->config->item('css_url');?>/flexisel.css" />
<link rel="stylesheet" type="text/css" href="<?=$this->config->item('css_url');?>/skins/tango/skin.css" />

<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->


<script type="text/javascript">
	$(".get_ph").live("click", function(){ var id= this.id; $('.hide').hide(); if ($('#'+id).is(':checked')) {  $('.albb_'+id).show(); }else{ $('.albb_'+id).hide();}  });
	
	head.js(
			
			);
</script> 
 
	<!--Content for the Filter Page-->
<form id="cover_data">
	<div id="filter_content">
		<!--fb_data-->
		<div id="fb_data" class="tab2_content">
			
				<!--- START OF WRAPPER -->
				<div id="wrapper">     
									            
					<div id="tabs">
						<!--
						<ul class='etabs'>					    
					    	<li class='tab'><a href="#tabs1-facebook">Upload from Facebook</a></li>
						</ul>
					  	-->
					  
					  <div class='panel-container'>
					  <div id="tabs1-facebook">
					  		<!--  Upload from Facebook -->
		                    <div id="opt_div_fb">                            
		                            <!-- Albums -->                            
		                            <h4>Photos from album</h4>
		                            <ul  id="myalbums" >
		                           	
		                           	<?php      
		                            foreach ($albums as $album){										                                         
										//$fbdata = unserialize($album->fbdata);
										
		                                echo "<li><img src='{$album->fbdata->cover_photo}'/>
                                   			<br/>
                                   			<center><input class='user_album' value='".$album->fbdata->id."'  type='checkbox' name='album_{$album->fbdata->id}' rel='{$album->album_id}'   id='id_{$album->fbdata->id}'><span>".count($album->photos)."</span></center>
                                   			</li>";
		                                
                                   	}
                                   	?>

		                            </ul>
		                                              
		                            <div id="album_photo_raw_data" style=" float: left;    margin: 3px 0 0 5px;">
									</div>
									
		                            <!--- Photo Sizes -->                             
		                            <div id="filter_menu_size"  style="display:none;">
		                                <h3>Photo size</h3>
		                                <?php
		                                    $str = $book_filter->photo_size;
		                                    $arr = explode(";",$str);
		                                    
		                                    if (in_array("1",$arr)) {$hd = 'value="1" checked=checked';}
		                                    if (in_array("2",$arr)) {$medium = 'value="1" checked=checked';}
		                                    if (in_array("3",$arr)) {$small = 'value="1" checked=checked';}
		                                ?>     
		                                <ul id="menu_size">
		                                    <li><input type="checkbox" name="photo_size_hd" id="photo_size_hd" <?php echo $hd;?>/> <span><a href="#" onClick="clickMe('photo_size_hd'); return false;">HD</a></span></li> 
		                                    <li><input type="checkbox" name="photo_size_medium" id="photo_size_medium" <?php echo $medium;?>/> <span><a href="#" onClick="clickMe('v'); return false;">Medium</a></span></li>
		                                    <li><input type="checkbox" name="photo_size_small" id="photo_size_small" <?php echo $small;?>/> <span><a href="#" onClick="clickMe('photo_size_small'); return false;">Small</a></span></li>
		                                </ul>
		                            </div> 
		                    </div>
					  </div>
					  </div>
					</div>
              </div>
              <!--- END OF WRAPPER -->
           	                  	
		</div>						
		<div id="button_edit_right" class="pull-right">
			<input type="hidden" id="front_back" name="front_back" value="<?=$front_back;?>" />	
			<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button> <input id="save_cover" type="button" class="btn btn-orange pull-right save_cover" value="Upload" name="button" />             
		</div>
	</div>	
</form> 
 

<script>
head.ready(function() {

	$( document ).ready(function() {
		$("#myalbums").flexisel({
			visibleItems: 6,	
	        enableResponsiveBreakpoints: true,
	        responsiveBreakpoints: { 
	            portrait: { 
	                changePoint:480,
	                visibleItems: 1
	            }, 
	            landscape: { 
	                changePoint:640,
	                visibleItems: 2
	            },
	            tablet: { 
	                changePoint:768,
	                visibleItems: 3
	            }
	        }
		});

		
	});
		
	//prevent checking of more than one photo
	$(".album_photo").live("click", function (event) {
		
		if (this.checked){
			$(".album_photo").prop('checked', false);
			$(this).prop('checked', true);	
		}
		
	});	
	
});
</script>


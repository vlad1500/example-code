
	<link href="<?=$this->config->item('css_url');?>/filter.css" rel="stylesheet" />
	<link href="//code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" rel="stylesheet" />
	<link href="<?=$this->config->item('js_url');?>/libs/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" rel="stylesheet"/>
	<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
	<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<style>
   
  	</style>  
    
	<script type="text/javascript">
		$(".get_ph").live("click", function(){ var id= this.id; $('.hide').hide(); if ($('#'+id).is(':checked')) {  $('.albb_'+id).show(); }else{ $('.albb_'+id).hide();}  });
		
		head.js({'uploader':'<?php echo $this->config->item("js_url"); ?>/libs/plupload.full.min.js'},
				{'ui-uploader':'<?php echo $this->config->item("js_url"); ?>/libs/jquery.ui.plupload/jquery.ui.plupload.js'},
				'<?php echo $this->config->item("js_url"); ?>/filter_page.js'
				);
		
	    head.ready(function() {
	    	$( "#tabs" ).tabs();
	    });
	</script>
 
	<!--Content for the Filter Page-->
<form id="form_filter_data">
	<div id="filter_content">
		<!--fb_data-->
		<div id="fb_data" class="tab2_content">
			
				<!--- START OF WRAPPER --->
				<div id="wrapper">     
									            
					<div id="tabs">
					  <ul class='etabs'>
					    <li class='tab'><a href="#tabs1-computer">Upload from Computer</a></li>
					    <li class='tab'><a href="#tabs1-facebook">Upload from Facebook</a></li>
					  </ul>
					  
					  <div class='panel-container'>
					  <div id="tabs1-computer">
							<!-- Upload from computer -->
							<form  method="post" action="dump.php">
								<div id="uploader">
									<p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
								</div>
							</form>							
					  </div>
					  <div id="tabs1-facebook">
					  		<!--  Upload from Facebook -->
		                    <div id="opt_div_fb">                            
		                            <!--- Albums --->                            
		                            <h3>Photos from album</h3>                        
		                            
						<!-- <div id='carousel_container'>
		                                  <div style="margin:0 auto;">
		                                      <div id='left_scroll'><img src='/images/HardCover_leftArrowBig.png' /></div>
		                                        <div id='carousel_inner'> id="photos_from_album" -->
		                                            <ul  id="mycarousel" class="jcarousel-skin-tango">
		                                            	<?php      
															 
									$albums = explode(',',$book_filter->albums);
									$user_albums1 = $user_albums;
		                                            	$f_data = '';
		                                            	 //print_r($user_albums_data); exit;
		                                            	  
		                                            		foreach ($user_albums as $album){
											$f_data .=$user_albums_data[$album->album_id];
		                                            			$fbdata = unserialize($album->fbdata);
		                                            			//print_r($fbdata); exit;	
															        $checked='';									
		if (in_array($fbdata->id, $albums)) $checked = ' checked=checked ';                                         
		                                         			echo "<li  ><img src='https://graph.facebook.com/{$fbdata->cover_photo}/picture?type=thumbnail&access_token=$token'/>
		                                            				<br/>
		                                            				<center><input class='get_ph' value='".$fbdata->id."'  type='checkbox' $checked name='album_{$fbdata->id}' onclick='get_album_photos({$fbdata->id})'   id='id_{$fbdata->id}'><span>{$fbdata->count}</span></center>
		                                            				</li>";
		                                            		}
		                                            	?>

		                                            </ul>
							<!--		                                        
							</div>
		                                       <div id='right_scroll'><img src='/images/HardCover_rightArrowBig.png' /></div> 
		                                  </div>
		                            </div>      
		                                        -->
		                                              
		                            <div id="album_photo_raw_data" style=" float: left;    margin: 3px 0 0 56px;">
                                    <?php    echo  $f_data ;  ?>
    
									</div>
		                            <!--- Photo Sizes--->                             
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
              <!--- END OF WRAPPER --->
           	                  	
		</div>						
		<div id="button_edit_right" class="float_right">
			<input id="filter_next1" type="button" class="float_right" value="Next1" name="submit" /> 
			<input id="filter_close" onclick="hideoverlay()" type="button" class="float_right" value="Close" name="submit" />             
		</div>
	</div>	
</form> 
 
<script type="text/javascript" src="<?=$this->config->item('js_url');?>/jquery.jcarousel.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/skins/tango/skin.css" />

<script type="text/javascript">
function hideoverlay()
{
	
	jQuery("#main_inner_uploder_pop").fadeOut("100");
	jQuery("#app_loader23").fadeOut("100");
}
jQuery(document).ready(function() {
	if(jQuery("#mycarousel").length > 0)
    jQuery('#mycarousel').jcarousel();
});

</script>
<script>

	head.ready('ui-uploader',function() {
		$("#uploader").plupload({
			// General settings
			runtimes : 'html5',
			url : 'uploader/upload_from_pc',
			max_file_size : '1000mb',
			max_file_count: 1, // user can add no more then 20 files at a time
			chunk_size : '1mb',
			unique_names : true,
			multiple_queues : false,

			// Resize images on clientside if we can
			//resize : {
			//	width : 200, 
			//	height : 200, 
			//	quality : 90,
			//	crop: true // crop to exact dimensions
			//},

			// Specify what files to browse for
			filters : [
				{title : "Image files", extensions : "jpg,gif,png"}				
			],

			// Rename files by clicking on their titles
			rename: true,
			
			// Sort files
			sortable: true,

			// Enable ability to drop files onto the widget (currently only HTML5 supports that)
			dragdrop: true,

			// Views to activate
			views: {
				list: true,
				thumbs: true // Show thumbs
			},
			default_view: 'thumbs',
			remember_view: true // requires jquery cookie plugin
		});

		// Client side form validation
		$('form').submit(function(e) {
	        var uploader = $('#uploader').plupload('getUploader');

	        // Files in queue upload them first
	        if (uploader.files.length > 0) {
	            // When all files are uploaded submit form
	            uploader.bind('StateChanged', function() {
	                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
	                    $('form')[0].submit();
	                }
	            });
	                
	            uploader.start();
	        } else
	            alert('You must at least upload one file.');

	        return false;
	    });

       /* var uploader = $('#uploader').pluploadQueue();
        uploader.bind('FileUploaded', function() {
	        alert('ok');
            if (uploader.files.length == (uploader.total.uploaded + uploader.total.failed)) {
            	uploader.splice();
            }
        });
        alert(uploader.files.length);
        uploader.splice();
	    */
    });
    
    function get_album_photos(alb_id)
    {
		 if($("#id_"+alb_id).is(":checked")) {
		   
		   $.ajax({
				url 	: "../../../main/get_album_photos",
				type : "post",
				data : 'alb_id='+alb_id,
				success : function(res){
					 if(res != '')
					 {
						 $('#album_photo_raw_data').append(res);
					 }else{
					 }
					} 
			});
	}
		else
		{
			$(".cla_"+alb_id).remove();	
		}
	}
</script>



<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->


<script type="text/javascript">
	$(".get_ph").live("click", function(){ var id= this.id; $('.hide').hide(); if ($('#'+id).is(':checked')) {  $('.albb_'+id).show(); }else{ $('.albb_'+id).hide();}  });	
</script> 
 
<!--Content for the Filter Page-->
<form method="post" accept-charset="utf-8" enctype="multipart/form-data" id="cover_data">	
	<div id="filter_content">
		<div id="filter_content">
			<!--fb_data-->
			<div id="fb_data" class="tab2_content">
			
				<!--- START OF WRAPPER -->
				<div id="wrapper">            
					<div id="tabs">
					  	<!--
					  	<ul class='etabs'>
					    	<li class='tab'><a href="#tabs1-computer">Upload from Computer</a></li>
					  	</ul>
						-->
					  	<div class='panel-container'>
						  	<div id="tabs1-computer">
								<!-- Upload from computer -->								
								<input type="file" accept="image/*" multiple name="userfile" id="image"/>	
								<input type="hidden" id="front_back" name="front_back" value="<?=$front_back;?>"/>							
						  	</div>
					  	</div>
					</div>
          		</div>
            	<!--- END OF WRAPPER -->
           	                  	
			</div>
			<hr/>						
			<div id="server_response">
				<div class="progress">
					<!--<progress value="0" max="100"></progress>-->
					<div id="progress-bar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
				</div>
			</div>
			<div id="button_edit_right" class="pull-right">
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button> <input id="save_cover" type="button" class="btn btn-orange pull-right upload" value="Upload" name="upload" />             
			</div>
		</div>
	</div>	
</form> 
 

<script>
		// Client side form validation
		
		
		$('.upload').on("click", function(){
			var form = new FormData($('#cover_data')[0]);
		
	        // Make the ajax call
	        $.ajax({
	            url: 'cover/saveCoverUploadPc',
	            type: 'POST',	            
	            xhr: function() {
	                var myXhr = $.ajaxSettings.xhr();
	                if(myXhr.upload){
	                    myXhr.upload.addEventListener('progress',progress, false);
	                }
	                return myXhr;
	            },
	            //add beforesend handler to validate or something
	            //beforeSend: functionname,
	            success: function (res) {
	            	var _obj = $.parseJSON(res);

	            	if (_obj.status==200) {
						$(".book-cover__pic").html("<img src='" + _obj.url_image + "' />");
						if ($("#front_back").val()=='front')						
							window.front_cover = _obj.url_image;
						else
							window.back_cover = _obj.url_image;
		            }		
	                $('#server_response').append('<p>' + _obj.message + '</p>');
	            },
	            //add error handler for when a error occurs if you want!
	            //error: errorfunction,
	            data: form,
	            cache: false,
	            contentType: false,
	            processData: false
	        });
	        
	        return false;
	    });

		function progress(e){
		    if(e.lengthComputable){
				//this makes a nice fancy progress bar
		        //$('progress').attr({value:e.loaded,max:e.total});
		        $('#progress-bar').css({'width':e.loaded+'px'});
		    }
		}
	
</script>


<style>
#cover_img_wrapper .book-cover__input {
    text-align: center;
    background: transparent;
    color:#000;    
    width:100%;
}
#cover_img_wrapper .book-cover__input--title {    
    top:0px;
    left:0;    
}
#cover_img_wrapper .book-cover__input--author {    
    bottom: -5px;
    left: 0;
}
</style>
<div id="my_cover" class="tab2_content">
    
	<div id="book_content">

		<div class="section section--header" id="cover_header">
			<div class="row">
				<div class="col-sm-9">
					<h2 class="section__title h3">Book Cover</h2>
				</div>
				<div class="col-sm-3">
					<div id="cover_header_right" class="text-right">
						<button id="previewer" class="btn btn-orange">Preview/Publish</button>
						<input type="button" id="js-save" value="Save" class="btn btn-orange"/>
					</div>
				</div>
			</div>
		</div>

		<div id="cover_main">
			<div id="cover_wrapper" class="section shadow">
				<div class="row">
					<div class="col-sm-4">
						<input type="hidden" name="mode" id="mode" value="front"/>

						<ul class="nav nav-tabs" id="js-tab-cover-uploader">
							<li id="front_cover" class="active">
								<a href="#js-front-cover" class="cover_type_tab" rel="front" data-toggle="tab">Front Cover</a>
							</li>
							<li id="back_cover">
								<a href="#js-back-cover" class="cover_type_tab" rel="back" data-toggle="tab">Back Cover</a>
							</li>
						</ul>
						
						<div class="tab-content clearfix">
							<div class="tab-pane active" id="js-front-cover" stat="<?=$show_title ?>">
								<h2 class="h5 tab-title">Choose front cover image</h2>																
								<p><a href="javascript:void(0)"  rel="front" user_id="" class="btn btn-orange cover_upload_pc">Upload from PC</a></p>
								<p><a href="javascript:void(0)"  rel="front" user_id="" class="btn btn-orange cover_upload_fb">Upload from Facebook</a></p>
								<p style="margin:15px 0 5px 0;">Show book title</p>
                                <p style="margin:0px 0 5px 0;"><input type="radio" name="show_title" id="show_title" value="1" <?php if($show_title == "checked" || !$show_title) echo "checked"; ?> /> Yes <input type="radio" name="show_title" id="show_title" value="0" <?php if($show_title == "notChecked") echo "checked"; ?> /> No</p>
								<p style="margin:20px 0 5px 0;">Show author's name</p>
                                <p style="margin:0px 0 5px 0;"><input type="radio" name="show_author" id="show_author" value="1" <?php if($show_author == "checked" || !$show_author) echo "checked"; ?> /> Yes <input type="radio" name="show_author" id="show_author" value="0" <?php if($show_author == "notChecked") echo "checked"; ?> /> No</p>
							</div><!-- End of #js-front-cover -->

							<div class="tab-pane" id="js-back-cover">
								<h2 class="h5 tab-title">Choose back cover image</h2>
								<p><a href="javascript:void(0)"  rel="back" class="btn btn-orange cover_upload_pc">Upload from PC</a></p>
								<p><a href="javascript:void(0)"  rel="back" class="btn btn-orange cover_upload_fb">Upload from Facebook</a></p>
							</div><!-- End of #js-back-cover -->
						</div>
						
						<div id="front_cover1" class="add_active">
							<div></div>
						</div>

						<div id="back_cover1" class="add_hide">							
						</div>

						<!-- temporarly delete code -->
						<div id="cover_menu_left_bottom" style="display:none;">
							<h3>Cover type: <span class="s6"></span></h3>
							<!--<p><input type="button" value="Change Cover Type" /></p>-->
							<input type="button" id="my_friends" value="Add/Remove Friends" />
							<p><input type="button" id="my_profile_pic" value="Show My Picture" /></p>
						</div>
						<!-- temporarly delete code -->
					</div>

					<div class="col-sm-8">
						<div class="book-cover">
							<div id="cover_content" class="book-cover__<?php echo $cover; ?>">
								<div class="book-cover__bleed"></div>
								<div id="cover_img_wrapper" class="book-cover__body">
									<input type="text" id="cover_title" name="cover_title" value="<?=$book_name;?>" class="book-cover__input book-cover__input--title" <?php if($show_title == "notChecked") echo "style='display:none;'"; ?> />
									<div id="cover_prof_pic" class="book-cover__pic"></div>
									<ul style="display:none !important;" id="cover_friends_pic" class="back_display test"></ul>
									<input type="text" id="cover_caption" name="cover_caption" value="by <?=$book_author->fname." ".$book_author->lname;?>" disabled class="book-cover__input book-cover__input--author" <?php if($show_author == "notChecked") echo "style='display:none;'"; ?> />
								</div>
							</div>
						</div>
					</div>
				</div>

			</div><!--End of cover_wrapper-->
		</div><!--end of cover_main-->
	</div><!--end of book_content-->
</div><!--End of my_cover-->
<!--albums-->

<script type="text/javascript">
	/* ===== Please note these are only temporary =====*/
	$("#previewer").on('click', function(){
        //$('#js-dialog-common').modal();
		//$('#js-dialog-common .dialog-content').empty().html('<i class="fa fa-spinner fa-spin"></i> Changes Save!');
        $.ajax({
			url : '/cover/saveCoverTitle',
			data: 'cover_title=' + $("#cover_title").val() + "&cover_author=" + $("#cover_caption").val() + "&is_show_book_title=" + $("#show_title:checked").val() + "&is_show_author=" + $("#show_author:checked").val(),
			type: 'POST',
			success : function(res){					
				var _obj = $.parseJSON(res);
				//$('#js-dialog-common .dialog-content').empty().html(_obj.msg);
				if (_obj.status==0){
					createBookCoverThumbnail();
					console.log('creating cover...'+$.cookie("hardcover_book_info_id"));
				}
                var WinId = window.open('/edit_album/preview/', 'edit_album_preview', 'width=' + screen.width +',height='+screen.height);
			},error : function(res, err, errTxt) {
				//$('#js-dialog-common .dialog-content').empty().html('Something went wrong during the saving...');
                console.log('Something went wrong during the saving...');
			}, complete : function (){
				 
			}
		});		
	});

	var front_cover = '<?=$front_cover;?>';
	var back_cover = '<?=$back_cover;?>';

	//initialize book cover to the front cover image    
	$(".book-cover__pic").html("<img id='front_cover_pic' src='" + front_cover + "' /><img id='back_cover_pic' src='" + back_cover + "' style='display:none;' />");

	//on every change of the book cover tab, switch to the cover image
	$(".cover_type_tab").on("click", function() {
		if ($(this).attr('rel')=='front'){
            $("#front_cover_pic").show();
            $("#back_cover_pic").hide();
		}else{
		    $("#front_cover_pic").hide();
            $("#back_cover_pic").show();   
		}
					

	});    
    $('input[type=radio][name=show_title]').change(function() {
        var ifCheck = $(this).val();
        console.log(ifCheck);
        if(ifCheck == "1") $("#cover_title").css("display","block");
        else $("#cover_title").css("display","none");
    });
    $('input[type=radio][name=show_author]').change(function() {
        var ifCheck = $(this).val();
        console.log(ifCheck);
        if(ifCheck == "1") $("#cover_caption").css("display","block");
        else $("#cover_caption").css("display","none");
    });
	// Dialog box for notification.
	var secs = 3000;
	
	$('#js-save').on('click', function() {
		console.log( $("#show_title:checked").val() );
		$('#js-dialog-common').modal();
        $('#js-dialog-common .dialog-content').empty().html('<i class="fa fa-spinner fa-spin"></i> Changes Save!');

		$.ajax({
			url : '/cover/saveCoverTitle',
			data: 'cover_title=' + $("#cover_title").val() + "&cover_author=" + $("#cover_caption").val() + "&is_show_book_title=" + $("#show_title:checked").val() + "&is_show_author=" + $("#show_author:checked").val(),
			type: 'POST',
			success : function(res){
				var _obj = $.parseJSON(res);
				$('#js-dialog-common .dialog-content').empty().html(_obj.msg);
				if (_obj.status==0){
					createBookCoverThumbnail();
					console.log('creating cover...'+$.cookie("hardcover_book_info_id"));
				}
			},error : function(res, err, errTxt) {
				$('#js-dialog-common .dialog-content').empty().html('Something went wrong during the saving...');				
			}, complete : function (){
				 
			}
		});					
		//$(document).scrollTop();	
		return false;	

		setTimeout(function() {
			$('.close').trigger('click');
		}, secs);
	});

	function createBookCoverThumbnail(){
		$.ajax({
			url : '/image_creator/create_book_thumbnail/'+$.cookie("hardcover_book_info_id")+"/196/144/0",
			data: '',
			type: 'POST',	 
			success : function(res){					
				//var _obj = $.parseJSON(res);
				//$('#js-dialog-common .dialog-content').empty().html(_obj.msg);
			},error : function(res, err, errTxt) {
			    console.log(res);
                console.log(err);
                console.log(errTxt);
				$('#js-dialog-common .dialog-content').empty().html('Something went wrong during the saving...');
			}, complete : function (){
				 
			}
		});
	}

			
	head.ready(function() {
		/**
		 * Loading the Cover tab
		 * 
		 */
		$("#app_loader").fadeIn("slow");
		$.cookie("css", null);
		$.cookie("hardcover_friends_fbid", null);
		//$("#"+ active_cover).click(); //execute the click method for the active cover
	});	

</script>

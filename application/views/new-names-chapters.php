<link rel="stylesheet" href="<?php echo $this->config->item("js_url"); ?>/friendchooser/friendChooserMinimalistic.css" />
<style>
#popup-chooser {
    width: 300px;
    z-index: 999999;
}    
</style>
<div class="section">
	<div class="row">
		<div class="col-sm-12 col-lg-12">
			<h3 class="h4"><?=$book_name; ?></h3>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 col-lg-12">
			<div class="section section--main">

				<form id="form-book-creation" class="form-horizontal" role="form">
					<div class="form-group">
				    	<label class="col-sm-2 control-label">Book Name</label>
				    	<div class="col-sm-10">
				      		<input type="text" class="form-control" id="bookName" name="bookName">
				    	</div>
				  	</div>
				  	<div class="form-group">
				    	<label class="col-sm-2 control-label">Book Description</label>
				    	<div class="col-sm-10">
				      		<textarea class="form-control" rows="3" id="bookDesc" name="bookDesc"></textarea>
				    	</div>
				  	</div>

				  	<div class="form-group" style="">
				  		<div class="col-sm-2"></div>
				    	<div class="col-sm-10">
				      		<div class="radio">
				        		<label class="radio-inline">
				          			<input type="radio" name="optWithChapter" id="js-no-chapters" value="0" checked> No Chapters
				        		</label>
				        		<label class="radio-inline">
				        			<input type="radio" name="optWithChapter" id="js-with-chapters" value="1"> Book with Chapters
				        		</label>
				      		</div>
				    	</div>
				  	</div>
				  	
                    <div id="chapter_container" style="display:none">
    				  	<div class="form-group" id="chapterBox1">
    				    	<label class="col-sm-2 control-label">Chapter Name:</label>
    				    	<div class="col-sm-7">
    				      		<input type="text" class="form-control" name="chapter_name[]">
    				    	</div>
    				    	<div class="col-sm-3">
    				    		Assigned to: <span class="js-assigned"></span><input type="hidden" name="assigned_friend[]"><br />
    				    		<a href="#js-modal-common" class="js-add-user-chapter">Assign chapter to friend</a>
    				    	</div>
    				  	</div>
    				  	<div class="form-group" id="chapterBox2">
    				    	<label class="col-sm-2 control-label">Chapter Name:</label>
    				    	<div class="col-sm-7">
    				      		<input type="text" class="form-control" name="chapter_name[]">
    				    	</div>
    				    	<div class="col-sm-3">
    				    		Assigned to: <span class="js-assigned"></span><input type="hidden" name="assigned_friend[]"><br />
    				    		<a href="#js-modal-common" class="js-add-user-chapter">Assign chapter to friend</a>
    				    	</div>
    				  	</div>
    					<div id="js-chapters" style="">
    					  	<div class="form-group" id="chapterBox3">
    					    	<label class="col-sm-2 control-label">Chapter Name:</label>
    					    	<div class="col-sm-7">
    					      		<input type="text" class="form-control" name="chapter_name[]">
    					    	</div>
    					    	<div class="col-sm-3">
    					    	    Assigned to: <span class="js-assigned"></span><input type="hidden" name="assigned_friend[]"><br />
    					    		<a href="#" class="js-add-user-chapter">Assign chapter to friend</a>
    					    	</div>
    					  	</div>
    					</div>
    				  	<div class="form-group" id="chapterBox4" >
    				    	<label class="col-sm-2 control-label"></label>
    				    	<div class="col-sm-7 text-right">    				    	    
    				      		<a href="#" id="js-add-chapters">Add another chapter</a>
    				    	</div>
    				  	</div>    				  	
    				</div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10 text-right">
                            <button type="submit" class="btn btn-default btn-orange" id="js-create-book-save">Create Book</button>
                            <div id="response" class="response"></div>
                        </div>
                    </div>
    				
				</form>

			</div>
		</div>
	</div>
</div>
<div id="popup-chooser"></div>

<script type="text/javascript" src="<?php echo $this->config->item("js_url");?>/friendchooser/jquery.friendChooser-packed.js"></script>

<script type="text/javascript">
function loadGhostFriends(thisCaller){
    var divId = thisCaller.parent().parent()[0].id.toString();
    var name = '#'+divId+' span.js-assigned'; 
    var id = '#'+divId+' input[type=hidden][name="assigned_friend[]"]';    
    
    jQuery('#popup-chooser').friendChooser({
        display: "popup",
        max: 1,
        min:1,
        useCheckboxes : true,
        showSubmit: true,
        returnData: "all",
        showSelectAllCheckbox: true,
        showCounter: true,                            
        onOpen: function() {
            var thisParent = $(".js-add-user-chapter");
            var position = thisParent.offset();
            var thisLeft = position.left;
            var thisTop = position.top;
            $(this).css("top",thisTop+"px")
            $(this).css("left",thisLeft+"px")
            $(this).slideDown(); 
        },
        onClose: function() { 
            $(".default_add").prop('checked', true);
            $(this).hide();
        },
        lang: {
            title: "Select friends",
            requestTitle: "Select friends",
            requestMessage: "Select friends"
        },       
        onSubmit: function(users) { 
            if(users.length) {
                
                var thisID = "";
                var thisName = "";
                for(i in users) {
                    var thisID = users[i].id;                                    
                    var thisName = users[i].name;
                    //friends += thisID+","; 
                }   
                $(name).text(thisName);
                $(id).val(thisID);     
                console.log(thisID);                                                                              
            } else {
                console.log("no friends selected");                                        
            }
        }
    });    
}

    //assign friend to a chapter
    $(".js-add-user-chapter").on("click", function() {
        console.log('add user chapter');
        loadGhostFriends($(this));
        jQuery('#popup-chooser').friendChooser('open');
    });
                
	// Handling Modal
	$("#js-no-chapters").click( function (e) {
        $("#chapter_container").hide();
	});
	
	$("#js-with-chapters").click( function (e) {
        $("#chapter_container").show();
        console.log('click chapters');
    });
    
	$(".js-edit-assign").click( function (e) {
		e.preventDefault();
		$('#js-modal-common').modal();
		$('#js-modal-common .modal-title').html('Edit Assigned To');
		$('#js-modal-common .modal-body').html('Display list of friends here.');
	});
	$(".js-assign").click( function (e) {
		e.preventDefault();
		$('#js-modal-common').modal();
		$('#js-modal-common .modal-title').html('Assign Chapter To Friends');
		$('#js-modal-common .modal-body').html('Display list of friends here.');
	});

	var counter = 4;
	
	//Add chapters
	$('#js-add-chapters').click(function() {
		var newChapterBox = $(document.createElement('div')).attr({id:"chapterBox" + counter, 'class':"form-group"});
		newChapterBox.html('<label class="col-lg-2 control-label">Chapter Name:</label><div class="col-lg-7"><input type="text" class="form-control" name="chapter'+counter+'" id="chapter'+counter+'"></div><div class="col-lg-3"><a href="#">Assign chapter to friend</a></div>');
		newChapterBox.appendTo('#js-chapters');
		counter++; 
	});

	//Create Book
	$('#js-create-book-save').click(function(e) {
		e.preventDefault();

		if($.cookie('hardcover_fbid')==null) {
			alert('Session has been expired. Please Refresh the Hardcover App. ');	
			return false;
		}

		var bookName = $('#bookName').val();
        var data     = $('#form-book-creation').serialize();
        
		if(bookName != '') {
			$.ajax({
				type:'post',
				url 	: "/main/set_name_book_info",
				data: data,
				success : function(data){
				    var _obj = $.parseJSON(data);
		 			if(_obj.status == 200) {
						loadImageUploader();
						createBookCoverThumbnail();
					} else {
						$('#response').html('<div class="alert alert-warning"><p class="h5">Error creating book...'+_obj.status+'</p></div>');
					}
				}
			});
		} else {
			$('#response').html('<div class="alert alert-warning"><p class="h5">Please enter a book name..</p></div>');
		}
	});

	function loadImageUploader () {
		$.ajax({
	        type: "POST",
	        url: "../filter/filter_page",
	        success: function(data){
	        	var _obj = $.parseJSON(data);
	            $('#js-maincontent').html(_obj.data);
	            $('#js-maincontent').fadeIn('slow');
	            $('#js-dropdown .dropdown-menu > li').removeClass('open');
	            $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                $('#js-my-books').parent().addClass('active');
                $('#js-dropdown').addClass('open');
    			$('#js-upload-images').addClass('active');
	        }, error: function () {

	        }
    	});
	}

    //we create a thumbnail cover immediate when a book is created
	function createBookCoverThumbnail(){
		$.ajax({
			url : '/image_creator/create_book_thumbnail/'+$.cookie("hardcover_book_info_id")+"/196/144/0",
			data: '',
			type: 'POST',	 
			success : function(res){					
				var _obj = $.parseJSON(res);
			},error : function(res, err, errTxt) {
				console.log('Something went wrong during the saving...');				
			}, complete : function (){
				 
			}
		});
	}
	
</script>
<?php
foreach($booklist as $bookinfo){
    if($bookinfo->book_info_id == $booked_info->book_info_id){
        $total_pages = $bookinfo->total_pages;
        $url_ref = str_replace('https','https',$this->config->item('base_url')).'/books/'.$fb_username.'/'.strtolower(str_replace(' ','_',$bookinfo->book_name));        
    }
} 
$whoSee = "";
$whoCanSeeIds = "";
if($book_settings->who_can_see == "some_friends"){
    $count = 0;
    $ids = explode(",",$book_settings->select_can_see_ids);
    foreach($ids as $id){
        $count++;
        $whoCanSeeIds[] = $id;       
    }
    $whoSee = '<strong>'.$count.' friend(s)</strong>, <a href="#js-modal-common" id="js-who-can-view">View list</a>';    
} else{ 
    $whoVal = $book_settings->who_can_see;
    $whoSee = '<strong>'.$whoVal.'</strong>';    
}
$whoAdd = "";
$whoCanAddIds = "";
if($book_settings->who_can_contribute == "select"){
    $count = 0;
    $ids = explode(",",$book_settings->select_ids);
    foreach($ids as $id){
        $count++;
        $whoCanAddIds[] = $id;       
    }
    $whoAdd = '<strong>'.$count.' friend(s)</strong>, <a href="#js-modal-common" id="js-allowed-to-add">View list</a>';
} else { 
    $whoVal = $book_settings->who_can_contribute;
    $whoAdd = '<strong>'.$whoVal.'</strong>';
}
if($book_settings->collaborative)
    $isCollab = "Yes"; 
else
    $isCollab = "No";
    
$this->load->helper('url');
if(!isset($fbid))
    $fbid = $_COOKIE['hardcover_fbid']; 	
$album_new_contents = $this->main_model->get_bookpages_ready_to_share($fbid);
$hasAddedEle = "";
$ids_found = "";
if ($album_new_contents) {  
    for ($i = 0; $i < count($album_new_contents); $i++) {
        $album_new_contents[$i] = (array)$album_new_contents[$i];        
        $book_name = $album_new_contents[$i]['book_name'];
        $new_items = $album_new_contents[$i]['new_items'];
        $ids_found = $album_new_contents[$i]['ids_found'];
        $post_date = date("m/d/Y", strtotime($album_new_contents[$i]['fbdata_postedtime']));
        $bii = $album_new_contents[$i]['book_info_id'];
        $hasAddedEle = '<strong>'.$new_items.' friend(s)</strong>, <a href="#js-modal-common" id="js-who-added">View list</a>';
    }
} else {
    $hasAddedEle = "<strong>No photos added by others</strong>";
}  
?>

<div class="row">
	<div class="col-sm-12">
		<h3 class="h4"><?php echo $booked_info->book_name; ?></h3>
	</div>
</div>

<div class="row">
    <div style="display:none;"><?php print_r($dashboard_detils); ?></div>
	<div class="col-sm-9 col-lg-9">
		<div class="section section--main">
			<ul class="list">
				<li>Book name: <strong><?php echo $booked_info->book_name; ?></strong></li>
				<li>Creator: <strong><?php echo $fb_name; ?></strong></li>
				<li>Created on: <strong><?php echo $booked_info->created_date; ?></strong></li>
				<li>Pages: <strong><?php echo $total_pages; ?></strong></li>
			</ul>
			
			<ul class="list">
				<li>Last updated on: <strong><?php echo $booked_info->modify_date; ?></strong></li>
			</ul>
			
			<ul class="list">
				<li><strong>Viewing permission:</strong></li>
				<li>Who can view this book: <?=$whoSee; ?></li>
			</ul>

			<ul class="list">
				<li><strong>Collaboration:</strong></li>
				<li>Is this a collaborative book: <strong><?=$isCollab; ?></strong></li>
				<li>Who is allowed to add content to this book: <?=$whoAdd; ?> </li>
				<li>Who have added content to this book: <?=$hasAddedEle; ?></li>
			</ul>

			<ul class="list">
				<li>Ghost writer(s): <strong>2 friends</strong>, <a href="#js-modal-common" id="js-ghost-writers">View list</a></li>
			</ul>
		</div>
	</div>
	<div class="col-sm-3 col-lg-3">
		<ul class="list">
			<li><a href="javascript:void(0);" class="btn btn-lg btn-block btn-orange btn-editbook">Edit This Book</a></li>
			<li><a href="javascript:void(0);" class="btn btn-lg btn-block btn-orange" id="js-rearr">Rearrange Pages</a></li>
			<li><a href="javascript:void(0);" class="btn btn-lg btn-block btn-orange" id="js-designed-cover">Design Cover</a></li>
			<li><a href="javascript:void(0);" class="btn btn-lg btn-block btn-orange" id="previewer">Publish Book</a></li>
			<li><a href="javascript:void(0);" class="btn btn-lg btn-block btn-orange" id="save">Save</a></li>
			<li><a href="<?php echo $url_ref; ?>" target="_blank" class="btn btn-lg btn-block btn-orange">View Book</a></li>
		</ul>
	</div>
</div>

<script type="text/javascript">
// Handling Modal
$("#js-who-can-view").click( function (e) {
	e.preventDefault();
    var whoCanViewEle = '<ul>';
    friends_ids = <?php echo json_encode($whoCanSeeIds); ?>; 
    //FB.init({appId: '<?=$this->config->item('fb_appkey')?>', xfbml: true, cookie: true});
    FB.api('/me/friends', function(response) {
        if(response.data) {            
            $.each(response.data,function(index,friend) {
                var pic_url = "https://graph.facebook.com/"+friend.id+"/picture";
                var profile_url = "https://www.facebook.com/"+friend.id;                
                if($.inArray( friend.id, friends_ids ) != -1){
                    whoCanViewEle += '<li class="whoSeeItem"><a href="'+profile_url+'" target="_blank"><img src="'+pic_url+'" /><span>'+friend.name+'</span></a></li>'; 
                    //console.log(whoCanViewEle);    
                }                    
            });
            whoCanViewEle += "</ul>";
            $('#js-modal-common').modal();
            $('#js-modal-common .modal-title').html('Friends who can view this book');
            $('#js-modal-common .modal-body').html(whoCanViewEle);
        } else {
            console.log("unable to get friends");
        }
    });	
});
$("#js-allowed-to-add").click( function (e) {
	e.preventDefault();
    var whoCanCollabEle = '<ul>';
    friends_ids = <?php echo json_encode($whoCanAddIds); ?>;
    FB.api('/me/friends', function(response) {
        if(response.data) {            
            $.each(response.data,function(index,friend) {
                var pic_url = "https://graph.facebook.com/"+friend.id+"/picture";
                var profile_url = "https://www.facebook.com/"+friend.id;                
                if($.inArray( friend.id, friends_ids ) != -1){
                    whoCanCollabEle += '<li class="whoSeeItem"><a href="'+profile_url+'" target="_blank"><img src="'+pic_url+'" /><span>'+friend.name+'</span></a></li>'; 
                    //console.log(whoCanViewEle);    
                }                    
            });
            whoCanCollabEle += "</ul>";
            $('#js-modal-common').modal();
            $('#js-modal-common .modal-title').html('Who can collaborate');
            $('#js-modal-common .modal-body').html(whoCanCollabEle);
        } else {
            console.log("unable to get friends");
        }
    });
});
$("#js-who-added").click( function (e) {
	e.preventDefault();
    var whoCollabEle = '<ul>';
    friends_ids = <?php echo json_encode($ids_found); ?>;
    FB.api('/me/friends', function(response) {
        if(response.data) {            
            $.each(response.data,function(index,friend) {
                var pic_url = "https://graph.facebook.com/"+friend.id+"/picture";
                var profile_url = "https://www.facebook.com/"+friend.id;                
                if($.inArray( friend.id, friends_ids ) != -1){
                    whoCollabEle += '<li class="whoSeeItem"><a href="'+profile_url+'" target="_blank"><img src="'+pic_url+'" /><span>'+friend.name+'</span></a></li>'; 
                    //console.log(whoCanViewEle);    
                }                    
            });
            whoCollabEle += "</ul>";
            $('#js-modal-common').modal();
            $('#js-modal-common .modal-title').html('Who collaborated');
            $('#js-modal-common .modal-body').html(whoCollabEle);
        } else {
            console.log("unable to get friends");
        }
    });
});
$("#js-ghost-writers").click( function (e) {
	e.preventDefault();
	$('#js-modal-common').modal();
	$('#js-modal-common .modal-title').html('Ghost Writers');
	$('#js-modal-common .modal-body').html('Display list of Ghost Writers here.');
});
</script>
<script>
            $('a.btn-editbook').live('click',function(){
				    console.log('edit click');
					$('#app_loader').fadeIn();
					var album_id = $.cookie('hardcover_book_info_id');
                    console.log(album_id);
					$.cookie('hardcover_book_info_id',album_id);
					$.ajax({
						url     : '/main/edit_album',
						type    : 'post',
						cache   :  true,
						data 	: {'book_info_id':album_id},
						success: function(data){
                            var _obj = $.parseJSON(data);
                            $('#js-maincontent').html(_obj.data);
                            $('#js-maincontent').fadeIn('slow');
                            $('#js-dropdown .dropdown-menu > li').removeClass('open');
                            $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                            $('#js-my-books').parent().addClass('active');
                            $('#js-dropdown').addClass('open');
                            $('#js-editor').addClass('active');
                        }, error: function () {
                        }
					});	
					return false;
				});
    $('#js-rearr').live('click',function(e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "POST",
	        url: "/main/new_ui_rearrange",
	        success: function(data){
	        	var _obj = $.parseJSON(data);
	            $('#js-maincontent').html(_obj.data);
	            $('#js-maincontent').fadeIn('slow');
                $('#js-dropdown .dropdown-menu > li').removeClass('open');
                $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                $('#js-my-books').parent().addClass('active');
                $('#js-dropdown').addClass('open');
                $('#js-rearrange').addClass('active');
	        }, error: function () {

	        }
    	});
	});
    $('#js-designed-cover').live('click',function(e) {
		e.preventDefault();

		$('#js-maincontent').fadeOut('slow');

		$.ajax({
	        type: "POST",
	        url: "/cover/design",
	        success: function(data){
	        	var _obj = $.parseJSON(data);
	            $('#js-maincontent').html(_obj.data);
	            $('#js-maincontent').fadeIn('slow');
                $('#js-dropdown .dropdown-menu > li').removeClass('open');
                $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                $('#js-my-books').parent().addClass('active');
                $('#js-dropdown').addClass('open');
                $('#js-design-cover').addClass('active');
	        }, error: function () {

	        }
    	});
	});
    $("#previewer").live('click',function(){
		var WinId = window.open('edit_album/preview/', 'edit_album_preview', 'width=' + screen.width +',height='+screen.height);		
	});			
 </script>
<?php 
	//dennis:load this helper in the controller section
	$this->load->helper('url');
	
 	if(!isset($fbid)) 		
 		$fbid = $_COOKIE['hardcover_fbid'];
 		
	//marlo edit starts here 12/28/2012
	
 	//Dennis: commented this code;  Views should only be views
	//$deleted_albums = $this->Delete->getDeletedAlbums($fbid);
	//$photos_share_sent = $this->GE->getShareSentInfo($fbid);
	//$albums_deleted = (array)$deleted_albums;
	
	//function deletedBook($parents, $searched) {
	//	if (empty($searched) || empty($parents)) {
	//		return false;
	//	}
	//	 
	//	foreach ($parents as $key => $value) {
	//		$exists = true;
	//		foreach ($searched as $skey => $svalue) {
	//			$parents[$key] = (array)$parents[$key];
	//			$exists = ($exists && isset($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
	//		}
	//		if ($exists) return $key;
	//	}
	//	return false;
	//}
	//marlo edit ends here 12/28/2012
    
//josh mod to add collabs
if ($album_new_contents) {
    $book_collab = "";
    for ($i = 0; $i < count($album_new_contents); $i++) {
        $album_new_contents[$i] = (array)$album_new_contents[$i];
        //if (deletedBook($albums_deleted, array("book_info_id" => $album_new_contents[$i]["book_info_id"])) === FALSE) {
            $book_name = $album_new_contents[$i]['book_name'];
            $new_items = $album_new_contents[$i]['new_items'];
            $post_date = date("m/d/Y", strtotime($album_new_contents[$i]['fbdata_postedtime']));
            $bii = $album_new_contents[$i]['book_info_id'];
            $book_collab[$book_name]["link"] = '<a rel="prettyPhoto[iframes]" href="'.$this->config->item('base_url').'/main/new_album_contents_page/'. $bii .'?iframe=true&width=825&height=500" rel="#overlay" title="Click to Approve Photos Added by Others in your album '. $book_name .'">'. $book_name .'</a>';
            if($new_items)
                $book_collab[$book_name]["count"] = $new_items;
            else
                $book_collab[$book_name]["count"] = 0;
        //}
    }
}       
?>
<script type="text/javascript">
function pretty_close(){
	$.prettyPhoto.close(); return false;  
}
$(document).ready(function(){
    $('a[rel^="prettyPhoto"]').prettyPhoto({
	theme:'facebook',
	default_width: 600,
		default_height: 500,
		social_tools: false
        // any configuration options as per the online documentation.
    });
            jQuery('.fb-share').on("click",function(event){
                var parentUL = $(this).parent().parent().parent();                
                var page_number = parentUL.attr('page_number');                
                event.preventDefault();
                event.stopPropagation();
                var page_image = getCurrentImg(page_number);                
                var testThis = page_image.split("src=");    
                console.log(testThis);          
                if(testThis[0] != page_image){
                    page_image = testThis;                
                    page_image = page_image[1].split("&h=");
                    page_image = page_image[0];    
                } 
                var title = parentUL.attr("title");
                var desc = "A HardCover book";
                var linked = parentUL.attr("u_url");
                    app_id = $("#pp_header").attr("app_id");	
                    FB.init({appId: app_id, xfbml: true, cookie: true});
                    FB.ui({
                        method: 'feed',
                        name: title,
                        link: linked,
                        picture: page_image,
                        description: desc
                    },
                        function(response) {                            
                            if (response && response.post_id) {
                                alert('Post was published.');
                            } else {
                                alert('Post was not published.');
                            }
                        }
                    );                              
            }); 
            jQuery('.twitter-share').click(function(event){
                var parentUL = $(this).parent().parent().parent();
                var page_number = parentUL.attr('page_number');
                event.preventDefault();
                event.stopPropagation();                
                var title = parentUL.attr("title");
                var message = "A HardCover book";
                var linked = parentUL.attr("u_url");
                var link = 'http://twitter.com/intent/tweet?url='+getCurrentImg(page_number)+'&text='+title+'. '+message+' '+encodeURI(getCurrentImg(page_number))+' '+encodeURI(linked)+'&hashtags=hardcover';                                   
                    newWindow = window.open(link,'_blank','width=700,height=260'); 
                    newWindow.focus();
            });
            jQuery('.pinterest-share').click(function(event){
                var parentUL = $(this).parent().parent().parent();
                var page_number = parentUL.attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var title = parentUL.attr("title");
                var message = "A HardCover book";
                var linked = parentUL.attr("u_url");
                var link = '//www.pinterest.com/pin/create/button/?url='+encodeURI(linked)+'&media='+encodeURI(getCurrentImg(page_number))+'&description='+title+'. '+message;
                    newWindow = window.open(link,'_blank','width=700,height=260');                    
                    newWindow.focus();
            }); 
            jQuery('.email-share').click(function(event){
                var parentUL = $(this).parent().parent().parent();
                var page_number = parentUL.attr('page_number');
                event.preventDefault();
                event.stopPropagation();
                var linked = parentUL.attr("u_url");
                var link = 'mailto:?subject=HardCover&amp;body=Check out my life in HardCover.'+encodeURI(linked);                
                    newWindow = window.open(link,'_parent','width=700,height=260');                    
                    newWindow.focus();
            }); 
            function getCurrentImg(page_number) {
                return $(".bookImage"+page_number).attr('src');
            }
});
</script>
<style>
.media--booklisting img {
    width: 200px;
}
.shareButtons img{
    width: 20px;
}
</style>
<div class="row" user_id="<?=$fbid ?>">
	<div class="col-sm-6">
		<a href="#" id="js-create-book" class="btn btn-orange">Create New Book</a>
	</div>
	<div class="col-sm-6"></div>
</div>
<div class="row">
    <img src="/images/no-book-holder.jpg" width="100%" />	
    <script src="<?php echo $this->config->item("js_url"); ?>/book_summarylist.js"></script>
 	<script>
 		$('#js-create-book').click(function (e) {
			e.preventDefault();
            var album_id = $.cookie('hardcover_book_info_id');
            $('#js-maincontent').fadeOut('slow');
            $.ajax({
                type: "POST",
                url: "/main/new_names_chapters",
                data 	: {},
                success: function(data){
                    var _obj = $.parseJSON(data);                            
                    $('#js-maincontent').html(_obj.data);
                    $('#js-maincontent').fadeIn('slow');
                    $('.dropdown-menu').show();
                    $('#js-dropdown .dropdown-menu > li').removeClass('open');
                    $('#js-dropdown .dropdown-menu > li > a').removeClass('active');
                    $('#js-my-books').parent().addClass('active');
                    $('#js-my-books').parent().siblings().removeClass('active');
                    $('#js-dropdown').addClass('open');
                    $('#js-name-chapters').addClass('active');
                }, error: function () {

                }
            });
		});
    </script>
</div>
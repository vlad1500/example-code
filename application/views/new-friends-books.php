<script>
$(document).ready(function(){
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
<div class="row">
	<div class="col-sm-6">
		<div class="box">
            <div style="display:none;"><?php //print_r($friends_books) ?></div>
            <?php
                    $book_count = 0;
                    $friends_books_elem = "";
					foreach($friends_books as $v){
						$last_save = date('m/d/Y',strtotime($v->modify_date));
						$bkid = str_replace(" ", "", $v->book_name);
						$unique_href_file = $this->config->item('base_url').'/books/'.$v->fb_username.'/'.$bkid;
						$unique_dir_file = $this->config->item('unique_url').'/'.$v->fb_username.'_'.$bkid.'.htm';

						if (file_exists($unique_dir_file))
							$unique_url_link = '<a target="_blank" href="'.$unique_href_file.'" >Unique URL</a>';
						else
				            $unique_url_link = 'no unique url';							
						$url_ref = $this->config->item('base_url').'/books/'.$v->fb_username.'/'.strtolower(str_replace(' ','_',$v->book_name));
                        if($book_count == 0)$first = 'first';
                        else $first = '';
                        $front_covered = $v->booked_info["data"]->front_cover_page;
                        $front_covered = $v->front_cover_location;
                        if($front_covered == "" || $front_covered=='NULL')$cover_url = $this->config->item('image_url')."/200x220";
                        else $cover_url = "/timthumb.php?src=$front_covered&h=220&w=200&zc=2&time=" . time();                                
						$friends_books_elem .= '
                <li class="box__item '.$first.'">
					<div class="media media--booklisting">
					  <a target="_blank" href="'.$url_ref.'" id="'. $v->book_info_id .'" class="pull-left">
					    <img class="media-object bookImage'.$book_count.'" src="'.$cover_url.'" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="h4 media-heading">'.$v->book_name.'</h3>
					    <ul class="list list-inline strong clearfix">					    	
					    	<li><a target="_blank" href="'.$url_ref.'">View</a></li>					    	
					    </ul>

					    <ul class="list">
					    	<li>'. $v->total_pages .' page(s)</li>
					    	<li>Last Updated: '. $last_save .'</li>					    	
					    	<li></li>
					    </ul>

					    <ul class="list list-inline clearfix shareButtons" page_number="'.$book_count.'" title="'.$v->book_name.'" u_url="'.$url_ref.'">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<span class="st_facebook" displaytext=""><a href="javascript:void(0);" class="fb-share"><img src="'.$this->config->item('base_url').'/images/facebook.png" alt="Facebook"></a></span>
					    	</li>
					    	<li>
					    	    <span class="st_twitter" displaytext=""><a href="javascript:void(0);" class="twitter-share"><img src="'.$this->config->item('base_url').'/images/twitter.png" alt="Twitter"></a></span>  	
					    	</li>
                            <li>
                                <span class="st_pinterest" displaytext=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="'.$this->config->item('base_url').'/images/pinterest.png" alt="Pinterest"></a></span>
                            </li>
                            <li>
                                <span class="st_googleplus" displaytext=""></span><span class="st_email" displaytext=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="'.$this->config->item('base_url').'/images/mail.png" alt="mail"></a></span>
                            </li>
					    </ul>

					  </div>
					</div>
				</li>'; 
                        $book_count++;                               
					}
			?>
			<h3 class="h5 box__title">Friends Books (<?php echo $book_count; ?>)</h3>
			<ul class="box__content">
				<?php echo $friends_books_elem; ?>
			</ul>
		</div>
	</div><!--End col-lg-6 -->
	<div class="col-sm-6">
		<div class="box">
            <?php
                    $book_count = 0;
                    $friends_books_elem = "";
					foreach($get_friends_collab as $v){
						$last_save = date('m/d/Y',strtotime($v->modify_date));
						$bkid = str_replace(" ", "", $v->book_name);
						$unique_href_file = $this->config->item('base_url').'/books/'.$v->fb_username.'/'.$bkid;
						$unique_dir_file = $this->config->item('unique_url').'/'.$v->fb_username.'_'.$bkid.'.htm';

						if (file_exists($unique_dir_file))
							$unique_url_link = '<a target="_blank" href="'.$unique_href_file.'" >Unique URL</a>';
						else
				            $unique_url_link = 'no unique url';							
						$url_ref = $this->config->item('base_url').'/books/'.$v->fb_username.'/'.strtolower(str_replace(' ','_',$v->book_name));
                        if($book_count == 0)$first = 'first';
                        else $first = '';
                        $front_covered = $v->booked_info["data"]->front_cover_page;
                        $front_covered = $v->front_cover_location;
                        if($front_covered == "" || $front_covered=='NULL')$cover_url = $this->config->item('image_url')."/200x220";
                        else $cover_url = "/timthumb.php?src=$front_covered&h=220&w=200&zc=2&time=" . time();                                
						$friends_books_elem .= '
                <li class="box__item '.$first.'">
					<div class="media media--booklisting">
					  <a target="_blank" href="'.$url_ref.'" id="'. $v->book_info_id .'" class="pull-left">
					    <img class="media-object bookImage'.$book_count.'" src="'.$cover_url.'" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="h4 media-heading">'.$v->book_name.'</h3>
					    <ul class="list list-inline strong clearfix">					    	
					    	<li><a target="_blank" href="'.$url_ref.'">View</a></li>					    	
					    </ul>

					    <ul class="list">
					    	<li>'. $v->total_pages .' page(s)</li>
					    	<li>Last Updated: '. $last_save .'</li>					    	
					    	<li></li>
					    </ul>

					    <ul class="list list-inline clearfix shareButtons" page_number="'.$book_count.'" title="'.$v->book_name.'" u_url="'.$url_ref.'">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<span class="st_facebook" displaytext=""><a href="javascript:void(0);" class="fb-share"><img src="'.$this->config->item('base_url').'/images/facebook.png" alt="Facebook"></a></span>
					    	</li>
					    	<li>
					    	    <span class="st_twitter" displaytext=""><a href="javascript:void(0);" class="twitter-share"><img src="'.$this->config->item('base_url').'/images/twitter.png" alt="Twitter"></a></span>  	
					    	</li>
                            <li>
                                <span class="st_pinterest" displaytext=""><a target="_blank" href="javascript:void(0);" class="pinterest-share"><img src="'.$this->config->item('base_url').'/images/pinterest.png" alt="Pinterest"></a></span>
                            </li>
                            <li>
                                <span class="st_googleplus" displaytext=""></span><span class="st_email" displaytext=""><a target="_blank" href="javascript:void(0);" class="email-share"><img src="'.$this->config->item('base_url').'/images/mail.png" alt="mail"></a></span>
                            </li>
					    </ul>

					  </div>
					</div>
				</li>'; 
                        $book_count++;                               
					}
			?>
			<h3 class="h5 box__title">Friends Collaborative Books (<?php echo $book_count; ?>)</h3>
			<ul class="box__content">
				<?php echo $friends_books_elem; ?>
			</ul>			
		</div>
	</div><!--End col-lg-6 -->

</div>
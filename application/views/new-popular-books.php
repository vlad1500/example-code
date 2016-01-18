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
	<div class="col-md-6 col-lg-6">
		<div class="box">
			<h3 class="h5 box__title">Popular Books (26)</h3>
			<ul class="box__content">
				<li class="box__item first">
					<div class="media media--booklisting">
					  <a class="pull-left" href="#">
					    <img class="media-object" src=" <?=$this->config->item('image_url'); ?>/200x220" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="t5 media-heading">The cookie book of wisdom</h3>
					    <ul class="list list-inline strong clearfix">
					    	<li class="first-child"><a href="#">Edit</a></li>
					    	<li><a href="#">View</a></li>
					    	<li><a href="#">Delete</a></li>
					    </ul>

					    <ul class="list">
					    	<li>325 pages</li>
					    	<li>Last Updated: 07/08/2013</li>
					    	<li><a href="#">26 Collaborators</a></li>
					    	<li><a href="#">Content Waiting Approval</a></li>
					    </ul>

					    <ul class="list list-inline clearfix">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<div class="social-icons img-circle">
					    			<i class="icon-facebook"></i>
					    		</div>
					    	</li>
					    	<li>
					    		<div class="social-icons img-circle">
					    			<i class="icon-facebook"></i>
					    		</div>
					    	</li>

					    </ul>

					  </div>
					</div>
				</li>
				
			</ul>
		</div>
	</div><!--End col-lg-6 -->
	<div class="col-md-6 col-lg-6">
		<div class="box">
			<h3 class="h5 box__title">Popular Collaborative Books (1,154)</h3>
			<ul class="box__content">
				<li class="box__item first">
					<div class="media media--booklisting">
					  <a class="pull-left" href="#">
					    <img class="media-object" src=" <?=$this->config->item('image_url'); ?>/200x220" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="t5 media-heading">The cookie book of wisdom</h3>
					    <ul class="list list-inline strong clearfix">
					    	<li class="first-child"><a href="#">Edit</a></li>
					    	<li><a href="#">View</a></li>
					    	<li><a href="#">Delete</a></li>
					    </ul>

					    <ul class="list">
					    	<li>325 pages</li>
					    	<li>Last Updated: 07/08/2013</li>
					    	<li><a href="#">26 Collaborators</a></li>
					    	<li><a href="#">Content Waiting Approval</a></li>
					    </ul>

					    <ul class="list list-inline clearfix">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<div class="social-icons img-circle">
					    			<i class="icon-facebook"></i>
					    		</div>
					    	</li>
					    	<li>
					    		<div class="social-icons img-circle">
					    			<i class="icon-facebook"></i>
					    		</div>
					    	</li>

					    </ul>

					  </div>
					</div>
				</li>
				<li class="box__item">
					<div class="media media--booklisting">
					  <a class="pull-left" href="#">
					    <img class="media-object" src=" <?=$this->config->item('image_url'); ?>/200x220" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="t5 media-heading">The cookie book of wisdom</h3>
					    <ul class="list list-inline strong clearfix">
					    	<li class="first-child"><a href="#">Edit</a></li>
					    	<li><a href="#">View</a></li>
					    	<li><a href="#">Delete</a></li>
					    </ul>

					    <ul class="list">
					    	<li>325 pages</li>
					    	<li>Last Updated: 07/08/2013</li>
					    	<li><a href="#">26 Collaborators</a></li>
					    	<li><a href="#">Content Waiting Approval</a></li>
					    </ul>

					    <ul class="list list-inline clearfix">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<div class="social-icons img-circle">
					    			<i class="icon-facebook"></i>
					    		</div>
					    	</li>
					    	<li>
					    		<div class="social-icons img-circle">
					    			<i class="icon-facebook"></i>
					    		</div>
					    	</li>

					    </ul>

					  </div>
					</div>
				</li>
				<li class="box__item">
					<div class="media media--booklisting">
					  <a class="pull-left" href="#">
					    <img class="media-object" src=" <?=$this->config->item('image_url'); ?>/200x220" alt="" class="img-responsive">
					  </a>
					  <div class="media-body">
					    <h3 class="t5 media-heading">The cookie book of wisdom</h3>
					    <ul class="list list-inline strong clearfix">
					    	<li class="first-child"><a href="#">Edit</a></li>
					    	<li><a href="#">View</a></li>
					    	<li><a href="#">Delete</a></li>
					    </ul>

					    <ul class="list">
					    	<li>325 pages</li>
					    	<li>Last Updated: 07/08/2013</li>
					    	<li><a href="#">26 Collaborators</a></li>
					    	<li><a href="#">Content Waiting Approval</a></li>
					    </ul>

					    <ul class="list list-inline clearfix">
					    	<li class="first-child">Share</li>
					    	<li>
					    		<div class="social-icons img-circle">
					    			<i class="icon-facebook"></i>
					    		</div>
					    	</li>
					    	<li>
					    		<div class="social-icons img-circle">
					    			<i class="icon-facebook"></i>
					    		</div>
					    	</li>

					    </ul>

					  </div>
					</div>
				</li>
			</ul>
		</div>
	</div><!--End col-lg-6 -->

</div>



					
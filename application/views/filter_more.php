<!--home-->     
    <div id="my_home" class="tab2_content"></div>   
<!--Content for the Filter Page-->

	<div id="filter_content">
		<!--fb_data-->
		<div id="fb_data" class="tab2_content">
            <div id="wrapper">     
                    <div class="accordionButton">
                        <h3>Photos <?php echo $total_objects; ?></h3>                        
                        <input type="button" value="Delete Photos" id="del_photos_button" class="float_right" style="margin-top:-20px;"/>
                    </div>
                    <!--- start of accordion 1 --->
                    <div class="accordionContent"> 
                    		<div>
                            	<select>
                                	<option>Friends Data</option>
                                </select>
                            </div>
                    		<ul id="del_images" style="width:auto;height:auto;overflow:hidden;box-sizing:border-box;" >
								<?php								
									$cnt = 0;
									$isnew = true;
                                    foreach ($book_pages as $book_page){
										$cnt += 1;
                                        $page = $book_page->fbdata;
                                        $fb_comments = $book_page->comment;
                                        $page_num = $book_page->page_num;
										if ($isnew){ 
											echo '<li style="margin: 5px; padding:5px; box-sizing:border-box; overflow:hidden; width:auto; height:auto; ">';
										}
										if ($cnt <= 7 ){											
											if ($page->source  == ''){ 
												$msg = '<p style="width:auto;padding:2px;white-space:normal;font-size:10px;">'.$page->message.'</p>';			
											}else{	
												$msg = '<img src="'.$page->source.'" width="100%">';
											}
										    echo '<div id="div-'.$book_page->fb_dataid.'" class="float_left img_con" style="margin:5px;padding:10px;height:auto;background:#F7F7F7;border:1px solid #EEE;"><div style="width:100px;height:100px;overflow:hidden;margin-bottom:7px;">'.$msg.'</div><p>Comments <span style="color:#F37400;font-weight:bold;">'.count($fb_comments).'</span></p><p><input type="checkbox" id="'.$book_page->fb_dataid.'"/><span>Delete</span></p></div>';	
											$isnew = false;
											if ( $cnt == 7 ){
												 $cnt = 0;
												 $isnew = true;
												 
											}												
											if ($isnew){ 
												echo '</li>';
											}
										}	
																			
                                    }
                                ?>
							</ul>
                    </div>
					<div class="accordionButton" style="background-image:none;">
                        <div class="paginate">
                            <?php 
                                $page_ = ceil($total_objects / 40);
                                if ($page>1){
                                    $pagination_prev = '
                                        <div id="prev_" class="paginate-front-cover">Prev</div>
                                        <ul>'; 
                                                                    
								}	
								$ppage = 1;
								for ($i = 1; $i <= $page_ ; $i++){									
                                	$pagination_numbers .=  '<li><div id="'.$ppage.'" class="paginate-left-page">'.$i.'</div></li>';
									$ppage += 40;
                                }	
                                if ($page>1){							
                                    $pagination_next ='</ul>           
                                        <div id="next_" class="paginate-back-cover" style="float:left;">Next</div>';
                                }   
                                echo $pagination_prev . $pagination_numbers . $pagination_next;                          
                             ?>
                             <input type="button" value="Done" id="done_button" class="float_right"/>
                             <input type="button" value="Delete Photos" id="del_photos_button" class="float_right"/>
                        </div> 
                    </div>
           </div> 
       </div>              
	</div>
<script type="text/javascript">
	$(document).ready(function(e) {
		
		var pageid;

		$.cookie('pageid_') === undefined || pageid == null ? pageid = 1 : pageid;
		$('.paginate ul li div#'+pageid).addClass('active');
		$('.img_con').css({'opacity':.6});
		$('.img_con').each(function(i,el){
			$(this).mouseenter(function(e) {
                $(this).animate({ opacity : 1 },'slow','linear');
            }).mouseleave(function(e) {
                $(this).animate({ opacity : .6 },'slow','linear');
            })
		});
		
		if ( $.cookie('storedObj') ){
			console.log($.cookie('storedObj'));
			var _obj_deleted = $.cookie('storedObj').split(',');													
			$.each(_obj_deleted,function(i,el){						
				$('input#'+el).attr('checked','checked');
			});//end of each
		}
		
		$('input:checkbox').each(function(i,el){			
			$(this).live('click',function(){
				//var storageFiles = JSON.parse(localStorage.getItem("storageFiles")) || {};									
				if ( $(this).is(':checked') ) {										

					if ($.cookie('storedObj') === undefined || $.cookie('storedObj') === null ) {
						//storageFiles.delObj = $(this).attr('id');	
						$.cookie('storedObj',$(this).attr('id'));						
					}else{
						//storageFiles.delObj = storageFiles.delObj + ';' + $(this).attr('id');	
						$.cookie('storedObj',$.cookie('storedObj') +','+ $(this).attr('id'));
						var _new = $.cookie('storedObj').replace(',,',',');						
						$.cookie('storedObj',null);
						$.cookie('storedObj',_new);						
					}		
				}else{
					var _thisID = $(this).attr('id');
					
					if ( $.cookie('storedObj') !== null || $.cookie('storedObj') !== undefined ){
						var _obj_deleted = $.cookie('storedObj').split(',');									
					}
					
					$.each(_obj_deleted,function(i,el){						
						if ( _thisID == el ) {																				
							var _str = $.cookie('storedObj');
							var _new = $.trim(_str.replace(el,''));
							_new = _new.replace(',,',',');
							console.log(_new);
							$.cookie('storedObj',null);
							$.cookie('storedObj',_new);							
						}
						if ($.cookie('storedObj') == ',' || $.cookie('storedObj') == ',,') { $.cookie('storedObj',null); }
					});//end of each
					
				}//End of if :checked
			});
		});
		
		
		$('.paginate ul li div').each(function(i, el) {
            $(this).click(function(){
				var _id = $(this).attr('id');
				var _book_info = <?=$book_info_id;?>;				
				$('#main_inner').animate({opacity : .5},'slow','linear',function(){
					$(this).append('<div class="ajax_loader"></div>');				
				});
				$.cookie('pageid_',_id);
				$.ajax({
					cache   : false,
					url     : 'filter/filter_more',
					type    : 'post',
					data	: { 'book_info_id' : _book_info , 'page_num' : _id },
					success : function(res){
						var _obj_ = $.parseJSON(res);	
						$('#main_inner').animate({opacity : 1},'slow','linear',function(){
							$('.ajax_loader').remove();				
						});					
						$('#main_inner').html(_obj_.data);			
						$('#fb_data').fadeIn(); //Fade in the active ID content	
						$('.paginate ul li div').removeClass('active');			
						$('.paginate ul li div#'+_id).addClass('active');						
					}			
					
				});		
			});
        });
        


        		
		$('#prev_').die('click').live('click',function(e){
			var _id = $('.paginate ul li div.active').attr('id');
				_id -= 40;
				_id <= 1 ? _id = 1 : _id = Math.floor(_id - 40);

				var _book_info = <?=$book_info_id;?>;				
				$('#main_inner').animate({opacity : .5},'slow','linear',function(){
					$(this).append('<div class="ajax_loader"></div>');				
				});			
				$.cookie('pageid_',_id);
				$.ajax({
					cache   : false,
					url     : 'filter/filter_more',
					type    : 'post',
					data	: { 'book_info_id' : _book_info , 'page_num' : _id},
					success : function(res){
						var _obj_ = $.parseJSON(res);						
						$('#main_inner').animate({opacity : 1},'slow','linear',function(){
							$('.ajax_loader').remove();				
						});			
						$('#main_inner').html(_obj_.data);			
						$('#fb_data').fadeIn(); //Fade in the active ID content	
						$('.paginate ul li div').removeClass('active');			
						$('.paginate ul li div#'+_id).addClass('active');						
					}			
					
				});		
			return false;
		});
		
		$('#next_').die('click').live('click',function(e){
			var _id = $('.paginate ul li div.active').attr('id');
			var total_obj = <?=$total_objects;?>;
			_id >= total_obj ? _id = total_obj : _id = parseInt(_id) + parseInt("40");
				var _book_info = <?=$book_info_id;?>;				
				$('#main_inner').animate({opacity : .5},'slow','linear',function(){
					$(this).append('<div class="ajax_loader"></div>');				
				});			
				$.cookie('pageid_',_id);		
				$.ajax({
					cache   : false,
					url     : 'filter/filter_more',
					type    : 'post',
					data	: { 'book_info_id' : _book_info , 'page_num' : _id},
					success : function(res){
						var _obj_ = $.parseJSON(res);						
						$('#main_inner').animate({opacity : 1},'slow','linear',function(){
							$('.ajax_loader').remove();				
						});				
						$('#main_inner').html(_obj_.data);			
						$('#fb_data').fadeIn(); //Fade in the active ID content	
						$('.paginate ul li div').removeClass('active');			
						$('.paginate ul li div#'+_id).addClass('active');
					}								
				});		
				
				return false;
		 });		
	});

</script>

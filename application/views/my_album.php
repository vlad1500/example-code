

<!--home-->     
    <div id="my_home" class="tab2_content"></div>   
<!--edit-->        
    <div id="my_edit" class="tab2_content"></div><!--End of my_edit-->
<!--albums-->        
    <div id="my_album" class="tab2_content">
                      
			<?php 
			$arrayLen = count($booklist);
			$arr_book_type[0] = array('first_20_pages'=>4.95,'per_additional_page'=>0.50);
			$arr_book_type[1] = array('first_60_pages'=>14.95,'per_additional_page'=>0.50);
			$arr_book_type[2] = array('first_100_pages'=>24.95,'per_additional_page'=>1);
			$arr_book_type[3] = array('first_150_pages'=>34.95,'per_additional_page'=>1);
			
			
			for ($x=0;$x<$arrayLen;$x++) {
				//echo $booklist[$x]->book_info_id . " -- " . $ctr . " / "; 
				
					//Computation
					$curr_book_type = 0; //change this when the book_types are already available
					$book_type = $arr_book_type[$curr_book_type];
					
					$default_pages = 20; // change this into the data coming the table or db
					$additional_pages = 0;
					$total_price = 0;
					$price_additional_pages = 0;
					if ($booklist[$x]->total_pages > 0 ) {
						$total_price = $book_type["first_20_pages"];
						if ($booklist[$x]->total_pages >= $default_pages) {
							$additional_pages = $booklist[$x]->total_pages - $default_pages;
							$price_additional_pages = $additional_pages * $book_type["per_additional_page"];
						}
						$total_price = $total_price + $price_additional_pages;
					}
					/*$new_class = "float_left";
					if ($x % 2 != 0) {
						$new_class = "float_right";
					}*/
					echo '
							<div class="my_album_list">
								<h3>Book name: '.$booklist[$x]->book_name.'</h3>
								<div class="album_img">	
									<img src="https://dev.hardcover.me/images/slide2/HardCover_exampleBook.png" width="100%">
									<p><input type="button" id="print_book" value="Print Book"></p>
									<p><input type="button" id="group_gift" value="Group Gift"></p>                 
								</div>    
								<div class="album_txt">
									<table>
										<tr>
											<td class="label">Pages</td>
											<td>'.(!$booklist[$x]->total_pages ? '0' : $booklist[$x]->total_pages).'</td>
										</tr>
										<tr>
											<td class="label">('.$booklist[$x]->orientation.')</td>
											<td>'.(!$booklist[$x]->width ? '0' : $booklist[$x]->width).' x '.(!$booklist[$x]->height ? '0' : $booklist[$x]->height).'</td>
										</tr>         	
										<tr>
											<td class="label">Starts at</td>
											<td>$'.(!$booklist[$x]->total_pages ? '0' : $book_type["first_20_pages"]).'</td>
										</tr>
										<tr>
											<td class="label">Additional page</td>
											<td>$'.(!$booklist[$x]->total_pages ? '0' : $book_type["per_additional_page"]).' each</td>
										</tr>
										<tr>
											<td class="label">Total Price</td>
											<td>$'.$total_price.'</td>
										</tr>
										<tr>
											<td class="label">Last save</td>
											<td>'.(!$booklist[$x]->total_pages ? '?' : date("Y/m/d",$booklist[$x]->modify_date)).'</td>
										</tr>        
										<tr>
											<td class="label">New Comments</td>
											<td>'.(!$booklist[$x]->total_newcomments ? '0' : $booklist[$x]->total_newcomments).'</td>
										</tr>
									</table>
									<table>
										<tr>
											<td class="label"><a href="#" id="save" rel="'.$booklist[$x]->book_info_id.'" name="save_'.$booklist[$x]->book_info_id.'">Save PDF</a></td>
										</tr>
										<tr>
											<td class="label"><a href="#" id="edit" name="edit_'.$booklist[$x]->book_info_id.'">Edit</a></td>
										</tr>
										<tr>
											<td class="label"><a href="#" id="preview" name="preview_'.$booklist[$x]->book_info_id.'">Preview</a></td>
										</tr>
									</table>       
								</div>
							</div><!--end .my_album_list -->
					';
					
			}
			?>
			
</div><!--End of my_album--> 

    <script type="text/javascript">
	$(function(){
		$('div input#print_book').each(function(){
			$(this).click(function(event){
				$(this).myModal({printBook:true});		
			});		
		});
		
		$('div input#group_gift').each(function(){
			$(this).click(function(event){
				$(this).myModal({friendSelector:true});		
			});		
		});
		
		$('td a#save').each(function(){
			$(this).click(function(event){
				var _book_info_id = $(this).attr('rel');
				$('#app_loader').fadeIn('slow');			
				$.ajax({
						url		: 	'main/save_pdf',
						type	:	'post',
						data	:	{'book_info_id' : _book_info_id},
						success	:	function(res){
											var _obj = $.parseJSON(res);	
											$('#app_loader').fadeOut('slow');							
									}
				});
				event.preventDefault();
			});
		});
		
		$('td a#edit').each(function(){
			$(this).click(function(event){
				var el_name = this.name;
				var n = el_name.indexOf("_"); 
				_book_info_id = el_name.substring(n+1, el_name.length);
				
				$('#app_loader').fadeIn('slow');			
					$.ajax({
						url		: 	'main/edit_album',
						type	:	'post',
						data	:	{'book_info_id' : _book_info_id},
						success	:	function(res){
											var _obj = $.parseJSON(res);	
											$('#app_loader').fadeOut('slow');							
											$('#main_inner').html(_obj.data);
											$('#book_cover').addClass('hideDiv').fadeOut();
											$('#book_content').fadeIn();									
											$('#my_edit').fadeIn('slow');
											$('div#pagination ul li').filter(':first').addClass('current').next().addClass('current');
											$('p#_curr').removeClass('_current');
									}
				});
			});
		});
		
		$('td a#preview').each(function(){
			$(this).click(function(event){
				var el_name = this.name;
				var n = el_name.indexOf("_"); 
				_book_info_id = el_name.substring(n+1, el_name.length);
					//var windowSizeArray = [ "width=200,height=200",
                    //                "width=300,height=400,scrollbars=yes" ];
					//var _bookid = $.cookie('hardcover_book_info_id');
                    var url = 'http://hardcover.shoppingthing.com/books/'+_book_info_id+'?page_num=1';
                    var windowName = "popUp";
                    //var windowSize = windowSizeArray[$(this).attr("rel")];
 
                    alert(url);
                    //window.open(url, windowName);
 
                    event.preventDefault();				
			});
		});
					
	});
	</script>
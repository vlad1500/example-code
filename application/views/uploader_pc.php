			<table class="options">
				<tr>
					<td>
						<div id="uploader_div">

						</div>
					</td>
				</tr>
			</table>
		<script type="text/javascript">
		$('#uploader_div').ajaxupload({
			url:'/upload.php',
			remotePath:'uploads/<?php echo $fb_id?>/',
			allowExt:['jpg', 'gif', 'png'],
			thumbPath: 'uploads/thumbnail/<?php echo $fb_id?>/',
			thumbHeight: 150,
			thumbWidth: 150,
			thumbPostfix: '',
			data : {
				db_user : '<?php echo $db_user?>',
				db_pass : '<?php echo $db_pass?>',
				db_name: '<?php echo $db_name?>',
				fb_id: '<?php echo $fb_id?>',
				book_info_id: '<?php echo $bii?>',
				fb_id_owner: '<?php echo $fb_id_owner?>',
				friend_name: '<?php echo $friend_name?>'
			},
			success: function () {
				alert(json.response);
			}
		});
		</script>
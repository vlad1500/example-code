<br/><br/>
<form method="POST" action="<?php echo base_url()?>images_uploader/fb_upload">
<div id="liquid1" class="liquid">
	<span class="previous"></span>
	<div class="wrapper">
		<ul>
		<?php
		foreach ($fb_photos as $pix) {
				$thumb = $pix->small;
				$t_id = $pix->id;
				$bool = $this->uploadm->photo_in_server($t_id);
				if ($bool) {
					continue;
				}
		?>
			<li>
				<a href="#"><img src="<?php echo $thumb?>"/></a><br/>
				<input type="checkbox" name="pics[]" value="<?php echo $t_id?>"/>
			</li>
		<?php } // foreach ?>
		</ul>
	</div>
	<span class="next"></span>
</div>
<div>
	<input type="hidden" name="fb_id" value="<?php echo $fb_id?>"/>
	<input type="submit" name="submit" value="Submit"/>
</div>
</form>	
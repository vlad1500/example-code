<link rel="stylesheet" href="<?php echo $this->config->item("css_url"); ?>/coverstyle.css" type="text/css" />
<!--hardcover's front cover preview-->
<div id="cover_preview_page" align="center">
	<div id="cover_preview_content" class="cover_preview_<?php echo $cover; ?>">
		<div id="cover_preview_img_wrapper" align="center">
			<input type="text" id="cover_title" readonly />
			<div id="cover_prof_pic"></div>
			<ul id="cover_friends_pic"></ul>
			<input type="text" id="cover_author" readonly />
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->config->item("js_url"); ?>/coverpreviewscript.js"></script>

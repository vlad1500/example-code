<?php
$bii = $this->uri->segment(3);

//$this->main_model->demtest(1);

$data = $this->main_model->get_bookpages_ready_to_share_by_book_info_id($bii);
//echo '<pre>';
//print_r($data);
//exit;
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item("css_url"); ?>/lightbox/css/jquery.lightbox-0.5.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item("css_url"); ?>/layout.css" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item("css_url"); ?>/lightbox/img_overlay.css" />-->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item("css_url"); ?>/lightbox/js/jquery.lightbox-0.5.pack.js"></script>
 
<script type="text/javascript">
$(document).ready(function(){
	
	$('.pic a').lightBox({
		
		imageLoading: '<?php echo $this->config->item("css_url"); ?>/lightbox/images/loading.gif',
		imageBtnClose: '<?php echo $this->config->item("css_url"); ?>/lightbox/images/close.gif',
		imageBtnPrev: '<?php echo $this->config->item("css_url"); ?>/lightbox/images/prev.gif',
		imageBtnNext: '<?php echo $this->config->item("css_url"); ?>/lightbox/images/next.gif'

	});
	
	$('closeme').click(function(){
		window.close();
	});
	
	
});
</script>
<style>
	 
	
.main {
width:806px;
float:left;
 
height:auto !important;
}
.hed-name{
	width:806px;
	position:fixed !important;
	
}
.main-content{
width:806px;
height:auto !important;
margin-top:40px !important;
margin-bottom:60px !important;


}
a.pretty {
   background-color: #FF9839;
   background-image: -moz-linear-gradient(center top , #FF9839, #FD7E0B);
   background-repeat: repeat-x;
   border: 1px solid #E66D00;
   border-radius: 5px 5px 5px 5px;
   box-shadow: 0 1px 1px #C4C4C4, 0 0 1px #EFEFEF inset;
   color: #131313;
   margin-right: 5px;
   min-width: 50px;
   padding: 5px 10px;
   text-shadow: 0 1px 0 #FFB26C;
}
 
 

.abs_btn {
	position:fixed;
	bottom:0px;
	background:#FFFFFF;
	width:100%;
}

</style>
</head>
<body>
<script language="javascript" type="text/javascript">
<!-- 
//Browser Support Code
function ajaxFunction(id, pointr){
	var ajaxRequest;  // The variable that makes Ajax possible!
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			thisspan = "el" + pointr;
			//document.myForm.time.value = ajaxRequest.responseText;
			//document.getElementById("test").innerHTML = ajaxRequest.responseText;
			var resp = ajaxRequest.responseText;
			if (resp == 'a' || resp == 'd') {
				document.getElementById(thisspan).style.display = 'none';
			} else {
				document.getElementById('tr' + pointr).style.background = '#cccccc';
			}
		}
	}
	ajaxRequest.open("POST", "<?php echo $this->config->item('base_url')?>/main/new_album_content_approval/"+id, true);
	ajaxRequest.send(null); 
}

//-->
</script>


<div class="main" style="margin-left:0px ;"> 
	<div class="hed-name">
    <div class="hed-name-left" style="float:left; width:560px;"><h4>Approve content for: <?php echo $data[0]['book_name']?></h4></div>
    <div class="hed-name-right" style="float:right; width:40px;"><a onclick="pretty_close();"   href="javascript:void(0)"><img src="..../../../../images/close.png" width="24" height="29" / class="close-im"></a></div>
  </div>
 
    	<div class="main-content" style="margin-top:0px;">  
    	  <div class="main-content-l">
    	    <div align="center" class="book_name_appoval"><?php echo $data[0]['book_name']?></div>
		    <div class="book_name_appoval_info">
			Created: <?php echo date('d/m/Y',strtotime($data[0]['book_created']));?>  <br/>
			Pages: <?php echo $data[0]['total_pages']?><br/>
			Items awaiting approval: <?php echo $data[0]['new_items']?><br/>
		    </div>
    	  </div>
    	  <div class="main-content-r">
    	    <table width="100%" border="0" class="left_bar_info" cellspacing="0" cellpadding="3" style="height:38px; margin-top:10px;padding-right:10px;">
		<tr>
			<td width="43%"  ><span style="margin-left:2px;">Images</span></td>
			<td  ><span  style="margin-left:2px;">Submitted by<span></td> 
		</tr>
	</table>
	<?php
	$i = 0;
	foreach ($data as $row) {  
		$i++;
		$fbdata = $row['fbdata'];
		$fullname = $row['fullname'];
		$thumb = $fbdata->picture;
		$img = $fbdata->source;
		//test image
		//$img = $thumb = "http://i2.wp.com/boygeniusreport.files.wordpress.com/2012/12/samsung-galaxy-s-iii-923.jpg";
	?>
		<span id="el<?php echo $row['id']?>">
		<table width="100%" border="0" <?php if($i%2==1) { ?> class="color-tr" <?php } ?> >
			<tr id="tr<?php echo $row['id']?>">
				<td width="208" height="120" valign="top">
					<?php /*
					<div class="pic nomargin" style="background:url(<?php echo $thumb?>) no-repeat 50% 50%">
						<a href="<?php echo $img?>" title="" target="_blank">image <?php echo $i?></a>
					</div> */ ?>
					<a href="<?php echo $img?>" title="" target="_blank"><img src="<?php echo "/timthumb.php?src=".$img.'&h=120&w=208'?>" width="208" height="120" border="0"/></a>
				</td>
				<td valign="top" width="70%" style="vertical-align:top;">
					<table width="100%" border="0">
					<tr>
						<td style="vertical-align:top;">
							<div class="appr_left_info">
								<img src="<?php echo $row['profile_pic']?>" width="50" height="50"/> 
							 
								<span><?php echo $fullname;?></span><br/><br/>
							<span>	<?php echo date('d/m/Y',strtotime($row['fbdata_postedtime']));?> </span>
							</div>
							<div class="appr_right_info">
								<input type="submit" name="Approve" value="approve" onClick="ajaxFunction('a<?php echo $row['id']?>', '<?php echo $row['id']?>');"/>&nbsp;
								<input type="submit" name="Reject" value="reject" onClick="ajaxFunction('r<?php echo $row['id']?>', '<?php echo $row['id']?>');"/>&nbsp;
								<input type="submit" name="Delete" value="delete" onClick="ajaxFunction('d<?php echo $row['id']?>', '<?php echo $row['id']?>');"/>&nbsp;
								<input type="hidden" name="id" value="<?php echo $row['id']?>"/>
						   </div>
						</td>
					</tr>
					</table>
				</td>
			</tr>
		</table>
		</span>
	<?php
	}
	?>
	</td>
    	  </div>
	 <div class="abs_btn"><a class="approve_close pretty" href="javascript:void(0)" onclick="pretty_close()">Close</a></div>
        </div>
        
        
</div>
        




<?php /* ?>

<h3>Approve content for: <?php echo $data[0]['book_name']?></h3>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<TR>
	<td width="25%" valign="top">
		<div align="center"><?php echo $data[0]['book_name']?></div>
		<div>
			Created: <?php echo $data[0]['book_created']?><br/>
			Pages: <?php echo $data[0]['total_pages']?><br/>
			Items awaiting approval: <?php echo $data[0]['new_items']?><br/>
		</div>
	</td>
	<td width="75%" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
			<td width="30%" bgcolor="#cccccc">Images</td>
			<td bgcolor="#cccccc">Submitted by</td>
		</tr>
	</table>
	<?php
	$i = 0;
	foreach ($data as $row) {
		$i++;
		$fbdata = $row['fbdata'];
		$fullname = $fbdata->from->name;
		$thumb = $fbdata->picture;
		$img = $fbdata->source;
		//test image
		//$img = $thumb = "http://i2.wp.com/boygeniusreport.files.wordpress.com/2012/12/samsung-galaxy-s-iii-923.jpg";
	?>
		<span id="el<?php echo $row['id']?>">
		<table width="100%" border="0">
			<tr id="tr<?php echo $row['id']?>">
				<td width="30%" valign="top">
					<?php /*
					<div class="pic nomargin" style="background:url(<?php echo $thumb?>) no-repeat 50% 50%">
						<a href="<?php echo $img?>" title="" target="_blank">image <?php echo $i?></a>
					</div> */ ?>
				<?php /* ?>	<a href="<?php echo $img?>" title="" target="_blank"><img src="<?php echo $thumb?>" width="100" height="100" border="0"/></a>
				</td>
				<td valign="top" width="70%" style="vertical-align:top;">
					<table width="100%" border="0">
					<tr>
						<td style="vertical-align:top;"><img src="<?php echo $row['profile_pic']?>" width="50" height="50"/></td>
						<td style="vertical-align:top;">
							<?php echo $fullname?><br/>
							<?php echo $row['fbdata_postedtime']?><br/><br/>
								<input type="submit" name="Approve" value="approve" onClick="ajaxFunction('a<?php echo $row['id']?>', '<?php echo $row['id']?>');"/>&nbsp;
								<input type="submit" name="Reject" value="reject" onClick="ajaxFunction('r<?php echo $row['id']?>', '<?php echo $row['id']?>');"/>&nbsp;
								<input type="submit" name="Delete" value="delete" onClick="ajaxFunction('d<?php echo $row['id']?>', '<?php echo $row['id']?>');"/>&nbsp;
								<input type="hidden" name="id" value="<?php echo $row['id']?>"/>
						</td>
					</tr>
					</table>
				</td>
			</tr>
		</table>
		</span>
	<?php
	}
	?>
	</td>
	</TR>
<tr>
<td colspan="2" align="right">&nbsp;</td>
</tr>
</table>
<?php */ ?>
</body>
<script>
function pretty_close(){  
 window.parent.$.prettyPhoto.close();
 }
</script>
</html>




<?php

list($width, $height) = getimagesize($_POST["imageSource"]);
$viewPortW = $_POST["viewPortW"];
$viewPortH = $_POST["viewPortH"];
$pWidth = $_POST["imageW"];
$pHeight =  $_POST["imageH"];
$ext = end(explode(".",$_POST["imageSource"]));
$function = returnCorrectFunction($ext);
$image = $function($_POST["imageSource"]);
$width = imagesx($image);
$height = imagesy($image);
// Resample
$image_p = imagecreatetruecolor($pWidth, $pHeight);
setTransparency($image,$image_p,$ext);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $pWidth, $pHeight, $width, $height);
imagedestroy($image);
$widthR = imagesx($image_p);
$hegihtR = imagesy($image_p);

$selectorX = $_POST["selectorX"];
$selectorY = $_POST["selectorY"];

if($_POST["imageRotate"]){
	$angle = 360 - $_POST["imageRotate"];
	$image_p = imagerotate($image_p,$angle,0);
	
	$pWidth = imagesx($image_p);
	$pHeight = imagesy($image_p);
	
	//print $pWidth."---".$pHeight;

	$diffW = abs($pWidth - $widthR) / 2;
	$diffH = abs($pHeight - $hegihtR) / 2;
		
	$_POST["imageX"] = ($pWidth > $widthR ? $_POST["imageX"] - $diffW : $_POST["imageX"] + $diffW);
	$_POST["imageY"] = ($pHeight > $hegihtR ? $_POST["imageY"] - $diffH : $_POST["imageY"] + $diffH);

	
}



$dst_x = $src_x = $dst_y = $src_y = 0;

if($_POST["imageX"] > 0){
	$dst_x = abs($_POST["imageX"]);
}else{
	$src_x = abs($_POST["imageX"]);
}
if($_POST["imageY"] > 0){
	$dst_y = abs($_POST["imageY"]);
}else{
	$src_y = abs($_POST["imageY"]);
}


$viewport = imagecreatetruecolor($_POST["viewPortW"],$_POST["viewPortH"]);
setTransparency($image_p,$viewport,$ext);

imagecopy($viewport, $image_p, $dst_x, $dst_y, $src_x, $src_y, $pWidth, $pHeight);
imagedestroy($image_p);


$selector = imagecreatetruecolor($_POST["selectorW"],$_POST["selectorH"]);
setTransparency($viewport,$selector,$ext);
imagecopy($selector, $viewport, 0, 0, $selectorX, $selectorY,$_POST["viewPortW"],$_POST["viewPortH"]);

$file = "uploads/o_".$ext.time().".".$ext;
parseImage($ext,$selector,$file);

$fileName = $file;
					
// Create image for 1920x1440
$this->convert_images($fileName, 1920, 1440);
					
// Create image for 1680x1050
$this->convert_images($fileName, 1680, 1050);

// Create image for 1440x900
$this->convert_images($fileName, 1440, 900);

// Create image for 1366x768
$this->convert_images($fileName, 1366, 768);

// Create image for 1280x1024
$this->convert_images($fileName, 1280, 1024);

// Create image for 1024x768
$this->convert_images($fileName, 1024, 768);

// Create image for 640x480
$this->convert_images($fileName, 640, 480);

// Create image for 1920x1440
$this->convert_images($fileName, 480, 320);

// Create image for 1920x1440
$this->convert_images($fileName, 320, 240);

// Create image for 150x150
$this->convert_images($fileName, 150, 150);

imagedestroy($viewport);
//Return value
echo $file;
/* Functions */

function convert_images($image_filename = '', $image_width=320, $image_height=240)
{
	$image_source = $this->config->item('book_images_dir') . "/uploads/" . $image_filename;
	$image_size = $image_width . 'x' . $image_height;
	$image_filename_new = pathinfo($image_source);
	$image_filename_new = $image_filename_new['filename'] . '.' . $image_filename_new['extension'];
	$image_destination = $this->config->item('book_images_dir') . "/uploads/" . $image_size . '/' . $image_filename_new;
	exec('C:\PROGRA~1\IMAGEM~1.8-Q\convert.exe' . ' "'.$image_source.'" -resize "'.$image_size.'" "'.$image_destination.'"', $o, $r);
}

function determineImageScale($sourceWidth, $sourceHeight, $targetWidth, $targetHeight) {
	$scalex =  $targetWidth / $sourceWidth;
	$scaley =  $targetHeight / $sourceHeight;
	return min($scalex, $scaley);
}

function returnCorrectFunction($ext){
	$function = "";
	switch($ext){
		case "png":
			$function = "imagecreatefrompng";
			break;
		case "jpeg":
			$function = "imagecreatefromjpeg";
			break;
		case "jpg":
			$function = "imagecreatefromjpeg";
			break;
		case "gif":
			$function = "imagecreatefromgif";
			break;
	}
	return $function;
}

function parseImage($ext,$img,$file = null){
	switch($ext){
		case "png":
			imagepng($img,($file != null ? $file : ''));
			break;
		case "jpeg":
			imagejpeg($img,($file ? $file : ''),90);
			break;
		case "jpg":
			imagejpeg($img,($file ? $file : ''),90);
			break;
		case "gif":
			imagegif($img,($file ? $file : ''));
			break;
	}
}

function setTransparency($imgSrc,$imgDest,$ext){

	if($ext == "png" || $ext == "gif"){
		$trnprt_indx = imagecolortransparent($imgSrc);
		// If we have a specific transparent color
		if ($trnprt_indx >= 0) {
			// Get the original image's transparent color's RGB values
			$trnprt_color    = imagecolorsforindex($imgSrc, $trnprt_indx);
			// Allocate the same color in the new image resource
			$trnprt_indx    = imagecolorallocate($imgDest, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			// Completely fill the background of the new image with allocated color.
			imagefill($imgDest, 0, 0, $trnprt_indx);
			// Set the background color for new image to transparent
			imagecolortransparent($imgDest, $trnprt_indx);
		}
		// Always make a transparent background color for PNGs that don't have one allocated already
		elseif ($ext == "png") {
			// Turn off transparency blending (temporarily)
			imagealphablending($imgDest, true);
			// Create a new transparent color for image
			$color = imagecolorallocatealpha($imgDest, 0, 0, 0, 127);
			// Completely fill the background of the new image with allocated color.
			imagefill($imgDest, 0, 0, $color);
			// Restore transparency blending
			imagesavealpha($imgDest, true);
		}

	}
}

?>

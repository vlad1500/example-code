<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Image_creator extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	
		$this->load->model("main_model");
		$this->load->model("book_cover_model");
	}
	
	public function run(){
		echo "runnning...";
	}
	
	public function create_book_thumbnail($book_id, $width=196, $height=144, $is_display=1){
		/**
		 * Author: Dennis Toribio
		 * Creates a book cover thumbnail and returns if if needed
		 * book_id = the id(primary key) of the book_info table that tells about the book
		 * width = width of the book
		 * height = height of the book
		 * is_display = if you want the image to be displayed when this method is called; only the front cover will be displayed
		 */

		if (!$book_id){
			echo '{"status":400,"message":"No book id pass."}';
			return;
		}

        $param = new stdClass;
		$param->book_info_id = $book_id;
		$book_info_creator = $this->main_model->get_book_creator_by_book_info_id($param);

		if (!$book_info_creator){
			echo '{"status":400, "message":"Book ID does not exist!"}';
			return;
		}

		//the canvas use when no image has been uploaded for this cover
		$book_template = $this->config->item('book_thumbnail_canvas');
		$thumbnail_dir = $this->config->item('book_cover_dir');

		//lets retrieve cover info
		//echo "book info";
		//print_r($book_info_creator);

		$front_img = $book_info_creator->front_cover;
		if (strlen($front_img)==0 || is_null($front_img)) $front_img = $book_template;

		$back_img = $book_info_creator->back_cover;
		if (strlen($back_img)==0 || is_null($back_img)) $back_img = $book_template;

		//start creation of front cover thumbnail
		$book_author = $book_info_creator->is_show_book_author==1?($book_info_creator->fname . ' ' . $book_info_creator->lname):'';
		$book_name = $book_info_creator->is_show_book_title==1?$book_info_creator->book_name:'';
		//echo "$book_name:$book_author";
        //josh mod
        
        //we will not create a thumbnail for FB images
        if (strpos($front_img,'.fbcdn.net')===false){
            $front_img = strpos($front_img,'/uploads/')?$this->config->item('image_upload_')."/".basename($front_img):$front_img;
            
            //$front_img = explode("/uploads/",$front_img);
            //$front_img = $this->config->item('image_upload_')."/".$front_img[1];
        
    		$front_thumbnail_image = $this->generate_book_front_thumbnail($front_img, $book_name, $book_author, $width, $height);
    
    		/*** write image to disk ***/
    		$filename = 'front_'.$book_info_creator->book_info_id.'_'.$book_info_creator->facebook_id.'.png';    
    		$front_image_file = "$thumbnail_dir/$filename";
    		$front_thumbnail_image->writeImage( $front_image_file );
        }
        
        //josh mod
        //do not replace when image is coming from FB        
        if (strpos($back_img,'.fbcdn.net')===false){
            $back_img = strpos($back_img,'/uploads/')?$this->config->item('image_upload_')."/".basename($back_img):$back_img;            
                
    		//start creation of back cover thumbnail
    		$back_thumbnail_image = $this->generate_book_back_thumbnail($back_img, $width, $height);
    		$back_image_file = $thumbnail_dir . '/back_'.$book_info_creator->book_info_id.'_'.$book_info_creator->facebook_id.'.png';
    		$back_thumbnail_image->writeImage( $back_image_file );
        }
        
		//save to db the location of the image
		if ($front_image_file)
            $param->front_cover_location = str_replace($this->config->item('book_images_dir'),$this->config->item('book_images_url'),$front_image_file);
        else 
            $param->front_cover_location = $front_img;
            
        if ($back_image_file)
            $param->back_cover_location = str_replace($this->config->item('book_images_dir'),$this->config->item('book_images_url'),$back_image_file);
        else 
            $param->back_cover_location = $back_img;
            
		$ret = $this->book_cover_model->save_cover($param);

		//check if user wants the image to be display to the browser
		if ($is_display==1){
			$front_image_file = $param->front_cover_location;
			echo "<img src='$front_image_file' />";

			$back_image_file = $param->back_cover_location;
			echo "<img src='$back_image_file' />";
		}else{
			$ret = array('status'=>0,'msg'=>'','data'=>'','front_cover'=>$param->front_cover_location, 'back_cover'=>$param->back_cover_location);
			echo json_encode($ret);
		}
	}

	public function generate_book_front_thumbnail($cover_img = '',$book_name='', $book_author='', $width=196, $height=144){

		$book_template = $this->config->item('book_template');
		$im = new Imagick($book_template);
		$im->setImageBackgroundColor('white');
		$im = $im->flattenImages();
		$im->scaleImage($width, $height);
		
		//echo $cover_img;
		if (strlen($cover_img)>0){
			// Open the watermark		
			$watermark = new Imagick();
			$watermark->readImage($cover_img);
			//echo "cover_img: $cover_img";
			
			// how big are the images?
			$iWidth = $im->getImageWidth();
			$iHeight = $im->getImageHeight();
			
			//let check if we need to scale the book cover image to position it in the book template properly
			$watermark_sizes = $this->scale_watermark($watermark, $iHeight, $iWidth);
			$wHeight = $watermark_sizes['height']; 
			$wWidth = $watermark_sizes['width'];
			
			// calculate the position
			$x = ($iWidth/2) - ($wWidth/2) ;
			$y = ($iHeight/2) - ($wHeight/2);
				
			$watermark->scaleImage($wWidth, $wHeight);
			
			// Overlay the watermark on the original image
			$im->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
		}
		
		/* Create a drawing object and set the font size */
		$text_watermark = new Imagick();
		$draw = new ImagickDraw();
				
		/*** set the font size ***/
		// Set font properties
		//$draw->setFont('Arial');
		$draw->setFontSize(20);
		//$draw->setFillColor('black');
		
		//annovate with book name
		$draw->setGravity( Imagick::GRAVITY_NORTH );
		$im->annotateImage( $draw, 0, 0, 0, $book_name );
		
		//annotate with book author
		$draw->setGravity( Imagick::GRAVITY_SOUTH );
		$im->annotateImage( $draw, 0, 0, 0, $book_author );
		
		//$im->compositeImage($text_watermark, imagick::COMPOSITE_OVER, 0, 0);
		
		$im->thumbnailImage($width,$height);
		
		/**** set to png ***/
		$im->setImageFormat( "png" );
		
		return $im;
	}
	
	function generate_book_back_thumbnail($cover_img, $width, $height){
		$book_template = $this->config->item('book_template');
		$im = new Imagick($book_template);
		$im->scaleImage($width, $height);
		
		// Open the watermark
		$watermark = new Imagick();
        echo "iii: $cover_img";
		$watermark->readImage($cover_img);
		
		// how big are the images?
		$iWidth = $im->getImageWidth();
		$iHeight = $im->getImageHeight();
		
		//let check if we need to scale the book cover image to position it in the book template properly
		$watermark_sizes = $this->scale_watermark($watermark, $iHeight, $iWidth);
		$wHeight = $watermark_sizes['height']; 
		$wWidth = $watermark_sizes['width'];
		
		// calculate the position
		$x = ($iWidth/2) - ($wWidth/2) ;
		$y = ($iHeight/2) - ($wHeight/2);
				
		// Overlay the watermark on the original image
		$im->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
		
		//$im->thumbnailImage($width,$height);
		
		/**** set to png ***/
		$im->setImageFormat( "png" );
		
		return $im;
	}

	function scale_watermark($watermark, $book_height, $book_width, $has_book_name_author=0){
		/**
		 * Author: Dennis Toribio
		 * Tries to scale the book image so that it will fit in the right location of the book template
		 */
		//125 = is the total size taken by both top and lower portion of the book template
		//100 = is the total size taken by both left and right portion of the book template
		$book_width_occupied_portion = 0;
		$book_height_occupied_portion =  $has_book_name_author?39:0;

		$wWidth = $watermark->getImageWidth();
		$wHeight = $watermark->getImageHeight();
		
		//echo "w: " . $wWidth;
		//echo "h: " . $wHeight;
		
		if (($book_height-$book_height_occupied_portion) < $wHeight || ($book_width) < $wWidth) {
			// resize the watermark
			$h = 0;
			$w = 0;
			if ( ($book_height-$book_height_occupied_portion) < $wHeight ){
				$h = ($book_height-$book_height_occupied_portion);
			}else if (($book_width-$book_width_occupied_portion) < $wWidth){
				$w = ($book_width-$book_width_occupied_portion);
			}
			
			//echo "w1: " . $w;
			//echo "h2: " . $h;			
			//$wWidth = $w;
			//$wHeight = $h;
			
			$watermark->scaleImage($w, $h);
		
			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		}
		
		$watermark_sizes['width'] = $wWidth;
		$watermark_sizes['height'] = $wHeight;
		 
		//print_r($watermark_sizes);
		return $watermark_sizes;
	}
}
?>
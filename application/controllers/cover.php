<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Cover extends CI_Controller {
	
	public $new_cover;
	public $fb_user_info;
	public $cover_info;
	public $return_val;
	
	public function __construct($fb_id = 0, $book_info_id = 0, $which_cover = "front") {
		parent::__construct();
		
		$this->load->model("CoverModel");
		$this->load->model("book_cover_model");
		$this->load->model("main_model");
		$this->load->model("photo_albums_model");
		
		
		$this->fb_user_info = new stdClass;
		$this->cover_info = new stdClass;
		$this->return_val = new stdClass;
		
		$this->fb_user_info->facebook_id = $fb_id == 0 ? $_COOKIE["hardcover_fbid"] : $fb_id;
		$this->cover_info->book_info_id = $book_info_id == 0 ? $_COOKIE["hardcover_book_info_id"] : $book_info_id;
		$this->cover_info->which_cover = $which_cover == $this->cover_info->which_cover ? $this->cover_info->which_cover : $which_cover;
		
	}
	
	public function test(){
		echo "controller is running";
	}
	
	public function design() {
		/**
		 * Show the cover designer tab contents
		 * 
		 */
        $signed_request_data = $this->main_model->CheckIfSigned();
        //die(print_r( $signed_request_data ));
        $fbid = $signed_request_data['user_id'];
        $book_owner_id = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
        $is_ghost_writer     = $this->main_model->isGhostWriter($fbid,$_COOKIE['hardcover_book_info_id']);
        
      if ($fbid != $book_owner_id && $is_ghost_writer === false):
        setcookie("hardcover_book_info_id", "", time() - 3600);
        $ret->data = $this->main_model->select_books_again();
        $ret->xBid = "true";
        echo json_encode($ret);
      else:
		try{
			$view = new stdClass;		
			$new_cover = new Cover();
			
			$book_info_id = $_COOKIE['hardcover_book_info_id'];
			$data["cover"] = $new_cover->cover_info->which_cover;
			
			$book_info = $this->main_model->get_book_cover($book_info_id);
	
			$data['book_name'] = $book_info->book_name;
			$data['book_author'] = $this->main_model->get_book_creator($book_info);
			$data['front_cover'] = $book_info->front_cover;
			$data['back_cover'] = $book_info->back_cover;
			$data['show_title'] = ($book_info->is_show_book_title != "0")?'checked':'notChecked';
			$data['show_author'] = ($book_info->is_show_book_author != "0")?'checked':'notChecked';
			$view->data = $this->load->view("coverview", $data, TRUE);
			
			echo json_encode($view);
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
       endif;
	}
	function saveCoverTitle(){
		 $ret = $this->main_model->update_cover_data($_POST);
		 echo json_encode($ret);
	}
	//josh added base url function
    function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
        else $base_url = 'http://localhost/';

        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }

        return $base_url;
    }
    //end josh function
	public function insert_for_album()
	{
		   $data = array();
		   $data['book_info_id'] = $_COOKIE['hardcover_book_info_id'];  
		   $data['ids'] = $_POST['form_data'];
		   $data['type'] = $_POST['mode'];  
		    $this->main_model->insert_for_album($data);  
		    exit;
		 //$data["cover"] = $new_cover->cover_info->which_cover;
	}
	public function insert_for_album_cover()
	{
		   $data = array();
		   $data['book_info_id'] = $_COOKIE['hardcover_book_info_id'];  
		   $data['ids'] = $_POST['form_data'];
		   $data['type'] = $_POST['mode'];  
		    $this->main_model->insert_for_album_cover($data);  
		    exit;
		 //$data["cover"] = $new_cover->cover_info->which_cover;
	}

	public function getCoverInfo($book_info_id = 0, $which_cover) {   
		/**
		 * Retrieve Facebook friends' ID from raw data table
		 * 
		 * @access	Public
		 * @param	Integer book_info_id Current Book ID
		 * @param	String which_cover Value of the active cover to work on
		 * @return	Object return_val Facebook friends' ID in a form of JSON object
		 */

		$this->cover_info->book_info_id = ($book_info_id == 0 ? $this->cover_info->book_info_id : $book_info_id);
		$this->cover_info->which_cover = (empty($which_cover) ? $this->cover_info->which_cover : $which_cover);
		$friends_id = array();
		$num_friends = ($this->cover_info->which_cover == "front" ? 24 : 35);
		
	   // $cover = $this->CoverModel->getCover($this->cover_info->book_info_id, $this->cover_info->which_cover);  
		
		$cover = $this->CoverModel->getCoverDefaultInfo($this->cover_info->book_info_id);
		 
		$book_pages = $this->main_model->get_book_info_c($this->cover_info->book_info_id);
		  if($book_pages[0]->book_author!='')
		  $cover->author = $book_pages[0]->book_author;
		//echo $this->cover_info->book_info_id; echo $_COOKIE['hardcover_fbid'];exit;
		$this->return_val->status = 0;
		$this->return_val->msg = "No Data";
		if (1) {
			$this->return_val->status = 1;
			$this->return_val->msg = "";
			$this->return_val->cover = $cover;

			$offset = 0;
			$limit = 100;
			$param = new stdClass();
			$param->type = $this->cover_info->which_cover;
			$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
			// $b_name = $this->AlbumModel->get_book_cover($_COOKIE['hardcover_book_info_id']);
			// $_COOKIE['book_name'] = $b_name->book_name;
			$param->facebook_id = $_COOKIE['hardcover_fbid'];
			$param->limit = $limit;
			$param->offset = $offset;
			$book_pages = $this->main_model->get_book_content($param);
			$book_pages_selected = $this->main_model->get_book_content_selected($param);
			 $cover_page_selected = $this->main_model->cover_page_selected($param); 
			
			//$total_pages = $this->main_model->get_total_pages($param->book_info_id);		
			$data['book_pages_selected'] = $book_pages_selected['data'];
			$str_s = '';
			$selpictures = array();
			foreach($data['book_pages_selected'] as $k => $v) { 
			     $selpictures[] = $v->book_pages_id;
                             $str_s .= '<li class="float_left" id="1216568374">';
			     $str_s .= '<img width=50 height=50 src="'.base_url(TRUE).'/timthumb.php?src='.$v->image_url.'&h=50&w=50"> <a id="delete" style="display:none;" title="Remove photo" class="tooltip" href="#">';
			     $str_s .= '<img src="'.$v->image_url.'" class="ximg"></a>';
			     $str_s .= '</li>';
			}

			$this->return_val->book_pages_selected = $str_s;
			$this->return_val->cover_page_selected = $cover_page_selected;
			
			
			//$total_pages = $this->main_model->get_total_pages($param->book_info_id);		
			$data['book_pages'] = $book_pages['data'];
			$str = '';
			foreach($data['book_pages'] as $k => $v) { 
                             $str .= '<li class="" id="1216568374">';
				$stemp = '';
			     if(in_array($v->book_pages_id, $selpictures))
				$stemp = 'checked="checked"';
			     $str .= '<img width=50 height=50 src="'.base_url(TRUE).'/timthumb.php?src='.$v->image_url.'&h=50&w=50"><br/><center><input type="checkbox" id="pic_data_id_'.$v->book_pages_id.'" '.$stemp.' name="pic_data_id['.$v->book_pages_id.']" value="'.$v->book_pages_id.'"/></center><a id="delete" style="display:none;" title="Remove photo" class="tooltip" href="#">';
			     $str .= '<img src="'.$v->image_url.'" class="ximg"></a>';
			     $str .= '</li>';
			}

			$this->return_val->book_pages = $str;

			
		} else {
			$cover = $this->CoverModel->getCoverDefaultInfo($this->cover_info->book_info_id);
			$friends = $this->getFBFriends(0, TRUE);
			$friends = explode(";", $friends->friends);
			$friends = array_slice($friends, 0, $num_friends);
			foreach ($friends as $key => $val) {
				$friends_id = explode(":", $val);
				$cover->friends_pic .= ";". $friends_id[0];
			}
			$cover->friends_pic = substr($cover->friends_pic, 1);
			$cover->user_pic = "";
			$cover->cover_type_name = "HardCover (default)";
			$cover->cover_design_id = 0;
			$this->return_val = new stdClass;
			$this->return_val->status = 1;
			$this->return_val->msg = "";
			$this->return_val->cover = $cover;
		}
		echo json_encode($this->return_val);
	}
	
	
	 
	public function getFBFriends($fb_id = 0, $retrieve = FALSE) {
		/**
		 * Retrieve all the current user's Facebook friends' ID & Name
		 * 
		 * @access	Public
		 * @param	Integer fb_id Contains the current user's Facebook ID
		 * @param	Boolean retrieve Determine whether to return the value either in raw object or JSON object
		 * @return	Object return_val Contains the current user's Facebook friends' ID & Name (pattern - ID:Name;ID:Name;ID:Name)
		 */
		$fb_friends = new stdClass;
		$this->fb_user_info->facebook_id = (!$this->fb_user_info->facebook_id OR $fb_id == 0) ? $_COOKIE["hardcover_fbid"] : $fb_id;
		
		$fb_friends = $this->CoverModel->getFriends($this->fb_user_info->facebook_id);
		
		$this->return_val->status = 0;
		$this->return_val->msg = "No Friends Info";
		if ($fb_friends) {
			$this->return_val->status = 1;
			$this->return_val->msg = "";
			$fb_friends = $this->formatDBFriends($fb_friends);
			$this->return_val->friends = $fb_friends;
		}
		
		if ($retrieve) {
			return $this->return_val;
		} else {
			echo json_encode($this->return_val);
		}
	}
	
	public function popupScreen() {
		/**
		 * Load pop-up screen View for Add/Remove Friends button  
		 * 
		 * @access	Public
		 * @return	Object return_val View pop-up screen data in a form of JSON object 
		 */
		$this->return_val->status = 1;
		$this->return_val->msg = "";
		$data["friends"] = $this->formatDBFriends($this->CoverModel->getFriends($this->fb_user_info->facebook_id));
		$this->return_val->popup = $this->load->view("coverpopup", $data, TRUE);
		echo json_encode($this->return_val);
	}
	
	public function preview($which_cover) {
		/**
		 * Show preview of the active cover  
		 * 
		 * @access	Public
		 * @param	String which_cover Value of the active cover to work on
		 */
		$this->return_val->status = 1;
		$this->return_val->msg = "";
		$data["cover"] = (empty($which_cover) || is_null($which_cover) ? "front" : $which_cover);
		$this->return_val->preview = $this->load->view("coverpreview", $data, TRUE);
		echo json_encode($this->return_val);
	}
	
	public function saveCoverDesign($cover_design_id = 0, $which_cover) {
		/**
		 * Save the current cover design info
		 * 
		 * @access	Public
		 * @param	Integer cover_design_id Contains the current cover design ID
		 * @param	String which_cover This is the active cover
		 * @return	Object return_val Contains the status and confirmation message of saving cover design info
		 */
		$cover_design = new stdClass;
		$cover_design_id = ($cover_design_id = 0 ? $_COOKIE["hardcover_design_id"] : $cover_design_id);
		$this->cover_info->which_cover = (empty($which_cover) ? $this->cover_info->which_cover : $which_cover);
		$cover_design = $this->CoverModel->updateCover($this->cover_info->which_cover, $cover_design_id, $_COOKIE["hardcover_design_values"]);
		
		$this->return_val->status = 0;
		$this->return_val->msg = "";
		if ($cover_design) {
			$this->return_val->status = $cover_design["status"];
			$this->return_val->msg = $cover_design["msg"];
			$this->return_val->cover_design = $cover_design["data"];
		}
		echo json_encode($this->return_val);
	}
	
	private function formatDBFriends($fb_friends) {
		/**
		 * Make a comma separated value for Facebook friends' ID & Name  
		 * 
		 * @access	Private
		 * @param	Object fb_friends Contains Facebook friends' ID & Name
		 * @return	String friends Contains Facebook friends' ID & Name in this form - ID:Name;ID:Name(...)
		 */
		$friends = "";
		
		foreach ($fb_friends as $friend) {
			$friends .= ";". $friend->friends_fbid .":". $friend->friends_name;
		}
		$friends = substr($friends, 1);
		return $friends;
	}	

	public function getFrontCoverID() {
		/**
		 * Get front cover for specific album
		 * 
		 * @access	Public
		 * @return	image ID for front cover
		 */


		$cover = $this->CoverModel->getCoverDefaultInfo($this->cover_info->book_info_id);

		$cover_id = $this->uri->segment(2);

		echo $this->CoverModel->getFrontCoverID(array('front_cover_id'=> $cover_id));
	}
	public function committest() {
		echo 'success';
	}
	
	public function getCoverUploadPC($front_back='front'){
		/**
		 * Author: Dennis Toribio
		 * Displays the content of the PC uploader for the cover
		 */
		$ret->status = 200;
		$ret->msg = 'No Data';
		
		//print_r($_POST);
		$data['front_back'] = $_POST['front_back']?$_POST['front_back']:'back';
		$ret->data = $this->load->view('cover_upload_pc',$data,TRUE);
		echo json_encode($ret);		
	}
	
	public function getCoverUploadFB($front_back='front'){
		/**
		 * Author: Dennis
		 * This will 
		 */		
		
		$ret->status = 200;		
				
		$facebook_id = $_COOKIE['hardcover_fbid'];
		$token = $_COOKIE['hardcover_token'];
		$model_data = $this->photo_albums_model->getUserAlbums($facebook_id);
	 if($model_data['data']):     
		//$albums = [];
		$total_albums = count( $model_data['data'] );
		
		foreach ($model_data['data'] as $album){
			$fbdata = unserialize($album->fbdata);
			$album_cover = "https://graph.facebook.com/{$fbdata->cover_photo}/picture?type=thumbnail&access_token=$token";
			$fbdata->cover_photo = $album_cover;
				
			$photos = $this->photo_albums_model->getPhotosOfAlbum($album->album_id);
			
			$model_data['data'][$album->album_id]->photos = $photos['data'];
			$model_data['data'][$album->album_id]->fbdata = $fbdata;				
		}
		
		$data['token'] = $token;
		$data['albums'] = $model_data['data'];
		$data['front_back'] = $_POST['front_back']?$_POST['front_back']:'back';
        $ret->msg = 'Has Data';
		$ret->data = $this->load->view('cover_upload_fb',$data,TRUE);
     else:
        $ret->msg = 'No Data'; 
        $ret->fbId = $facebook_id;
	 endif;	
		echo json_encode($ret);				
	}
	
	function convert_images($image_filename = '', $image_width=320, $image_height=240)
	{
		$image_source = $this->config->item('book_images_dir') . "/uploads/" . $image_filename;
		$image_size = $image_width . 'x' . $image_height;
		$image_filename_new = pathinfo($image_source);
		$image_filename_new = $image_filename_new['filename'] . '.' . $image_filename_new['extension'];
		$image_destination = $this->config->item('book_images_dir') . "/uploads/" . $image_size . '/' . $image_filename_new;
		exec('convert' . ' "'.$image_source.'" -resize "'.$image_size.'" "'.$image_destination.'"', $o, $r);
	}	
	
	public function saveCoverUploadPC(){
		/**
		 * Author: Dennis Toribio
		 * This will upload and save the in the db the image
		 */
		
		$book_info_id = $_COOKIE['hardcover_book_info_id'];
		$facebook_id = $_COOKIE['hardcover_fbid'];
		$front_back = $_POST['front_back'];	//tells if its a 'front' or 'back' cover
				
		$max_file_size = 500000*1024;
		$upload_dirs = $this->config->item('image_upload');
		
		$err = ""; 
		$status = 0;
		$url_image = '';
		
		$upload_values = $this->checkUploadValues($upload_dirs);
		$err = $upload_values->message;
		
		if ($upload_values->status) {
			if (filesize($_FILES["userfile"]["tmp_name"]) > $max_file_size) $err .= "Maximum file size limit: $max_file_size bytes";
			else {
				//file naming convention: facebookID_bookID_front_back
				$ext = pathinfo($_FILES["userfile"]["name"], PATHINFO_EXTENSION);
				$save_filename = $facebook_id.'_'.$book_info_id.'_'.$front_back . ".$ext";
				if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $upload_dirs.'/'.$save_filename)) {
				
					$fileName = $save_filename;
					
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
				
					$url_image = $this->config->item('image_upload_').'/'.$save_filename;
					//echo "front_back => $front_back: ";
					if ($front_back=='front')
						$ret_model = $this->book_cover_model->save_front_cover($url_image, $book_info_id);
					else
						$ret_model = $this->book_cover_model->save_back_cover($url_image, $book_info_id);
					
					$status = $ret_model['status']==200?1:0;
					
				}
				else $err .= "There are some errors!";
			}
		}
		
		
		$ret->status = (!$status)?400:200;
		$ret->message = (!$status)?$err:"&quot;".$_FILES["userfile"]["name"]."&quot; was successfully uploaded.";  
		$ret->url_image = $url_image;
		echo json_encode($ret);
		
	}
	
	public function saveCoverUploadFB(){
		/**
		 * Author: Dennis Toribio
		 * This will upload and save the in the db the image
		 */
	
		$book_info_id = $_COOKIE['hardcover_book_info_id'];
		$facebook_id = $_COOKIE['hardcover_fbid'];
		$front_back = $_POST['front_back'];	//tells if its a 'front' or 'back' cover
		$cover_img = $_POST['cover_img'];
		
		$err = "";
		$status = 0;
		$url_image = '';
	
		if ($front_back=='front')
			$ret_model = $this->book_cover_model->save_front_cover($cover_img, $book_info_id);
		else
			$ret_model = $this->book_cover_model->save_back_cover($cover_img, $book_info_id);
		
		
		$ret->status = $ret_model['status']; 
		$ret->message = "Success";
		$ret->url_image = $cover_img;
		
		echo json_encode($ret);
	
	}
	
	private function checkUploadValues($upload_dirs){	
		/**
		 * Author: Dennis Toribio
		 * This will check dirs and files before upload the proceed
		 */	
		$data->status = 1;
		$data->message = '';	
		
		if (!ini_get("file_uploads")) { $data->message .= "HTTP file uploading is blocked in php configuration file (php.ini). Please, contact to server administrator."; $data->status=0; }
		
		$pos = strpos(ini_get("disable_functions"), "move_uploaded_file");
		
		if ($pos !== false) { $data->message .= "PHP function move_uploaded_file is blocked in php configuration file (php.ini). Please, contact to server administrator."; $data->status=0; }
		
		if (!isset($upload_dirs)) { $data->message .= "Incorrect path"; $data->status=0; }		
		
		if (!isset($_FILES["userfile"])) { $data->message .= "Empty file"; $data->status=0; }
		elseif (!is_uploaded_file($_FILES['userfile']['tmp_name'])) { $data->message .= "Empty file"; $data->status=0; }

		return $data;
	}
	
}
?>

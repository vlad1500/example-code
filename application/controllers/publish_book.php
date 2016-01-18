<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Publish_book extends CI_Controller {
	protected $path_img_upload_folder;
    protected $path_img_thumb_upload_folder;
    protected $path_url_img_upload_folder;
    protected $path_url_img_thumb_upload_folder;

    protected $delete_img_url;

	public function __construct(){
        parent::__construct();

		$this->load->model("publish_bookm");
			$this->load->model("AlbumModel");

		$this->load->helper(array('form', 'url'));
		$this->load->model('uploadm');
		$this->smarty->assign('base_url',$this->config->item('base_url'));
		$this->smarty->assign('css',$this->config->item('css_url'));
		$this->smarty->assign('js',$this->config->item('js_url'));
		$this->smarty->assign('img',$this->config->item('image_url'));
		$this->smarty->assign('fb_appkey',$this->config->item('fb_appkey'));

	//Set relative Path with CI Constant
		$this->setPath_img_upload_folder("uploads/");
		$this->setPath_img_thumb_upload_folder("uploads/thumbnail/");


	//Delete img url
		$this->setDelete_img_url(base_url() . 'admin/deleteimg/');


	//Set url img with Base_url()
		$this->setPath_url_img_upload_folder(base_url() . "uploads/");
		$this->setPath_url_img_thumb_upload_folder(base_url() . "uploads/thumbnail/");
	}

	function index() {
		$this->load->view('publishv');
	}

    function choose_friends() {
 		$reply = $this->publish_bookm->publish($_POST);
        //$reply = "TEST";
        //die(print_r($this));
 		if($reply){
     		$ret->status = 200;
	    	$ret->msg = 'Success';
		    $ret->data = $reply;
     		echo json_encode($ret);
        }
	}
	//josh add for ask permissions
    function ask_photo_permission() {
        $ask = $this->publish_bookm->ask_photo_permission($_POST);        
    }
    function ask_see_permission() {
        $ask = $this->publish_bookm->ask_see_permission($_POST);        
    }
    //end josh
	// getter and setter
	// GETTER & SETTER 
	
	
		public function getPath_img_upload_folder() {
			return $this->path_img_upload_folder;
		}
	
		public function setPath_img_upload_folder($path_img_upload_folder) {
			$this->path_img_upload_folder = $path_img_upload_folder;
		}
	
		public function getPath_img_thumb_upload_folder() {
			return $this->path_img_thumb_upload_folder;
		}
	
		public function setPath_img_thumb_upload_folder($path_img_thumb_upload_folder) {
			$this->path_img_thumb_upload_folder = $path_img_thumb_upload_folder;
		}
	
		public function getPath_url_img_upload_folder() {
			return $this->path_url_img_upload_folder;
		}
	
		public function setPath_url_img_upload_folder($path_url_img_upload_folder) {
			$this->path_url_img_upload_folder = $path_url_img_upload_folder;
		}
	
		public function getPath_url_img_thumb_upload_folder() {
			return $this->path_url_img_thumb_upload_folder;
		}
	
		public function setPath_url_img_thumb_upload_folder($path_url_img_thumb_upload_folder) {
			$this->path_url_img_thumb_upload_folder = $path_url_img_thumb_upload_folder;
		}
	
		public function getDelete_img_url() {
			return $this->delete_img_url;
		}
	
	
		public function setDelete_img_url($delete_img_url) {
			$this->delete_img_url = $delete_img_url;
		}			
}

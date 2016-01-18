<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Edit_Album extends CI_Controller {
		
	public function __construct(){
        parent::__construct();
		
		$this->load->model("main_model"); 
		$this->load->model("AlbumModel");
		$this->load->helper(array('form', 'url'));
		$this->smarty->assign('base_url',$this->config->item('base_url'));
		$this->smarty->assign('css',$this->config->item('css_url'));
		$this->smarty->assign('js',$this->config->item('js_url'));
		$this->smarty->assign('img',$this->config->item('image_url'));
		$this->smarty->assign('fb_appkey',$this->config->item('fb_appkey'));		
		
	}
	
	public function get_book_pages_uni($fbid=0, $book_info_id=0,$offset=0,$limit=20){  
		$fbid = $_COOKIE['hardcover_fbid'];
		$book_info_id = $_COOKIE['hardcover_book_info_id'];
		
		if(!$book_info_id) {
			die('Invalid facebook id / book id (fb/bkid). ' . $fbid .'/'.$book_info_id);
		}
		
		$offset = 0;
		$limit = 100000;
		$param = new stdClass();
		$param->book_info_id = $book_info_id;
		 $b_name = $this->AlbumModel->get_book_cover($book_info_id);
		 $_COOKIE['book_name'] = $b_name->book_name;
		$param->facebook_id = $fbid;
		$param->limit = $limit;
		$param->offset = $offset;        
		$book_pages = $this->main_model->get_book_content($param);
		$total_pages = $this->main_model->get_total_pages($param->book_info_id);
				
		$data = $book_pages['data'];
		//print_r($data);
		//$data['link_url'] = "Hi";
		
		
		echo json_encode($data);
	}
	
	public function get_book_pages($fbid=0, $book_info_id=0,$offset=0,$limit=20){    
		$fbid = $fbid?$fbid:$_COOKIE['hardcover_fbid'];
		$book_info_id = $book_info_id?$book_info_id:$_COOKIE['hardcover_book_info_id'];
		if(!$fbid || !$book_info_id) {
			die('Invalid facebook id / book id (fb/bkid). ' . $fbid .'/'.$book_info_id);
		}
		$offset = 0;
		$limit = 10000;
		$param = new stdClass();
		$param->book_info_id = $book_info_id;
		$b_name = $this->AlbumModel->get_book_cover($book_info_id);
		$_COOKIE['book_name'] = $b_name->book_name;
		$param->facebook_id = $fbid;
		$param->limit = $limit; 
		$param->offset = $offset;
		$book_pages = $this->main_model->get_book_content($param);

		$fb_user = $this->main_model->get_users_fb_name($fbid);
		$data['fb_username'] = $fb_user[0]->fb_username;
		$data['book_name'] = $b_name->book_name;
		$data['book_id'] = $book_info_id;		
		$data['book_pages'] = $book_pages['data'];
		
		echo json_encode($data);
	}
	
	public function get_book_pages_share($fbid=0, $book_info_id=0,$offset=0,$limit=20){
		/**
		Returns the list of book pages content that will be populated to the book
		 */
		$fbid = $_COOKIE['hardcover_fbid']; 
		$book_info_id = $_COOKIE['hardcover_book_info_id'];
		if(!$book_info_id) {
			die('Invalid facebook id / book id.');
		}
		$offset = 0;
		$limit = 500;//josh 1
		
        
		$param = new stdClass();
		$param->book_info_id = $book_info_id;
		$b_name = $this->AlbumModel->get_book_cover($book_info_id);
		$_COOKIE['book_name'] = $b_name->book_name;
        
		$param->facebook_id = $fbid;
		$param->limit = $limit;
		$param->offset = $offset;
		$book_pages = $this->main_model->get_book_content($param);
		//$total_pages = $this->main_model->get_total_pages($param->book_info_id);		
		$data= $book_pages['data'];
		
		echo json_encode($data);
	}
	function save_wall_friends()
	{
		 $data = array();
		 $data['book_info_id'] = $_POST['book_info_id'];
		 $frd_data = explode('_',$_POST['friend_fb_id']);
		 $data['friends_fbid'] = $frd_data[0];
		 $this->AlbumModel->save_wall_friends($data);
		 exit;
	}
	public function preview(){
        $fbid = $_COOKIE['hardcover_fbid'];
		$book_info_id = $_COOKIE['hardcover_book_info_id'];
		
		$param = new stdClass();
		$data['json'] = $_POST['json']; 
		//$data['book_data'] = $this->AlbumModel->get_book_cover($book_info_id); 
		$data['book_data'] = $this->main_model->get_book_cover($book_info_id);
		
		//$data['book_setting_data'] = $this->AlbumModel->get_book_settings($book_info_id); 
        
		$param->facebook_id = $fbid;
		$data['user_details'] = $this->main_model->get_book_creator($param);
		$data['book_id'] = $book_info_id;
		
		
		if(!$book_info_id) {
			die('Invalid facebook id / book id (fb/bkid). ' . $fbid .'/'.$book_info_id);
		}
		
		$offset = 0;
		$limit = 100000;
		$param = new stdClass();
		$param->book_info_id = $book_info_id;
		
		//$b_name = $this->AlbumModel->get_book_cover($book_info_id);
		$b_name = $data['book_data'];
		
		$_COOKIE['book_name'] = $b_name->book_name;
		$param->facebook_id = $fbid;
		$param->limit = $limit;
		$param->offset = $offset;
		$book_pages = $this->main_model->get_book_content($param);
		$total_pages = $this->main_model->get_total_pages($param->book_info_id);
		$data['book_settings'] = $this->main_model->get_settings_unique($book_info_id);		
		$data['booked_data'] = $book_pages['data'];			
		//$fb_user = $this->main_model->get_book_creator($param);				
		//$data['fb_user'] = $fb_user;        
		$this->load->view("new-ui-publishpreview", $data);
	}
	
	public function setGhostWriter($ghost_writer_id='', $book_info_id=''){
		//set book ghost writer; current we only allow 1 ghost writer

		$book_info_id = $book_info_id?$book_info_id:$_COOKIE['hardcover_book_info_id'];
		
		if ($book_info_id=='' or $ghost_writer_id==''){
			$ret = array('status'=>400,'msg'=>'Something is missing from the required parameters.','data'=>$book_info_id);
			echo json_encode($ret);
		}
		
		$ret = $this->main_model->setGhostWriter($ghost_writer_id, $book_info_id);
		echo json_encode($ret);
	}

    function savePageInfo(){
        $this->main_model->updatePageInfo($_POST);
        $ret->status = 200;
		$ret->msg = 'Success';
		$ret->data = $_POST;
 		echo json_encode($ret);
    }
}

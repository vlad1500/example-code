<?php
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Uniqueurl extends CI_Controller {
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model("main_model");
	}
	
	function create_static_pages(){
		$this->load->helper('file');
		$book_info_id = $this->input->get('book_info_id');
		$fb_username = $this->input->get('fb_username');
		
		$param->limit = 0;
		$param->offset = 0;
		$param->book_info_id = $book_info_id - $this->config->item('book_info_id_key');
		$param->fb_username = $fb_username;
		$creator = $this->main_model->get_book_creator_by_book_info_id($param);
		$param->facebook_id = $creator->facebook_id;
		
		$book_pages = $this->main_model->get_book_pages_for_testpage($param);
		$data['book_pages'] = $book_pages['data'];		
		$data['book_info_id'] = $param->book_info_id;
		$static_data = $this->load->view('unique_url_template',$data,TRUE);
		write_file("/storage/www/codebase/apps/devhardcover/application/views/static_pages/{$fb_username}_{$book_info_id}.htm",$static_data);
	}
}


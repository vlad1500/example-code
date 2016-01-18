<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Main extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("main_model");
	}
	
	public function delete_book($book_info_id) {
		$this->main_model->delete_book($book_info_id);
		echo 'deleted';
	}
	
	public function delete_allbooks($fbid) {
		$this->main_model->delete_allbooks($fbid);
		echo 'deleted';
	}
	
	public function delete_allraw($fbid){
		$this->main_model->delete_all_rawdata($fbid);
		echo 'deleted';
	}
}
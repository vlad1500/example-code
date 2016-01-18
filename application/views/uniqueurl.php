<?php
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Uniqueurl extends CI_Controller {
	
	
	public function __construct(){
		parent::__construct();
		$this->load->model("main_model");
	}
	
	function create_static_pages(){
		$this->load->helper('file');
		echo 'create-static';
		log_message('error', 'Some variable did not contain a value.');
		$data='';
		$static_data = $this->view->load('unique_url_template',$data,TRUE);
		write_file("./application/views/static_pages/dencio.htm",$static_data);
	}
}


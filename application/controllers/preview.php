<?php
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Preview extends CI_Controller {
	
	public function __construct($book_id){
        parent::__construct();
		$this->load->model("main_model");
		parse_str($_SERVER['QUERY_STRING'],$_GET);
	}
	
	function _remap(){		
		$this->load->view('page_preview');		
	}
	
	function load_preview(){		
		$this->load->view('page_preview');		
	}	
}
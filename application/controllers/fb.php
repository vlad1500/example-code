<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Fb extends CI_Controller {
	public function __construct(){
        parent::__construct();
	}
	
	function test1(){
		echo 'seeing this means fb controller is running good';
	}
	
	function loginByFacebook(){
        	$this->load->library('fb_connect');
	        $param['redirect_uri']=site_url("fb/facebook");
	        redirect($this->fb_connect->getLoginUrl($param));
	}
 
	function facebook() {
	        $this->load->library('fb_connect');
	        if (!$this->fb_connect->user_id) {
	            //Handle not logged in,
				echo 'not logged in';
	        } else {
	           $fb_uid = $this->fb_connect->user_id;
	           $fb_usr = $this->fb_connect->user;
			   echo 'test';
			   print_r($fb_usr);
	           //Hanlde user logged in, you can update your session with the available data
        	   //print_r($fb_usr) will help to see what is returned
	        }
	}
}
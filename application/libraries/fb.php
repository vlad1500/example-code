<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Fb extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->load->model('main_model');
		$this->CI->load->helper('common_helper');
	}
	
    public function getFBLoginUrl($fbid = 0, $redirect_url=''){
    	$params->app_id = $this->CI->config->item('fb_appkey');
    	$params->app_secret = $this->CI->config->item('fb_appsecret');
    	$params->redirect_url = $redirect_url?$redirect_url:$this->CI->config->item('fb_canvaspage');
    	
    	$res->status = 0;
    	$res->msg = '';
    	
    	$fbid = $fbid?$fbid:$_COOKIE['hardcover_fbid'];
    	if(empty($fbid)) {    		
    		$dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
    				. $params->app_id . "&redirect_uri=" . urlencode($params->redirect_url)
    				. '&scope=' . $this->CI->config->item('fb_scope_permission');    		
    		//$res->data = "<script> top.location.href='" . $dialog_url . "'</script>";
    		$res->data = $dialog_url;
    		$res->status = 1;	//need to login
    	}
    	return $res;    	
    }
    
    //checks if the user has run the fbdata_updater in the server
    public function runFBDataUpdater($facebook_id,$token){
    	$last_run = $this->CI->main_model->get_lastrun_fbdata_updater($facebook_id,$token);
    
    	if ($last_run['data']!=false){
    		$lastrun_date = date('Y-m-d',strtotime($last_run['data']));
    		$today_date = date('Y-m-d');
    		$start = strtotime($lastrun_date);
    		$end = strtotime($today_date);
    		$days_diff = ceil(abs($end - $start) / 86400);    
    		
    		if ($days_diff > 0) {
    			$this->CI->main_model->set_lastrun_fbdata_updater($facebook_id,$token);
    			updateFBData($facebook_id,$token);
    		}
    	}else
    		updateFBData($facebook_id,$token);
    
    }    
}

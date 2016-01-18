<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
/**
 * Album Class
 * 
 * Controller for My Albums tab which extends CI_Controller
 * 
 * @author	Marlo Morales
 * 
 */
class Termsofuse extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model("AlbumModel");
		
		$this->load->helper(array('form', 'url'));
	}
	
	function get_all_data($book_info_id){
		$param->book_info_id = $book_info_id;
		$param->facebook_id = $_COOKIE['hardcover_fbid'];
		$param->limit = 50;
		$param->offset = 0;
		$book_pages = $this->AlbumModel->get_book_pages_for_testpage($param);		
		$data['book_pages'] = $book_pages['data'];

		echo json_encode($data);
	}
	
	function error($code){
		$this->load->view('4oh4');
	}
	
	//this is the start of hardcover application
	public function index(){
		echo   $this->load->view('terms',$data,TRUE);
	}
	
	function init_user_profile($fbid){		
		$fbuser = getFacebookUserDetails($_COOKIE['hardcover_token']);
		$param->facebook_id = $fbid;
		$param->fname = $fbuser->first_name;
		$param->lname = $fbuser->last_name;
		$param->fb_username = $fbuser->username;
		$param->fbdata = serialize($fbuser);
		$this->AlbumModel->set_book_creator($param);
	}	
	
	public function album_cover(){
		$ret->status = 0;
		$ret->msg = '';
		$data['book_info'] = $this->AlbumModel->get_book_cover($book_info_id);
		$ret->data = $this->load->view('album_cover',$data,TRUE);
		echo json_encode($ret);
	}
	
	//this is called after 
	public function first_page(){
		$ret->status = 0;
		$ret->msg = '';
		$ret->data = $this->load->view('first_page','',TRUE);	
		echo json_encode($ret);
	}
	
	//this is call to list the albums/quotes being created
	public function book_summarylist(){
		$ret->status = 0;
		$ret->msg = '';
		$data['booklist'] = $this->AlbumModel->get_booklist($fbid);
		$ret->data = $this->load->view('book_summarylist',$data,TRUE);	
		echo json_encode($ret);
	}
	
	//get the user fb friends and format it in command-separated
	public function get_fb_friends($fbid=0){
		$param->facebook_id = empty($_COOKIE['hardcover_fbid'])?$fbid:$_COOKIE['hardcover_fbid'];
		//$fb_friends = $this->AlbumModel->get_friends($param,$this->config->item('initial_album_cover_profile'),0);	//get only 27 friends for initial book cover
		$fb_friends = $this->AlbumModel->get_friends($param);
		if ($fb_friends){
			$ret->status = 0;		
			$ret->msg = '';
			$ret->friends_fbid = $this->format_db_friends($fb_friends);
		}else{
			$ret->status=1;
			$ret->msg = 'no data';
		}
		echo json_encode($ret);
	}
	
	//this will gave us all the the user fb friends without limit and in the following format
	//fbid:name;fbid:name
	public function get_fb_friends_withname(){
		$param->facebook_id = $_COOKIE['hardcover_fbid'];
		$fb_friends = $this->AlbumModel->get_friends($param);	//get only 27 friends for initial book cover
		if ($fb_friends){
			$ret->status = 0;		
			$ret->msg = '';
			$ret->friends = $this->format_db_friends_withname($fb_friends);
		}else{
			$ret->status=1;
			$ret->msg = 'no data';
		}
		echo json_encode($ret);
	}
	
	//this will retrive all friends name that starts with;for use in search	
	public function get_fb_names(){
		$param->facebook_id = $_COOKIE['hardcover_fbid'];
		$param->first_name = $this->input->post('first_name');
		if (empty($param->first_name))
			$ret = '';
		else		
			$ret = $this->AlbumModel->get_fb_friends_by_name($param);
		echo json_encode($ret);
	}
	
	//this will present the edit_album.php where the pageflip is shown
	public function edit_album() {
		/**
		 * Let's you modify the album which you want to edit upon clicking Edit link from My Albums tab
		 * 
		 * @author	Marlo Morales
		 *  
		 */
		$ret->status = 0;
		$ret->msg = '';
		
		$param->facebook_id = $_COOKIE["hardcover_fbid"];
		$creator = $this->AlbumModel->get_book_creator($param);
		$creator->fbdata = unserialize($creator->fbdata);
		$data["creator"] = $creator;
		
		$book_info_id = $this->input->post("book_info_id");
		$book_info_id = empty($book_info_id) ? $_COOKIE['hardcover_book_info_id'] : $book_info_id;
		if ($book_info_id) setcookie("hardcover_book_info_id", $book_info_id, time()+86400, "/");
		$book_info = $this->AlbumModel->get_book_info($book_info_id);
		$data["book_info"] = $book_info["data"];
		$data["encrypted_book_info_id"] = $book_info_id + $this->config->item("book_info_id_key");
		$ret->data = $this->load->view("edit_album", $data, TRUE);
		echo json_encode($ret);
	}
	
	public function get_book_pages($book_info_id=0, $offset=0, $limit=20) {
		$book_info_id = empty($_COOKIE["hardcover_book_info_id"]) ? $book_info_id : $_COOKIE["hardcover_book_info_id"];
		$param->book_info_id = $book_info_id;
		$param->facebook_id = $_COOKIE["hardcover_fbid"];
		setcookie("hardcover_pagebatch", $offset, time()+86400, "/");
		$param->limit = $limit;
		$param->offset = $offset;
		
		$book_pages = $this->AlbumModel->get_book_pages($param);
		$total_pages = $this->AlbumModel->get_total_pages($book_info_id);
		$data['total_pages'] = $total_pages['data'];		
		$data['book_pages'] = $book_pages['data'];
		echo json_encode($data);	
	}
	
	public function preview(){
		/**
		 * Whole screen display of your HardCover album
		 * 
		 */
		$this->load->view("albumpreview");
	}
	//retrieves the  filter page for display
	public function filter_page(){		
		$param = new stdClass();
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$param->book_name = $this->input->post('book_name');
		$fbid = $_COOKIE['hardcover_fbid'];
		//having a book_name means the user is now creating or update the book
		//if ($param->book_name && ($param->book_name != 0 || $param->book_name != '0')) $res = $this->AlbumModel->set_book_info($param);
		
		//ge the filter information for the said book.
		$data['book_filter'] = $this->AlbumModel->get_filter($param);
		$data['user_friend_location'] = $this->AlbumModel->get_friend_location($fbid);
		$ret->status = 0;
		$ret->msg = '';
		$ret->data = $this->load->view('filter_page',$data,TRUE);
		echo json_encode($ret);
	}
	
	public function popup_screens(){
		$ret->status = 0;
		$ret->msg = '';
		
		$param->facebook_id = $_COOKIE['hardcover_fbid'];
		$data['book_creator'] = $this->AlbumModel->get_book_creator($param);
		$ret->data = $this->load->view('albumpopupscreens',$data,TRUE);
		echo json_encode($ret);
	}
	
	public function my_album(){
		$fbid = $_COOKIE['hardcover_fbid'];
		$ret->status = 0;
		$ret->msg = '';
		$data['booklist'] = $this->AlbumModel->get_booklist($fbid);		
		$ret->data = $this->load->view('albumview',$data,TRUE);
		echo json_encode($ret);
	}
	
	function save_pdf(){
		$ret->status=0;
		$ret->msg = '';		
		$book_info_id = $this->input->post('book_info_id');
		$ret->data = $this->create_book_pdf($book_info_id);
		//$ret->data=$book_info_id;		
		echo json_encode($ret);
	}
	
	function add_app(){
		$book_info_id = $this->input->post('book_info_id');
		$friends_fbid = $this->input->post('friends_fbid');
		
		$token = $this->input->post('token');
		$param->status = 'approve'; //based on db ENUM; pending /  approve / denied
		$param->book_info_id = $book_info_id;
		$param->friends_fbid = $friends_fbid;
		$this->update_fbdata($param->friends_fbid ,$token);
		$this->AlbumModel->set_friends_being_askfor_fbdata($param);
		$ret->status = 0;
		$ret->msg = '';
		echo json_encode($ret);
	}

	function invite_friends(){
		$ret->status=0;
		$ret->msg = '';	
		$ret->data = $this->load->view('invite_friends',$data,TRUE);
		echo json_encode($ret);		
	}
	
	function help(){
		$ret->status=0;
		$ret->msg = '';	
		$ret->data = $this->load->view('help',$data,TRUE);
		echo json_encode($ret);		
	}
	
	function about(){
		$ret->status=0;
		$ret->msg = '';	
		$ret->data = $this->load->view('about',$data,TRUE);
		echo json_encode($ret);		
	}
	
	//this is called after the JS plot the content as was able to determine the page number of the book
	function save_pagenum(){
		$pagenum = $this->input->post('pagenum');
		if ($pagenum){
			$arr_pagenum = explode(',',$pagenum);
			$arr_fbdata = array();
			$arr_pageid = array();
			
			
			//$arr_pagenum = explode(',','fbid_12345:1,cid_234345345:1,fbid_56789:2,cid_675498698:2,cid_675498698:3,fbid_45687:4,fbid_34566:5');
			$total_data = count($arr_pagenum);
			for($x=0;$x<$total_data;$x++){
				//echo $arr_pagenum[$x] . '==' .strpos($arr_pagenum[$x],'fbid') . '<br/>';
				if (strpos($arr_pagenum[$x],'fbid')===0){
					$tmp = substr($arr_pagenum[$x],5);
					$arr_pageid = explode(':',$tmp);
					$arr_fbdata[$arr_pageid[0]] = $arr_pageid[1].':'.$arr_pageid[2]; 
				}else{
					$tmp = substr($arr_pagenum[$x],4);
					$arr_pageid = explode(':',$tmp);
					$arr_comment[$arr_pageid[0]] = $arr_pageid[1];
				}
			}
			
			$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
			$ret = $this->AlbumModel->set_pagenum($param,$arr_fbdata,$arr_comment);
			$this->get_book_pages();
		}else{
			$ret->status = 1;
			$ret->msg = 'No page number posted';
			$data='';
			echo json_encode($ret);
		}
	}
	
	//called when the DONE button is press fromt he Edit Album page
	//to invoke the server script to creat the static pages for the unique url
	//by the way, we can directly called the static_page_creation method but we dont want the response from the server to wait for our
	//reponse, so we invoke  the server script to run this for us in another thread.
	public function create_static_book(){
		$ret->status = 0;
		$ret->msg = '';
		$ret->data = '';
		
		$book_info_id = $this->input->post('book_info_id') ;
		$fb_username = $this->input->post('fb_username');
		
		$ret->data = $this->config->item('base_url')."/books/$fb_username/$book_info_id";
		
		$this->create_static_pages_for_uniqueurl($book_info_id,$fb_username);
		
		echo json_encode($ret);
	}	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////PRIVATE
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	private function create_static_pages_for_uniqueurl($book_info_id, $fb_username){
		$create_static = $this->config->item('tools')."/create_pageflip_static_pages.php $book_info_id $fb_username";
		$command = " php -f $create_static";
		exec("$command > ".$this->config->item('updater_log_folder') . " &",$arrOutput);
	}
	
	private function create_book_pdf($book_info_id){
		$return = array();
		$create_pdf_content = $this->config->item('tools')."/create_pdf_content.php $book_info_id";
		$command = " php -f $create_pdf_content";
		//exec("$command > /dev/null &",$arrOutput);		
		//$command = str_replace("php", "D:\xampp\php\php.exe", $command);
		exec("$command > ".$this->config->item('updater_log_folder') . " &", $arrOutput);
		//pclose(popen("start /B ".str_replace("php", "D:\xampp\php\php.exe", $command)." 2> ". $this->config->item("base_url"), "r"));
		
		//exec("start /B \"PHP\"". $command ." > ".$this->config->item("base_dir"), $arrOutput);
		$return[] = $arrOutput;
		$create_pdf_cover = $this->config->item('tools')."/create_pdf_cover.php $book_info_id";
		$command = " php -f $create_pdf_cover";
		//$command = str_replace("php", "D:\xampp\php\php.exe", $command);
		exec("$command > ".$this->config->item('updater_log_folder') . " &",$arrOutput);
		//$command = str_replace("php", "\"\"D:\\xampp\\php\\php.exe\"\"", $command);
		//exec("start /B ""PHP""". $command ." > ". $this->config->item("updater_log_folder"), $arrOutput);
		$return[] = $arrOutput;
		//pclose(popen("start /B ".str_replace("php", "D:\xampp\php\php.exe", $command)." 2> ". $this->config->item("updater_log_folder"), "r"));
		//print_r($arrOutput);
		//return json_encode((Object)$arrOutput);
		//$return += str_replace("php", "D:\xampp\php\php.exe", $command);
		//$return += $command;
		//$return = explode(" ", $return);
		return json_encode($return);
	}
	
	private function retrieve_fbdata($fbid,$token){
		/* commented and uses update as it have the new code
		$retriever_url = $this->config->item('tools')."/fbdata_retriever.php $fbid $token";
		$command = " php -f $retriever_url";
		exec("$command > /dev/null &",$arrOutput);
		*/
		
		$this->update_fbdata($fbid,$token);		
	}
	
	private function update_fbdata($fbid,$token){
		//update the fb data statuses
		$updater_url = $this->config->item('tools')."/fbstatus_updater.php $fbid $token";
		$command = " php -f $updater_url";
		exec("$command > ".$this->config->item('updater_log_folder') . " &",$arrOutput);
			
		//update the fb data feed
		$updater_url = $this->config->item('tools')."/fbfeed_updater.php $fbid $token";
		$command = " php -f $updater_url";
		exec("$command > ".$this->config->item('updater_log_folder') . " &",$arrOutput);

		//update the fb data photo
		$updater_url = $this->config->item('tools')."/fbphoto_updater.php $fbid $token";
		$command = " php -f $updater_url";
		exec("$command > ".$this->config->item('updater_log_folder') . " &",$arrOutput);

		//update the fb data photo
		$updater_url = $this->config->item('tools')."/fbalbumphoto_updater.php $fbid $token";
		$command = " php -f $updater_url";
		exec("$command > ".$this->config->item('updater_log_folder') . " &",$arrOutput);


		//update user fb friends
		$updater_url = $this->config->item('tools')."/fbfriends_updater.php $fbid $token";
		$command = " php -f $updater_url";
		exec("$command > ".$this->config->item('updater_log_folder') . " &",$arrOutput);
	}

	
	private function book_page_organizer($book_info_id,$start_page_num,$action=0){
		$book_organizer = $this->config->item('tools')."/book_page_organizer.php $book_info_id $start_page_num $action";
		$command = " php -f $book_organizer";
		exec("$command > ".$this->config->item('updater_log_folder') . " &",$arrOutput);
	}
	

	private function format_db_friends($fb_friends){
		foreach ($fb_friends as $friend ){
			$friends_fbid .= ';'.$friend->friends_fbid;
		}
		$friends_fbid = substr($friends_fbid,1);		
		return $friends_fbid;		
	}
	
	private function format_db_friends_withname($fb_friends){
		foreach ($fb_friends as $friend ){
			$friends_withname .= ';'.$friend->friends_fbid.':'.$friend->friends_name;
		}
		$friends_withname = substr($friends_withname,1);		
		return $friends_withname;		
	}
	
	/*
	//call server to run the script to retrieve friends id and save to db for the album cover
	private function retrieve_friends_for_coverphoto($param){	
		$retriever_url = $this->config->item('tools').'/get_fbfriends.php?book_info_id='.$param->book_info_id;
		$command = " php -f $retriever_url";
		exec("$command > /dev/null &",$arrOutput);
	}*/
	

	private function init_fb_login(){
		$params->app_id = $this->config->item('fb_appkey');
	   	$params->app_secret = $this->config->item('fb_appsecret');
	   	$params->redirect_url = $this->config->item('fb_canvaspage');

	   	$res->status = 0;
		$res->msg = '';
		
		$fbid = $_COOKIE['hardcover_fbid'];
		//print "func init_fb_login fbid: " . $fbid;
		if(empty($fbid)) {
			//$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
			$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
						. $params->app_id . "&redirect_uri=" . urlencode($params->redirect_url) 						
						. '&scope=' . $this->config->item('fb_scope_permission');
						//. '&state='  . $_SESSION['state']
			$res->data = "<script> top.location.href='" . $dialog_url . "'</script>";			
			$res->status = 1;	//need to login
		}		
		return $res;
	}
	
	//checks if the user has ru the fbdata_updater in the server
	private function run_fbdata_updater($facebook_id,$token){
		$last_run = $this->AlbumModel->get_lastrun_fbdata_updater($facebook_id,$token);
		
		if ($last_run['data']!=false){
			$lastrun_date = date('Y-m-d',strtotime($last_run['data']));
			$today_date = date('Y-m-d');
			$start = strtotime($lastrun_date);
			$end = strtotime($today_date);
			$days_diff = ceil(abs($end - $start) / 86400);

			//$day_diff = gregoriantojd(12, 25, 2010) - gregoriantojd(2, 19, 2010);
			if ($days_diff > 0) {				
				$this->AlbumModel->set_lastrun_fbdata_updater($facebook_id,$token);
				$this->update_fbdata($facebook_id,$token);
			}
		}else
			$this->update_fbdata($facebook_id,$token);	
				
	}
}
?>

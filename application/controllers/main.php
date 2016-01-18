<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
class Main extends CI_Controller {
	protected $path_img_upload_folder;
	protected $path_img_thumb_upload_folder;
	protected $path_url_img_upload_folder;
	protected $path_url_img_thumb_upload_folder;
	protected $delete_img_url;
	//test 1
	public function __construct() {
		parent::__construct();
		$this->load->model("main_model");
		$this->load->model("filter_model");
		$this->load->model("DeleteModel", "Delete");
		$this->load->model("GroupEditModel", "GE");
		//marlo end here 01/05/2013
		$this->load->model("AlbumModel");
		$this->load->helper(array(
			'form',
			'url'
		));
		$this->smarty->assign('base_url', $this->config->item('base_url'));
		$this->smarty->assign('css', $this->config->item('css_url'));
		$this->smarty->assign('js', $this->config->item('js_url'));
		$this->smarty->assign('img', $this->config->item('image_url'));
		$this->smarty->assign('fb_appkey', $this->config->item('fb_appkey'));
		//Set relative Path with CI Constant
		$this->setPath_img_upload_folder("uploads/");
		$this->setPath_img_thumb_upload_folder("uploads/thumbnail/");
		//Delete img url
		$this->setDelete_img_url(base_url() . 'admin/deleteimg/');
		//Set url img with Base_url()
		$this->setPath_url_img_upload_folder(base_url() . "uploads/");
		$this->setPath_url_img_thumb_upload_folder(base_url() . "uploads/thumbnail/");
	}
	function update_thumb_order() {
		$book_pages = $this->main_model->update_thumb_order($_POST);
	}
	function update_thumb_jump_order() {
		$param               = new stdClass();
		$offset              = 0;
		$limit               = 100;
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$data['book_id']     = $_COOKIE['hardcover_book_info_id'];
		$param->facebook_id  = $_COOKIE['hardcover_fbid'];
		$param->limit        = $limit;
		$param->offset       = $offset;
		//pagination
		$this->load->library('pagination');
		$config['base_url']   = "main/edit_album";
		$config['total_rows'] = $this->main_model->get_book_content_count($param);
		$data['total_pages']  = $config['total_rows'];
		$config['per_page']   = 100;
		$this->pagination->initialize($config);
		$page_info = '';
		if (isset($_POST['page'])) {
			$param->page = $_POST['page'];
		} else {
			$param->page = 0;
		}
		$book_pages    = $this->main_model->get_book_content_paginate($param);
		$curr_order_id = $book_pages['data'][0]->thumb_view_order;
		$book_pages    = $this->main_model->update_thumb_jump_order($curr_order_id + 1, $_POST['ids']);
	}
	function get_all_data($book_info_id) {
		$param->book_info_id = $book_info_id;
		$param->facebook_id  = $_COOKIE['hardcover_fbid'];
		$param->limit        = 50;
		$param->offset       = 0;
		$book_pages          = $this->main_model->get_book_pages_for_testpage($param);
		$data['book_pages']  = $book_pages['data'];
		echo json_encode($data);
	}
	function get_book_filter() {
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		//ge the filter information for the said book.
		$data['book_filter'] = $this->main_model->get_filter($param);
		print_r($data['book_filter']);
	}
	function error($code) {
		$this->load->view('4oh4');
	}
	public function index() {
		/**
		 * Author: Dennis
		 * This page redirects the user either to the FB Auth or directly the HC dashboard
		 */
		 
		$data['isMobile'] = $this->check_user_agent('mobile');
		$u_username       = $_REQUEST["user"];
		$u_book           = $_REQUEST["book"];
		if ($u_username && $u_book) {
			$data["user"] = $u_username;
			$data["book"] = $u_book;
			$this->load->view('router', $data);
		}
		$signed_request_data = $this->main_model->CheckIfSigned();
		$fbid                = $signed_request_data['user_id'];
		$token               = $signed_request_data['oauth_token'];
		$this->run_fbdata_updater($fbid, $token);
        
		if ($fbid) {		    
			setcookie('hardcover_fbid', $fbid, time() + 86400, '/');
			$_COOKIE['hardcover_fbid'] = $fbid;
			$data['fbid']              = $fbid;
			$param                     = new stdClass();
			$param->facebook_id        = $fbid;            
			$data['user_details']      = $this->main_model->get_book_creator($param);
			$user_status               = $this->main_model->is_returning_user($param);
			$this->init_user_profile($fbid, $token);
            
			if ($user_status['data']) {
			    			    
				$data['dashboard_detils'] = $this->main_model->get_dashboard_detils($fbid);
				foreach ($data['dashboard_detils'] as $k => $v) {
					$data['user_detils']                                    = $this->main_model->get_users_detils($v->book_info_id);
					$data['dashboard_detils'][$k]->book_name                = $data['user_detils'][0]->book_name;
					$data['dashboard_detils'][$k]->book_owner_facebook_id   = $data['user_detils'][0]->facebook_id;
					$data['user_name']                                      = $this->main_model->get_users_fb_name($data['user_detils'][0]->facebook_id);
					$data['dashboard_detils'][$k]->book_owner_facebook_name = $data['user_name'][0]->fname . ' ' . $data['user_name'][0]->lname;
				}
                
				$fb_user                      = $this->main_model->get_book_creator($param);
				$data['fb_user']              = $fb_user;
				$data['booklist']             = $this->main_model->get_booklist($fbid);
				$data['chapters_for_friends'] = $this->main_model->get_chapters_for_friends($fbid);
				$data['album_new_contents']   = $this->main_model->get_bookpages_ready_to_share($fbid);
				
				if ($data['booklist'] != false)
					$data['html'] = $this->load->view('new-home', $data, TRUE);
				else
					$data['html'] = $this->load->view('book-no-book', $data, TRUE);
                
				$data['signed_request_data'] = $signed_request_data;
				$this->load->view('new-ui-container', $data);
			} else {
				$this->load->view('welcome.html');
			}
		} else {
			if (preg_match("/hardcover.me/i", $_SERVER['HTTP_HOST'], $matches)) {
				$this->load->view('welcome.html');
			} else {
				$res                     = $this->init_fb_login();
				$data['redirect_script'] = "";
				if (isset($res->data))
					$data['redirect_script'] = $res->data;
				echo "init fb";
				$this->load->view('init_fb', $data);
			}
		}
	}
	public function new_album_content_approval() {
		$str = $this->uri->segment(3);
		$act = $str[0];
		$id  = substr($str, 1);
		$this->main_model->new_content_approval($act, $id);
		echo $act;
	}
	public function new_album_contents_page() {
		$this->load->view('new_album_contents_page', '');
	}
	function init_user_profile($fbid, $token) {
        $params                     = new stdClass();
        $params->app_id = $this->config->item('fb_appkey');
        $params->app_secret = $this->config->item('fb_appsecret');
        $params->base_dir = $this->config->item('base_dir');        
		$fbuser = getFacebookUserDetails($params);
        
		$param  = new stdClass();
		$fbid   = (empty($fbid) || is_null($fbid) || !$fbid) ? "" : $fbid;
		if (isset($fbid))
			$param->facebook_id = $fbid;
		$param->fname       = $fbuser['first_name'];
		$param->lname       = $fbuser['last_name'];
		$param->fb_username = $fbuser['username'];
		$param->fbdata      = serialize($fbuser);
		$this->main_model->set_book_creator($param);
        
	}
    
	//this function is called when the GET STARTED button is press
	public function init_fb() {
		$param    = new stdClass();
		$res      = $this->init_fb_login(); //this will call the fb autho
		$ret->msg = '';
		if ($res->status == 1) {
			$ret->status = $res->status; //there is a process error
			$ret->data   = $res->data;
		} else if ($res->status == 2) {
			$ret->status = 0; //open the fb auth login
			$ret->data   = $res->data;
		} else { //everything is good; app has been added before
			$ret->status          = 0;
			//this will check if this is a first time user so you can display the Create Store immediately and not the summary page
			$fbid                 = $_COOKIE['hardcover_fbid'];
			$param->facebook_id   = $fbid;
			$data['user_details'] = $this->main_model->get_book_creator($param);
			//marlo edit starts here 01/05/2013
			//$this->load->model("DeleteModel");
			//$deleted_albums = $this->DeleteModel->getDeletedAlbums($fbid);
			//$data["deleted_albums"] = $deleted_albums;
			$this->load->model("GroupEditModel");
			$photos_share_sent         = $this->GroupEditModel->getShareSentInfo($fbid);
			$data["photos_share_sent"] = $photos_share_sent;
			//marlo edit ends here 01/05/2013
			$total_books               = $this->main_model->get_total_books($fbid);
			$data['dashboard_detils']  = $this->main_model->get_dashboard_detils($_COOKIE['hardcover_fbid']);
			foreach ($data['dashboard_detils'] as $k => $v) {
				$data['user_detils']                                    = $this->main_model->get_users_detils($v->book_info_id);
				$data['dashboard_detils'][$k]->book_name                = $data['user_detils'][0]->book_name;
				$data['dashboard_detils'][$k]->book_owner_facebook_id   = $data['user_detils'][0]->facebook_id;
				$data['user_name']                                      = $this->main_model->get_users_name($data['dashboard_detils'][0]->book_owner_facebook_id);
				$data['dashboard_detils'][$k]->book_owner_facebook_name = $data['user_name'][0]->friends_name;
			}
			if ($total_books['data'] > 0) {
				$data['booklist']             = $this->main_model->get_booklist($fbid);
				$data['chapters_for_friends'] = $this->main_model->get_chapters_for_friends($fbid);
				$ret->data                    = $this->load->view('book_summarylist', $data, TRUE);
			} else {
				if ($this->start_create_book())
					$ret->data = $this->load->view('filter_page', '', TRUE);
				else {
					$ret->status = 1;
					$ret->msg    = 'Error creating book.';
				}
			}
		}
		echo json_encode($ret);
	}
	public function get_last_insert_images() {
		$ret = $this->main_model->get_last_insert_images();
		echo json_encode($ret);
	}
	//marlo starts here 12.23.2012
	public function home_select_booktype() {
		$ret->status = 0;
		$ret->msg    = 'No Data';
		if ($_REQUEST['code']) {
			//just authenticated the application
			$params->app_id       = $this->config->item('fb_appkey');
			$params->redirect_url = $this->config->item('fb_canvaspage') . '/main/filter_page/';
			$params->app_secret   = $this->config->item('fb_appsecret');
			$params->code         = trim($_REQUEST['code']);
			$token_info           = getAccessToken($params);
			$user                 = getFacebookUserDetails($params);
			$this->retrieve_fbdata($user->id, $token_info->access_token);
			$this->start_create_book();
		} else {
			//this is creating another book
			$ret->status = 1;
			$ret->msg    = '';
		}
		$param                    = new stdClass();
		$param->book_info_id      = $_COOKIE['hardcover_book_info_id'];
		$param->facebook_id       = $_COOKIE['hardcover_fbid'];
		$data['token']            = $_COOKIE['hardcover_token'];
		$data['book_filter']      = $this->filter_model->get_filter($param);
		$data['user_albums']      = $this->filter_model->get_user_albums($param);
		$data['user_albums_data'] = array();
		$p_data                   = '';
		foreach ($data['user_albums'] as $k1 => $v1) {
			$p_data          = '';
			$param->album_id = $v1->album_id;
			$photo_data      = $this->filter_model->get_album_photos($param);
			if (count($photo_data) > 0) {
				$p_data .= '<ul style="display:none;" class="alb_photo hide albb_' . $v1->album_id . '" id="photos_from_album"  >';
				foreach ($photo_data as $k => $v) {
					$p_data .= '<li><img  src="' . $v->small . '"/><br/><center><input type="checkbox"  name="photo[' . $v1->album_id . '][' . $v->fb_dataid . ']" id="photo_' . $v->fb_dataid . '" value="' . $v->fb_dataid . '" /></center></li>';
				}
				$p_data .= '</ul>';
			}
			$data['user_albums_data'][$v1->album_id] = $p_data;
		}
		$ret->data = $this->load->view('filter_page_new', $data, TRUE);
		echo json_encode($ret);
	}
	public function home_select_booktype_cover() {
		$ret->status = 0;
		$ret->msg    = 'No Data';
		if ($_REQUEST['code']) {
			//just authenticated the application
			$params->app_id       = $this->config->item('fb_appkey');
			$params->redirect_url = $this->config->item('fb_canvaspage') . '/main/filter_page/';
			$params->app_secret   = $this->config->item('fb_appsecret');
			$params->code         = trim($_REQUEST['code']);
			$token_info           = getAccessToken($params);
			$user                 = getFacebookUserDetails($params);
			$this->retrieve_fbdata($user->id, $token_info->access_token);
			$this->start_create_book();
		} else {
			//this is creating another book
			$ret->status = 1;
			$ret->msg    = '';
		}
		$param                    = new stdClass();
		$param->book_info_id      = $_COOKIE['hardcover_book_info_id'];
		$param->facebook_id       = $_COOKIE['hardcover_fbid'];
		$data['token']            = $_COOKIE['hardcover_token'];
		$data['book_filter']      = $this->filter_model->get_filter($param);
		$data['user_albums']      = $this->filter_model->get_user_albums($param);
		$data['user_albums_data'] = array();
		$p_data                   = '';
		foreach ($data['user_albums'] as $k1 => $v1) {
			$p_data          = '';
			$param->album_id = $v1->album_id;
			$photo_data      = $this->filter_model->get_album_photos($param);
			if (count($photo_data) > 0) {
				$p_data .= '<ul style="display:none;" class="alb_photo hide albb_' . $v1->album_id . '" id="photos_from_album"  >';
				foreach ($photo_data as $k => $v) {
					$p_data .= '<li><img  src="' . $v->small . '"/><br/><center><input type="checkbox"  name="photo[' . $v1->album_id . '][' . $v->fb_dataid . ']" id="photo_' . $v->fb_dataid . '" value="' . $v->fb_dataid . '" /></center></li>';
				}
				$p_data .= '</ul>';
			}
			$data['user_albums_data'][$v1->album_id] = $p_data;
		}
		$ret->data = $this->load->view('filter_page_new_cover', $data, TRUE);
		echo json_encode($ret);
	}
	public function home_select_booktype1() {
		$ret->status = 0;
		$ret->msg    = 'No Data';
		if ($_REQUEST['code']) {
			//just authenticated the application
			$params->app_id       = $this->config->item('fb_appkey');
			//$params->redirect_url = $this->config->item('fb_canvaspage') . '/main/home_select_booktype/';
			$params->redirect_url = $this->config->item('fb_canvaspage') . '/main/filter_page/';
			$params->app_secret   = $this->config->item('fb_appsecret');
			$params->code         = trim($_REQUEST['code']);
			$token_info           = getAccessToken($params);
			$user                 = getFacebookUserDetails($params);
			//setcookie("hardcover_fbid", $user->id, 0,'/');
			//setcookie("hardcover_token", $token_info->access_token,0,'/');	//expires in 2hrs
			$this->retrieve_fbdata($user->id, $token_info->access_token);
			$this->start_create_book();
		} else {
			//this is creating another book
			$ret->status = 1;
			$ret->msg    = '';
			//$this->start_create_book();
			//$this->start_create_book();
		}
		$param                    = new stdClass();
		$param->book_info_id      = $_COOKIE['hardcover_book_info_id'];
		$param->facebook_id       = $_COOKIE['hardcover_fbid'];
		$data['token']            = $_COOKIE['hardcover_token'];
		$data['book_filter']      = $this->filter_model->get_filter($param);
		$data['user_albums']      = $this->filter_model->get_user_albums($param);
		$data['user_albums_data'] = array();
		$p_data                   = '';
		foreach ($data['user_albums'] as $k1 => $v1) {
			$p_data          = '';
			$param->album_id = $v1->album_id;
			$photo_data      = $this->filter_model->get_album_photos($param);
			if (count($photo_data) > 0) {
				$p_data .= '<ul style="display:none;" class="alb_photo hide albb_' . $v1->album_id . '" id="photos_from_album"  >';
				foreach ($photo_data as $k => $v) {
					$p_data .= '<li><img  src="' . $v->small . '"/><br/><center><input type="checkbox"  name="photo[' . $v1->album_id . '][' . $v->fb_dataid . ']" id="photo_' . $v->fb_dataid . '" value="' . $v->fb_dataid . '" /></center></li>';
				}
				$p_data .= '</ul>';
			}
			$data['user_albums_data'][$v1->album_id] = $p_data;
		}
		// print_r($data); exit;
		$ret->data = $this->load->view('filter_page_new1', $data, TRUE);
		echo json_encode($ret);
	}
	public function home_select_booktype_unique() {
		$ret->status = 0;
		$ret->msg    = 'No Data';
		if ($_REQUEST['code']) {
			//just authenticated the application
			$params->app_id       = $this->config->item('fb_appkey');
			//$params->redirect_url = $this->config->item('fb_canvaspage') . '/main/home_select_booktype/';
			$params->redirect_url = $this->config->item('fb_canvaspage') . '/main/filter_page/';
			$params->app_secret   = $this->config->item('fb_appsecret');
			$params->code         = trim($_REQUEST['code']);
			$token_info           = getAccessToken($params);
			$user                 = getFacebookUserDetails($params);
			//setcookie("hardcover_fbid", $user->id, 0,'/');
			//setcookie("hardcover_token", $token_info->access_token,0,'/');	//expires in 2hrs
			$this->retrieve_fbdata($user->id, $token_info->access_token);
			$this->start_create_book();
		} else {
			//this is creating another book
			$ret->status = 1;
			$ret->msg    = '';
		}
		$param                    = new stdClass();
		$param->book_info_id      = $_COOKIE['hardcover_book_info_id'];
		$param->facebook_id       = $_COOKIE['hardcover_fbid'];
		$data['token']            = $_COOKIE['hardcover_token'];
		$data['book_filter']      = $this->filter_model->get_filter($param);
		$data['user_albums']      = $this->filter_model->get_user_albums($param);
		$data['user_albums_data'] = array();
		$p_data                   = '';
		foreach ($data['user_albums'] as $k1 => $v1) {
			$p_data          = '';
			$param->album_id = $v1->album_id;
			$photo_data      = $this->filter_model->get_album_photos($param);
			if (count($photo_data) > 0) {
				$p_data .= '<ul style="display:none;" class="alb_photo hide albb_' . $v1->album_id . '" id="photos_from_album"  >';
				foreach ($photo_data as $k => $v) {
					$p_data .= '<li><img  src="' . $v->small . '"/><br/><center><input type="checkbox"  name="photo[' . $v1->album_id . '][' . $v->fb_dataid . ']" id="photo_' . $v->fb_dataid . '" value="' . $v->fb_dataid . '" /></center></li>';
				}
				$p_data .= '</ul>';
			}
			$data['user_albums_data'][$v1->album_id] = $p_data;
		}
		// print_r($data); exit;
		$ret->data = $this->load->view('filter_page_new_unique', $data, TRUE);
		echo json_encode($ret);
	}
	public function home_select_booktype_unique_mobile() {
		$ret->status = 0;
		$ret->msg    = 'No Data';
		if ($_REQUEST['code']) {
			//just authenticated the application
			$params->app_id       = $this->config->item('fb_appkey');
			//$params->redirect_url = $this->config->item('fb_canvaspage') . '/main/home_select_booktype/';
			$params->redirect_url = $this->config->item('fb_canvaspage') . '/main/filter_page/';
			$params->app_secret   = $this->config->item('fb_appsecret');
			$params->code         = trim($_REQUEST['code']);
			$token_info           = getAccessToken($params);
			$user                 = getFacebookUserDetails($params);
			//setcookie("hardcover_fbid", $user->id, 0,'/');
			//setcookie("hardcover_token", $token_info->access_token,0,'/');	//expires in 2hrs
			$this->retrieve_fbdata($user->id, $token_info->access_token);
			$this->start_create_book();
		} else {
			//this is creating another book
			$ret->status = 1;
			$ret->msg    = '';
			//$this->start_create_book();
			//$this->start_create_book();
		}
		$param                    = new stdClass();
		$param->book_info_id      = $_COOKIE['hardcover_book_info_id'];
		$param->facebook_id       = $_COOKIE['hardcover_fbid'];
		$data['token']            = $_COOKIE['hardcover_token'];
		$data['book_filter']      = $this->filter_model->get_filter($param);
		$data['user_albums']      = $this->filter_model->get_user_albums($param);
		$data['user_albums_data'] = array();
		$p_data                   = '';
		foreach ($data['user_albums'] as $k1 => $v1) {
			$p_data          = '';
			$param->album_id = $v1->album_id;
			$photo_data      = $this->filter_model->get_album_photos($param);
			if (count($photo_data) > 0) {
				$p_data .= '<ul style="display:none;" class="alb_photo hide albb_' . $v1->album_id . '" id="photos_from_album"  >';
				foreach ($photo_data as $k => $v) {
					$p_data .= '<li><img  src="' . $v->small . '"/><br/><center><input type="checkbox"  name="photo[' . $v1->album_id . '][' . $v->fb_dataid . ']" id="photo_' . $v->fb_dataid . '" value="' . $v->fb_dataid . '" /></center></li>';
				}
				$p_data .= '</ul>';
			}
			$data['user_albums_data'][$v1->album_id] = $p_data;
		}
		// print_r($data); exit;
		$ret->data = $this->load->view('filter_page_new_unique_mobile', $data, TRUE);
		echo json_encode($ret);
	}
	public function get_album_photos() {
		$param                = new stdClass();
		$param->album_id      = $_POST['alb_id'];
		$photo_data           = $this->filter_model->get_album_photos($param);
		//$photo_data = $this->filter_model->get_album_user_photos($param);
		$p_data               = '';
		$offset               = 0;
		$limit                = 100;
		$param                = new stdClass();
		$param->book_info_id  = $_COOKIE['hardcover_book_info_id'];
		$data['book_id']      = $_COOKIE['hardcover_book_info_id'];
		$b_name               = $this->AlbumModel->get_book_cover($_COOKIE['hardcover_book_info_id']);
		$_COOKIE['book_name'] = $b_name->book_name;
		$param->facebook_id   = $_COOKIE['hardcover_fbid'];
		$param->limit         = $limit;
		$param->offset        = $offset;
		$book_pages           = $this->main_model->get_book_content($param);
		$temp                 = array();
		foreach ($book_pages['data'] as $key => $val) {
			$temp[] = $val->fb_dataid;
		}
		if (count($photo_data) > 0) {
			$i = 1;
			foreach ($photo_data as $k => $v) {
				$st = '';
				if (in_array($v->fb_dataid, $temp))
					$st = "checked='checked'";
				if ($i % 5 == 1)
					$p_data .= "<div style='width:102%'>";
				$p_data .= '<span class="rawphoto cla_' . $_POST['alb_id'] . '"><center class="span"><img  src="' . '/timthumb.php?src=' . $v->small . '?w=90&h=130&a=t' . '"/></center><center><input ' . $st . ' type="checkbox" class="alb_photo_vv" name="photo[' . $_POST['alb_id'] . '][' . $v->fb_dataid . ']" id="photo_' . $v->fb_dataid . '" value="' . $v->fb_dataid . '" /></center></span>';
				if ($i % 5 == 0)
					$p_data .= "</div>";
				$i++;
			}
			echo $p_data;
		}
		exit;
	}
	function getPhotosOfAlbums() {
		/**
		 * Author: Dennis
		 * Returns the list of photos of that particular album;
		 * Format the return into a 5 photos per row
		 */
		$this->load->model('photo_albums_model');
		$ret->status = 200;
		$ret->msg    = 'No Data';
		$album_id    = $_POST['album_id'];
		$photo_data  = $this->photo_albums_model->getPhotosOfAlbum($album_id);
		$i           = 1;
		foreach ($photo_data['data'] as $k => $v) {
			if ($i % 6 == 1)
				$photo .= "<div style='width:102%'>";
			$photo .= '<span class="rawphoto"><center class="span"><img  src="' . '/timthumb.php?src=' . $v->small . '?w=90&h=130&a=t' . '"/></center><center><input type="checkbox" rel="' . $v->hd . '" class="album_photo" name="photo_' . $album_id . '_' . $v->fb_dataid . '" id="photo_' . $v->fb_dataid . '" value="' . $v->fb_dataid . '" /></center></span>';
			if ($i % 6 == 0)
				$photo .= "</div>";
			$i++;
		}
		$ret->data = $photo;
		echo json_encode($ret);
	}
	public function album_cover() {
		$ret->status       = 0;
		$ret->msg          = '';
		$data['book_info'] = $this->main_model->get_book_cover($book_info_id);
		$ret->data         = $this->load->view('album_cover', $data, TRUE);
		echo json_encode($ret);
	}
	//this is called after
	public function first_page() {
		$ret->status = 0;
		$ret->msg    = '';
		$ret->data   = $this->load->view('first_page', '', TRUE);
		echo json_encode($ret);
	}
	//this is call to list the albums/quotes being created
	public function book_summarylist() {
		$ret->status      = 0;
		$ret->msg         = '';
		$fbid             = $_COOKIE['hardcover_fbid'];
		$data['booklist'] = $this->main_model->get_booklist($fbid);
		$ret->data        = $this->load->view('book_summarylist', $data, TRUE);
		echo json_encode($ret);
	}
	//get the user fb friends and format it in command-separated
	public function get_fb_friends($fbid = 0) {
		$param->facebook_id = empty($_COOKIE['hardcover_fbid']) ? $fbid : $_COOKIE['hardcover_fbid'];
		$fb_friends         = $this->main_model->get_friends($param, $this->config->item('initial_album_cover_profile'), 0); //get only 27 friends for initial book cover
		if ($fb_friends) {
			$ret->status       = 0;
			$ret->msg          = '';
			$ret->friends_fbid = $this->format_db_friends($fb_friends);
		} else {
			$ret->status = 1;
			$ret->msg    = 'no data';
		}
		echo json_encode($ret);
	}
	//this will gave us all the the user fb friends without limit and in the following format
	//fbid:name;fbid:name
	public function get_fb_friends_withname() {
		$param->facebook_id = $_COOKIE['hardcover_fbid'];
		$fb_friends         = $this->main_model->get_friends($param); //get only 27 friends for initial book cover
		if ($fb_friends) {
			$ret->status  = 0;
			$ret->msg     = '';
			$ret->friends = $this->format_db_friends_withname($fb_friends);
		} else {
			$ret->status = 1;
			$ret->msg    = 'no data';
		}
		echo json_encode($ret);
	}
	//this will retrive all friends name that starts with;for use in search
	public function get_fb_names() {
		$param->facebook_id = $_COOKIE['hardcover_fbid'];
		$param->first_name  = $this->input->post('first_name');
		if (empty($param->first_name))
			$ret = '';
		else
			$ret = $this->main_model->get_fb_friends_by_name($param);
		echo json_encode($ret);
	}
    
	public function set_name_book_info() {
	    /*
         * This is called when a user create a book
         */
         
		$fbid                = $_COOKIE['hardcover_fbid']?$_COOKIE['hardcover_fbid']:$_COOKIE['c_user'];
        
        //set book book info table with fb basic info
		$param               = new stdClass();			
		$param->facebook_id  = $fbid;
		$param->book_name    = trim($_POST['bookName']);
		$param->book_desc    = trim($_POST['bookDesc']);
        $param->book_size_id = 2;

		$res                 = $this->main_model->set_name_book_creator($param);
        $book_info_id        = $res['data'];
        if ($res['status']==0){
			setcookie('hardcover_book_info_id', $book_info_id, time() + 86400, '/');
            
            if ($_POST['optWithChapter']=='1'){
                $this->main_model->set_book_chapter($param, $book_info_id, $_POST['chapter_name'], $_POST['assigned_friend']);
            }
			
			if (is_array($res) and $res['status'] == 0) {    				
				$this->main_model->init_book_settings($book_info_id);
			}
            $ret = array('status'=>200, 'message'=>'Book created successfully.');
        }else{
            $ret = array('status'=>400, 'message'=>$res['msg']);
        }
		echo json_encode($ret);
		exit;
	}
	//this is call when the NEXT button is clicked from SELECT BOOK TYPE page
	public function start_create_book() {
		$params->app_id = $this->config->item('fb_appkey');
        $params->app_secret = $this->config->item('fb_appsecret');
		$fbuser = getFacebookUserDetails($params);
		if ($fbuser->error) {
			$ret->status = 1;
			$ret->msg    = $fbuser->error->message;
			log_message('debug', $ret->msg);
			return false;
		} else {
			$fbid               = $_COOKIE['hardcover_fbid'];
			//get total books
			$res                = $this->main_model->get_total_books($fbid);
			$total_books        = $res['data'];
			//set book creator table with fb basic info
			$param->facebook_id = $fbid;
			$param->fname       = $fbuser->first_name;
			$param->lname       = $fbuser->last_name;
			$param->fb_username = $fbuser->username;
			$param->fbdata      = serialize($fbuser);
			$this->main_model->set_book_creator($param);
			unset($param);
			//init the data to be stored in the db
			$param               = new stdClass();
			$param->facebook_id  = $fbid;
			$param->book_name    = $fbuser->first_name . ' life in HardCover ' . ($total_books + 1);
			$param->book_type    = 'album'; //constant
			// $param->book_size_id = $this->input->post('booksize'); //commented by mychelle for the meantime, we will use the booktype 2
			$param->book_size_id = 2; //default
			$param->created_date = date('Y-n-j H:i:s');
			$res                 = $this->main_model->set_book_info($param);
			$param->book_info_id = $res['data'];
			//setcookie("hardcover_book_info_id", "", time() - 3600);
			setcookie('hardcover_book_info_id', $param->book_info_id, time() + 86400, '/');
			//CESAR: Save the cover to the DB
			//create a list of friends for the cover
			$fb_friends            = $this->main_model->get_friends($param, $this->config->item('initial_album_cover_profile'), 0); //get only 27 friends for initial book cover
			$friends_in_csv_format = $this->format_db_friends($fb_friends);
			$ret                   = $this->main_model->initialize_book_cover($param, $this->config->item('initial_album_cover_profile'), 0);
			return true;
		}
	}
	public function update_book_info() {
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$param->book_name    = $this->input->post('book_name');
		$param->book_caption = $this->input->post('book_caption');
		$param->book_caption = empty($param->book_caption) ? '' : $param->book_caption; //since we cannot directly make condition; do this instead
		$ret                 = $this->main_model->set_book_info($param);
		echo json_encode($ret);
	}
	//this will present the edit_album.php where the pageflip is shown
	public function edit_album() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		//die(print_r( $signed_request_data ));
		$fbid                = $signed_request_data['user_id'];
		$book_owner_id       = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
        $is_ghost_writer     = $this->main_model->isGhostWriter($fbid,$_COOKIE['hardcover_book_info_id']);
        
        //echo  $_COOKIE['hardcover_book_info_id'];die();
        
		if ($fbid != $book_owner_id && $is_ghost_writer===false):
			$ret->data = $this->select_books();
			$ret->xBid = "true";
			echo json_encode($ret);
		else:
            $param                          = new stdClass();
            
            if ($is_ghost_writer===true){
                $param->facebook_id         = $book_owner_id;
                //setcookie('hardcover_fbid', $book_owner_id, time() + 86400, '/');   //this is the book owner id
                //setcookie('hardcover_fb_user_id', $fbid, time() + 86400, '/');      //this is the ghost writer id
            }else 
                $param->facebook_id         = $_COOKIE['hardcover_fbid'];
            
			$ret->status                    = 0;
			$ret->msg                       = '';

			$param->book_info_id            = $_COOKIE['hardcover_book_info_id'];
			$creator                        = $this->main_model->get_book_creator($param);
			//$b_name = $this->AlbumModel->get_book_cover( $param->book_info_id );
			$book_info                      = $this->main_model->get_book_cover($book_info_id);
			$_COOKIE['book_name']           = $book_info->book_name;
			$data['front_cover']            = $book_info->front_cover;
			$data['back_cover']             = $book_info->back_cover;
			$data["fb_username"]            = $creator->fb_username;
			$data["fb_name"]                = $creator->fname . " " . $creator->lname;
			$data['encrypted_book_info_id'] = $param->book_info_id + $this->config->item('book_info_id_key');
			// start
			$offset                         = 0;
			$limit                          = 100;
			$data['book_id']                = $param->book_info_id;
			$data['fbid']                   = $param->facebook_id;
			//$b_name = $this->AlbumModel->get_book_cover( $param->book_info_id );

			//$_COOKIE['book_name'] = $b_name->book_name;

			//$param->facebook_id = $param->facebook_id;
			$param->limit                   = $limit;
			$param->offset                  = $offset;
			$data['cover_page_selected']    = $this->main_model->cover_page_selected_v($param); //print_r($data['cover_page_selected']);

			//pagination
			$this->load->library('pagination');
			$config['base_url']   = "main/edit_album";
			$config['total_rows'] = $this->main_model->get_book_content_count($param);
			$data['total_pages']  = $config['total_rows'];
			$config['per_page']   = 100;
			$this->pagination->initialize($config);
			$page_info = '';
			$pi        = 0;
			if (isset($_POST['page'])) {
				$param->page = 0;
				$param->page = $_POST['page'];
				$pi          = $param->page + 100;
				if ($config['total_rows'] <= $pi)
					$pi = $config['total_rows'];
				if ($param->page == '')
					$param->page = 1;
				$data['page_info_text'] = "Showing " . ($param->page) . " to " . $pi . " of " . $config['total_rows'];
				$data['pagination']     = $this->pagination->create_links($_POST['page']);
			} else {
				$param->page            = 1;
				$data['page_info_text'] = "Showing 1 to 100 of " . $config['total_rows'];
				$data['pagination']     = $this->pagination->create_links(0);
			}
			$data['pi']         = $param->page;
			// pagination end
			$testJ              = $param;
			$book_pages         = $this->main_model->get_book_content_paginate($param);
			$data['book_pages'] = $book_pages['data'];
			$ret->data          = $this->load->view('new_ui_edit_album', $data, TRUE);
			echo json_encode($ret);
		endif;
	}
	public function new_ui_edit_album() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		//die(print_r( $signed_request_data ));
		$fbid                = $signed_request_data['user_id'];
		$book_owner_id       = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
        $is_ghost_writer     = $this->main_model->isGhostWriter($fbid,$_COOKIE['hardcover_book_info_id']);
        
		
		if ($fbid != $book_owner_id && $is_ghost_writer === false):
			setcookie("hardcover_book_info_id", "", time() - 3600);
			$ret->data = $this->main_model->select_books_again();
			$ret->xBid = "true";
			echo json_encode($ret);
		else:
			$ret->status                    = 0;
			$ret->msg                       = '';
			$param                          = new stdClass();
            if ($is_ghost_writer===true){
                $param->facebook_id         = $book_owner_id;
                //setcookie('hardcover_fbid', $book_owner_id, time() + 86400, '/');   //this is the book owner id
                setcookie('hardcover_fb_user_id', $fbid, time() + 86400, '/');      //this is the ghost writer id
            }else 
                $param->facebook_id         = $_COOKIE['hardcover_fbid'];
                        
			
			$param->book_info_id            = $_COOKIE['hardcover_book_info_id'];
			$creator                        = $this->main_model->get_book_creator($param);
			$b_name                         = $this->AlbumModel->get_book_cover($param->book_info_id);
			$_COOKIE['book_name']           = $b_name->book_name;
			$data["fb_username"]            = $creator->fb_username;
			$data["fb_name"]                = $creator->fname . " " . $creator->lname;
			$data['encrypted_book_info_id'] = $param->book_info_id + $this->config->item('book_info_id_key');
			// start
			$offset                         = 0;
			$limit                          = 500;
			$data['book_id']                = $param->book_info_id;
			$data['fbid']                   = $param->facebook_id;
			$b_name                         = $this->AlbumModel->get_book_cover($param->book_info_id);
			$_COOKIE['book_name']           = $b_name->book_name;
			$param->facebook_id             = $param->facebook_id;
			$param->limit                   = $limit;
			$param->offset                  = $offset;
			$data['cover_page_selected']    = $this->main_model->cover_page_selected_v($param); //print_r($data['cover_page_selected']);

			//pagination
			$this->load->library('pagination');
			$config['base_url']   = "main/edit_album";
			$config['total_rows'] = $this->main_model->get_book_content_count($param);
			$data['total_pages']  = $config['total_rows'];
			$config['per_page']   = 500;
			$this->pagination->initialize($config);
			$page_info   = '';
			$pi          = 0;
			$param->page = 1;
			if (isset($_POST['page']) && $_POST['page'] != "undefined") {
				$param->page = 0;
				$param->page = $_POST['page'] + 1;
				$pi          = $param->page + 99;
				if ($config['total_rows'] <= $pi)
					$pi = $config['total_rows'];
				if ($param->page == '')
					$param->page = 1;
				$data['page_info_text'] = "Showing " . ($param->page) . " to " . $pi . " of " . $config['total_rows'];
				$data['pagination']     = $this->pagination->create_links($_POST['page']);
			} else {
				if ($config['total_rows'] > 100)
					$data['page_info_text'] = "Showing 1 to 100 of " . $config['total_rows'];
				else
					$data['page_info_text'] = "";
				$data['pagination'] = $this->pagination->create_links(0);
			}
			$data['pi']         = $param->page;
			// pagination end
			$book_pages         = $this->main_model->get_book_content_paginate($param);
			$data['book_pages'] = $book_pages['data'];
			//die(print_r($data['book_pages']));
			$ret->data          = $this->load->view('new_ui_edit_album', $data, TRUE);
			echo json_encode($ret);
		endif;
	}
	public function new_ui_rearrange() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		//die(print_r( $signed_request_data ));
		$fbid                = $signed_request_data['user_id'];
		$book_owner_id       = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
        $is_ghost_writer     = $this->main_model->isGhostWriter($fbid,$_COOKIE['hardcover_book_info_id']);
        
        
        if ($fbid != $book_owner_id && $is_ghost_writer === false):
			setcookie("hardcover_book_info_id", "", time() - 3600);
			$ret->data = $this->main_model->select_books_again();
			$ret->xBid = "true";
			echo json_encode($ret);
		else:
			$ret->status                    = 0;
			$ret->msg                       = '';
			$param                          = new stdClass();
            if ($is_ghost_writer===true){
                $param->facebook_id         = $book_owner_id;
                //setcookie('hardcover_fbid', $book_owner_id, time() + 86400, '/');   //this is the book owner id
                setcookie('hardcover_fb_user_id', $fbid, time() + 86400, '/');      //this is the ghost writer id
            }else 
                $param->facebook_id         = $_COOKIE['hardcover_fbid'];

			$param->book_info_id            = $_COOKIE['hardcover_book_info_id'];
			$creator                        = $this->main_model->get_book_creator($param);
			$b_name                         = $this->AlbumModel->get_book_cover($param->book_info_id);
			$_COOKIE['book_name']           = $b_name->book_name;
			$data["fb_username"]            = $creator->fb_username;
			$data["fb_name"]                = $creator->fname . " " . $creator->lname;
			$data['encrypted_book_info_id'] = $param->book_info_id + $this->config->item('book_info_id_key');
			// start
			$offset                         = 0;
			$limit                          = 500;
			$data['book_id']                = $param->book_info_id;
			$data['fbid']                   = $param->facebook_id;
			$b_name                         = $this->AlbumModel->get_book_cover($param->book_info_id);
			$_COOKIE['book_name']           = $b_name->book_name;
			$param->facebook_id             = $param->facebook_id;
			$param->limit                   = $limit;
			$param->offset                  = $offset;
			$data['cover_page_selected']    = $this->main_model->cover_page_selected_v($param); //print_r($data['cover_page_selected']);

			//pagination
			$this->load->library('pagination');
			$config['base_url']   = "main/edit_album";
            
			$config['total_rows'] = $this->main_model->get_book_content_count($param);
            
			$data['total_pages']  = $config['total_rows'];
			$config['per_page']   = 100;
			$this->pagination->initialize($config);
			$page_info   = '';
			$pi          = 0;
			$param->page = 0;
			if (isset($_POST['page']) && $_POST['page'] != "undefined") {
				$param->page = 0;
				$param->page = $_POST['page'] + 1;
				$pi          = $param->page + 99;
				if ($config['total_rows'] <= $pi)
					$pi = $config['total_rows'];
				if ($param->page == '')
					$param->page = 1;
				$data['page_info_text'] = "Showing " . ($param->page) . " to " . $pi . " of " . $config['total_rows'];
				$data['pagination']     = $this->pagination->create_links($_POST['page']);
			} else {
				if ($config['total_rows'] > 100)
					$data['page_info_text'] = "Showing 1 to 100 of " . $config['total_rows'];
				else
					$data['page_info_text'] = "";
				$data['pagination'] = $this->pagination->create_links(0);
			}
			$data['pi']         = $param->page;
			// pagination end
			$book_pages         = $this->main_model->get_book_content_paginate($param);
			$data['book_pages'] = $book_pages['data'];
			$ret->data          = $this->load->view('new_ui_rearrange', $data, TRUE);
			echo json_encode($ret);
		endif;
	}
	public function delete_book_pades_d() {
		$this->main_model->delete_book_pades_d($_POST);
	}
	public function share_chapter() {
		$param->facebook_id  = $_COOKIE['hardcover_fbid'];
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$data                = $this->main_model->set_share_chapter($param);
		$ret->data           = $this->load->view('edit_album', $data, TRUE);
		echo json_encode($ret);
	}
	// revised by mychelle to cater adding filter for chapters
	public function save_book_filter_for_chapter() {
		$facebook_id          = $_COOKIE['hardcover_fbid'];
		$filter->book_info_id = $_COOKIE['hardcover_book_info_id'];
		if (!$facebook_id OR !$filter->book_info_id) {
			$ret->status = 1;
			$ret->msg    = 'book info id is missing';
			echo json_encode($ret);
		} else {
			$arr_where             = array();
			$arr_table             = array(
				'album_photos_raw_data' => 'album_photos_raw_data',
				'photos_raw_data' => 'photos_raw_data',
				'statuses_raw_data' => 'statuses_raw_data',
				'feed_raw_data' => 'feed_raw_data'
			);
			//check ALBUM FOR WHO
			$filter->album_for_who = 2;
			//location
			if ($this->input->post('location') == 'location_all') {
				$filter->location = '';
			} else {
				$filter->location = $this->input->post('location');
			}
			//check DATE
			if ($this->input->post('date_range') == 'entire_timeline') { //entire timeline
				$filter->date_range = 1;
				$date_where         = '';
			} else {
				$filter->date_range = 2;
				$filter->from_date  = date("Y-m-d", strtotime($this->input->post('date_range_from')));
				$filter->to_date    = date("y-m-d", strtotime($this->input->post('date_range_to')));
				$date_where         = " AND (DATE(fbdata_postedtime)>='" . $filter->from_date . "' AND DATE(fbdata_postedtime)<='" . $filter->to_date . "')";
			}
			//album content
			$filter->album_content = $this->input->post('album_content');
			if ($filter->album_content == 'photo_only') {
				unset($arr_table['statuses_raw_data']);
				unset($arr_table['feed_raw_data']);
			}
			//check STATUS UPDATES
			$filter->status_update = '';
			if ($this->input->post('status_my_update')) {
				$filter->status_update = ';1';
				if ($this->input->post('status_friends_comment')) {
					$filter->status_update .= ';2';
					$status_where = " AND friends_that_commented!=''";
				}
				if ($this->input->post('status_friends_like')) {
					$filter->status_update .= ';3';
					$status_where .= " AND friends_that_like!=''";
				}
			}
			if (empty($filter->status_update))
				unset($arr_table['statuses_raw_data']); //do not include statuses if nothing is checked for statuses
			if ($this->input->post('status_i_commented'))
				$filter->status_update .= ';4';
			//$status_where = substr($status_where,4);
			//check POST I LIKE
			$filter->post_like = '';
			if ($this->input->post('post_all')) {
				$filter->post_like = ';1';
			} else {
				if ($this->input->post('post_photos')) {
					$filter->post_like .= ';2';
					$post_where .= " OR feed_type='photo'";
				}
				if ($this->input->post('post_comment')) {
					$filter->post_like .= ';3';
					$post_where .= " OR feed_type='status'";
				}
				if ($this->input->post('post_article')) {
					$filter->post_like .= ';4';
					$post_where .= " OR feed_type='link'";
				}
				$post_where = ' AND (' . substr($post_where, 3) . ')';
			}
			//$post_where = substr($post_where,4);
			if (empty($filter->post_like))
				unset($arr_table['feed_raw_data']); //do not include feeds if nothing is checked
			//check ALBUM
			$post_vars       = $this->input->post();
			$album_ids       = '';
			$albums_where_id = '';
			$albums_where    = '';
			$cities          = '';
			foreach ($post_vars as $name => $value) {
				$pos = strpos($name, 'album');
				if (is_int($pos) && $name != 'album_content' && $name != 'album_for_who') {
					$arr_albums = explode('_', $name);
					$album_ids .= ';' . $arr_albums[1];
					$albums_where_id .= " OR album_id='" . $arr_albums[1] . "'";
				}
				$pos_city = strpos($name, 'select_city');
				if (is_int($pos_city) && $filter->location == 'location_cities') {
					$cities .= ';' . $value;
				}
			}
			$filter->location = $cities;
			$filter->albums   = substr($album_ids, 1);
			if ($albums_where_id)
				$albums_where_id = ' AND (' . substr($albums_where_id, 3) . ')';
			$filter->photos_from = '';
			if ($this->input->post('photos_friend_commented')) {
				$filter->photos_from .= ';2';
				$albums_where .= " AND friends_that_commented!=''";
			}
			if ($this->input->post('photos_friend_like')) {
				$filter->photos_from .= ';3';
				$albums_where .= " AND friends_that_like!=''";
			}
			//if (empty($albums_where_id)) $albums_where = substr($albums_where,4);
			$albums_where = $albums_where_id . $albums_where;
			//check POST FROM
			if ($this->input->post('photos_tagged'))
				$filter->photos_from .= ';1';
			else
				unset($arr_table['photos_raw_data']); //do not included photos I was tagged since the user did not check the filter option
			//check PHOTO SIZE
			$filter->photo_size = '';
			if ($this->input->post('photo_size_hd')) {
				$filter->photo_size = ';1';
				$photos_where       = ' OR (width>=1200 OR height>=1200)';
			}
			if ($this->input->post('photo_size_medium')) {
				$filter->photo_size .= ';2';
				$photos_where .= ' OR ((width>=600 AND width<1200) OR (height>=600 AND height<1200)) ';
			}
			if ($this->input->post('photo_size_small')) {
				$filter->photo_size .= ';3';
				$photos_where .= ' OR (width<600 OR height<600)';
			}
			if (!empty($photos_where)) {
				$photos_where = substr($photos_where, 3);
				$photos_where = " AND ($photos_where)";
			}
			//this will save the filter information
			$filter->facebook_id = $_COOKIE['hardcover_fbid'];
			$this->main_model->set_book_filter($filter);
			//set the where condition
			$arr_where['album_photos_raw_data'] = $albums_where . $photos_where . $date_where; //album photos
			$arr_where['photos_raw_data']       = ''; //photos I was tagged need no condition of WHERE
			$arr_where['statuses_raw_data']     = $status_where . $date_where; //statuses
			$arr_where['feed_raw_data']         = $post_where . $date_where; //feed
			$filter->table                      = $arr_table;
			$filter->where                      = $arr_where;
			$filter->facebook_id                = $_COOKIE['hardcover_fbid'];
			$this->main_model->set_book_pages($filter); //copy all the raw data to the book_page table
			//set the content of the 1st and 2nd page
			$ret->status                    = 0;
			$ret->msg                       = '';
			$param->facebook_id             = $_COOKIE['hardcover_fbid'];
			$creator                        = $this->main_model->get_book_creator($param);
			$creator->fbdata                = unserialize($creator->fbdata);
			$data['creator']                = $creator;
			$data['encrypted_book_info_id'] = $_COOKIE['hardcover_book_info_id'] + $this->config->item('book_info_id_key');
			$ret->data                      = $this->load->view('edit_album', $data, TRUE);
			echo json_encode($ret);
		}
	}
	//this will create a copy of the book
	public function saveas() {
		$param->book_info_id = $this->input->post('book_info_id');
		$param->book_name    = $this->input->post('book_name');
		$this->main_model->set_book_info($param);
	}
	//this method gets the book pages to be feed to the pageflip
	public function get_book_pages($book_info_id = 0, $offset = 0, $limit = 20) {
		$param->book_info_id = empty($_COOKIE['hardcover_book_info_id']) ? $book_info_id : $_COOKIE['hardcover_book_info_id'];
		//$param->book_info_id = 110;
		$param->facebook_id  = $_COOKIE['hardcover_fbid'];
		setcookie("hardcover_pagebatch", $offset, time() + 86400, '/');
		$param->limit        = $limit;
		$param->offset       = $offset;
		$book_pages          = $this->main_model->get_book_pages_for_testpage($param);
		$total_pages         = $this->main_model->get_total_pages($param->book_info_id);
		$data['total_pages'] = $total_pages['data'];
		$data['book_pages']  = $book_pages['data'];
		echo json_encode($data);
	}
	public function set_page_layout() {
		$param->book_info_id = $this->input->post('book_info_id');
		$param->page_num     = substr($this->input->post('page_num'), 5);
		$param->page_layout  = $this->input->post('page_layout');
		$this->main_model->set_page_layout($param);
		$this->book_page_organizer($param->book_info_id, $param->page_num, 1);
		sleep(1);
		//need algo to get starting and ending pages at specified page num
		$param->page_num_start  = 1;
		$param->page_num_end    = 20;
		$book_info              = $this->main_model->get_book_info($book_info_id);
		$data['book_info']      = $book_info['data'];
		$book_pages             = $this->main_model->get_book_pages($param);
		$data['book_pages']     = $book_pages['data'];
		$data['page_num_start'] = $param->page_num_start;
		$data['page_num_end']   = $param->page_num_end;
		$total_pages            = $this->main_model->get_total_pages($param->book_info_id);
		$data['total_pages']    = $total_pages['data'];
		$ret->data              = $this->load->view('edit_album', $data, TRUE);
		$ret->current_pagenum   = $param->page_num;
		echo json_encode($ret);
	}
	public function set_page_layout_per_page() {
		$param->book_info_id = $this->input->post('book_info_id');
		$param->page_num     = substr($this->input->post('page_num'), 5);
		$param->page_layout  = $this->input->post('page_layout');
		$this->main_model->set_page_layout($param);
		$this->book_page_organizer($param->book_info_id, $param->page_num, 1);
		sleep(1);
		//need algo to get starting and ending pages at specified page num
		$param->page_num_start  = $param->page_num;
		//$param->page_num_end = 20;
		$param->page_num_end    = $param->page_num;
		$book_info              = $this->main_model->get_book_info($book_info_id);
		$data['book_info']      = $book_info['data'];
		$book_pages             = $this->main_model->get_book_pages($param);
		$data['book_pages']     = $book_pages['data'];
		$data['page_num_start'] = $param->page_num_start;
		$data['page_num_end']   = $param->page_num_end;
		$total_pages            = $this->main_model->get_total_pages($param->book_info_id);
		$data['total_pages']    = $total_pages['data'];
		$ret->data              = $this->load->view('page_layout', $data, TRUE);
		$ret->current_pagenum   = $param->page_num;
		echo json_encode($ret);
	}
	public function fb_message() {
		$ret->status          = 0;
		$ret->msg             = '';
		$param->facebook_id   = $_COOKIE['hardcover_fbid'];
		$data['book_creator'] = $this->main_model->get_book_creator($param);
		$ret->data            = $this->load->view('fb_message', $data, TRUE);
		echo json_encode($ret);
	}
	public function my_album() {
		$fbid             = $_COOKIE['hardcover_fbid'];
		$ret->status      = 0;
		$ret->msg         = '';
		$data['booklist'] = $this->main_model->get_booklist($fbid);
		$ret->data        = $this->load->view('my_album', $data, TRUE);
		echo json_encode($ret);
	}
	public function set_edited_image() {
		$param->book_info_id  = $_COOKIE['hardcover_book_info_id'];
		$param->new_image_url = $this->input->post('url');
		$param->fb_dataid     = trim(substr($this->input->post('fb_dataid'), 4));
		//$book_filter = $this->main_model->get_book_filter($param->book_info_id);
		//$arr_photo_size = split(';',$book_filter['data']->photo_size);
		//$param->photo_size = $book_filter['data']->photo_size;
		$ret                  = $this->main_model->set_new_image($param);
		echo json_encode($ret);
	}
	private function is_image_size_within_filter($filter) {
	}
	public function friends_being_requested_for_fbdata() {
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$param->friends_fbid = $this->input->post('friends_fbid');
		$param->status       = 'pending';
		$ret                 = $this->main_model->set_friends_being_askfor_fbdata($param);
		echo json_encode($ret);
	}
	//display the share url page
	public function share_url() {
		$ret->status            = 0;
		$ret->msg               = '';
		$param->book_info_id    = $this->input->get('book_info_id');
		$param->friends_fbid    = $this->input->get('rated');
		$param->page_num_start  = 1;
		$param->page_num_end    = 10;
		//get the book pages
		$book_pages             = $this->main_model->get_book_pages($param);
		$data['book_pages']     = $book_pages['data'];
		$data['page_num_start'] = $param->page_num_start;
		$data['page_num_end']   = $param->page_num_end;
		//get the total pages for the navigation
		$total_pages            = $this->main_model->get_total_pages($param->book_info_id);
		$data['total_pages']    = $total_pages['data'];
		//get the creator of the book
		$data['book_creator']   = $this->main_model->get_book_creator_by_book_info_id($param);
		//get friend's name
		$param->facebook_id     = $data['book_creator']->facebook_id;
		$data['friends_info']   = $this->main_model->get_friends_info($param);
		$data['param']          = $param;
		$data['app_canvas']     = $this->config->item('fb_canvaspage');
		$this->load->view('share_url', $data);
	}
	//this will revert the image to the original image and returns the original image url
	function revert_image() {
		$ret->status         = 0;
		$ret->msg            = '';
		$image_id            = substr($this->input->post('image_id'), 4);
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$param->fb_dataid    = $image_id;
		$ret                 = $this->main_model->revert_image_to_original($param);
		echo json_encode($ret);
	}
	function save_image_border() {
		$ret->status         = 0;
		$ret->msg            = '';
		$ret->data           = '';
		$image_id            = substr($this->input->post('image_id'), 4);
		$border_size         = $this->input->post('border_size');
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$param->fb_dataid    = $image_id;
		$param->border_size  = $border_size;
		$ret                 = $this->main_model->set_book_page_image_border($param);
		echo json_encode($ret);
	}
	function save_pdf() {
		$ret->status  = 0;
		$ret->msg     = '';
		$book_info_id = $this->input->post('book_info_id');
		$this->create_book_pdf($book_info_id);
		$ret->data = $book_info_id;
		echo json_encode($ret);
	}
	function add_app() {
		$book_info_id        = $this->input->post('book_info_id');
		$friends_fbid        = $this->input->post('friends_fbid');
		$token               = $this->input->post('token');
		$param->status       = 'approve'; //based on db ENUM; pending /  approve / denied
		$param->book_info_id = $book_info_id;
		$param->friends_fbid = $friends_fbid;
		$this->update_fbdata($param->friends_fbid, $token);
		$this->main_model->set_friends_being_askfor_fbdata($param);
		$ret->status = 0;
		$ret->msg    = '';
		echo json_encode($ret);
	}
	function invite_friends() {
		$ret->status = 0;
		$ret->msg    = '';
		$ret->data   = $this->load->view('invite_friends', $data, TRUE);
		echo json_encode($ret);
	}
	function help() {
		$ret->status = 0;
		$ret->msg    = '';
		$ret->data   = $this->load->view('help', $data, TRUE);
		echo json_encode($ret);
	}
	function about() {
		$ret->status = 0;
		$ret->msg    = '';
		$ret->data   = $this->load->view('about', $data, TRUE);
		echo json_encode($ret);
	}
	function get_user_friend_locations() {
		$ret->status = 0;
		$ret->msg    = '';
		$fbid        = $_COOKIE['hardcover_fbid'];
		$ret->data   = $this->main_model->get_friend_location($fbid);
		echo json_encode($ret);
	}
	//this is called after the JS plot the content as was able to determine the page number of the book
	function save_pagenum() {
		$pagenum = $this->input->post('pagenum');
		if ($pagenum) {
			$arr_pagenum = explode(',', $pagenum);
			$arr_fbdata  = array();
			$arr_pageid  = array();
			//$arr_pagenum = explode(',','fbid_12345:1,cid_234345345:1,fbid_56789:2,cid_675498698:2,cid_675498698:3,fbid_45687:4,fbid_34566:5');
			$total_data  = count($arr_pagenum);
			for ($x = 0; $x < $total_data; $x++) {
				//echo $arr_pagenum[$x] . '==' .strpos($arr_pagenum[$x],'fbid') . '<br/>';
				if (strpos($arr_pagenum[$x], 'fbid') === 0) {
					$tmp                        = substr($arr_pagenum[$x], 5);
					$arr_pageid                 = explode(':', $tmp);
					$arr_fbdata[$arr_pageid[0]] = $arr_pageid[1] . ':' . $arr_pageid[2];
				} else {
					$tmp                         = substr($arr_pagenum[$x], 4);
					$arr_pageid                  = explode(':', $tmp);
					$arr_comment[$arr_pageid[0]] = $arr_pageid[1];
				}
			}
			$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
			$ret                 = $this->main_model->set_pagenum($param, $arr_fbdata, $arr_comment);
			$this->get_book_pages();
		} else {
			$ret->status = 1;
			$ret->msg    = 'No page number posted';
			$data        = '';
			echo json_encode($ret);
		}
	}
	//called when the DONE button is press fromt he Edit Album page
	//to invoke the server script to creat the static pages for the unique url
	//by the way, we can directly called the static_page_creation method but we dont want the response from the server to wait for our
	//reponse, so we invoke  the server script to run this for us in another thread.
	public function create_static_book() {
		$ret->status  = 0;
		$ret->msg     = '';
		$ret->data    = '';
		$book_info_id = $this->input->post('book_info_id');
		$fb_username  = $this->input->post('fb_username');
		$ret->data    = $this->config->item('base_url') . "/books/$fb_username/$book_info_id";
		$this->create_static_pages_for_uniqueurl($book_info_id, $fb_username);
		echo json_encode($ret);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////PRIVATE
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	private function create_static_pages_for_uniqueurl($book_info_id, $fb_username) {
		$create_static = $this->config->item('tools') . "/create_pageflip_static_pages.php $book_info_id $fb_username";
		$command       = " php -f $create_static";
		exec("$command > " . $this->config->item('updater_log_folder') . " &", $arrOutput);
	}
	private function create_book_pdf($book_info_id) {
		$create_pdf_content = $this->config->item('tools') . "/create_pdf_content.php $book_info_id";
		$command            = " php -f $create_pdf_content";
		exec("$command > /dev/null &", $arrOutput);
		$create_pdf_cover = $this->config->item('tools') . "/create_pdf_cover.php $book_info_id";
		$command          = " php -f $create_pdf_cover";
		exec("$command > " . $this->config->item('updater_log_folder') . " &", $arrOutput);
	}
	private function retrieve_fbdata($fbid, $token) {
		/* commented and uses update as it have the new code
		$retriever_url = $this->config->item('tools')."/fbdata_retriever.php $fbid $token";
		$command = " php -f $retriever_url";
		exec("$command > /dev/null &",$arrOutput);
		*/
		$this->update_fbdata($fbid, $token);
	}
	private function update_fbdata($fbid, $token) {
		//update the fb data photo
		$updater_url = $this->config->item('tools') . "/fbalbumphoto_updater.php $fbid $token";
		$command     = " php -f $updater_url";
		exec("$command > " . $this->config->item('updater_log_folder') . " 2>&1 &", $arrOutput);
		log_message('info', 'fbfriends_updater: ');
		//update the fb data photo
		$updater_url = $this->config->item('tools') . "/fbphoto_updater.php $fbid $token";
		$command     = " php -f $updater_url";
		exec("$command > " . $this->config->item('updater_log_folder') . " 2>&1 &", $arrOutput);
		log_message('info', 'fbphoto_updater: ');
		//update user fb friends
		$updater_url = $this->config->item('tools') . "/fbfriends_updater.php $fbid $token";
		$command     = " php -f $updater_url";
		exec("$command > " . $this->config->item('updater_log_folder') . " 2>&1 &", $arrOutput);
		log_message('info', 'fbfriends_updater: ');
	}
	private function book_page_organizer($book_info_id, $start_page_num, $action = 0) {
		$book_organizer = $this->config->item('tools') . "/book_page_organizer.php $book_info_id $start_page_num $action";
		$command        = " php -f $book_organizer";
		exec("$command > " . $this->config->item('updater_log_folder') . " &", $arrOutput);
	}
	private function format_db_friends($fb_friends) {
		foreach ($fb_friends as $friend) {
			$friends_fbid .= ';' . $friend->friends_fbid;
		}
		$friends_fbid = substr($friends_fbid, 1);
		return $friends_fbid;
	}
	private function format_db_friends_withname($fb_friends) {
		foreach ($fb_friends as $friend) {
			$friends_withname .= ';' . $friend->friends_fbid . ':' . $friend->friends_name;
		}
		$friends_withname = substr($friends_withname, 1);
		return $friends_withname;
	}
	private function init_fb_login() {
		$params               = new stdClass();
		$params->app_id       = $this->config->item('fb_appkey');
		$params->app_secret   = $this->config->item('fb_appsecret');
		$params->redirect_url = $this->config->item('fb_canvaspage');
		$res                  = new stdClass();
		$res->status          = 0;
		$res->msg             = '';
		$fbid                 = $_COOKIE['hardcover_fbid'];
		//print "func init_fb_login fbid: " . $fbid;
		if (empty($fbid)) {
			//$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
			$dialog_url  = "http://www.facebook.com/dialog/oauth?client_id=" . $params->app_id . "&redirect_uri=" . urlencode($params->redirect_url) . '&scope=' . $this->config->item('fb_scope_permission');
			//. '&state='  . $_SESSION['state']
			$res->data   = "<script> top.location.href='" . $dialog_url . "'</script>";
			$res->status = 1; //need to login
		}
		return $res;
	}
	//checks if the user has ru the fbdata_updater in the server
	private function run_fbdata_updater($facebook_id, $token) {
		$last_run = $this->main_model->get_lastrun_fbdata_updater($facebook_id, $token);
		if ($last_run['data'] != false) {
			$lastrun_date = date('Y-m-d', strtotime($last_run['data']));
			$today_date   = date('Y-m-d');
			$start        = strtotime($lastrun_date);
			$end          = strtotime($today_date);
			$days_diff    = ceil(abs($end - $start) / 86400);
			//$day_diff = gregoriantojd(12, 25, 2010) - gregoriantojd(2, 19, 2010);
			if ($days_diff > 0) {
				$this->main_model->set_lastrun_fbdata_updater($facebook_id, $token);
				$this->update_fbdata($facebook_id, $token);
			}
		} else {
			$this->update_fbdata($facebook_id, $token);
		}
	}
	function set_save_as_book() {
		$old_book_info_id        = $_POST['book_info_id'];
		$book_name               = $_POST['book_name'];
		// 1. get user info
		$params->app_id = $this->config->item('fb_appkey');
        $params->app_secret = $this->config->item('fb_appsecret');
		$fbuser = getFacebookUserDetails($params);
		$fbid                    = $_COOKIE['hardcover_fbid'];
		// 2. set book creator
		$param->facebook_id      = $fbid;
		$param->fname            = $fbuser->first_name;
		$param->lname            = $fbuser->last_name;
		$param->fb_username      = $fbuser->username;
		$param->fbdata           = serialize($fbuser);
		/*
		$ret->msg = $this->main_model->set_book_creator($param);
		*/
		// 3. init the data to be stored in DB
		$param                   = new stdClass();
		$param->facebook_id      = $fbid;
		$param->book_name        = $book_name;
		$param->book_type        = 'album'; //constant
		$param->book_size_id     = 2; // change this when there is already book sizes
		$param->created_date     = date('Y-n-j H:i:s');
		$res1                    = $this->main_model->set_book_info($param);
		$ret->msg1               = $res1['msg'];
		$param->old_book_info_id = $old_book_info_id;
		$param->new_book_info_id = $res1['data'];
		setcookie("hardcover_book_info_id", "", time() - 3600);
		setcookie('hardcover_book_info_id', $param->book_info_id, time() + 86400, '/');
		// 4. copy all content of the current book
		$res2      = $this->main_model->set_clone_book_pages($param);
		$ret->msg2 = $res2['msg'];
		$res3      = $this->main_model->set_clone_book_comments($param);
		$ret->msg3 = $res3['msg'];
		//$ret->old_book_info_id = $param->old_book_info_id;
		//$ret->book_name = $param->book_name;
		/*
		$ret->fname = $param->fname;
		$ret->lname = $param->lname;
		$ret->fb_username = $param->fb_username;
		*/
		echo json_encode($ret);
	}
	public function set_is_edited($fb_dataid, $book_info_id) {
		$param               = new stdClass();
		$param->fb_dataid    = $fb_dataid;
		$param->book_info_id = $book_info_id;
		$ret->data           = $this->main_model->set_is_edited_dao($param);
		echo json_encode($ret);
	}
	public function set_save_edited_photos() {
		$param               = new stdClass();
		$param->book_info_id = $_POST['book_info_id'];
		$param->facebook_id  = $_POST['fb_id'];
		$param->origin       = $_POST['origin'];
		$param->origin_id    = $_POST['origin_id'];
		$param->original_url = $_POST['original_url'];
		$param->edited_url   = $_POST['edited_url'];
		$ret->data           = $this->main_model->set_save_edited_photos_dao($param);
		echo json_encode($ret);
	}
	//end mychelle's code
	// Rob's 7/26/2012
	public function _save_img_file() {
		$file_ = $this->input->post('filename');
		$uri_  = $this->input->post('uri_');
		$path  = $this->config->item('image_upload') . '/' . $file_;
		chmod($path, 0777);
		$fp = (file_exists($path)) ? fopen($path, "a+") : fopen($path, "wb");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_URL, $uri_);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		fwrite($fp, $result);
		fclose($savefile);
		return $result;
	}
	public function save_img_file() {
		$file_    = $this->input->post('filename');
		$uri_     = $this->input->post('uri_');
		$contents = file_get_contents($uri_);
		$path     = $this->config->item('image_upload') . '/' . $file_;
		$savefile = fopen($path, 'w');
		fwrite($savefile, $contents);
		fclose($savefile);
		echo $file_;
	}
	public function save_canvas_file() {
		$file_ = $this->input->get_post('filename');
		if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
			// Get the data
			$imageData     = $GLOBALS['HTTP_RAW_POST_DATA'];
			// Remove the headers (data:,) part.
			// A real application should use them according to needs such as to check image type
			$filteredData  = substr($imageData, strpos($imageData, ",") + 1);
			// Need to decode before saving since the data we received is already base64 encoded
			$unencodedData = base64_decode($filteredData);
			//echo "unencodedData".$unencodedData;
			// Save file.  This example uses a hard coded filename for testing,
			// but a real application can specify filename in POST variable
			$path          = $this->config->item('image_upload') . '/' . $file_;
			$fp            = fopen($path, 'w+');
			fwrite($fp, $unencodedData);
			fclose($fp);
			echo $file_;
		}
	}
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
	//End of upload
	//josh
	public function new_home() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		$fbid                = $signed_request_data['user_id'];
		$token               = $signed_request_data['oauth_token'];
		if ($fbid):
			$param                = new stdClass();
			$param->facebook_id   = $fbid;
			$data['user_details'] = $data['fb_user'] = $this->main_model->get_book_creator($param);
			$user_status          = $this->main_model->is_returning_user($param);
			$this->init_user_profile($fbid, $token);
			$fb_user                  = $this->main_model->get_book_creator($param);
			$data['dashboard_detils'] = $this->main_model->get_dashboard_detils($fbid);
			foreach ($data['dashboard_detils'] as $k => $v) {
				$data['user_detils']                                    = $this->main_model->get_users_detils($v->book_info_id);
				$data['dashboard_detils'][$k]->book_name                = $data['user_detils'][0]->book_name;
				$data['dashboard_detils'][$k]->front_cover              = $data['user_detils'][0]->front_cover;
				$data['dashboard_detils'][$k]->total_pages              = $data['user_detils'][0]->total_pages;
				$data['dashboard_detils'][$k]->book_owner_facebook_id   = $data['user_detils'][0]->facebook_id;
				$data['user_name']                                      = $this->main_model->get_users_fb_name($data['user_detils'][0]->facebook_id);
				$data['dashboard_detils'][$k]->book_owner_facebook_name = $data['user_name'][0]->fname . ' ' . $data['user_name'][0]->lname;
			}

			$data['booklist']    = $this->main_model->get_booklist($fbid);
			$data['booked_info'] = $this->main_model->get_book_info_by_user_id($fbid);
			$data['fb_user']     = $fb_user;

		endif;
        
		if ($data['booklist'])
			$this->load->view("new-home", $data);
		else
			$this->load->view('book-no-book', $data);
	}
	public function new_my_books() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		//die(print_r($signed_request_data));
		$fbid                = $signed_request_data['user_id'];
		$token               = $signed_request_data['oauth_token'];
		if ($fbid):
			$param                = new stdClass();
			$param->facebook_id   = $fbid;
			$data['user_details'] = $this->main_model->get_book_creator($param);
			$user_status          = $this->main_model->is_returning_user($param);
			$this->init_user_profile($fbid, $token);
			$fb_user                  = $this->main_model->get_book_creator($param);
			$data['fb_user']          = $fb_user;
			$data['dashboard_detils'] = $this->main_model->get_dashboard_detils($fbid);
			foreach ($data['dashboard_detils'] as $k => $v) {
				$data['user_detils']                                    = $this->main_model->get_users_detils($v->book_info_id);
				$data['dashboard_detils'][$k]->book_name                = $data['user_detils'][0]->book_name;
				$data['dashboard_detils'][$k]->book_owner_facebook_id   = $data['user_detils'][0]->facebook_id;
				$data['user_name']                                      = $this->main_model->get_users_fb_name($data['user_detils'][0]->facebook_id);
				$data['dashboard_detils'][$k]->book_owner_facebook_name = $data['user_name'][0]->fname . ' ' . $data['user_name'][0]->lname;
			}
			$data['booklist'] = $this->main_model->get_booklist($fbid);
			$this->load->view("new-my-books", $data);
		endif;
	}
	public function new_summary() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		//die(print_r( $signed_request_data ));
		$fbid                = $signed_request_data['user_id'];
		$book_owner_id       = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
		if ($fbid != $book_owner_id):
			setcookie("hardcover_book_info_id", "", time() - 3600);
			$ret->data = $this->main_model->select_books_again();
			$ret->xBid = "true";
			echo json_encode($ret);
		else:
			$param                          = new stdClass();
			$book_info_id                   = $_REQUEST['book_info_id'];
			$param->facebook_id             = $fbid;
			$param->book_info_id            = $_COOKIE['hardcover_book_info_id'];
			$creator                        = $this->main_model->get_book_creator($param);
			$book_info                      = $this->main_model->get_book_cover($book_info_id);
			$_COOKIE['book_name']           = $book_info->book_name;
			$data['front_cover']            = $book_info->front_cover;
			$data['back_cover']             = $book_info->back_cover;
			$data["fb_username"]            = $creator->fb_username;
			$data['book_settings']          = $this->main_model->get_settings_unique($book_info_id);
			$data["fb_name"]                = $creator->fname . " " . $creator->lname;
			$data['encrypted_book_info_id'] = $param->book_info_id + $this->config->item('book_info_id_key');
			$data['booked_info']            = $book_info;
			$data['creatored']              = $creator;
			$data['booklist']               = $this->main_model->get_booklist($fbid);
			$data['dashboard_detils']       = $this->main_model->get_dashboard_detils($fbid);
			//foreach($data['dashboard_detils'] as $k=>$v){

			//					$data['user_detils'] = $this->main_model->get_users_detils($v->book_info_id);

			//                	$data['dashboard_detils'][$k]->book_name = $data['user_detils'][0]->book_name;

			//					$data['dashboard_detils'][$k]->book_owner_facebook_id = $data['user_detils'][0]->facebook_id;

			//					$data['user_name'] = $this->main_model->get_users_fb_name($data['user_detils'][0]->facebook_id);

			//					$data['dashboard_detils'][$k]->book_owner_facebook_name = $data['user_name'][0]->fname.' '.$data['user_name'][0]->lname;

			//				}
			$ret->data                      = $this->load->view('new-summary', $data, TRUE);
			echo json_encode($ret);
		endif;
	}
	public function new_seo() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		//die(print_r($signed_request_data));
		$fbid                = $signed_request_data['user_id'];
		$token               = $signed_request_data['oauth_token'];
		if ($fbid):
			$param                          = new stdClass();
			$book_info_id                   = $_REQUEST['book_info_id'];
			$param->facebook_id             = $fbid;
			$param->facebook_id             = $_COOKIE['hardcover_fbid'];
			$param->book_info_id            = $_COOKIE['hardcover_book_info_id'];
			$creator                        = $this->main_model->get_book_creator($param);
			$book_info                      = $this->main_model->get_book_cover($book_info_id);
			$_COOKIE['book_name']           = $book_info->book_name;
			$data['front_cover']            = $book_info->front_cover;
			$data['back_cover']             = $book_info->back_cover;
			$data["fb_username"]            = $creator->fb_username;
			$data["fb_name"]                = $creator->fname . " " . $creator->lname;
			$data['encrypted_book_info_id'] = $param->book_info_id + $this->config->item('book_info_id_key');
			$data['booked_info']            = $book_info;
			$data['creatored']              = $creator;
			$data['booklist']               = $this->main_model->get_booklist($fbid);
			$data['dashboard_detils']       = $this->main_model->get_dashboard_detils($fbid);
		//foreach($data['dashboard_detils'] as $k=>$v){

		//					$data['user_detils'] = $this->main_model->get_users_detils($v->book_info_id);

		//                	$data['dashboard_detils'][$k]->book_name = $data['user_detils'][0]->book_name;

		//					$data['dashboard_detils'][$k]->book_owner_facebook_id = $data['user_detils'][0]->facebook_id;

		//					$data['user_name'] = $this->main_model->get_users_fb_name($data['user_detils'][0]->facebook_id);

		//					$data['dashboard_detils'][$k]->book_owner_facebook_name = $data['user_name'][0]->fname.' '.$data['user_name'][0]->lname;

		//				}
			$ret->data                      = $this->load->view('new-seo', $data, TRUE);
			echo json_encode($ret);
		endif;
	}

	public function new_names_chapters() {
	    $signed_request_data = $this->main_model->CheckIfSigned();
        //comment by Dennis: Everybody can create a book so no need to filter this section below
        //$fbid = $signed_request_data['user_id'];
        //$book_owner_id = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
        
        //if($fbid != $book_owner_id):
        //    setcookie("hardcover_book_info_id", "", time() - 3600);
        //    $ret->data = $this->main_model->select_books_again();
        //    $ret->xBid = "true";
        //    echo json_encode($ret);
        // else:
            $param                = new stdClass();
            $book_info_id         = $_COOKIE['hardcover_book_info_id'];
            $book_info            = $this->main_model->get_book_cover($book_info_id);
            $_COOKIE['book_name'] = $book_info->book_name;
            $data['book_name']    = $book_info->book_name;
            $ret->data            = $this->load->view('new-names-chapters', $data, TRUE);
            echo json_encode($ret);
        //endif;
	}
    
	public function new_friends_books() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		//die(print_r($signed_request_data));
		$fbid                = $signed_request_data['user_id'];
		$token               = $signed_request_data['oauth_token'];
		if ($fbid):
			$data['fbid']               = $fbid;
			$param->facebook_id         = $fbid;
			$data['user_details']       = $this->main_model->get_book_creator($param);
			$data['friends_books']      = $this->main_model->get_friends_books($fbid);
			$data['get_friends_collab'] = $this->main_model->get_friends_collab($fbid);
			$fb_user                    = $this->main_model->get_book_creator($param);
			$data['fb_user']            = $fb_user;
			$this->load->view('new-friends-books', $data);
		endif;
	}
	public function new_popular_books() {
		$signed_request_data = $this->main_model->CheckIfSigned();
		//die(print_r($signed_request_data));
		$fbid                = $signed_request_data['user_id'];
		$token               = $signed_request_data['oauth_token'];
		if ($fbid):
			$data['fbid']               = $fbid;
			$param->facebook_id         = $fbid;
			$data['user_details']       = $this->main_model->get_book_creator($param);
			$data['friends_books']      = $this->main_model->get_friends_books($fbid);
			$data['get_friends_collab'] = $this->main_model->get_friends_collab($fbid);
			$fb_user                    = $this->main_model->get_book_creator($param);
			$data['fb_user']            = $fb_user;
			$this->load->view('new-popular-books', $data);
		endif;
	}
	/* USER-AGENTS
	================================================== */
	function check_user_agent($type = NULL) {
		$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if ($type == 'bot') {
			// matches popular bots
			if (preg_match("/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent)) {
				return true;
				// watchmouse|pingdom\.com are "uptime services"
			}
		} else if ($type == 'browser') {
			// matches core browser types
			if (preg_match("/mozilla\/|opera\//", $user_agent)) {
				return true;
			}
		} else if ($type == 'mobile') {
			// matches popular mobile devices that have small screens and/or touch inputs
			// mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
			// detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
			if (preg_match("/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent)) {
				// these are the most common
				return true;
			} else if (preg_match("/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent)) {
				// these are less common, and might not be worth checking
				return true;
			}
		}
		return false;
	}
}
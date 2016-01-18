<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
/**
 * Cover Class
 * 
 * Controller for Cover tab which extends CI_Controller
 * 
 * @author Dennis Jade Toribio
 * 
 */
class Filter extends CI_Controller {
	
	protected $path_img_upload_folder;
    protected $path_img_thumb_upload_folder;
    protected $path_url_img_upload_folder;
    protected $path_url_img_thumb_upload_folder;
    protected $delete_img_url;
	
	public function __construct(){
        parent::__construct();
		
		$this->load->model("main_model");
		$this->load->model("filter_model");
		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));
		$this->smarty->assign('base_url',$this->config->item('base_url'));
		$this->smarty->assign('css',$this->config->item('css_url'));
		$this->smarty->assign('js',$this->config->item('js_url'));
		$this->smarty->assign('img',$this->config->item('image_url'));
		$this->smarty->assign('fb_appkey',$this->config->item('fb_appkey'));
	}
	public function filter_page_cover(){
		/**
		 * This is called when the Filter Data tab is clicked
		 */
	
		$param = new stdClass();
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$param->book_name = $this->input->post('book_name');
		$param->facebook_id = $_COOKIE['hardcover_fbid'];

		//get the filter information for the said book.
		$data['token'] = $_COOKIE['hardcover_token'];
		$data['book_filter'] = $this->filter_model->get_filter($param);
		$data['user_albums'] = $this->filter_model->get_user_albums($param);		
		$ret->status = 0;
		$ret->msg = '';
		
		$ret->data = $this->load->view('filter_page_cover_new',$data,TRUE);
		
		echo json_encode($ret);
	}
	public function filter_page()
	{
		/**
		 * This is called when the Filter Data tab is clicked
		 */
    $signed_request_data = $this->main_model->CheckIfSigned();
    //die(print_r( $signed_request_data ));
    $fbid = $signed_request_data['user_id'];
    $book_owner_id = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
    $is_ghost_writer     = $this->main_model->isGhostWriter($fbid,$_COOKIE['hardcover_book_info_id']);
    
    if($fbid != $book_owner_id && $is_ghost_writer===false):
        setcookie("hardcover_book_info_id", "", time() - 3600);
        $ret->data = $this->main_model->select_books_again();
        $ret->xBid = "true";
        echo json_encode($ret);
    else:
		$param = new stdClass();
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		$param->book_name = $this->input->post('book_name');
		$param->facebook_id = $_COOKIE['hardcover_fbid'];

		//get the filter information for the said book.
		$data['token'] = $_COOKIE['hardcover_token'];
		$data['book_filter'] = $this->filter_model->get_filter($param);
		$data['user_albums'] = $this->filter_model->get_user_albums($param);		
		$ret->status = 0;
		$ret->msg = '';
		
		$ret->data = $this->load->view('filter_page_new',$data,TRUE);
		
		echo json_encode($ret);
      endif;
	}
	
	function save_book_filter(){
		 //This saves the filter information selected by the user
		 
		$filter->book_info_id = $_COOKIE['hardcover_book_info_id'];
		if (!$filter->book_info_id){
			$ret->status = 1;
			$ret->msg = 'Book has not been initialize. Cannot Save filter info.';
			echo json_encode($ret);
		}else{
			$arr_where = array();
			$arr_table = array('album_photos_raw_data'=>'album_photos_raw_data','photos_raw_data'=>'photos_raw_data');
								
			//check ALBUM
			$post_vars = $this->input->post();
			$album_ids = '';
			$albums_where_id = '';
			$albums_where = '';
			$temp = $this->input->post('photo');  
			foreach($post_vars as $name=>$value){
				$pos = strpos($name,'album');
				if (is_int ($pos) && $name!='album_content' && $name!='album_for_who'){
					$arr_albums = explode('_',$name);
					$album_ids .= ';'.$arr_albums[1];
					
					$did ='';
					$temp1 ='';
					$inarg ='';
				 
					if($arr_albums[1]!='')
					{  
					 
					 $did = $arr_albums[1];
					 
					 
					 foreach($temp[$did] as $k=>$j)
					  {
						  if($temp1!='') 
						    $temp1 .= ','.$k;
						    else
						    $temp1 .= $k;
					  }
					    
					   
					if($temp1!='')
					  $inarg = 'AND fb_dataid in('.$temp1.')';
					}
					
					$albums_where_id .= " OR (album_id='".$arr_albums[1]."'".$inarg. ")";
                   
					//$albums_where_id .= " OR album_id='".$arr_albums[1]."'";
				}					
			}
			
			$filter->albums = substr($album_ids,1);
			if ($albums_where_id) $albums_where_id = ' AND ('.substr($albums_where_id,3).')';
						
			//check PHOTO SIZE
			$filter->photo_size = '';
			if ($this->input->post('photo_size_hd')){
				$filter->photo_size = ';1';
				$photos_where = ' OR (width>=1200 OR height>=1200)';
			}
			if ($this->input->post('photo_size_medium')){
				$filter->photo_size .= ';2';
				$photos_where .= ' OR ((width>=600 AND width<1200) OR (height>=600 AND height<1200)) ';
			}
			if ($this->input->post('photo_size_small')){
				$filter->photo_size .= ';3';
				$photos_where .= ' OR (width<600 OR height<600)';
			}
			if (!empty($photos_where)){
				$photos_where = substr($photos_where,3);
				$photos_where = " AND ($photos_where)";
			}

            $book_owner_id       = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
            $is_ghost_writer     = $this->main_model->isGhostWriter($_COOKIE['hardcover_fbid'],$_COOKIE['hardcover_book_info_id']);

        
			//this will save the filter information
			$filter->facebook_id = $is_ghost_writer===true?$_COOKIE['hardcover_fbid']:$book_owner_id;
            //$filter->from_facebook_id = $_COOKIE['hardcover_fb_user_id'];        // josh commented does not exist in book_filter table in database  
			$this->main_model->set_book_filter($filter);
			
			//set the where condition
			$arr_where['album_photos_raw_data'] = $albums_where_id . $photos_where ;	//album photos
			$filter->table = $arr_table;
			$filter->where = $arr_where;
			$filter->facebook_id = $_COOKIE['hardcover_fbid'];
			$this->main_model->set_book_pages($filter);							//copy all the raw data to the book_page table
	
			//get the book cover info
			$ret->status = 0;
			$ret->msg = '';	
			$param->facebook_id = $filter->facebook_id;
			$creator = $this->main_model->get_book_creator($param);
			  
			//we will pass the book cover info here in case we need to have the book cover for this page; comment if not needed
			$creator->fbdata = unserialize($creator->fbdata);
			$data['creator'] = $creator;
			$data['book_info'] = $this->main_model->get_book_cover($_COOKIE['hardcover_book_info_id']);
			 
			$_COOKIE['book_name']=$data['book_info']->book_name;
			$data['encrypted_book_info_id'] = $_COOKIE['hardcover_book_info_id'] + $this->config->item('book_info_id_key');
			$ret->data = $this->load->view('edit_album_unique',$data,TRUE);
			echo json_encode($ret);		
		}
	}
	
	function save_book_filter_cover(){  
		 //This saves the filter information selected by the user
		 
		$filter->book_info_id = $_COOKIE['hardcover_book_info_id'];
		if (!$filter->book_info_id){
			$ret->status = 1;
			$ret->msg = 'Book has not been initialize. Cannot Save filter info.';
			echo json_encode($ret);
		}else{
			$arr_where = array();
			$arr_table = array('album_photos_raw_data'=>'album_photos_raw_data','photos_raw_data'=>'photos_raw_data');
								
			//check ALBUM
			$post_vars = $this->input->post();
			$album_ids = '';
			$albums_where_id = '';
			$albums_where = '';
			$temp = $this->input->post('photo');  
			foreach($post_vars as $name=>$value){
				$pos = strpos($name,'album');
				if (is_int ($pos) && $name!='album_content' && $name!='album_for_who'){
					$arr_albums = explode('_',$name);
					$album_ids .= ';'.$arr_albums[1];
					
					$did ='';
					$temp1 ='';
					$inarg ='';
				 
					if($arr_albums[1]!='')
					{  
					 
					 $did = $arr_albums[1];
					 
					 
					 foreach($temp[$did] as $k=>$j)
					  {
						  if($temp1!='') 
						    $temp1 .= ','.$k;
						    else
						    $temp1 .= $k;
					  }
					    
					   
					if($temp1!='')
					  $inarg = 'AND fb_dataid in('.$temp1.')';
					}
					
					$albums_where_id .= " OR (album_id='".$arr_albums[1]."'".$inarg. ")";
                   
					//$albums_where_id .= " OR album_id='".$arr_albums[1]."'";
				}					
			}
			
			$filter->albums = substr($album_ids,1);
			if ($albums_where_id) $albums_where_id = ' AND ('.substr($albums_where_id,3).')';
						
			//check PHOTO SIZE
			$filter->photo_size = '';
			if ($this->input->post('photo_size_hd')){
				$filter->photo_size = ';1';
				$photos_where = ' OR (width>=1200 OR height>=1200)';
			}
			if ($this->input->post('photo_size_medium')){
				$filter->photo_size .= ';2';
				$photos_where .= ' OR ((width>=600 AND width<1200) OR (height>=600 AND height<1200)) ';
			}
			if ($this->input->post('photo_size_small')){
				$filter->photo_size .= ';3';
				$photos_where .= ' OR (width<600 OR height<600)';
			}
			if (!empty($photos_where)){
				$photos_where = substr($photos_where,3);
				$photos_where = " AND ($photos_where)";
			}

			//this will save the filter information
			$filter->facebook_id = $_COOKIE['hardcover_fbid'];
			//$this->main_model->set_book_filter($filter);
			 
			//set the where condition
			$arr_where['album_photos_raw_data'] = $albums_where_id . $photos_where ;	//album photos
			$filter->table = $arr_table;
			$filter->where = $arr_where;
			$filter->facebook_id = $_COOKIE['hardcover_fbid'];
			$y = $this->main_model->set_book_pages_cover($filter);							//copy all the raw data to the book_page table
	         
			//get the book cover info
			$ret->status = 0;
			$ret->msg = '';	
			$param->facebook_id = $_COOKIE['hardcover_fbid'];
			$creator = $this->main_model->get_book_creator($param);
			  
			//we will pass the book cover info here in case we need to have the book cover for this page; comment if not needed
			$creator->fbdata = unserialize($creator->fbdata);
			$data['creator'] = $creator;
			$data['book_info'] = $this->main_model->get_book_cover($_COOKIE['hardcover_book_info_id']);
			 
			$_COOKIE['book_name']=$data['book_info']->book_name;
			$data['encrypted_book_info_id'] = $_COOKIE['hardcover_book_info_id'] + $this->config->item('book_info_id_key');
			$ret->data = $this->load->view('edit_album_unique',$data,TRUE);
			echo json_encode($ret);		
		}
	}
	function save_book_filter_cover_unique(){  
		 //This saves the filter information selected by the user
		 
		$filter->book_info_id = $_COOKIE['hardcover_book_info_id'];
        if (!$filter->book_info_id)$filter->book_info_id = $this->input->post('book_info_id');
		if (!$filter->book_info_id){
			$ret->status = 1;
			$ret->msg = 'Book has not been initialize. Cannot Save filter info.';
			echo json_encode($ret);
		}else{
			$arr_where = array();
			$arr_table = array('album_photos_raw_data'=>'album_photos_raw_data','photos_raw_data'=>'photos_raw_data');
								
			//check ALBUM
			$post_vars = $this->input->post();
			$album_ids = '';
			$albums_where_id = '';
			$albums_where = '';
			$temp = $this->input->post('photo');  
			foreach($post_vars as $name=>$value){
				$pos = strpos($name,'album');
				if (is_int ($pos) && $name!='album_content' && $name!='album_for_who'){
					$arr_albums = explode('_',$name);
					$album_ids .= ';'.$arr_albums[1];
					
					$did ='';
					$temp1 ='';
					$inarg ='';
				 
					if($arr_albums[1]!='')
					{  
					 
					 $did = $arr_albums[1];
					 
					 
					 foreach($temp[$did] as $k=>$j)
					  {
						  if($temp1!='') 
						    $temp1 .= ','.$k;
						    else
						    $temp1 .= $k;
					  }
					    
					   
					if($temp1!='')
					  $inarg = 'AND fb_dataid in('.$temp1.')';
					}
					
					$albums_where_id .= " OR (album_id='".$arr_albums[1]."'".$inarg. ")";
                   
					//$albums_where_id .= " OR album_id='".$arr_albums[1]."'";
				}					
			}
			
			$filter->albums = substr($album_ids,1);
			if ($albums_where_id) $albums_where_id = ' AND ('.substr($albums_where_id,3).')';
						
			//check PHOTO SIZE
			$filter->photo_size = '';
			if ($this->input->post('photo_size_hd')){
				$filter->photo_size = ';1';
				$photos_where = ' OR (width>=1200 OR height>=1200)';
			}
			if ($this->input->post('photo_size_medium')){
				$filter->photo_size .= ';2';
				$photos_where .= ' OR ((width>=600 AND width<1200) OR (height>=600 AND height<1200)) ';
			}
			if ($this->input->post('photo_size_small')){
				$filter->photo_size .= ';3';
				$photos_where .= ' OR (width<600 OR height<600)';
			}
			if (!empty($photos_where)){
				$photos_where = substr($photos_where,3);
				$photos_where = " AND ($photos_where)";
			}

			//this will save the filter information
			$filter->facebook_id = $_COOKIE['hardcover_fbid'];
			$this->main_model->set_book_filter($filter);
			 
			//set the where condition
			$arr_where['album_photos_raw_data'] = $albums_where_id . $photos_where ;	//album photos
			$filter->table = $arr_table;
			$filter->where = $arr_where;
			$filter->facebook_id = $_COOKIE['hardcover_fbid'];
			$y = $this->main_model->set_book_pages_cover_unique($filter);							//copy all the raw data to the book_page table
	         
			//get the book cover info
			$ret->status = 0;
			$ret->msg = '';	
			$param->facebook_id = $_COOKIE['hardcover_fbid'];
			$creator = $this->main_model->get_book_creator($param);
			  
			//we will pass the book cover info here in case we need to have the book cover for this page; comment if not needed
			$creator->fbdata = unserialize($creator->fbdata);
			$data['creator'] = $creator;
			$data['book_info'] = $this->main_model->get_book_cover($filter->book_info_id);
			 
			$_COOKIE['book_name']=$data['book_info']->book_name;
			$data['encrypted_book_info_id'] = $filter->book_info_id + $this->config->item('book_info_id_key');
			$ret->data = $this->load->view('edit_album_unique',$data,TRUE);
			echo json_encode($ret);		
		}
	}
	
	
	/*
	function save_book_filter(){
		
		 This saves the filter information selected by the user
		 
		$filter->book_info_id = $_COOKIE['hardcover_book_info_id'];
		if (!$filter->book_info_id){
			$ret->status = 1;
			$ret->msg = 'Book has not been initialize. Cannot Save filter info.';
			echo json_encode($ret);
		}else{	
			$arr_where = array();
			$arr_table = array('album_photos_raw_data'=>'album_photos_raw_data',
					'photos_raw_data'=>'photos_raw_data',
					'statuses_raw_data'=>'statuses_raw_data',
					'feed_raw_data'=>'feed_raw_data');
	
			//check ALBUM FOR WHO
			switch ($this->input->post('album_for_who')){
				case 'album_for_me':
					$filter->album_for_who = 1;
					break;
				case 'album_for_friends':
					$filter->album_for_who = 2;
					break;
				case 'album_quick_book':
					$filter->album_for_who = 3;
					break;
			}
	
			//location
			if ($this->input->post('location')=='location_all'){
				$filter->location = '';
			}else{
				$filter->location = $this->input->post('location');
			}
	
			//check DATE
			if ($this->input->post('date_range')=='entire_timeline'){	//entire timeline
				$filter->date_range = 1;
				$date_where = '';
			}else{
				$filter->date_range = 2;
				$filter->from_date = date("Y-m-d" ,strtotime($this->input->post('date_range_from')));
				$filter->to_date = date("y-m-d",strtotime($this->input->post('date_range_to')));
				$date_where = " AND (DATE(fbdata_postedtime)>='".$filter->from_date . "' AND DATE(fbdata_postedtime)<='".$filter->to_date."')";
			}
	
	
			//album content
			//echo $this->input->post('album_content'); echo "ji"; exit;
		 	$filter->album_content = $this->input->post('album_content');
			if ($filter->album_content=='photo_only'){
				unset($arr_table['statuses_raw_data']);
				unset($arr_table['feed_raw_data']);
			}
	
			//check STATUS UPDATES
			$filter->status_update = '';
			if ($this->input->post('status_my_update'))	{
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
	
			if (empty($filter->status_update)) unset($arr_table['statuses_raw_data']);		//do not include statuses if nothing is checked for statuses
	
			if ($this->input->post('status_i_commented'))	$filter->status_update .= ';4';
			//$status_where = substr($status_where,4);
				
			//check POST I LIKE
			$filter->post_like = '';
			if ($this->input->post('post_all')) {
				$filter->post_like = ';1';
			}else{
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
				$post_where = ' AND ('.substr($post_where,3) .')';
			}
			//$post_where = substr($post_where,4);
			if (empty($filter->post_like)) unset($arr_table['feed_raw_data']);		//do not include feeds if nothing is checked
	
			//check ALBUM
			$post_vars = $this->input->post();
			$album_ids = '';
			$albums_where_id = '';
			$albums_where = '';
			$cities = '';
			foreach($post_vars as $name=>$value){
				$pos = strpos($name,'album');
				if (is_int ($pos) && $name!='album_content' && $name!='album_for_who'){
					$arr_albums = explode('_',$name);
					$album_ids .= ';'.$arr_albums[1];
					$albums_where_id .= " OR album_id='".$arr_albums[1]."'";
				}
					
				$pos_city = strpos($name,'select_city');
				if (is_int($pos_city) && $filter->location=='location_cities'){
					$cities .= ';'.$value;
				}
			}
	
			$filter->location = $cities;
			$filter->albums = substr($album_ids,1);
			if ($albums_where_id) $albums_where_id = ' AND ('.substr($albums_where_id,3).')';
	
	
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
			$albums_where = $albums_where_id  . $albums_where;
	
			//check POST FROM
			if ($this->input->post('photos_tagged'))
				$filter->photos_from .= ';1';
			else
				unset($arr_table['photos_raw_data']);				//do not included photos I was tagged since the user did not check the filter option
	
			//check PHOTO SIZE
			$filter->photo_size = '';
			if ($this->input->post('photo_size_hd')){
				$filter->photo_size = ';1';
				$photos_where = ' OR (width>=1200 OR height>=1200)';
			}
			if ($this->input->post('photo_size_medium')){
				$filter->photo_size .= ';2';
				$photos_where .= ' OR ((width>=600 AND width<1200) OR (height>=600 AND height<1200)) ';
			}
			if ($this->input->post('photo_size_small')){
				$filter->photo_size .= ';3';
				$photos_where .= ' OR (width<600 OR height<600)';
			}
			if (!empty($photos_where)){
				$photos_where = substr($photos_where,3);
				$photos_where = " AND ($photos_where)";
			}
	
			//this will save the filter information
			$filter->facebook_id = $_COOKIE['hardcover_fbid'];
			$this->main_model->set_book_filter($filter);
	
	
			//set the where condition
			$arr_where['album_photos_raw_data'] = $albums_where . $photos_where . $date_where ;	//album photos
			$arr_where['photos_raw_data'] = '';									//photos I was tagged need no condition of WHERE
			$arr_where['statuses_raw_data'] = $status_where . $date_where;		//statuses
			$arr_where['feed_raw_data'] = $post_where . $date_where;			//feed
			$filter->table = $arr_table;
			$filter->where = $arr_where;
			$filter->facebook_id = $_COOKIE['hardcover_fbid'];
			$this->main_model->set_book_pages($filter);							//copy all the raw data to the book_page table
	
			//set the content of the 1st and 2nd page
			$ret->status = 0;
			$ret->msg = '';	
			$param->facebook_id = $_COOKIE['hardcover_fbid'];
			$creator = $this->main_model->get_book_creator($param);
			  
			$creator->fbdata = unserialize($creator->fbdata);
			$data['creator'] = $creator;
			$data['book_info'] = $this->main_model->get_book_cover($_COOKIE['hardcover_book_info_id']);
			 
			$_COOKIE['book_name']=$data['book_info']->book_name;
			$data['encrypted_book_info_id'] = $_COOKIE['hardcover_book_info_id'] + $this->config->item('book_info_id_key');
			$ret->data = $this->load->view('edit_album',$data,TRUE);
			echo json_encode($ret);
		}
	}
	*/
	
	public function saveShareBookPagesContent(){
		$book_info_id = $_COOKIE['hardcover_book_info_id'];
		$filter_info = $this->organizeUserFilterInfo($book_info_id, $this->input->post());

		//set the where condition
		$arr_where['album_photos_raw_data'] = $filter_info['albums_where'] . $filter_info['photos_where'] . $filter_info['date_where'] ;	//album photos
		$arr_where['photos_raw_data'] = '';													//photos I was tagged need no condition of WHERE
		$arr_where['statuses_raw_data'] = $filter_info['status_where'] . $filter_info['date_where'];						//statuses
		$arr_where['feed_raw_data'] = $filter_info['post_where'] . $filter_info['date_where'];							//feed
		
		$filter->table = $filter_info['arr_table'];
		$filter->where = $arr_where;
		$filter->facebook_id = $_COOKIE['hardcover_fbid'];
		$filter->book_info_id = $book_info_id;

		$ret = $this->filter_model->shareDataToInviter($filter);
		echo json_encode($ret);
	}
	
	public function createBookCover(){
		/**
		 * This will try to create the book cover for the first time
		 */
		//$this->load->model("covermodel");
		//$param->book_info_id = $this->input->post('book_info_id');
		//$param->facebook_id = $_COOKIE['hardcover_fbid'];
		//$this->covermodel->setBookCover($param);
		
	}
	
    public function filter_more(){
		/**
		* This is called after the filter is saved. This will allows the user to filter 
		* more of what has been filtered
		*/
        
        $param->book_info_id = $this->input->post('book_info_id')?$this->input->post('book_info_id'):$_COOKIE['hardcover_book_info_id'];
        $param->facebook_id = $_COOKIE['hardcover_fbid'];
        $page_num = (int) $this->input->post('page_num');
        
        if (empty($page_num)){
            $param->page_num_start = 0;         
        }else{
            $param->page_num_start = $page_num;         
        }
        $param->page_num_end = 39 + $param->page_num_start;             
        
        $param->limit = 40;
        $param->offset = $param->page_num_start;
        $book_pages = $this->main_model->get_book_pages_for_testpage($param);       
        $total_objects = $this->main_model->get_total_objects($param);
        $data['book_pages'] = $book_pages['data'];
        $data['total_objects'] = $total_objects;
        $data['book_info_id'] = $param->book_info_id;        
        $data['page_num_start'] = $param->page_num_start;
        $data['page_num_end'] = $param->page_num_end;
        $data['pagenum'] = $param->page_num_start;

        $ret->data = $this->load->view('filter_more',$data,TRUE);               
        echo json_encode($ret);
        
    }       
	
    public function delete_filtermore(){
    	$book_info_id = $_COOKIE['hardcover_book_info_id'];
	    $fbdata_ids = $this->input->post('fbdataid');
	    if ($fbdata_ids) {	       
	       $this->filter_model->delete_filter_more($book_info_id, $fbdata_ids);	             
	    }
	    $this->filter_more();
    }
    
    public function filtermore_done(){
        $book_info_id = $_COOKIE['hardcover_book_info_id'];
        $fbdata_ids = $this->input->post('fbdataid');
        if ($fbdata_ids) {         
           $this->filter_model->delete_filter_more($book_info_id, $fbdata_ids);              
        }
        redirect('main/edit_album');		
    }

    public function organizeUserFilterInfo($book_info_id, $post_vars){    	
    	$filter->book_info_id = $book_info_id;
    	if (!$filter->book_info_id){
    		$ret->status = 1;
    		$ret->msg = 'Book has not been initialize. Cannot Save filter info.';
    		echo json_encode($ret);
    	}else{
    		$arr_where = array();
    		$arr_table = array('album_photos_raw_data'=>'album_photos_raw_data',
    				'photos_raw_data'=>'photos_raw_data',
    				'statuses_raw_data'=>'statuses_raw_data',
    				'feed_raw_data'=>'feed_raw_data');
    	
    		//check ALBUM FOR WHO
    		switch ($this->input->post('album_for_who')){
    			case 'album_for_me':
    				$filter->album_for_who = 1;
    				break;
    			case 'album_for_friends':
    				$filter->album_for_who = 2;
    				break;
    			case 'album_quick_book':
    				$filter->album_for_who = 3;
    				break;
    		}
    	
    		//location
    		if ($this->input->post('location')=='location_all'){
    			$filter->location = '';
    		}else{
    			$filter->location = $post_vars['location'];
    		}
    	
    		//check DATE
    		if ($post_vars['date_range']=='entire_timeline'){	//entire timeline
    			$filter->date_range = 1;
    			$date_where = '';
    		}else{
    			$filter->date_range = 2;
    			$filter->from_date = date("Y-m-d" ,strtotime($post_vars['date_range_from']));
    			$filter->to_date = date("y-m-d",strtotime($post_vars['date_range_to']));
    			$date_where = " AND (DATE(fbdata_postedtime)>='".$filter->from_date . "' AND DATE(fbdata_postedtime)<='".$filter->to_date."')";
    		}
    	
    	
    		//album content
    		$filter->album_content = $post_vars['album_content'];
    		if ($filter->album_content=='photo_only'){
    			unset($arr_table['statuses_raw_data']);
    			unset($arr_table['feed_raw_data']);
    		}
    	
    		//check STATUS UPDATES
    		$filter->status_update = '';
    		if ($post_vars['status_my_update'])	{
    			$filter->status_update = ';1';
    			if ($post_vars['status_friends_comment']) {
    				$filter->status_update .= ';2';
    				$status_where = " AND friends_that_commented!=''";
    			}
    			if ($post_vars['status_friends_like']) {
    				$filter->status_update .= ';3';
    				$status_where .= " AND friends_that_like!=''";
    			}
    		}
    	
    		if (empty($filter->status_update)) unset($arr_table['statuses_raw_data']);		//do not include statuses if nothing is checked for statuses
    	
    		if ($post_vars['status_i_commented'])	$filter->status_update .= ';4';
    		//$status_where = substr($status_where,4);
    	
    		//check POST I LIKE
    		$filter->post_like = '';
    		if ($post_vars['post_all']) {
    			$filter->post_like = ';1';
    		}else{
    			if ($post_vars['post_photos']) {
    				$filter->post_like .= ';2';
    				$post_where .= " OR feed_type='photo'";
    			}
    			if ($post_vars['post_comment']) {
    				$filter->post_like .= ';3';
    				$post_where .= " OR feed_type='status'";
    			}
    			if ($post_vars['post_article']) {
    				$filter->post_like .= ';4';
    				$post_where .= " OR feed_type='link'";
    			}
    			$post_where = ' AND ('.substr($post_where,3) .')';
    		}
    		//$post_where = substr($post_where,4);
    		if (empty($filter->post_like)) unset($arr_table['feed_raw_data']);		//do not include feeds if nothing is checked
    	
    		//check ALBUM
    		//$post_vars = $this->input->post();
    		$album_ids = '';
    		$albums_where_id = '';
    		$albums_where = '';
    		$cities = '';
    		foreach($post_vars as $name=>$value){
    			$pos = strpos($name,'album');
    			if (is_int ($pos) && $name!='album_content' && $name!='album_for_who'){
    				$arr_albums = explode('_',$name);
    				$album_ids .= ';'.$arr_albums[1];
    				$albums_where_id .= " OR album_id='".$arr_albums[1]."'";
    			}
    				
    			$pos_city = strpos($name,'select_city');
    			if (is_int($pos_city) && $filter->location=='location_cities'){
    				$cities .= ';'.$value;
    			}
    		}
    	
    		$filter->location = $cities;
    		$filter->albums = substr($album_ids,1);
    		if ($albums_where_id) $albums_where_id = ' AND ('.substr($albums_where_id,3).')';
    	
    	
    		$filter->photos_from = '';
    		if ($post_vars['photos_friend_commented']) {
    			$filter->photos_from .= ';2';
    			$albums_where .= " AND friends_that_commented!=''";
    		}
    		if ($post_vars['photos_friend_like']) {
    			$filter->photos_from .= ';3';
    			$albums_where .= " AND friends_that_like!=''";
    		}
    		//if (empty($albums_where_id)) $albums_where = substr($albums_where,4);
    		$albums_where = $albums_where_id  . $albums_where;
    	
    		//check POST FROM
    		if ($post_vars['photos_tagged'])
    			$filter->photos_from .= ';1';
    		else
    			unset($arr_table['photos_raw_data']);				//do not included photos I was tagged since the user did not check the filter option
    	
    		//check PHOTO SIZE
    		$filter->photo_size = '';
    		if ($post_vars['photo_size_hd']){
    			$filter->photo_size = ';1';
    			$photos_where = ' OR (width>=1200 OR height>=1200)';
    		}
    		if ($post_vars['photo_size_medium']){
    			$filter->photo_size .= ';2';
    			$photos_where .= ' OR ((width>=600 AND width<1200) OR (height>=600 AND height<1200)) ';
    		}
    		if ($post_vars['photo_size_small']){
    			$filter->photo_size .= ';3';
    			$photos_where .= ' OR (width<600 OR height<600)';
    		}
    		if (!empty($photos_where)){
    			$photos_where = substr($photos_where,3);
    			$photos_where = " AND ($photos_where)";
    		}
    	}

    	$filter_info['arr_table'] = $arr_table;
    	$filter_info['albums_where'] = $albums_where;
    	$filter_info['photos_where'] = $photos_where;
    	$filter_info['date_where'] = $date_where;
    	$filter_info['status_where'] = $status_where;
    	$filter_info['post_where'] = $post_where;
    	return $filter_info;
    }
}

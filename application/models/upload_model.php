<?php
class Upload_model extends CI_Model {
 
    public function __construct(){
        parent::__construct();
	}
	
	function save_uploaded_to_book_pages($param){
		$status=0;
		$msg='';		
		$created_date = date('Y-m-j H:i:s');
		$book_pages = array(
				'book_info_id'=>$param->book_info_id,
				'facebook_id'=>$param->facebook_id,
				'from_facebook_id'=>$param->from_facebook_id,
				'connection'=>$param->connection,
				'fbdata'=>$param->fbdata,
				'width'=>$param->width,
				'height'=>$param->height,
				'is_for_approval'=>$param->is_for_approval,
				'fbdata_postedtime'=>$created_date
		);
		$this->db->insert('book_pages', $book_pages);
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();
			$status  = 1;
			$data = 0;
		}else
			$data = $this->db->insert_id();
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$data);
		return $ret;			
	}
	 
	function save_uploaded_to_book_pages_for_cv($param)
	{
		$status=0;
		$msg='';		
		$created_date = date('Y-m-j H:i:s');
		$book_pages = array(
				'book_info_id'=>$param->book_info_id,
				'facebook_id'=>$param->facebook_id,
				'connection'=>$param->connection,
				'fbdata'=>$param->fbdata,
				'width'=>$param->width,
				'height'=>$param->height,
				'fbdata_postedtime'=>$created_date
		);
		$this->db->insert('book_cover_pages', $book_pages);
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();
			$status  = 1;
			$data = 0;
		}else
			$data = $this->db->insert_id();
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$data);
		return $ret;
	}
}

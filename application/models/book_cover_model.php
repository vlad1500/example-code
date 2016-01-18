<?php
class Book_cover_model extends CI_Model {
 
    public function __construct(){
        parent::__construct();
	}
	
	function save_cover($param){
		/**
		 * Author: Dennis Toribio
		 * Saves the book thumbnail cover; Either insert or update the existing book cover info 
		 */
		
		$status=200;
		$msg='';
		$id = 0;
		
		$query = $this->db->get_where('book_cover_thumbnail', array('book_info_id' => $param->book_info_id ));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();
			$status  = 400;
		}else{
			if ($query->num_rows() > 0){
				$this->db->where('book_info_id',$param->book_info_id);
				$this->db->update('book_cover_thumbnail',$param);
				
				$id = $param->book_info_id;
				$msg = 'Updating existing cover info';
			}else{
				$param->updated_at =date('Y-m-j H:i:s');
				$this->db->insert('book_cover_thumbnail',$param);
				
				$id = $this->db->insert_id();
				$msg = 'Inserting new cover info.';
			}				
		}
		
		//print_r($this->db->last_query());
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	
	function save_front_cover($filename,$book_info_id){
		/**
		 * Author: Cesar
		 * Saves the book cover; Either insert or update the existing book cover info 
		 */
		
		$status=200;
		$msg='';
		$id = 0;
		
		$query = $this->db->get_where('book_info', array('book_info_id' => $book_info_id ));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();
			$status  = 400;
		}else{
			if ($query->num_rows() > 0){
				$this->db->where('book_info_id', $book_info_id );
				$this->db->update('book_info',array('front_cover_page'=>$filename));
				
				$id = $book_info_id;
				$msg = 'Updating existing cover info';
			}else{
				$data = array('created_date' => date('Y-m-j H:i:s'),
							'front_cover_page' => $filename);
				
				$this->db->insert('book_info',$data);
				
				$id = $this->db->insert_id();
				$msg = 'Inserting new cover info.';
			}				
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		//print_r($ret);
		return $ret;
	}
	
	
	function save_back_cover($filename,$book_info_id){
		/**
		 * Author: Cesar
		 * Saves the book cover; Either insert or update the existing book cover info 
		 */
		
		$status=200;
		$msg='';
		$id = 0;
		
		$query = $this->db->get_where('book_info', array('book_info_id' => $book_info_id ));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();
			$status  = 400;
		}else{
			if ($query->num_rows() > 0){
				$this->db->where('book_info_id',$book_info_id);
				$this->db->update('book_info', array('back_cover_page'=>$filename));
				
				$id = $book_info_id;
				$msg = 'Updating existing cover info';
			}else{
				$data = array('created_date' => date('Y-m-j H:i:s'),
								'back_cover_page' => $filename);
				
				$this->db->insert('book_info',$data);
				
				$id = $this->db->insert_id();
				$msg = 'Inserting new cover info.';
			}				
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
}
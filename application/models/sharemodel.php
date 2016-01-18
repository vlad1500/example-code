<?php
class Sharemodel extends CI_Model {
 
    public function __construct(){
        parent::__construct();
	}
	
	function shareDataToInviter($param){
		/**
		This will insert the data being share by the user to the inviter
		 */
		$status=0;
		$msg='';
		$param->created_date =date('Y-m-j H:i:s');
		
		//cleanup previous book pages as filter has been set again
		$comment_where = "(Select fb_dataid from book_pages where book_info_id = $param->book_info_id and facebook_id = $param->facebook_id)";
		$sql = "DELETE FROM book_comment where fb_dataid IN " . $comment_where;
		$this->db->query($sql);
		
		$this->db->delete('book_pages', array('book_info_id' => $param->book_info_id, 'facebook_id' => $param->facebook_id));

		foreach ($param->table as $table){
			//insert all data into the bookpages				
			$where = empty($param->where[$table])?'':$param->where[$table];
			$sql = "INSERT INTO book_pages(book_info_id,facebook_id,from_facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)";
	
			//photos table has width and height in their column
			if ($table=='album_photos_raw_data' || $table=='photos_raw_data'){
				$sql .= "
				(SELECT '{$param->book_info_id}','{$param->facebook_id}',facebook_id,connection,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
				FROM $table WHERE facebook_id='{$param->from_facebook_id}' $where)";
			}else{
				$sql .=	"
				(SELECT '{$param->book_info_id}','{$param->facebook_id}',facebook_id,connection,fb_dataid,fbdata,0,0,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
				FROM $table WHERE facebook_id='{$param->from_facebook_id}' $where)";
			}
			$this->db->query($sql);
			
			if ($param->album_content=='all'){
				$sql = "INSERT INTO book_comment(book_info_id,connection,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime)
						(SELECT '{$param->book_info_id}',book_rc.connection,book_rc.fb_dataid,book_rc.comment_id,book_rc.comment_obj,book_rc.page_num,book_rc.text_size,book_rc.status,book_rc.fbdata_postedtime
						FROM `book_raw_comment` book_rc
						WHERE book_rc.facebook_id='{$param->from_facebook_id}' AND
						book_rc.comment_id NOT IN
						(SELECT comment_id FROM book_comment WHERE book_info_id = $param->book_info_id))";
				$this->db->query($sql);
			}
			
			if ($this->db->_error_message()){
				$msg = $this->db->_error_message();
				$status  = 1;
				break;
			}
		}
	$ret = array('status'=>$status,'msg'=>$msg,'data'=>$book_cover_id);
	return $ret;
	}	
}
<?php
class Filter_model extends CI_Model {
 
    public function __construct(){
        parent::__construct();
	}
	
    function get_filter($param){        
        $query = $this->db->get_where('book_filter', array("book_info_id" => $param->book_info_id ));
        if ($query->num_rows() > 0){
            $data   = $query->result();
            $book_filter = $data[0];    
        }else
            $book_filter = false;
        return $book_filter;
    }

    function get_friend_location($fbid=0){
        $this->db->select('friend_location_name');
        $this->db->where("friend_location_name != ",'');
        if ($fbid){
            $this->db->where('facebook_id',$fbid);
        }       
        $query = $this->db->get('friends_raw_data');        
        
        if ($this->db->_error_message()){
            $msg = $this->db->_error_message(); 
            $status  = 1;
        }else{
            if ($query->num_rows() > 0){
                foreach ($query->result() as $row){
                    $data[] = $row->friend_location_name;
                }
                $friends_location = $data;  
            }else
                $friends_location = false;
        }
        return $friends_location;
    }
    
    function get_user_albums($param){
    	$query = $this->db->get_where('albums_raw_data', array("facebook_id" => $param->facebook_id ));
    	$user_albums = array();
    	if ($query->num_rows() > 0){
    		foreach ($query->result() as $row){
    			$user_albums[] = $row;
    		}    		
    	}
    	return $user_albums;    	
    }
    function get_album_photos($param)
    {
		 
		$query = $this->db->get_where('album_photos_raw_data', array("album_id" => $param->album_id ));
    	$photo_albums = array();
    	if ($query->num_rows() > 0){
    		foreach ($query->result() as $row){
    			$photo_albums[] = $row;
    		}    		
    	}
    	return $photo_albums;   
	}
    public function delete_pages(){
        $param->str_fb_dataid = $_POST['fbdataid'];
        
        $ret->data = $this->main_model->set_deleted_pages($param);
        echo json_encode($ret);
    }

    public function delete_filter_more($book_info_id, $fbdata_ids){    	
    	if (substr($fbdata_ids,0,1)==',') $fbdata_ids = substr($fbdata_ids,1);
        $arr_fbdata_id = explode(',',$fbdata_ids);
       
        $this->db->where_in('fb_dataid',$arr_fbdata_id);
        $this->db->where('book_info_id',$book_info_id);
        $this->db->delete(array('book_pages','book_comment'));   
         
    }
    
    function set_deleted_pages($param){
        $status=0;
        $msg='';
        try{
        	if ($param->str_fb_dataid && !($param->str_fb_dataid)=='null'){
                $sql = sprintf("UPDATE `book_pages` SET is_removed=1 WHERE fb_dataid IN ('%s'))",
                        mysql_real_escape_string($param->str_fb_dataid));
                $this->db->query($sql);
                if ($this->db->_error_message()){
                    $msg = $this->db->_error_message(); 
                    $status  = 1;
                }
        	}
        } catch (Exception $e) {
            $msg = $this->db->_error_message();
            $status  = 1;
        }
        $ret = array('status'=>$status,'msg'=>$msg,'data'=>'');
        return $ret;
    }
    
    function shareDataToInviter($param){
    	/**
    		This will insert the data being share by the user to the inviter
    	*/
		$status=0;
		$msg='';		
		$param->created_date =date('Y-m-j H:i:s');
		$this->db->delete('book_pages', array('book_info_id' => $param->book_info_id, 'from_facebook_id' => $param->facebook_id)); 
		
		foreach ($param->table as $table){
			//insert all data into the bookpages
			
			$where = empty($param->where[$table])?'':$param->where[$table];
			$sql = "INSERT INTO book_pages(book_info_id,from_facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)";

			//photos table has width and height in their column
			if ($table=='album_photos_raw_data' || $table=='photos_raw_data'){
				$sql .= "
					(SELECT '{$param->book_info_id}','{$param->facebook_id}',connection,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}' 
					FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
			}else{
				$sql .=	" 
					(SELECT '{$param->book_info_id}','{$param->facebook_id}',connection,fb_dataid,fbdata,0,0,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}' 
					FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
			}
			//echo " => ".$sql;
			$this->db->query($sql);
			
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

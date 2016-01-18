<?php
/**
 * AlbumModel Class
 * 
 * Model for Albums tab which extends CI_Model
 * 
 * @author	Marlo Morales
 * 
 */
class AlbumModel extends CI_Model {
 
    public function __construct(){
        parent::__construct();
	}
	
	function get_friends_info($param){
		/**
		 * Retrieves all the information of the current user's friends
		 * 
		 * @access	static
		 * @param	Object $param Contains current user's FB ID and friends' FB ID
		 * @return	Object $friends_info Contains all the information of the current user's friends
		 * 			Boolean $friends_info In case there's no retrieved information about current user's friends, this will return FALSE
		 */
		$query = $this->db->get_where('friends_raw_data', array('facebook_id'=>$param->facebook_id,'friends_fbid' => $param->friends_fbid ));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows() > 0){
				$data	= $query->result();
				$friends_info = $data[0];	
			}else
				$friends_info = false;
		}
		return $friends_info;
	}
	function save_wall_friends($data)
	{   
		  
		  $book_id = $data['book_info_id'];
		   $frd_id = $data['friends_fbid'];
		  $txt = "SELECT * FROM friends_being_askfor_fbdata WHERE book_info_id = '$book_id ' and friends_fbid = '$frd_id'";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0)
		   {			
				echo "already exist" ;
			}else{
				 
				$this->db->insert('friends_being_askfor_fbdata',$data);
			 	$id = $this->db->insert_id();				 
			} exit;
	}
	
	function get_book_cover($book_info_id){
		/**
		 * Retrieves all the information of a specific book
		 * 
		 * @access	static
		 * @param	Integer $book_info_id This is the ID of a specific book
		 * @return	Object $book_info Contains all the information of a specific book
		 * 			Boolean $book_info In case there's no retrieved information about the specific book, this will return FALSE
		 */
		$book_info = new StdClass();
		$query = $this->db->get_where('book_info_vw', array("book_info_id" => $book_info_id ));		
		if ($query->num_rows() > 0){
			$book_info	= $query->result();
			$book_info = $book_info[0];	
            $sql_front = "select * from book_info where book_info_id = '$book_info_id'";
            $query_front = $this->db->query($sql_front);
            $result_front = $query_front->result();  			
            if($result_front[0]->book_desc != NULL)
                $book_info->book_desc = $result_front[0]->book_desc;
            else		
                $book_info->book_desc = "<no description>";            
		}else
			$book_info = false;
		return $book_info;
	}

	function get_book_settings($book_info_id){
		
		$book_info = new StdClass();
		$query = $this->db->get_where('book_settings', array("book_info_id" => $book_info_id ));
		if ($query->num_rows() > 0){
			$book_info	= $query->result();
			$book_info = $book_info[0];	
		}else
			$book_info = false;
		return $book_info;	
	}
	
	
	function get_friend_location($fbid=0){
		/**
		 * Retrieves all the information of a specific book
		 * 
		 * @access	static
		 * @param	Integer $book_info_id This is the ID of a specific book
		 * @return	Object $book_info Contains all the information of a specific book
		 * 			Boolean $book_info In case there's no retrieved information about the specific book, this will return FALSE
		 */
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
	
	function get_booklist($fbid){
		$fb_username = '';
		$query = $this->db->get_where('book_info_vw', array("facebook_id" => $fbid ));		
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row){
				if (empty($fb_username)){
					$param->facebook_id = $row->facebook_id;
					$creator = $this->get_book_creator($param);
					$fb_username = $creator->fb_username;
				}
				$book_info_id = $row->book_info_id;
				$ret = $this->get_total_pages($book_info_id);
				$row->total_pages = $ret['data'];
				$row->fb_username = $fb_username;
				//$row->book_info_id = $book_info_id;
				$ret = $this->get_total_comments($book_info_id,'new');
				$row->total_newcomments = $ret['data'];
				$data[] = $row;
			}
		}else {
			$data = false;
		}
		return $data;
	}
	
	function get_chapters_for_friends($fbid){
		$sql = sprintf("select * from book_pages where facebook_id = %d AND is_chapter = 1 GROUP BY book_info_id",
						mysql_real_escape_string($fbid)
						);
		$query = $this->db->query($sql);
				
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row){
				// get total pages
				$param->book_info_id = $row->book_info_id;
				$param->facebook_id = $fbid;
				$row->total_pages = $this->get_total_objects_chapter($param);
				$data['total_pages'] = $row;
				
				// get book creator 
				$sql = sprintf("SELECT * FROM book_info bi LEFT JOIN book_creator bc ON bi.facebook_id = bc.facebook_id WHERE bi.book_info_id = %d",
						mysql_real_escape_string($row->book_info_id)
						);
				$query = $this->db->query($sql);
				$qry_result = $query->result();
				$data['book_creator'] = $qry_result[0];
				
			}
		}else
			$data = false;
		return $data;
	}
	
	function get_filter($param){		
		$query = $this->db->get_where('book_filter', array("book_info_id" => $param->book_info_id ));
		if ($query->num_rows() > 0){
			$data	= $query->result();
			$book_filter = $data[0];	
		}else
			$book_filter = false;
		return $book_filter;
	}

	//gets the book creator using the facebook id	
	function get_book_creator($param){
		$query = $this->db->get_where('book_creator', array("facebook_id" => $param->facebook_id));
		if ($query->num_rows() > 0){
			$data	= $query->result();
			$book_creator = $data[0];	
		}else
			$book_creator = false;
		return $book_creator;
	}
	
	//gets the book creator using the fb username
	function get_book_creator_by_fb_username($param){
		$query = $this->db->get_where('book_creator', array("fb_username" => $param->fb_username));
		if ($query->num_rows() > 0){
			$data	= $query->result();
			$book_creator = $data[0];	
		}else
			$book_creator = false;
		return $book_creator;
	}
	
	
	function get_book_creator_by_book_info_id($param){
		$query = $this->db->get_where('book_info_with_creator_vw', array("book_info_id" => $param->book_info_id));
		if ($query->num_rows() > 0){
			$data	= $query->result();
			$book_creator = $data[0];	
		}else
			$book_creator = false;

		return $book_creator;		
	}
	
	//get the book information
	function get_book_info($book_info_id){
		$status=0;
		$msg='';
		$book_info = false;		
		$query = $this->db->get_where('book_info', array('book_info_id' => $book_info_id));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			$data	= $query->result();
			$book_info = $data[0];
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$book_info);
		return $ret;
	}
	
	//get the filters associated with the book
	function get_book_filter($book_info_id){
		$status=0;
		$msg='';
		$book_filter = false;		
		$query = $this->db->get_where('book_filter', array('book_info_id' => $book_info_id));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			$data	= $query->result();
			$book_filter = $data[0];
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$book_filter);
		return $ret;
	}
	
	//get total books of the user based on facebook id
	function get_total_books($fbid){
		$status=0;
		$msg='';
		$total_book = 0;		
		$query = $this->db->get_where('book_info', array('facebook_id' => $fbid));
		$total_book = $query->num_rows();

		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$total_book);
		return $ret;
	}
	
	//get the total pages of the book
	function get_total_pages($book_info_id){
		$status=0;
		$msg='';
		$total_pages = 0;
		
		//we will check first if the user has able to flip all the pages so the page num is set
		//if not, meaning we should just make an approximate count of the pages by assuming
		//that each book_page record out in db is one page
		$sql = sprintf("SELECT count(book_pages_id) as pageno FROM book_pages WHERE book_info_id=%d AND page_num=0 AND is_removed != 1",mysql_real_escape_string($book_info_id));
		$query = $this->db->query($sql);
		if ($query->num_rows()){
			$sql = sprintf("SELECT count(book_pages_id) as pageno FROM book_pages WHERE book_info_id=%d AND is_removed != 1",mysql_real_escape_string($book_info_id));
		}else{
			$sql = sprintf("SELECT IFNULL( bc_page_num, bp_page_num ) AS pageno FROM book_details_vw WHERE book_info_id=%d 
							ORDER BY pageno DESC LIMIT 1",mysql_real_escape_string($book_info_id));		
		}
		$query = $this->db->query($sql);
		if ($query->num_rows()){
			$row = $query->result();
			$total_pages = $row[0]->pageno;
		}
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$total_pages);
		return $ret;
	}
	
	//get total comments on the book
	function get_total_comments($book_info_id,$status='new'){
		$status=0;
		$msg='';
		$total_comments = 0;		
		$query = $this->db->get_where('book_comment', array('book_info_id' => $book_info_id,'status'=>$status));
		$total_comments = $query->num_rows();

		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$total_comments);
		return $ret;
	}
	
	//this will get the book pages and its corresponding comments
	function get_book_pages($param){
		$status=0;
		$msg='';
		$data = false;
		
		if ($param->page_num_start == -1 || $param->page_num_end == -1 || empty($param->page_num_start)){
			$sql = sprintf("SELECT book_info_id,fb_dataid, fbdata, page_layout,page_num,connection,page_col FROM book_pages 
							WHERE book_info_id=%d ORDER BY page_num",
							mysql_real_escape_string($param->book_info_id)
							);
		}else{
			$sql = sprintf("SELECT book_info_id,fb_dataid, fbdata, page_layout,page_num,connection,page_col FROM book_pages 
							WHERE book_info_id=%d AND (page_num>=%d AND page_num<=%d) ORDER BY page_num",
							mysql_real_escape_string($param->book_info_id),
							mysql_real_escape_string($param->page_num_start),
							mysql_real_escape_string($param->page_num_end)
							);
		}
	
		$query = $this->db->query($sql);
		//echo $this->db->last_query();
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			foreach ($query->result() as $row){
				$sql_comment = sprintf("SELECT bc.* FROM  book_comment bc 
						WHERE bc.book_info_id=%d AND bc.fb_dataid='%s' AND bc.status!='deleted' AND (bc.page_num>=%d AND bc.page_num<=%d)",
						mysql_real_escape_string($param->book_info_id),
						mysql_real_escape_string($row->fb_dataid),
						mysql_real_escape_string($param->page_num_start),
						mysql_real_escape_string($param->page_num_end));
				$query_comment = $this->db->query($sql_comment);
				if ($query_comment->num_rows()) 
					$row->comment = $query_comment->result();
				else
					$row->comment = '';				

				$data[] = $row;
			}
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$data);
		return $ret;		
	}
	
	//this will get the book pages and its corresponding comments
	function get_book_pages_for_testpage($param){
		$status=0;
		$msg='';
		$data = false;		
		$add_where = " ";
		//get book owner
		$sql = sprintf("SELECT facebook_id FROM book_info WHERE book_info_id = %d",
				mysql_real_escape_string($param->book_info_id)
				);
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			$row = $query->row('facebook_id'); 
		
			if ($row == $param->facebook_id) {
				$add_where = " AND ready_to_share = 1 AND is_removed != 1 ";
			} else {
				$add_where = " AND facebook_id = $param->facebook_id AND is_removed != 1 ";
			}
		}
		
						
		//this will retrieve all records for a specific book
		if ($param->limit<1){
			$sql = sprintf("SELECT * FROM all_book_pages WHERE book_info_id = '%d'"
							.$add_where. 
							"ORDER BY page_num",
							mysql_real_escape_string($param->book_info_id)
							);		
		}else{
					$sql = sprintf("SELECT * FROM all_book_pages WHERE book_info_id = '%d'"
							.$add_where.
							"ORDER BY fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ",
							mysql_real_escape_string($param->book_info_id),
							$param->limit,
							$param->offset
							);
		}

		$query = $this->db->query($sql);
		
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			foreach ($query->result() as $row){
				$sql_comment = sprintf("SELECT bc.* FROM  book_comment bc 
						WHERE bc.book_info_id=%d AND bc.fb_dataid='%s' AND bc.status!='deleted' ORDER BY fbdata_postedtime ",
						mysql_real_escape_string($param->book_info_id),
						mysql_real_escape_string($row->fb_dataid)
						);
				$query_comment = $this->db->query($sql_comment);
				$row->comment = '';
				unset($comments);
				foreach ($query_comment->result() as $row_comment){
					$comment_obj = unserialize($row_comment->comment_obj);
					$row_comment->comment_obj = $comment_obj;
					
					$comments[] = $row_comment;
				}
				if ($query_comment->num_rows()) $row->comment = $comments;
					
				$fbdata = unserialize($row->fbdata);
				$row->fbdata = $fbdata;
				$data[] = $row;
			}
		}
		// get total number of objects
		
		/*
		$sql = sprintf("SELECT COUNT( * ) FROM book_pages WHERE book_info_id = %d",
				mysql_real_escape_string($param->book_info_id)
				);
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			$data['total_objects'] = $query->result();
		}
		*/
		
		//echo $this->db->last_query();
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$data);
		return $ret;		
	}
	
	function get_total_objects_chapter ($param) {
		// get total number of objects		
		$total = mysql_query("SELECT COUNT( * )  FROM book_pages WHERE book_info_id = $param->book_info_id AND facebook_id = $param->facebook_id"); 
        $total = mysql_fetch_array($total); 
        return $total[0]; 
	}
	
	function get_total_objects ($param) {
		// get total number of objects		
		$total = mysql_query("SELECT COUNT( * )  FROM book_pages WHERE book_info_id = $param->book_info_id"); 
        $total = mysql_fetch_array($total); 
        return $total[0]; 
	}
	
	function get_lastrun_fbdata_updater($facebook_id,$token){
		$status=0;
		$msg='';
		$query = $this->db->get_where('fbdata_updater_log',array('facebook_id'=>$facebook_id,));		
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{			
			if ($query->num_rows() > 0){
				$row = $query->result();
				$data = $row[0]->lastrun_date;
			}else{
				if ($facebook_id && $token)
					$this->db->insert('fbdata_updater_log',array('facebook_id'=>$facebook_id,'token'=>$token));
				$data = false;
			}
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$data);
		return $ret;
	}
	
		//get the friends of the user 
	function get_friends($param,$limit,$offset){
		$query = $this->db->get_where('friends_raw_data', array("facebook_id"=>$param->facebook_id ),$limit,$offset);		
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$data[] = $row;
			}
		}else
			$data = false;
		return $data;
	}
	
	function get_fb_friends_by_name($param){
		$status=0;
		$msg='';		
		$data = '';
		
		$this->db->like('friends_name',$param->first_name,'after');
		$query = $this->db->get_where('friends_raw_data', array("facebook_id"=>$param->facebook_id));	
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{			
			if ($query->num_rows() > 0){				
				foreach ($query->result() as $row){
					$data .= ';'.$row->friends_fbid .':'. $row->friends_name;
				}
				$data = substr($data,1);
			}else{
				$status = 2;
				$msg = 'no result';
			}
		}		

		//status = 0 = no error
		//status = 1 = with error
		//status = 2 = no data
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$data);
		return $ret;
	}
	
	//retrieve all location information of the 
	function get_fbuser_location(){
	}
	
	
	////////////////////////////////////////////////////inserts function
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function set_to_new_comment_to_active($param){
		$status=0;
		$msg='';
		$sql = sprintf("UPDATE `book_comment` SET status='active' WHERE book_info_id=%d AND status='%s' AND (datediff(curdate(),fbdata_postedtime)>0)",
					mysql_real_escape_string($param->book_info_id),
					mysql_real_escape_string($param->current_status));
		$this->db->query($sql);
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>'');
		return $ret;
	}
	//mychelle
	function set_deleted_pages($param){
		$status=0;
		$msg='';
		try{
			$sql = sprintf("UPDATE `book_pages` SET is_removed=1 WHERE fb_dataid IN ('%s'))",
						mysql_real_escape_string($param->str_fb_dataid));
			$this->db->query($sql);
			if ($this->db->_error_message()){
				$msg = $this->db->_error_message();	
				$status  = 1;
			}
		} catch (Exception $e) {
			$msg = $this->db->_error_message();
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>'');
		return $ret;
	}
	
	//this will update or insert to create a new book
	function set_book_info($param){
		$status=0;
		$msg='';
		$id = 0;
		
		$query = $this->db->get_where('book_info', array('book_info_id' => $param->book_info_id));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){
				$this->db->where('book_info_id',$param->book_info_id);
				$this->db->update('book_info',$param);
				$id = $query->row_array()->book_info_id;
			}else{
				$param->created_date =date('Y-m-j H:i:s');
				$this->db->insert('book_info',$param);
				$id = $this->db->insert_id();				
			}
		}
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	
	
	//this will insert the friend being ask for fb data or set the status of the friends request to shrae their data if it will be pending or approved
	function set_friends_being_askfor_fbdata($param){
		$status=0;
		$msg='';
		$id = 0;		
		
		if ($param->friends_fbid){
			$query = $this->db->get_where('friends_being_askfor_fbdata', array('book_info_id' => $param->book_info_id,'friends_fbid'=>$param->friends_fbid));
			if ($this->db->_error_message()){
				$msg = $this->db->_error_message();	
				$status  = 1;
			}else{
				if ($query->num_rows()>0){
					$row = $query->row();
					$this->db->where('friends_being_askfor_fbdata_id',$row->friends_being_askfor_fbdata_id);
					$this->db->update('friends_being_askfor_fbdata',$param);
					$id = $row->friends_being_askfor_fbdata;
				}else{
					$this->db->insert('friends_being_askfor_fbdata',$param);
					$id = $this->db->insert_id();
				}
			}
		}else{
			$status = 1;
			$msg = 'no friends_fbid posted';
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	//this will assign the page layout on a specific page
	function set_page_layout($param){
		$status=0;
		$msg = '';
		$this->db->where('book_info_id',$param->book_info_id);
		$this->db->where('page_num',$param->page_num);
		$this->db->update('book_pages',array('page_layout'=>$param->page_layout));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>'');
		return $ret;
	}
	
	//
	function set_book_cover($param){
		$status=0;
		$msg='';
		$book_cover_id = 0;
		$created_date = date('Y-m-j H:i:s');
		$book_cover = array(
					'book_info_id'=>$param->book_info_id,
					'friends_fbid'=>$param->friends_fbid,
					'created_date'=>$created_date
					);
		$this->db->insert('book_cover', $book_cover); 
		$book_cover_id = $this->db->insert_id();
	
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$book_cover_id);
		return $ret;	
	}
	
	//mychelle changed this
	
	function set_book_filter($param){
		$status=0;
		$msg='';
		$data='';
		$query = $this->db->get_where('book_filter', array('book_info_id' => $param->book_info_id, 'facebook_id' => $param->facebook_id));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){				
				$data = (array) $param;
				$this->db->where('book_filter_id',$query->row()->book_filter_id);
				$this->db->update('book_filter',$data);
				$id = $query->row_array()->book_filter_id;
			}else{
				$param->created_date =date('Y-m-j H:i:s');
				$data = (array) $param; 
				$this->db->insert('book_filter',$data);
				$id = $this->db->insert_id();				
			}
			//echo $this->db->last_query();
		}		
		//echo $this->db->last_query();
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	function set_book_filter_for_chapter($param){
		$status=0;
		$msg='';
		$data='';
		$query = $this->db->get_where('book_filter', array('book_info_id' => $param->book_info_id));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){				
				$data = (array) $param;
				$this->db->where('book_info_id',$param->book_info_id);
				$this->db->update('book_filter',$data);
				$id = $query->row_array()->book_filter_id;
			}else{
				$param->created_date =date('Y-m-j H:i:s');
				$data = (array) $param; 
				$this->db->insert('book_filter',$data);
				$id = $this->db->insert_id();				
			}
			//echo $this->db->last_query();
		}		
		//echo $this->db->last_query();
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	//this will add the border size to the fbdata object 
	function set_book_page_image_border($param){
		$status=0;
		$msg='';
		$id='';
		$query = $this->db->get_where('book_pages', array('fb_dataid' => $param->fb_dataid));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){
				$row = $query->result();				
				$fbdata = unserialize($row[0]->fbdata);
				$fbdata->border_size = $param->border_size;
				
				$data = array('fbdata'=>serialize($fbdata));
				
				$this->db->where('book_pages_id',$row[0]->book_pages_id);
				$this->db->update('book_pages',$data);
				$id = $row[0]->book_pages_id;				
			}
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	
	//this will revert the image to original
	function revert_image_to_original($param){
		$status=0;
		$msg='';
		$original_image='';
		$query = $this->db->get_where('book_pages', array('fb_dataid' => $param->fb_dataid));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){
				$row = $query->result();				
				$fbdata = unserialize($row[0]->fbdata);
				$fbdata->source = $fbdata->original_image;
				$original_image = $fbdata->original_image;
		
				$data = array('fbdata'=>serialize($fbdata));
				
				$this->db->where('book_pages_id',$row[0]->book_pages_id);
				$this->db->update('book_pages',$data);				
			}
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$original_image);
		return $ret;
	}
	
	//this will save the page number of the book pages and comment table
	function set_pagenum($param,$arr_fbdata,$arr_comment){
		$status=0;
		$msg='';			
		
		//update the book_pages page number
		foreach ($arr_fbdata as $key=>$val){
			$pagenum_pagelayout = explode(':',$val);
			$data_pages[] = array(
						'page_num'=>$pagenum_pagelayout[0],
						'page_layout'=>$pagenum_pagelayout[1],
						'fb_dataid'=>$key						
					  );		
		}
		if (count($data_pages)>0){		
			$this->db->where('book_info_id',$param->book_info_id);
			$this->db->update_batch('book_pages', $data_pages, 'fb_dataid'); 
		}
		
		//update the book_comment page number
		foreach ($arr_comment as $key=>$val){
			$data_comment[] = array(
						'page_num'=>$val,
						'book_comment_id'=>$key						
					  );		
		}	
		if (count($data_comment)>0){
			$this->db->where('book_info_id',$param->book_info_id);
			$this->db->update_batch('book_comment', $data_comment, 'book_comment_id'); 
		}
		
		
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		//$data = $this->db->last_query();		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>'');
		return $ret;				
	}
	
	// mychelle 7/25/2012
	// this will copy a current book pages to a new book
	function set_clone_book_pages ($param) {
		$param->created_date =date('Y-m-j H:i:s');
		
		$sql = "INSERT INTO book_pages (book_info_id,facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)
		(SELECT '{$param->new_book_info_id}',facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
		FROM book_pages
		WHERE book_info_id = {$param->old_book_info_id})";
		
		$this->db->query($sql); 
		
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
			echo $sql .'=='. $msg;die;
			break;			
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$book_cover_id);
		return $ret;
	}
	
	// this will copy a current book page comments to a new book
	function set_clone_book_comments ($param) {
		$param->created_date =date('Y-m-j H:i:s');
		
		$sql = "INSERT INTO book_comment (book_info_id,`connection`,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime)
		(SELECT '{$param->new_book_info_id}',connection,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime 
		FROM book_comment
		WHERE book_info_id = {$param->old_book_info_id})";
		
		$this->db->query($sql); 
		
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
			echo $sql .'=='. $msg;die;
			break;			
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$book_cover_id);
		return $ret;
	}
	
	//this will add book contents
	function set_book_pages_original_($param){
		$status=0;
		$msg='';		
		$param->created_date =date('Y-m-j H:i:s');
		
		//cleanup previous book pages as filter has been set again
		$this->db->delete('book_pages', array('book_info_id' => $param->book_info_id)); 
		
		//cleanup previous book pages as filter has been set again
		$this->db->delete('book_comment', array('book_info_id' => $param->book_info_id));		
		
		//print_r($param->table);
		foreach ($param->table as $table){
			//insert all data into the bookpages
			
			$where = empty($param->where[$table])?'':$param->where[$table];
			$sql = "INSERT INTO book_pages(book_info_id,facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)";

			//photos table has width and height in their column
			if ($table=='album_photos_raw_data' || $table=='photos_raw_data'){
				$sql .= "
					(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}' 
					FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
			}else{
				$sql .=	" 
					(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,0,0,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}' 
					FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
			}
			$this->db->query($sql);				
			//echo $sql;
			
			//insert comments data but first check if there is already a comment inserted
			/*$sql = "SELECT id FROM book_raw_comment raw 
					WHERE facebook_id='{$param->facebook_id}' AND 
					NOT EXISTS (SELECT book_comment_id FROM book_comment bc
								WHERE bc.book_info_id={$param->book_info_id} AND bc.comment_id=raw.comment_id)";
			$result = $this->db->query($sql);
			if (mysql_num_rows($result)==0){			
				
				$sql = "INSERT INTO book_comment(book_info_id,connection,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime)
						(SELECT '{$param->book_info_id}',book_rc.connection,book_rc.fb_dataid,book_rc.comment_id,book_rc.comment_obj,book_rc.page_num,book_rc.text_size,book_rc.status,book_rc.fbdata_postedtime
						FROM `book_raw_comment` book_rc 
						INNER JOIN $table raw_data ON book_rc.fb_dataid=raw_data.fb_dataid 
						WHERE raw_data.facebook_id='{$param->facebook_id}')";
				*/
				/*$sql = "INSERT INTO book_comment(book_info_id,connection,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime)
						(SELECT '{$param->book_info_id}',book_rc.connection,book_rc.fb_dataid,book_rc.comment_id,book_rc.comment_obj,book_rc.page_num,book_rc.text_size,book_rc.status,book_rc.fbdata_postedtime
						FROM `book_raw_comment` book_rc LEFT JOIN book_comment book_c ON book_rc.comment_id=book_c.comment_id 						
						WHERE book_rc.facebook_id='{$param->facebook_id}' AND book_c.comment_id IS NULL)
						";
				*/
				if ($param->album_content=='all'){
					$sql = "INSERT INTO book_comment(book_info_id,connection,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime)  
							(SELECT '{$param->book_info_id}',book_rc.connection,book_rc.fb_dataid,book_rc.comment_id,book_rc.comment_obj,book_rc.page_num,book_rc.text_size,book_rc.status,book_rc.fbdata_postedtime
							FROM `book_raw_comment` book_rc       
							WHERE book_rc.facebook_id='{$param->facebook_id}' AND 
							book_rc.comment_id NOT IN 
							(SELECT comment_id FROM book_comment WHERE book_info_id = $param->book_info_id))";
					$this->db->query($sql);
				}
			//}
			//echo $sql;
			
			if ($this->db->_error_message()){
				$msg = $this->db->_error_message();	
				$status  = 1;
				echo $sql .'=='. $msg;die;
				break;			
			}
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$book_cover_id);
		return $ret;			
	}
	
	// set book pages for chapter
	
	//this will add book contents
	function set_book_pages($param){
		$status=0;
		$msg='';		
		$param->created_date =date('Y-m-j H:i:s');
		$comment_where = "(Select fb_dataid from book_pages where book_info_id = $param->book_info_id and facebook_id = $param->facebook_id)";
		$sql = "DELETE FROM book_comment where fb_dataid IN " . $comment_where;
		$this->db->query($sql);
		//cleanup previous book pages as filter has been set again
		$this->db->delete('book_pages', array('book_info_id' => $param->book_info_id, 'facebook_id' => $param->facebook_id)); 
		
		//print_r($param->table);
		foreach ($param->table as $table){
			//insert all data into the bookpages
			
			$where = empty($param->where[$table])?'':$param->where[$table];
			$sql = "INSERT INTO book_pages(book_info_id,facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)";

			//photos table has width and height in their column
			if ($table=='album_photos_raw_data' || $table=='photos_raw_data'){
				$sql .= "
					(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}' 
					FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
			}else{
				$sql .=	" 
					(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,0,0,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}' 
					FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
			}
			$this->db->query($sql);				
			//echo $sql;
			
			
				if ($param->album_content=='all'){
					$sql = "INSERT INTO book_comment(book_info_id,connection,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime)  
							(SELECT '{$param->book_info_id}',book_rc.connection,book_rc.fb_dataid,book_rc.comment_id,book_rc.comment_obj,book_rc.page_num,book_rc.text_size,book_rc.status,book_rc.fbdata_postedtime
							FROM `book_raw_comment` book_rc       
							WHERE book_rc.facebook_id='{$param->facebook_id}' AND 
							book_rc.comment_id NOT IN 
							(SELECT comment_id FROM book_comment WHERE book_info_id = $param->book_info_id))";
					$this->db->query($sql);
				}
			//}
			//echo $sql;
			
			if ($this->db->_error_message()){
				$msg = $this->db->_error_message();	
				$status  = 1;
				echo $sql .'=='. $msg;die;
				break;			
			}
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$book_cover_id);
		return $ret;			
	}
	
	//saves information of the book creator
	function set_book_creator($param){
		$status=0;
		$msg='';
		$data='';
		$query = $this->db->get_where('book_creator', array('facebook_id' => $param->facebook_id));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){				
				$data = (array) $param;
				$this->db->where('facebook_id',$param->facebook_id);
				$this->db->update('book_creator',$data);
				$id = $query->row_array()->book_creator_id;
			}else{
				$param->created_date =date('Y-m-j H:i:s');
				$data = (array) $param; 
				$this->db->insert('book_creator',$data);
				$id = $this->db->insert_id();				
			}
			//echo $this->db->last_query();
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	function set_lastrun_fbdata_updater($facebook_id,$token){
		$status=0;
		$msg='';
		$data = 0;
		
		if ($facebook_id && $token){
			$cdate = date('Y-m-d H:i:s');
			$db_data = array('lastrun_date'=>$cdate,'token'=>$token);		
			$this->db->where('facebook_id',$facebook_id);		
			$this->db->update('fbdata_updater_log',$db_data);
	
			if ($this->db->_error_message()){
				$msg = $this->db->_error_message();	
				$status  = 1;
			}else
				$data = $this->db->insert_id();
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$data);
		return $ret;
	}
	
	function set_new_image($param){
		$status=0;
		$msg='';
		$data='';
		
		$query = $this->db->get_where('book_pages', array('book_info_id' => $param->book_info_id,'fb_dataid'=>$param->fb_dataid));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){				
				$row = $query->result();
				$fbdata = unserialize($row[0]->fbdata);
				$fbdata->previous_image = $fbdata->source;
				$fbdata->previous_width = $fbdata->width;
				$fbdata->previous_height = $fbdata->height;
				
				$size = getimagesize($param->new_image_url);
				$fbdata->source = $param->new_image_url;
				$fbdata->width = $size[0];
				$fbdata->height = $size[1];
				
				$data = array('fbdata'=>serialize($fbdata),'width'=>$size[0],'height'=>$size[1]);
				
				$this->db->where('book_pages_id',$row[0]->book_pages_id);
				$this->db->update('book_pages',$data);
				$id = $row[0]->book_pages_id;
			}else{
				$msg = 'invalid fb_dataid pass';
				$id=0;
			}
			//echo $this->db->last_query();
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	// check if user is a return user or new user
	//param = facebook_id
	function is_returning_user($param){
		$status=0;
		$msg='';
		$data='';
		$user_status = 0;
		$query = $this->db->get_where('book_creator', array('facebook_id' => $param->facebook_id));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){
				$user_status = 1;
			}else{
				$user_status = 0;
			}
		}
		//0 = new user
		//1 = returning user
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$user_status);
		return $ret;
	}
	//////////////////////////////////////////////delete
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function delete_allbooks($fbid){
		$query = $this->db->get_where('book_info', array("facebook_id"=>$fbid));		
		while ($row = mysql_fetch_object($sql)){
			$book_info_id = $row->book_info_id;
			$this->db->delete('book_pages',array('book_info_id'=>$book_info_id));sleep(1);
			$this->db->delete('book_cover',array('book_info_id'=>$book_info_id));sleep(1);
			$this->db->delete('book_filter',array('book_info_id'=>$book_info_id));sleep(1);
			$this->db->delete('book_comment',array('book_info_id'=>$book_info_id));sleep(1);
			$this->db->delete('friends_being_askfor_fbdata',array('book_info_id'=>$book_info_id));sleep(1);
		}
		
		$this->db->delete('fbdata_updater_log',array('facebook_id'=>$fbid));sleep(1);	
		$this->db->delete('book_info',array('facebook_id'=>$fbid));sleep(1);	
		$this->db->delete('albums_raw_data',array('facebook_id'=>$fbid));sleep(1);
		$this->db->delete('album_photos_raw_data',array('facebook_id'=>$fbid));sleep(1);
		if ($this->db->_error_message()){
			echo $this->db->_error_message();
		}
		$this->db->delete('feed_raw_data',array('facebook_id'=>$fbid));sleep(1);
		$this->db->delete('photos_raw_data',array('facebook_id'=>$fbid));sleep(1);
		$this->db->delete('statuses_raw_data',array('facebook_id'=>$fbid));sleep(1);
		$this->db->delete('book_raw_comment',array('facebook_id'=>$fbid));sleep(1);			
		$this->db->delete('friends_raw_data',array('facebook_id'=>$fbid));sleep(1);
		$this->db->delete('fbdata_updater_log',array('facebook_id'=>$fbid));sleep(1);			
	}
	
	
	function delete_book($book_info_id){
		$this->db->delete('book_info',array('book_info_id'=>$book_info_id));
		$this->db->delete('book_pages',array('book_info_id'=>$book_info_id));
		$this->db->delete('book_cover',array('book_info_id'=>$book_info_id));
		$this->db->delete('book_filter',array('book_info_id'=>$book_info_id));
		$this->db->delete('book_comment',array('book_info_id'=>$book_info_id));
	}
	
	function delete_all_rawdata($fbid){
		$this->db->delete('albums_raw_data',array('facebook_id'=>$fbid));
		$this->db->delete('album_photos_raw_data',array('facebook_id'=>$fbid));
		$this->db->delete('book_raw_comment',array('facebook_id'=>$fbid));
		$this->db->delete('feed_raw_data',array('facebook_id'=>$fbid));
		$this->db->delete('friends_raw_data',array('facebook_id'=>$fbid));
		$this->db->delete('photos_raw_data',array('facebook_id'=>$fbid));
		$this->db->delete('statuses_raw_data',array('facebook_id'=>$fbid));
	}
	
	//mychelle - start
	function set_share_chapter($param) {
		$status=0;
		$msg='';
		$sql = sprintf("UPDATE book_pages SET is_chapter = 1 WHERE book_info_id = %d AND facebook_id = %s",
						mysql_real_escape_string($param->book_info_id),
						mysql_real_escape_string($param->facebook_id)
						);
					
		$this->db->query($sql);
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>'');
		return $ret;
	}
	
	function set_is_edited_dao () {
		$status=0;
		$msg='';
		$id = 0;
		
		$query = $this->db->get_where('book_pages', array("fb_dataid"=>$param->fb_dataid));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status = 1;
		}else{
		$this->db->where('fb_dataid',$param->fb_dataid);
			$this->db->update('book_pages',$param->fb_dataid);
			$id = $query->row()->fb_dataid;
		}
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}
	
	function set_save_edited_photos_dao ($param) {
		$status=0;
		$msg='';
		$data='';
		
		$sql = sprintf("select * from edited_photos where book_info_id = %d AND origin_id = %d",
				mysql_real_escape_string($param->book_info_id),
				mysql_real_escape_string($param->origin_id)
				);
		$query = $this->db->query($sql);
				
		if ($query->num_rows()<=0){
			$param->date_created = date('Y-m-j H:i:s');
			
			$sql = "INSERT INTO edited_photos(book_info_id, facebook_id, origin, origin_id, original_url, edited_url, date_created) 
					VALUES ($param->book_info_id, $param->facebook_id, '$param->origin', $param->origin_id, '$param->original_url', '$param->edited_url', '$param->date_created')";
				
			$this->db->query($sql);
			if ($this->db->_error_message()){
				$msg = $this->db->_error_message();	
				$status  = 1;		
			}				
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>'');
		return $ret;
	}
	
	/*
	function set_save_edited_photos_dao ($param) {

		$status=0;
		$msg='';
		$data='';
		$query = $this->db->get_where('edited_photos', array('book_info_id' => $param->book_info_id,'origin_id'=>$param->origin_id));
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;
		}else{
			if ($query->num_rows()>0){
				$param->date_modified = date('Y-m-j H:i:s');				
				$data = (array) $param;
				$this->db->where('edited_photos_id', $query->row()->edited_photos_id);
				$this->db->update('edited_photos',$data);
				$id = $query->row_array()->edited_photos_id;
			}else{
				$param->date_created = date('Y-m-j H:i:s');
				$data = (array) $param; 
				$this->db->insert('edited_photos',$data);
				$id = $this->db->insert_id();				
			}
			//echo $this->db->last_query();
		}
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$id);
		return $ret;
	}*/
	//mychelle - end
	
		
	//////////////////////////////////////////////check function
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	
	
	
	
	/////////////////////////////////////////miscelaneous
	function initialize_book_cover($param,$limit,$offset=0){
		$status=0;
		$msg='';		
		$param->created_date =date('Y-m-j H:i:s');
		
		$sql = "INSERT INTO book_cover(book_info_id,friends_fbid,created_date) 
				(SELECT '{$param->book_info_id}',friends_fbid,'{$param->created_date}' 
				FROM friends_raw_data WHERE facebook_id='{$param->facebook_id}') LIMIT $limit OFFSET $offset";
		$this->db->query($sql);
		
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();	
			$status  = 1;		
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>'');
		return $ret;
	}
}

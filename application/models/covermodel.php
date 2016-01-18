<?php
/**
 * CoverModel Class
 * 
 * Model for Cover tab which extends CI_Model
 * 
 * @author	Marlo Morales
 * 
 */
class CoverModel extends CI_Model {
 
    public function __construct() {
        parent::__construct();
	}
	
	public function setBookCover($param){	
		$query = $this->db->get_where('book_cover_design', array('book_info_id' => $param->book_info_id));
		if (!$query->num_rows()){
		    $param->front_friends_pic = $this->getCoverFriends($param, 24);
		    $param->back_friends_pic = $this->getCoverFriends($param, 35);
		  
	        $cover_data = array(
	                       'cover_color'=>'',
	                       'cover_thumbnail'=>'',
	                       'cover_type_id'=>1,
	                       'front_cover_id'=>$this->getFrontCoverID($param),
	                       'back_cover_id'=>$this->getBackCoverID($param),
	                       'spine_id'=>$this->getSpineID($param),
	                       'book_info_id'=>$param->book_info_id   
	                       );
	        $this->db->insert('book_cover_design', $cover_data);
	        $id = $this->db->insert_id();

		}else{
			$row = $query->row();
			$id = $row->book_cover_design_id;
		}  
		return $id;
	}
	
	public function getCoverFriends($param, $limit=24, $offset=0){
		$this->db->select('friends_fbid');
		$query = $this->db->get_where('friends_raw_data', array('facebook_id' => $param->facebook_id), $limit, $offset);
		foreach ($query->result() as $row){
		   $friends .= $row->friends_fbid . ';';		   
		}
		return substr($friends,0,-1);
	}
	
	public function getFrontCoverID($param){
		$query = $this->db->get_where('book_front_cover', array('front_cover_id' => $param->front_cover_id));
		if ($query->num_rows()){
            $row = $query->row(); 
            $id = $row->front_cover_id;            
		}else{
            $data = array(
                  'author_name'=>'',
                  'user_pic'=>'',
                  'albums_pic'=>'',
                  'friends_pic'=>$param->front_friends_pic
                  );
            $this->db->insert('book_front_cover', $data);
            $id = $this->db->insert_id();		
		}
		return $id;
	}
	
    public function getBackCoverID($param){
        $query = $this->db->get_where('book_back_cover', array('back_cover_id' => $param->back_cover_id));
        if ($query->num_rows()){
            $row = $query->row(); 
            $id = $row->back_cover_id;           
        }else{
            $data = array(
                  'friends_pic'=>$param->back_friends_pic,
                  'fb_icons'=>'',
                  'other_icons'=>''
                  );
            $this->db->insert('book_back_cover', $data);
            $id = $this->db->insert_id();       
        }
        return $id;
    }
    	
    public function getSpineID($param){
        $query = $this->db->get_where('book_spine', array('spine_id' => $param->spine_id));
        if ($query->num_rows()){
            $row = $query->row(); 
            $id = $row->spine_id;            
        }else{
            $data = array(
                  'fb_icons'=>'',
                  'other_icons'=>''
                  );
            $this->db->insert('book_spine', $data);
            $id = $this->db->insert_id();          
        }
        return $id;
    }    
	public function getCover($book_info_id, $which_cover) {
		/**
		 * Retrieve all the cover information of a specific book 
		 * 
		 * @access	Public
		 * @param	Integer book_info_id This is the ID of a specific book information
		 * @param	String which_cover This is the specific part of the cover to retrieve info (e.g. front, back, spine)
		 * @return	Object cover_info Contains all the information of a specific book
		 * 			Boolean cover_info In case there's no retrieved information about the specific book, this will return FALSE
		 */
		$cover_info = new StdClass();
		$query = $this->db->get_where($which_cover ."_cover_view", array("book_info_id" => $book_info_id));
		
		if ($query->num_rows() > 0) {
			$cover_info	= $query->result();
			$cover_info = $cover_info[0];	
		} else {
			$cover_info = FALSE;
		}
		return $cover_info;
	}
	
	public function getCoverDefaultInfo($book_info_id) {
		/**
		 * Retrieve the basic cover information 
		 * 
		 * @access	Public
		 * @param	Integer book_info_id This is the ID of a specific book information
		 * @param	String which_cover This is the specific part of the cover to retrieve info (e.g. front, back, spine)
		 * @return	Object cover_info Contains all the information of a specific book
		 * 			Boolean cover_info In case there's no retrieved information about the specific book, this will return FALSE
		 */
		$cover_info = new StdClass();
		$sql = sprintf("SELECT book_name, CONCAT(fname, ' ', lname) AS author FROM book_info_with_creator_vw WHERE book_info_id=%d", mysql_real_escape_string($book_info_id));
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$cover_info	= $query->result();
			$cover_info = $cover_info[0];	
		} else {
			$cover_info = FALSE;
		}
		return $cover_info;
	}
	
	public function getFriends($fb_id = 0) {
		/**
		 * Retrieve the current user's Facebook friends info 
		 * 
		 * @access	Public
		 * @param	Integer fb_id This is the Facebook ID of the current user
		 * @return	Object friends Send back current user's Facebook friends information 
		 * 			Boolean friends In case there's no retrieved information, this will return FALSE
		 */
		$friends = new StdClass();
		
		$sql = sprintf("SELECT * FROM friends_raw_data WHERE facebook_id=%d ORDER BY friends_name", mysql_real_escape_string($fb_id));
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$friends = $query->result();
		} else {
			$friends = FALSE;
		}
		return $friends;
	}
	
	public function updateCover($which_cover, $cover_design = 0, $values = "") {
		/**
		 * Save new info of a specific part of the cover 
		 * 
		 * @access	Public
		 * @param	String which_cover This is the specific part of the cover (e.g. front, back, spine)
		 * @param	String values Contains the info to be created for a specific part of the cover 
		 * @return	Integer id This is the ID of the updated cover design info
		 */
		$status = 0;
		$msg = "";
		$id = 0;
		$book_info_id = 0;
		$book_name = "";
		$set_vals = array();
		$vals = array();
		$return_val = new StdClass();
		$cover = new StdClass();
		//echo "whichCover:". $which_cover .",coverDesignID:". $cover_design .",VALUES:". $values;
		$sql = sprintf("SELECT ". $which_cover ."_cover_id, book_info_id FROM book_cover_design WHERE book_cover_design_id=%d", mysql_real_escape_string($cover_design));
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$cover = $query->result();
			$cover = $cover[0];
			$book_info_id = $cover->book_info_id;
			if ($which_cover === "front") {
				$id = $cover->front_cover_id;
			} elseif ($which_cover === "back") {
				$id = $cover->back_cover_id;
			}
			$vals = explode(",", $values);
			$values = "";
			foreach ($vals as $key => $val) {
				$set_vals = explode(":", $val);
				if ($set_vals[0] != "book_name") {
					$values .= ", ".$set_vals[0] ." = '". $set_vals[1] ."'";
				} else {
					$book_name = $set_vals[1];
				}
			}
			if ($book_name) {
				$sql = sprintf("UPDATE book_info SET book_name='". $book_name ."' WHERE book_info_id=%d", mysql_real_escape_string($book_info_id));
				$query = $this->db->query($sql);
			}
			$sql = sprintf("UPDATE book_". $which_cover ."_cover SET". substr($values, 1) ." WHERE ". $which_cover ."_cover_id=%d", mysql_real_escape_string($id));
			$query = $this->db->query($sql);
			$status = 1;
			$msg = "Successfully saved!";
		}
		
		$return_val = array("status" => $status, "msg" => $msg, "data" => $id);
		
		return $return_val;
	}
}
?>

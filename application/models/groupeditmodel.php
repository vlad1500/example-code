<?php
/**
 * GroupEditModel Class
 * 
 * Model for Group Edit feature which extends CI_Model
 * 
 * @author	Marlo Morales
 * 
 */
class GroupEditModel extends CI_Model {
 
    public function __construct() {
        parent::__construct();
	}
	
	public function getShareSentInfo($facebook_id) {
		/**
		 * Retrieve all the information about the owner's friends who respond to the owner's invite to add photos to his/her album 
		 * 
		 * @access	Public
		 * @param	Integer book_info_id This is the ID of a specific book information
		 * @return	Object share_sent_info Contains all the information about list of owner's friends who respond to his/her message invite
		 * 			Boolean share_sent_info In case there's no retrieved information, this will return FALSE
		 */
		 
		 
		$share_sent_info = new StdClass();
		
		$sql = sprintf("SELECT frd.friends_name AS friend_name, "
					."biwcv.book_name AS album_name, fbaffd.requested_date AS date_requested, "
					."fbaffd.friends_being_askfor_fbdata_id AS id, fbaffd.book_info_id, "
					."fbaffd.friends_fbid, fbaffd.status, fbaffd.friend_book_info_id "
					."FROM book_info_with_creator_vw AS biwcv, friends_being_askfor_fbdata AS fbaffd, friends_raw_data AS frd "
					."WHERE biwcv.facebook_id=%d AND biwcv.book_info_id = fbaffd.book_info_id "
					."AND fbaffd.status = 'pending' AND biwcv.facebook_id = frd.facebook_id "
					."AND frd.friends_fbid = fbaffd.friends_fbid", 
					mysql_real_escape_string($facebook_id));
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$share_sent_info = $query->result();
			//$share_sent_info = $share_sent_info[0];	
		} else {
			$share_sent_info = FALSE;
		}
		return $share_sent_info;
		
	}

}
?>
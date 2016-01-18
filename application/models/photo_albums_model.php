<?php
class Photo_albums_model extends CI_Model {
 
    public function __construct(){
        parent::__construct();
	}
	
	function getUserAlbums($fbid){
		/**
		 * Author: Dennis 
		 * Returns the list of user albums where the album id is the key
		 */
		$status=0;
		$msg='';
		$albums = array();
		
		$query = $this->db->get_where('albums_raw_data', array('facebook_id'=>$fbid) );
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();
			$status  = 1;
		}else{
			if ($query->num_rows() > 0){
				$data	= $query->result();				
				foreach($data as $album){
					$albums[$album->album_id] = $album;					
				}				
			}
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$albums);
		return $ret;				
	}
	
	function getPhotosOfAlbum($album_id){
		/**
		 * Author: Dennis
		 * Returns the photos inside the album
		 */
		$status=0;
		$msg='';
		$photos = array();
		
		$query = $this->db->get_where('album_photos_raw_data', array('album_id'=>$album_id) );
		if ($this->db->_error_message()){
			$msg = $this->db->_error_message();
			$status  = 1;
		}else{
			if ($query->num_rows() > 0)
				$photos	= $query->result();
			
		}
		
		$ret = array('status'=>$status,'msg'=>$msg,'data'=>$photos);
		return $ret;		
	}
	
}
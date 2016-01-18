<?php
class Uploadm extends CI_Model {
 
    public function __construct(){
        parent::__construct();
	}
	
	function friend_name($id) {
		$txt = "SELECT friends_name FROM friends_raw_data WHERE friends_fbid = '$id'";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			$row = $q->row();
			return $row->friends_name;
		}
		
		return false;
	}
	
	function friend_fb_data($id) {
		$txt = "SELECT fbdata FROM friends_raw_data WHERE friends_fbid = '$id'";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			$row = $q->row();
			return $row->fbdata;
		}
		
		return false;
	}
	
	function friend_fb_photos($url) {
		$ch = curl_init();
	    $timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function album_photos_raw_data($id) {
		$txt = "SELECT id,small FROM album_photos_raw_data WHERE facebook_id = '$id'";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			return $q->result();
		}
		
		return false;
	}
	
	function photo_in_server($id) {
		$txt = "SELECT * FROM album_photos_raw_data_dl WHERE aprd_id = '$id'";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			return true;
		}
		
		return false;
	}
	
	function aprdd_write($id) {
		$txt = "INSERT INTO album_photos_raw_data_dl (aprd_id) VALUES ('$id')";
		$q = $this->db->query($txt);
		return true;
	}
	
	function album_photos_hd($id) {
		$txt = "SELECT small,hd from album_photos_raw_data WHERE id = '$id' LIMIT 0, 1";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			return $q->row();
		}
	}
}
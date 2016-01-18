<?php
/**
 * DeleteModel Class
 * 
 * Model for Deleted Albums in Home tab which extends CI_Model
 * 
 * @author	Marlo Morales
 * 
 */
class DeleteModel extends CI_Model {
 
    public function __construct() {
        parent::__construct();
	}
	
	public function setDelete($param) {
		/**
		 * Delete or restore an Album
		 * 
		 * @access	Public
		 * @param	Object param Contains all the parameters for adding and updating the books_deleted table
		 * @return	Integer id Added or updated album's ID
		 */
		
		$sql = sprintf('delete from book_info where book_info_id=%d', mysql_real_escape_string($param->book_info_id));
		$this->db->query($sql);
		
		$sql = sprintf('delete from book_cover_thumbnail where book_info_id=%d', mysql_real_escape_string($param->book_info_id));
		$this->db->query($sql);
		
		$sql = sprintf('delete from book_settings where book_info_id=%d', mysql_real_escape_string($param->book_info_id));
		$this->db->query($sql);
		
		$sql = sprintf('delete from book_filter where book_info_id=%d', mysql_real_escape_string($param->book_info_id));
		$this->db->query($sql);
		
		//$sql = sprintf("UPDATE book_info SET status = 'deleted'" 
		//		." WHERE book_info_id = %d", 
		//		mysql_real_escape_string($param->book_info_id));
		//$this->db->query($sql);
		 
		$id = 0?$this->db->_error_message() :$param->book_info_id;
		return $id;
	}
	
	public function getDeletedAlbums($facebook_id) {
		/**
		 * Get all deleted Albums of a specific owner
		 * 
		 * @access	Public
		 * @param	Object facebook_id ID of the Album owner
		 * @return	Object deleted_albums Contains all the information about the Album owner's deleted albums
		 * 			Boolean deleted_albums In case there's no retrieved information, this will return FALSE
		 */
		 
		$sql = sprintf("SELECT * FROM books_deleted WHERE facebook_id = '%d' AND deleted_date IS NOT NULL", 
						mysql_real_escape_string($facebook_id));
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$deleted_albums = $query->result();
			//$deleted_albums = $deleted_albums[0];	
		} else {
			$deleted_albums = FALSE;
		}
		
		return $deleted_albums;
		
	}
}
?>
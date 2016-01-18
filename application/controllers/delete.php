<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

//header("P3P: CP=\"IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\"");
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
/**
 * Delete Class
 * 
 * Controller for Delete feature which extends CI_Controller
 * 
 * @author	Marlo Morales
 * 
 */
class Delete extends CI_Controller {
	
	public $return_val;
	public $delete_info;
	
	public function __construct($facebook_id = 0, $book_info_id = 0) {
		/**
		 * This is the Delete's constructor which load the Delete model and set default values of important data
		 * 
		 */
		parent::__construct();
		
		$this->load->model("DeleteModel");
		
		$this->delete_info = new stdClass;
		$this->return_val = new stdClass;
		
		$this->delete_info->user_fbid = (empty($facebook_id) || is_null($facebook_id) || !$facebook_id) ? $_COOKIE["hardcover_fbid"] : $facebook_id;
		$this->delete_info->book_info_id = (empty($book_info_id) || is_null($book_info_id) || !$book_info_id) ? $_COOKIE["hardcover_book_info_id"] : $book_info_id;
	}
	
	public function deleteBook($book_info_id = 0, $reason = NULL) {
		//$this->load->model("DeleteModel");
		$book_info_id = !$book_info_id || empty($book_info_id) || is_null($book_info_id) ? $this->input->post("book_info_id") : $book_info_id;
		$reason = empty($reason) || is_null($reason) ? $this->input->post("reason") : $reason;		
		
		$param = new stdClass;
		$param->book_info_id = $book_info_id;
		//$param->facebook_id = $deleted_books->delete_info->user_fbid;
		//$param->deleted_reason = $reason;
		//$param->restored_reason = NULL;
		
		$deleted_book_id = $this->DeleteModel->setDelete($param);
		$this->return_val->status = 0;
		$this->return_val->msg = "An error occurred while deleting your Album";
		if ($deleted_book_id) {
			$this->return_val->status = 1;
			$this->return_val->msg = "You successfully deleted your Album";
			$this->return_val->id = $deleted_book_id;
		}
		echo json_encode($this->return_val);
	}
	
	public function restoreBook($book_info_id = 0, $reason = NULL) {
		//$this->load->model("DeleteModel");
		$book_info_id = !$book_info_id || empty($book_info_id) || is_null($book_info_id) ? $this->input->post("book_info_id") : $book_info_id;
		$reason = empty($reason) || is_null($reason) ? $this->input->post("reason") : $reason;
		$restored_books = new Delete(0, $book_info_id);
		$param = new stdClass;
		$param->book_info_id = $restored_books->delete_info->book_info_id;
		$param->facebook_id = $restored_books->delete_info->user_fbid;
		$param->deleted_reason = NULL;
		$param->restored_reason = $reason;
		$deleted_book_id = $this->DeleteModel->setDelete($param);
		$this->return_val->status = 0;
		$this->return_val->msg = "An error occurred while restoring your Album";
		if ($deleted_book_id) {
			$this->return_val->status = 1;
			$this->return_val->msg = "You successfully restored your Album";
			$this->return_val->id = $deleted_book_id;
		}
		echo json_encode($this->return_val);
	}

}
?>
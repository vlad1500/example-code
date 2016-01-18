<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

header("P3P: CP=\"IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\"");
/**
 * GroupEdit Class
 * 
 * Controller for Group Edit feature which extends CI_Controller
 * 
 * @author	Marlo Morales
 * 
 */
class GroupEdit extends CI_Controller {
	
	public $new_group_edit;
	public $group_edit_info;
	public $return_val;
	
	public function __construct($fb_id = 0, $book_info_id = 0) {
		/**
		 * This is the Group Edit's constructor which load the Group Edit model and set default values of important data
		 * 
		 */
		parent::__construct();
		
		$this->load->model("GroupEdit");
		$this->load->helper(array("form", "url"));
		
		$this->group_edit_info = new stdClass;
		$this->return_val = new stdClass;
		//table=friends_being_askfor_fbdata, fields=friends_being_askfor_fbdata_id, book_info_id, friends_fbid, status, friend_book_info_id, requested_date
		$this->group_edit_info->user_fbid = $fb_id == 0 ? $_COOKIE["hardcover_fbid"] : $fb_id;
		$this->group_edit_info->book_info_id = $book_info_id == 0 ? $_COOKIE["hardcover_book_info_id"] : $book_info_id;
	}

	public function getCoverInfo($book_info_id = 0, $which_cover) {
		/**
		 * Retrieve Facebook friends' ID from raw data table
		 * 
		 * @access	Public
		 * @param	Integer book_info_id Current Book ID
		 * @param	String which_cover Value of the active cover to work on
		 * @return	Object return_val Facebook friends' ID in a form of JSON object
		 */
		$this->cover_info->book_info_id = ($book_info_id == 0 ? $this->cover_info->book_info_id : $book_info_id);
		$this->cover_info->which_cover = (empty($which_cover) ? $this->cover_info->which_cover : $which_cover);
		$friends_id = array();
		$num_friends = ($this->cover_info->which_cover == "front" ? 24 : 35);
		
		$cover = $this->CoverModel->getCover($this->cover_info->book_info_id, $this->cover_info->which_cover);
		
		$this->return_val->status = 0;
		$this->return_val->msg = "No Data";
		if ($cover) {
			$this->return_val->status = 1;
			$this->return_val->msg = "";
			$this->return_val->cover = $cover;
		} else {
			$cover = $this->CoverModel->getCoverDefaultInfo($this->cover_info->book_info_id);
			$friends = $this->getFBFriends(0, TRUE);
			$friends = explode(";", $friends->friends);
			$friends = array_slice($friends, 0, $num_friends);
			foreach ($friends as $key => $val) {
				$friends_id = explode(":", $val);
				$cover->friends_pic .= ";". $friends_id[0];
			}
			$cover->friends_pic = substr($cover->friends_pic, 1);
			$cover->user_pic = "";
			$cover->cover_type_name = "HardCover (default)";
			$cover->cover_design_id = 0;
			$this->return_val = new stdClass;
			$this->return_val->status = 1;
			$this->return_val->msg = "";
			$this->return_val->cover = $cover;
		}
		echo json_encode($this->return_val);
	}
	
	public function getFBFriends($fb_id = 0, $retrieve = FALSE) {
		/**
		 * Retrieve all the current user's Facebook friends' ID & Name
		 * 
		 * @access	Public
		 * @param	Integer fb_id Contains the current user's Facebook ID
		 * @param	Boolean retrieve Determine whether to return the value either in raw object or JSON object
		 * @return	Object return_val Contains the current user's Facebook friends' ID & Name (pattern - ID:Name;ID:Name;ID:Name)
		 */
		$fb_friends = new stdClass;
		$this->fb_user_info->facebook_id = (!$this->fb_user_info->facebook_id OR $fb_id == 0) ? $_COOKIE["hardcover_fbid"] : $fb_id;
		
		$fb_friends = $this->CoverModel->getFriends($this->fb_user_info->facebook_id);
		
		$this->return_val->status = 0;
		$this->return_val->msg = "No Friends Info";
		if ($fb_friends) {
			$this->return_val->status = 1;
			$this->return_val->msg = "";
			$fb_friends = $this->formatDBFriends($fb_friends);
			$this->return_val->friends = $fb_friends;
		}
		
		if ($retrieve) {
			return $this->return_val;
		} else {
			echo json_encode($this->return_val);
		}
	}
	
	public function popupScreen() {
		/**
		 * Load pop-up screen View for Add/Remove Friends button  
		 * 
		 * @access	Public
		 * @return	Object return_val View pop-up screen data in a form of JSON object 
		 */
		$this->return_val->status = 1;
		$this->return_val->msg = "";
		$data["friends"] = $this->formatDBFriends($this->CoverModel->getFriends($this->fb_user_info->facebook_id));
		$this->return_val->popup = $this->load->view("coverpopup", $data, TRUE);
		echo json_encode($this->return_val);
	}
	
	public function preview($which_cover) {
		/**
		 * Show preview of the active cover  
		 * 
		 * @access	Public
		 * @param	String which_cover Value of the active cover to work on
		 */
		$this->return_val->status = 1;
		$this->return_val->msg = "";
		$data["cover"] = (empty($which_cover) || is_null($which_cover) ? "front" : $which_cover);
		$this->return_val->preview = $this->load->view("coverpreview", $data, TRUE);
		echo json_encode($this->return_val);
	}
	
	public function saveCoverDesign($cover_design_id = 0, $which_cover) {
		/**
		 * Save the current cover design info
		 * 
		 * @access	Public
		 * @param	Integer cover_design_id Contains the current cover design ID
		 * @param	String which_cover This is the active cover
		 * @return	Object return_val Contains the status and confirmation message of saving cover design info
		 */
		$cover_design = new stdClass;
		$cover_design_id = ($cover_design_id = 0 ? $_COOKIE["hardcover_design_id"] : $cover_design_id);
		$this->cover_info->which_cover = (empty($which_cover) ? $this->cover_info->which_cover : $which_cover);
		$cover_design = $this->CoverModel->updateCover($this->cover_info->which_cover, $cover_design_id, $_COOKIE["hardcover_design_values"]);
		
		$this->return_val->status = 0;
		$this->return_val->msg = "";
		if ($cover_design) {
			$this->return_val->status = $cover_design["status"];
			$this->return_val->msg = $cover_design["msg"];
			$this->return_val->cover_design = $cover_design["data"];
		}
		echo json_encode($this->return_val);
	}
	
	private function formatDBFriends($fb_friends) {
		/**
		 * Make a comma separated value for Facebook friends' ID & Name  
		 * 
		 * @access	Private
		 * @param	Object fb_friends Contains Facebook friends' ID & Name
		 * @return	String friends Contains Facebook friends' ID & Name in this form - ID:Name;ID:Name(...)
		 */
		$friends = "";
		
		foreach ($fb_friends as $friend) {
			$friends .= ";". $friend->friends_fbid .":". $friend->friends_name;
		}
		$friends = substr($friends, 1);
		return $friends;
	}
}
?>
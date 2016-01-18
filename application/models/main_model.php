<?php
class Main_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	///////////////////////////////////////////////////////query function//////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getFBDetailsByFBId($param) {
		/**
		 * Retrieves the fb details info of a particular fb id
		 */
		$query = $this->db->get_where('friends_raw_data', array(
			'friends_fbid' => $param->friends_fbid
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$data         = $query->result();
				$friends_info = $data[0];
			} else
				$friends_info = false;
		}
		return $friends_info;
	}
	function init_book_settings($book_info_id) {
		/**
		Call during book creation
		*/
		$sql   = sprintf("select bs_id from book_settings where book_info_id=%d", $book_info_id);
		$query = $this->db->query($sql);
        
		if ($query->num_rows() <= 0) {
			$sql = sprintf("insert into book_settings(book_info_id) values(%d)", $book_info_id);
			$this->db->query($sql);
		}
        
		$msg    = 'Book cover saved.';
		$status = 0;
		
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => ''
		);
		
		return $ret;
	}
    
    function set_book_chapter($orig_book_param, $book_info_id, $post_chapter_name, $post_chapter_user){        
        $x = 0;
        $batch_row = array();
        $param = new stdClass();
        $param = $orig_book_param;
        $orig_book_name = $orig_book_param->book_name;
        foreach($post_chapter_name as $chapter_name){
            if ($chapter_name && $post_chapter_user[$x]){
                $chapter_user = $_POST['assigned_friend'][$x];
                $param->facebook_id = $chapter_user;
                $param->book_name = $orig_book_name . " - $chapter_name";
                
                $res = $this->set_name_book_creator($param);                
                $chapter_book_id = $res['data'];
                
                $this->init_book_settings($chapter_book_id);
                
                $row = array(
                            'book_info_id'=> $book_info_id,
                            'chapter_book_info_id'=>$chapter_book_id, 
                            'chapter_user'=> $chapter_user,
                            'chapter_name'=> $chapter_name,
                            'created_date'=> date('Y-m-d H:i:s')
                            );                
                $batch_row[] = $row;
            } 
            $x++;
        }

        if ($batch_row){            
            $this->db->insert_batch('book_chapter', $batch_row);
        }

    }
    
	function update_cover_data($data) {
		$book_info_id = $_COOKIE['hardcover_book_info_id'];
		$sql          = sprintf("update  book_info set book_name='%s', book_caption='%s' WHERE book_info_id = %d ", $data['cover_title'], $data['cover_author'], $_COOKIE['hardcover_book_info_id']);
		$this->db->query($sql);
		$sql   = sprintf("select bs_id from book_settings where book_info_id=%d", $book_info_id);
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
			$sql = sprintf("update  book_settings set is_show_book_title='%s', is_show_book_author='%s' WHERE book_info_id = %d ", $data["is_show_book_title"], $data["is_show_author"], $_COOKIE['hardcover_book_info_id']);
		else
			$sql = sprintf("insert into book_settings(book_info_id) values(%d)", $book_info_id);
		$this->db->query($sql);
		$msg    = 'Book cover saved.';
		$status = 0;
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => ''
		);
		return $ret;
	}
	function get_book_info_c($cid) {
		$sql   = sprintf("SELECT book_author FROM book_info WHERE book_info_id = %d", $cid);
		$query = $this->db->query($sql);
		return $query->result();
	}
	function check_only_user($data) {
		$query     = $this->db->get_where('book_settings', array(
			'book_info_id' => $data['book_info_id']
		));
		$data1     = $query->result();
		$book_info = $data1[0];
		$book_data = explode(",", $book_info->select_ids);
		// echo $data['friends_fbid'];
		/* if(in_array($data['friends_fbid'],$book_data ))
		{
		echo "hai"; exit;
		}*/
		if (in_array($data['friends_fbid'], $book_data)) {
			return true;
		} else {
			return false;
		}
	}
	function check_friend_in_share($book_info_id, $frined_id) {
		//echo $book_info_id.'______'.$frined_id;
		$sql      = sprintf("SELECT * FROM book_settings WHERE book_info_id = %d", $book_info_id);
		$query    = $this->db->query($sql);
		$user_ids = '';
		foreach ($query->result() as $row) {
			$user_ids = $row->select_ids;
		}
		$ids = explode(',', $user_ids);
		//print_r($ids); exit;
		//$query = $this->db->get_where('book_settings', array('facebook_id'=>$book_creater_id,'friends_fbid'=>$frined_id));
		if (in_array($frined_id, $ids)) {
			return true;
		} else {
			return false;
		}
	}
	function check_friend_relation($book_creater_id, $frined_id) {
		$query = $this->db->get_where('friends_raw_data', array(
			'facebook_id' => $book_creater_id,
			'friends_fbid' => $frined_id
		));
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	function get_contributers_data($fbid) {
		$query = $this->db->get_where('book_settings', array(
			'book_info_id' => $data['book_info_id']
		));
		$data  = $query->result();
	}
	function cover_page_selected($param) {
		$this->db->select('book_cover_pages.*');
		$this->db->from('book_info');
		if ($param->type == 'front') {
			$this->db->join('book_cover_pages', 'book_cover_pages.book_pages_id = book_info.front_cover_page');
		} else {
			$this->db->join('book_cover_pages', 'book_cover_pages.book_pages_id = book_info.back_cover_page');
		}
		$this->db->where('book_info.book_info_id', $param->book_info_id);
		//$this->db->where('friends_raw_data.facebook_id', $book_owner_fbid);
		$data = $this->db->get();
		foreach ($data->result() as $row) {
			unset($book_page);
			// print_r($row);
			$fbdata              = unserialize($row->fbdata);
			//$book_page->fb_dataid = $row->fb_dataid;
			$book_page_image_url = $fbdata->source;
		}
		return $book_page_image_url;
	}
	function cover_page_selected_v($param) {
		$data = '';
		$this->db->select('book_cover_pages.*');
		$this->db->from('book_info');
		$this->db->join('book_cover_pages', 'book_cover_pages.book_pages_id = book_info.front_cover_page');
		$this->db->where('book_info.book_info_id', $param->book_info_id);
		$data_front = $this->db->get();
		foreach ($data_front->result() as $row) {
			unset($book_page);
			$fbdata                            = unserialize($row->fbdata);
			$data['book_page_front_image_url'] = $fbdata->source;
		}
		$this->db->select('book_cover_pages.*');
		$this->db->from('book_info');
		$this->db->join('book_cover_pages', 'book_cover_pages.book_pages_id = book_info.back_cover_page');
		$this->db->where('book_info.book_info_id', $param->book_info_id);
		$data_back = $this->db->get();
		foreach ($data_back->result() as $row) {
			unset($book_page);
			$fbdata                           = unserialize($row->fbdata);
			$data['book_page_back_image_url'] = $fbdata->source;
		}
		return $data;
	}
	function get_contributers_data_perm($book_id, $book_owner_fbid)
	//echo $book_id.','.$book_owner_fbid ; exit;
		{
		$this->db->select('friends_being_askfor_fbdata.*,friends_raw_data.friends_name,friends_raw_data.fbdata');
		$this->db->from('friends_being_askfor_fbdata');
		$this->db->join('friends_raw_data', 'friends_raw_data.friends_fbid = friends_being_askfor_fbdata.friends_fbid');
		$this->db->where('friends_being_askfor_fbdata.book_info_id', $book_id);
		$this->db->where('friends_raw_data.facebook_id', $book_owner_fbid);
		$data     = $this->db->get();
		$raw_data = $data->result();
		if ($data->num_rows > 0) {
			return $raw_data;
		} else
			return false;
	}
	function get_friends_info($param) {
		$query = $this->db->get_where('friends_raw_data', array(
			'facebook_id' => $param->facebook_id,
			'friends_fbid' => $param->friends_fbid
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$data         = $query->result();
				$friends_info = $data[0];
			} else
				$friends_info = false;
		}
		return $friends_info;
	}
	function get_book_cover($book_info_id) {
		$book_info = new StdClass();
		$query     = $this->db->get_where('book_info_vw', array(
			"book_info_id" => $book_info_id
		));
		if ($query->num_rows() > 0) {
			$book_info            = $query->result();
			$book_info            = $book_info[0];
			$q                    = $this->db->get_where('book_info', array(
				"book_info_id" => $book_info_id
			));
			$bookInfo             = $q->result();
			$bookInfo             = $bookInfo[0];
			$book_info->book_desc = $bookInfo->book_desc;
			if ($book_info->book_desc == NULL)
				$book_info->book_desc = "<no description>";
		} else
			$book_info = false;
		return $book_info;
	}
	function get_settings($unique_url) {
		$book_info = new StdClass();
		$query     = $this->db->get_where('book_settings', array(
			"unique_url" => $unique_url
		));
		if ($query->num_rows() > 0) {
			$book_info = $query->result();
			$book_info = $book_info[0];
		} else
			$book_info = false;
		return $book_info;
	}
	function get_settings_unique($book_id) {
		$book_info = new StdClass();
		$query     = $this->db->get_where('book_settings', array(
			"book_info_id" => $book_id
		));
		if ($query->num_rows() > 0) {
			$book_info = $query->result();
			$book_info = $book_info[0];
		} else
			$book_info = false;
		return $book_info;
	}
	// this is the function to get the still unapproved photos shared by other users
	function get_bookpages_ready_to_share($fbid) {
		$data = false;
		$txt  = "SELECT bi.*  FROM book_info bi   WHERE bi.facebook_id = '$fbid' and publish=1 ORDER BY book_info_id";
		$q    = $this->db->query($txt);
		$rows = $q->result();
		$i    = 0;
		foreach ($rows as $k => $v) {
			if ($v->publish == 1) {
				$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$v->book_info_id'";
				$q1           = $this->db->query($txt1);
				$row_settings = $q1->result();
				if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
					$txt3 = "SELECT *    FROM book_pages bs   WHERE bs.book_info_id = '$v->book_info_id' and ready_to_share=0 and  facebook_id!='$fbid'";
					$q3   = $this->db->query($txt3);
					if ($q3->num_rows() > 0) {
						$rows3 = $q3->result();
						$ids   = "";
						foreach ($rows3 as $k3 => $v3) {
							$ids[] = $v3->facebook_id;
						}
						$bii                           = $v->book_info_id;
						$book_info                     = $this->_get_book_name_and_create_date($bii);
						$book_name                     = $book_info['book_name'];
						$data[$i]['book_info_id']      = $bii;
						$data[$i]['book_name']         = $book_name;
						$data[$i]['new_items']         = $q3->num_rows();
						$data[$i]['ids_found']         = $ids;
						$date                          = $v->created_date;
						$data[$i]['fbdata_postedtime'] = date('d-m-Y', strtotime($date));
						$i++;
					}
				}
			}
		}
		// print_r($data); exit;
		return $data;
	}
	// this is the function to get the still unapproved photos shared by other users
	/*	function get_bookpages_ready_to_share($fbid) {
	$data = false;
	$txt = "SELECT *, COUNT(ready_to_share) as count_share FROM book_pages WHERE ready_to_share = 0 AND from_facebook_id = '$fbid' GROUP BY book_info_id";
	$q = $this->db->query($txt);

	if ($q->num_rows() > 0) {
	$rows = $q->result();
	$i = 0;
	foreach ($rows as $row) {
	$bii = $row->book_info_id;
	$book_info = $this->_get_book_name_and_create_date($bii);
	$book_name = $book_info['book_name'];
	$data[$i]['book_info_id'] = $bii;
	$data[$i]['book_name'] = $book_name;
	$data[$i]['new_items'] = $row->count_share;
	$date = $row->fbdata_postedtime;
	$date = explode(" ", $date);
	$data[$i]['fbdata_postedtime'] = $date[0];
	$i++;
	}
	}

	return $data;
	}*/
	function demtest($id) {
		if ($id)
			$txt = "SELECT * FROM book_pages where book_pages_id=$id LIMIT 1";
		else
			$txt = "SELECT * FROM book_pages limit 1";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			$row  = $q->row();
			$data = unserialize($row->fbdata);
			//echo 'hey '.$data->from->name;
			//exit;
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			exit;
		}
	}
	function test() {
		$data = false;
		$txt  = "SELECT bi.*  FROM book_info bi   WHERE bi.facebook_id = '$fbid' and publish=1 ORDER BY book_info_id";
		$q    = $this->db->query($txt);
		$rows = $q->result();
		$i    = 0;
		foreach ($rows as $k => $v) {
			if ($v->publish == 1) {
				$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$v->book_info_id'";
				$q1           = $this->db->query($txt1);
				$row_settings = $q1->result();
				if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
					echo $txt3 = "SELECT *   FROM book_pages bs   WHERE bs.book_info_id = '$v->book_info_id' and ready_to_share=0 and  facebook_id!='$fbid'";
					$q3 = $this->db->query($txt3);
					if ($q3->num_rows() > 0) {
						$rows = $q3->result();
						$i    = 0;
						foreach ($rows as $row) {
							$bii                           = $row->book_info_id;
							$book_info                     = $this->_get_book_name_and_create_date($bii);
							$book_name                     = $book_info['book_name'];
							$data[$i]['book_info_id']      = $bii;
							$data[$i]['book_name']         = $book_name;
							$data[$i]['new_items']         = $row->count_share;
							$date                          = $row->fbdata_postedtime;
							$date                          = explode(" ", $date);
							$data[$i]['fbdata_postedtime'] = $date[0];
							$i++;
						}
					}
				}
			}
		}
		print_r($data);
		exit;
		return $data;
	}
	function get_bookpages_ready_to_share_by_book_info_id($bii) {
		$data         = false;
		$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$bii'";
		$q1           = $this->db->query($txt1);
		$row_settings = $q1->result();
		if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
			$fbid = $_COOKIE['hardcover_fbid'];
			$txt  = "SELECT book_pages.*,book_creator.fname,book_creator.lname,book_creator.fb_username FROM book_pages left join book_creator on book_creator.facebook_id=book_pages.facebook_id WHERE ready_to_share = 0 AND book_info_id = '$bii' and ready_to_share=0 and  book_pages.facebook_id!='$fbid'";
			$q    = $this->db->query($txt);
			if ($q->num_rows() > 0) {
				$rows      = $q->result();
				$i         = 0;
				$total_rec = count($rows);
				foreach ($rows as $row) {
					$bii                      = $row->book_info_id;
					$book_info                = $this->_get_book_name_and_create_date($bii);
					$book_name                = $book_info['book_name'];
					$book_created             = explode(" ", $book_info['created_date']);
					$data[$i]['id']           = $row->book_pages_id;
					$data[$i]['book_info_id'] = $bii;
					if ($row->fname == '' and $row->lname == '')
						$data[$i]['fullname'] = $row->fb_username;
					else
						$data[$i]['fullname'] = $row->fname . " " . $row->lname;
					$data[$i]['book_name']         = $book_name;
					$data[$i]['new_items']         = $total_rec;
					$date                          = $row->fbdata_postedtime;
					$date                          = explode(" ", $date);
					$data[$i]['fbdata_postedtime'] = $date[0];
					$data[$i]['total_pages']       = $this->_album_total_pages($bii);
					$data[$i]['from_facebook_id']  = $row->from_facebook_id;
					$data[$i]['book_created']      = $book_created[0];
					$data[$i]['fbdata']            = unserialize($row->fbdata);
					$data[$i]['profile_pic']       = 'http://graph.facebook.com/' . $row->facebook_id . '/picture?type=small';
					$i++;
				}
			}
		}
		return $data;
	}
	function update_thumb_order($data) {
		foreach ($data['ids'] as $k => $v) {
			$txt = "UPDATE book_pages SET thumb_view_order = '" . $k . "' WHERE book_pages_id =  '" . $v . "'";
			$this->db->query($txt);
		}
		return true;
	}
	// this function processes approval of new contents submitted by user
	// to the album
	function new_content_approval($action, $id) {
		$action = strtolower($action);
		if ($action == 'a') {
			//approve
			$txt = "UPDATE book_pages SET ready_to_share = 1 WHERE book_pages_id = '$id'";
		} elseif ($action == 'r') {
			//reject
			$txt = "UPDATE book_pages SET ready_to_share = 2 WHERE book_pages_id = '$id'";
		} elseif ($action == 'd') {
			//delete
			$txt = "DELETE FROM book_pages WHERE book_pages_id = '$id'";
		}
		$this->db->query($txt);
		return true;
	}
	function _album_total_pages($bii) {
		$txt = "SELECT COUNT(book_pages_id) as total_pages FROM book_pages WHERE book_info_id = '$bii' GROUP BY book_info_id";
		$q   = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			$row = $q->row();
			return $row->total_pages;
		}
		return false;
	}
	function _get_book_name_and_create_date($id) {
		$data = array(
			'book_name' => '',
			'created_date' => ''
		);
		$this->db->select('book_name, created_date');
		$this->db->where('book_info_id', $id);
		$q = $this->db->get('book_info_vw');
		if ($q->num_rows() > 0) {
			$row                  = $q->row();
			$data['book_name']    = $row->book_name;
			$data['created_date'] = $row->created_date;
		}
		return $data;
	}
	function display_img($blob) {
		header("Content-type: image/jpeg");
		echo $blob;
	}
	function get_booklist($fbid) {
		try {
			$param       = new stdClass();
			$fb_username = '';
			
			$book_with_chapter = '';
			$query       = $this->db->get_where('book_chapter',array('chapter_user'=>$fbid));
            foreach ($query->result() as $row){
                $book_with_chapter .= ',' . $row->book_info_id;
            }
            
            if ($book_with_chapter){
                $book_with_chapter = substr($book_with_chapter, 1);
                $is_chapter_user = ", IF(book_info_id IN ($book_with_chapter), 1, 0) as is_chapter_user ";
                $book_with_chapter = " OR book_info_id IN ($book_with_chapter)";
            }
            
            
			$fbid        = mysql_real_escape_string($fbid);
			$sql1        = sprintf("select *, IF((select id from book_chapter where chapter_book_info_id=bk_vw.book_info_id),1,0) is_chapter_user  from book_info_vw bk_vw where (facebook_id = %d or ghost_writer_id=%d) AND status = 'active' ORDER BY book_info_id DESC", $fbid, $fbid);
			$query1      = $this->db->query($sql1);
			//echo 'sql: '.$this->db->last_query();
			
			if ($query1->num_rows() > 0) {
				//echo 'dennis: ' . $query1->num_rows();
				foreach ($query1->result() as $row) {
					$param->facebook_id     = $row->facebook_id;
					$creator                = $this->get_book_creator($param);
					$fb_username            = $creator->fb_username;
					$book_info_id           = $row->book_info_id;
					$ret                    = $this->get_total_pages($book_info_id);
					$row->total_pages       = $ret['data'];
					$row->fb_username       = $fb_username;
					$ret                    = $this->get_total_comments($book_info_id, 'new');
					$row->total_newcomments = $ret['data'];
					$data[$book_info_id]    = $row;
				}
			} else
				$data = false;
		}
		catch (Exception $e) {
			$data = false;
			//print($e->getMessage());
		}
		return $data;
	}
	function get_chapters_for_friends($fbid) {
		$sql   = sprintf("select * from book_pages where facebook_id = %d AND is_chapter = 1 GROUP BY book_info_id", mysql_real_escape_string($fbid));
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				// get total pages
				$param->book_info_id  = $row->book_info_id;
				$param->facebook_id   = $fbid;
				$row->total_pages     = $this->get_total_objects_chapter($param);
				$data['total_pages']  = $row;
				// get book creator
				$sql                  = sprintf("SELECT * FROM book_info bi LEFT JOIN book_creator bc ON bi.facebook_id = bc.facebook_id WHERE bi.book_info_id = %d", mysql_real_escape_string($row->book_info_id));
				$query                = $this->db->query($sql);
				$qry_result           = $query->result();
				$data['book_creator'] = $qry_result[0];
			}
		} else
			$data = false;
		return $data;
	}
	//gets the book creator using the facebook id
	function get_book_creator($param) {
		$query = $this->db->get_where('book_creator', array(
			"facebook_id" => $param->facebook_id
		));
		if ($query->num_rows() > 0) {
			$data         = $query->result();
			$book_creator = $data[0];
		} else
			$book_creator = false;
		return $book_creator;
	}
	//josh get book creator by user name
	function get_book_creator_id($book_owner_fb_username) {
		$query = $this->db->get_where('book_creator', array(
			"fb_username" => $book_owner_fb_username
		));
		if ($query->num_rows() > 0) {
			$data         = $query->result();
			$book_creator = $data[0];
		} else
			$book_creator = false;
		return $book_creator;
	}
	//gets the book creator using the fb username
	function get_book_creator_by_fb_username($param) {
		$query = $this->db->get_where('book_creator', array(
			"fb_username" => $param->fb_username
		));
		if ($query->num_rows() > 0) {
			$data         = $query->result();
			$book_creator = $data[0];
		} else
			$book_creator = false;
		return $book_creator;
	}
	//gets the book creator using the fb username
	function get_book_creator_by_fb_id($param) {
		$query = $this->db->get_where('book_creator', array(
			"facebook_id" => $param->fb_id
		));
		if ($query->num_rows() > 0) {
			$data         = $query->result();
			$book_creator = $data[0];
		} else
			$book_creator = false;
		return $book_creator;
	}
	function get_book_creator_by_book_info_id($param) {
		$query = $this->db->get_where('book_info_with_creator_vw', array(
			"book_info_id" => $param->book_info_id
		));
		if ($query->num_rows() > 0) {
			$data         = $query->result();
			$book_creator = $data[0];
		} else
			$book_creator = false;
		return $book_creator;
	}
	//get the book information
	function get_book_info_by_book_name($book_name) {
		$status    = 0;
		$msg       = '';
		$book_info = false;
		$str       = str_replace("_", " ", $book_name);
		$this->db->select("*");
		$this->db->from("book_info");
		$this->db->where("REPLACE(book_name,'_',' ') = '" . $str . "'");
		$query = $this->db->get();
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			$data      = $query->result();
			$book_info = $data[0];
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_info
		);
		return $ret;
	}
	//josh add search for book by user name
	function get_book_info_by_user_id($user_id) {
		$status    = 0;
		$msg       = '';
		$book_info = "";
		$this->db->select("*");
		$this->db->from("book_info");
		$this->db->where("facebook_id", $user_id);
		$this->db->where("status", "active");
		$this->db->where("publish", "1");
		$this->db->where("front_cover_page !=", "0");
		$this->db->order_by("modify_date", "desc");
		$this->db->limit(10, 0);
		$query = $this->db->get();
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			$books = $query->result();
			foreach ($books as $book)
				$book_info[] = $book;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_info
		);
		return $ret;
	}
	function get_book_info($book_info_id) {
		$status    = 0;
		$msg       = '';
		$book_info = false;
		$query     = $this->db->get_where('book_info', array(
			'book_info_id' => $book_info_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			$data      = $query->result();
			$book_info = $data[0];
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_info
		);
		return $ret;
	}
	//get the filters associated with the book
	function get_book_filter($book_info_id) {
		$status      = 0;
		$msg         = '';
		$book_filter = false;
		$query       = $this->db->get_where('book_filter', array(
			'book_info_id' => $book_info_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			$data        = $query->result();
			$book_filter = $data[0];
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_filter
		);
		return $ret;
	}
	//get total books of the user based on facebook id
	function get_total_books($fbid) {
		$status     = 0;
		$msg        = '';
		$total_book = 0;
		$query      = $this->db->get_where('book_info', array(
			'facebook_id' => $fbid
		));
		$total_book = $query->num_rows();
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $total_book
		);
		return $ret;
	}
	//get the total pages of the book
	function get_total_pages($book_info_id, $fbid = '') {
		$status      = 0;
		$msg         = '';
		$total_pages = 0;
		$fbid        = strlen($fbid) > 0 ? $fbid : $_COOKIE['hardcover_fbid'];
		//we will check first if the user has able to flip all the pages so the page num is set
		//if not, meaning we should just make an approximate count of the pages by assuming
		//that each book_page record out in db is one page
		$sql         = sprintf("SELECT count(book_pages_id) as pageno FROM book_pages WHERE (facebook_id!='%s' or facebook_id='%s') and  book_info_id=%d AND page_num=0 AND is_removed != 1", $fbid, $fbid, mysql_real_escape_string($book_info_id));
		$query       = $this->db->query($sql);
		if ($query->num_rows()) {
			$sql = sprintf("SELECT count(book_pages_id) as pageno FROM book_pages WHERE (facebook_id!='%s' or facebook_id='%s') and  book_info_id=%d AND is_removed != 1", $fbid, $fbid, mysql_real_escape_string($book_info_id));
		} else {
			$sql = sprintf("SELECT IFNULL( bc_page_num, bp_page_num ) AS pageno FROM book_details_vw WHERE book_info_id=%d
							ORDER BY pageno DESC LIMIT 1", mysql_real_escape_string($book_info_id));
		}
		$query = $this->db->query($sql);
		if ($query->num_rows()) {
			$row         = $query->result();
			$total_pages = $row[0]->pageno;
		}
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $total_pages
		);
		return $ret;
	}
	//get the total pages of the book
	function get_total_pages_uni($book_info_id) {
		$status       = 0;
		$msg          = '';
		$total_pages  = 0;
		$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$book_info_id'";
		$q1           = $this->db->query($txt1);
		$row_settings = $q1->result();
		if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
			//we will check first if the user has able to flip all the pages so the page num is set
			//if not, meaning we should just make an approximate count of the pages by assuming
			//that each book_page record out in db is one page
			$sql   = sprintf("SELECT count(book_pages_id) as pageno FROM book_pages WHERE ((facebook_id!=%s and ready_to_share=1 ) or facebook_id=%s) and book_info_id=%d AND page_num=0 AND is_removed != 1", $_COOKIE['hardcover_book_fbid'], $_COOKIE['hardcover_book_fbid'], mysql_real_escape_string($book_info_id));
			$query = $this->db->query($sql);
			if ($query->num_rows()) {
				$sql = sprintf("SELECT count(book_pages_id) as pageno FROM book_pages WHERE ((facebook_id!=%s and ready_to_share=1 ) or facebook_id=%s) and book_info_id=%d AND is_removed != 1", $_COOKIE['hardcover_book_fbid'], $_COOKIE['hardcover_book_fbid'], mysql_real_escape_string($book_info_id));
			} else {
				$sql = sprintf("SELECT IFNULL( bc_page_num, bp_page_num ) AS pageno FROM book_details_vw WHERE ((facebook_id!=%s and ready_to_share=1 ) or facebook_id=%s) and book_info_id=%d
												ORDER BY pageno DESC LIMIT 1", $_COOKIE['hardcover_book_fbid'], $_COOKIE['hardcover_book_fbid'], mysql_real_escape_string($book_info_id));
			}
		} else {
			//we will check first if the user has able to flip all the pages so the page num is set
			//if not, meaning we should just make an approximate count of the pages by assuming
			//that each book_page record out in db is one page
			$sql   = sprintf("SELECT count(book_pages_id) as pageno FROM book_pages WHERE book_info_id=%d AND page_num=0 AND is_removed != 1", mysql_real_escape_string($book_info_id));
			$query = $this->db->query($sql);
			if ($query->num_rows()) {
				$sql = sprintf("SELECT count(book_pages_id) as pageno FROM book_pages WHERE book_info_id=%d AND is_removed != 1", mysql_real_escape_string($book_info_id));
			} else {
				$sql = sprintf("SELECT IFNULL( bc_page_num, bp_page_num ) AS pageno FROM book_details_vw WHERE book_info_id=%d
												ORDER BY pageno DESC LIMIT 1", mysql_real_escape_string($book_info_id));
			}
		}
		$query = $this->db->query($sql);
		if ($query->num_rows()) {
			$row         = $query->result();
			$total_pages = $row[0]->pageno;
		}
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $total_pages
		);
		return $ret;
	}
	//get total comments on the book
	function get_total_comments($book_info_id, $status = 'new') {
		$status         = 0;
		$msg            = '';
		$total_comments = 0;
		$query          = $this->db->get_where('book_comment', array(
			'book_info_id' => $book_info_id,
			'status' => $status
		));
		$total_comments = $query->num_rows();
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $total_comments
		);
		return $ret;
	}
	function delete_book_pades_d($data) {
		$sql   = sprintf("DELETE  FROM book_pages WHERE book_info_id = '%d' and book_pages_id IN (%s)
				", $data['bid'], $data['ids']);
		//echo $sql; exit;
		$query = $this->db->query($sql);
		return true;
	}
	function update_book_pades_d($pageid, $pdata) {
		//echo "hai"; print_r(unserialize($data[0]->fbdata)); echo "hai111"; exit;
		$sql   = sprintf("update  book_pages set fbdata = '%s' where  book_pages_id = %d
				", $pdata, $pageid);
		//echo $sql; exit;
		$query = $this->db->query($sql);
		return true;
	}
	//this will get the book pages and its corresponding selected for albums
	function get_last_insert_images() {
		if ($_COOKIE['coverpage_data'] == '')
			return false;
		$sql   = sprintf("SELECT * FROM book_pages WHERE book_pages_id IN(%s) order by book_pages_id DESC LIMIT 0,1 ", $_COOKIE['coverpage_data']);
		$query = $this->db->query($sql);
		$str   = '';
		foreach ($query->result() as $row) {
			$fbdata = '';
			$fbdata = unserialize($row->fbdata);
			$str .= '<li style="width:50px; margin:5px; " id="' . $row->book_pages_id . '" class=""><img style="" width="50" height="50" src="' . $fbdata->source . '"><br><center><input style="margin:5px ;" checked="checked" type="radio" value="' . $row->book_pages_id . '" name="pic_data_id" id="pic_data_id_' . $row->book_pages_id . '"></center><a href="#" class="tooltip" title="Remove photo" style="display:none;" id="delete"><img class="ximg" src="http://hardcover.me/uploads/o_17l2kob8ea9ae6t1j0uhdescna.jpg"></a></li>';
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $str
		);
		return $ret;
	}
	//this will get the book pages and its corresponding selected for albums
	function get_book_content_selected($param) {
		$status = 0;
		$msg    = '';
		$data   = false;
		if ($param->type == 'front') {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'  and select_for_album =1
				ORDER BY fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		} else {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'  and select_for_back_album =1
				ORDER BY fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		}
		//echo $sql;
		$query = $this->db->query($sql);
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				unset($book_page);
				// print_r($row);
				$fbdata                   = unserialize($row->fbdata);
				$book_page->fb_dataid     = $row->fb_dataid;
				$book_page->image_url     = $fbdata->source;
				$book_page->message       = $fbdata->message;
				$book_page->book_pages_id = $row->book_pages_id;
				$sql_comment              = sprintf("SELECT bc.* FROM  book_comment bc
                        WHERE bc.book_info_id=%d AND bc.fb_dataid='%s'
                        AND bc.status!='deleted' ORDER BY fbdata_postedtime ", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($row->fb_dataid));
				$query_comment            = $this->db->query($sql_comment);
				//$row->comment = '';
				unset($comments);
				foreach ($query_comment->result() as $row_comment) {
					unset($comment);
					unset($from);
					$comment_obj                = unserialize($row_comment->comment_obj);
					$comment['book_comment_id'] = $row_comment->book_comment_id;
					$from->name                 = $comment_obj->from->name;
					$from->id                   = $comment_obj->from->id;
					$comment['from']            = $from;
					$comment['message']         = $comment_obj->message;
					//$comment['commend_obj'] = $comment_obj;
					$comments[]                 = $comment;
				}
				if ($query_comment->num_rows())
					$book_page->comment = $comments;
				$data[] = $book_page;
			}
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		return $ret;
	}
	function get_book_content_count($param) {
		$status       = 0;
		$msg          = '';
		$data         = false;
		$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$param->book_info_id'";
		$q1           = $this->db->query($txt1);
		$row_settings = $q1->result();
		if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
			$sql = sprintf("SELECT * FROM book_pages WHERE  ( (facebook_id!=%s and ready_to_share=1 ) or facebook_id=%s) and book_info_id = '%d'", $_COOKIE['hardcover_fbid'], $_COOKIE['hardcover_fbid'], mysql_real_escape_string($param->book_info_id));
		} else {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'", mysql_real_escape_string($param->book_info_id));
		}
		$query = $this->db->query($sql); //echo $query->num_rows(); exit;
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				unset($book_page);
				$fbdata                   = unserialize($row->fbdata);
				$book_page->fb_dataid     = $row->fb_dataid;
				$book_page->image_url     = $fbdata->source;
				$book_page->message       = $fbdata->message;
				$book_page->book_pages_id = $row->book_pages_id;
				/*
				$sql_comment = sprintf("SELECT bc.* FROM  book_comment bc
				WHERE bc.book_info_id=%d AND bc.fb_dataid='%s'
				AND bc.status!='deleted' ORDER BY fbdata_postedtime ",
				mysql_real_escape_string($param->book_info_id),
				mysql_real_escape_string($row->fb_dataid)
				);
				$query_comment = $this->db->query($sql_comment);
				unset($comments);

				foreach ($query_comment->result() as $row_comment){
				unset($comment);
				unset($from);
				$comment_obj = unserialize($row_comment->comment_obj);
				$comment['book_comment_id'] = $row_comment->book_comment_id;
				$from->name = $comment_obj->from->name;
				$from->id = $comment_obj->from->id;
				$comment['from'] = $from;
				$comment['message'] = $comment_obj->message;
				//$comment['commend_obj'] = $comment_obj;

				$comments[] = $comment;
				}

				if ($query_comment->num_rows()) $book_page->comment = $comments;
				*/
				$data[]                   = $book_page;
			}
		}
		return count($data);
	}
	function get_book_content_paginate($param) {
		$status       = 0;
		$msg          = '';
		$data         = false;
		
		$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$param->book_info_id'";
		$q1           = $this->db->query($txt1);
		$row_settings = $q1->result();        
        
		if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
			$sql = sprintf("SELECT * FROM book_pages WHERE  ( (facebook_id!=%s and ready_to_share=1 ) or facebook_id=%s) and book_info_id = '%d'
				            ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d , %d ", $_COOKIE['hardcover_fbid'], 
				            $_COOKIE['hardcover_fbid'], mysql_real_escape_string($param->book_info_id), $param->page, //josh
				            $param->limit);
		} else {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'
				            ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d , %d ", mysql_real_escape_string($param->book_info_id), $param->page, //josh
				            $param->limit);
		}
		
		$query = $this->db->query($sql); 
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				unset($book_page);				
				$fbdata                   = unserialize($row->fbdata);				
				$book_page->fb_dataid     = $row->fb_dataid;				
				$book_page->image_url     = $fbdata->source;
				$book_page->message       = $fbdata->message;
				$book_page->book_pages_id = $row->book_pages_id;
				
				/*
				$sql_comment              = sprintf("SELECT bc.* FROM  book_comment bc
                        WHERE bc.book_info_id=%d AND bc.fb_dataid='%s'
                        AND bc.status!='deleted' ORDER BY fbdata_postedtime ", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($row->fb_dataid));
				$query_comment            = $this->db->query($sql_comment);
				
				unset($comments);
				foreach ($query_comment->result() as $row_comment) {
					unset($comment);
					unset($from);
					$comment_obj                = unserialize($row_comment->comment_obj);
					$comment['book_comment_id'] = $row_comment->book_comment_id;
					$from->name                 = $comment_obj->from->name;
					$from->id                   = $comment_obj->from->id;
					$comment['from']            = $from;
					$comment['message']         = $comment_obj->message;		
					$comments[]                 = $comment;
				}
				if ($query_comment->num_rows())
					$book_page->comment = $comments;
                */
                
				$data[] = $book_page;
			}
		}
		
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		return $ret;
	}
	//this will get the book pages and its corresponding comments
	function get_book_content($param) {
		$status       = 0;
		$msg          = '';
		$data         = false;
		$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$param->book_info_id'";
		$q1           = $this->db->query($txt1);
		$row_settings = $q1->result();
		if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
			$sql = sprintf("SELECT * FROM book_pages WHERE  ( (facebook_id!=%s and ready_to_share=1 ) or facebook_id=%s) and book_info_id = '%d'
					ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", $_COOKIE['hardcover_fbid'], $_COOKIE['hardcover_fbid'], mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		} else {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'
					ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		}
		$sql_author = "select book_author,front_cover_page,back_cover_page,book_name from book_info where book_info_id='$param->book_info_id'";
		$res_author = $this->db->query($sql_author);
        //print_r($sql);
        
		foreach ($res_author->result() as $row_author) {
			$book_info->author_name      = $row_author->book_author;
			$book_info->front_cover_page = (strpos($row_author->front_cover_page, '.fbcdn.net') === false) ? basename($row_author->front_cover_page) : $row_author->front_cover_page;
			$book_info->back_cover_page  = (strpos($row_author->back_cover_page, '.fbcdn.net') === false) ? basename($row_author->back_cover_page) : $row_author->back_cover_page;
			$book_info->book_name        = $row_author->book_name;
		}
		$sql_front    = "select * from book_info_vw where book_info_id = '$param->book_info_id' AND status = 'active'";
		$query_front  = $this->db->query($sql_front);
		$result_front = $query_front->result();
		if ($result_front[0]->front_cover_location != NULL)
			$book_info->front_cover_location = $result_front[0]->front_cover_location;
		else
			$book_info->front_cover_location = $this->config->item('image_url') . "/196x144.png";
		$data->book_info = $book_info;
		//die(print_r($sql));
		$query           = $this->db->query($sql); //echo $query->num_rows(); exit;
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				unset($book_page);
				$fbdata                   = unserialize($row->fbdata);
				$book_page->fb_dataid     = $row->fb_dataid;
				$book_page->image_url     = (strpos($fbdata->source, '.fbcdn.net') === false) ? basename($fbdata->source) : $fbdata->source;
				$book_page->message       = $fbdata->message;
				$book_page->book_pages_id = $row->book_pages_id;
                $book_page->title = $row->title;
                $book_page->description = $row->description;
				$pages[]                  = $book_page;
			}
		}
		$data->book_pages = $pages;
		$ret              = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data,
			'sql' => $sql
		);
		return $ret;
	}
	//josh add for unique url
	function get_unique_book_content($param) {
		$status       = 0;
		$msg          = '';
		$data         = false;
		$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$param->book_info_id'";
		$q1           = $this->db->query($txt1);
		$row_settings = $q1->result();
		if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'
					ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		} else {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'
					ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		}
		$sql_author = "select book_author,front_cover_page,back_cover_page,book_name from book_info where book_info_id='$param->book_info_id'";
		$res_author = $this->db->query($sql_author);
		$imgaes_url = $this->config->item('image_upload_');
		foreach ($res_author->result() as $row_author) {
			$book_info->author_name      = $row_author->book_author;
			$book_info->front_cover_page = str_replace('http://images.hardcover.me/uploads/', '', $row_author->front_cover_page);
			$book_info->front_cover_page = str_replace('https://images.hardcover.me/uploads/', '', $book_info->front_cover_page);
			$book_info->front_cover_page = str_replace('//devimages.hardcover.me/uploads/', '', $book_info->front_cover_page);
			$book_info->front_cover_page = str_replace('https:', '', $book_info->front_cover_page);
			$book_info->front_cover_page = str_replace($this->config->item('image_upload_') . '/', '', $book_info->front_cover_page);
			//$book_info->front_cover_page=$row_author->front_cover_page;
			$book_info->back_cover_page  = str_replace('http://images.hardcover.me/uploads/', '', $row_author->back_cover_page);
			$book_info->back_cover_page  = str_replace('https://images.hardcover.me/uploads/', '', $book_info->back_cover_page);
			$book_info->back_cover_page  = str_replace('//devimages.hardcover.me/uploads/', '', $book_info->back_cover_page);
			$book_info->back_cover_page  = str_replace('https:', '', $book_info->back_cover_page);
			$book_info->back_cover_page  = str_replace($this->config->item('image_upload_') . '/', '', $book_info->back_cover_page);
			//$book_info->back_cover_page=$row_author->back_cover_page;
			$book_info->book_name        = $row_author->book_name;
			$filename                    = $row_author->front_cover_page;
			$nPathFront                  = explode("/", $filename);
			$nPathFront                  = $this->config->item('image_upload') . "/" . $nPathFront[4];
			$filename                    = $row_author->back_cover_page;
			$nPathBack                   = explode("/", $filename);
			$nPathBack                   = $this->config->item('image_upload') . "/" . $nPathBack[4];
			$exif_data                   = exif_read_data($nPathFront);
			//die(print_r($exif_data['FileDateTime']));
			if (!empty($exif_data['FileDateTime'])) {
				$book_info->front_created_date = date("Y,m,d,h,i,s", $exif_data['FileDateTime']);
			} else if (file_exists($nPathFront)) {
				$book_info->front_created_date = date("Y,m,d,h,i,s", filectime($nPathFront));
			}
			$exif_data = exif_read_data($nPathBack);
			if (!empty($exif_data['FileDateTime'])) {
				$book_info->back_created_date = date("Y,m,d,h,i,s", $exif_data['FileDateTime']);
			} else if (file_exists($nPathBack)) {
				$book_info->back_created_date = date("Y,m,d,h,i,s", filectime($nPathBack));
			}
		}
		$sql_front    = "select * from book_info_vw where book_info_id = '$param->book_info_id' AND status = 'active'";
		$query_front  = $this->db->query($sql_front);
		$result_front = $query_front->result();
		if ($result_front[0]->front_cover_location != NULL)
			$book_info->front_cover_location = $result_front[0]->front_cover_location;
		else
			$book_info->front_cover_location = "https://dev.hardcover.me/images/196x144.png";
		$data->book_info = $book_info;
		//die(print_r($sql));
		$query           = $this->db->query($sql); //echo $query->num_rows(); exit;
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				unset($book_page);
				$fbdata                   = unserialize($row->fbdata);
				$book_page->fb_dataid     = $row->fb_dataid;
				$book_page->image_url     = str_replace('http://images.hardcover.me/uploads/', '', $fbdata->source);
				$book_page->image_url     = str_replace('https://images.hardcover.me/uploads/', '', $book_page->image_url);
				$book_page->image_url     = str_replace('//devimages.hardcover.me/uploads/', '', $book_page->image_url);
				$book_page->image_url     = str_replace('https:', '', $book_page->image_url);
				$book_page->image_url     = str_replace($this->config->item('image_upload_') . '/', '', $book_page->image_url);
				//$book_page->image_url = $fbdata->source;
				$book_page->message       = $fbdata->message;
				$book_page->book_pages_id = $row->book_pages_id;
                $book_page->title = $row->title;
                $book_page->description = $row->description;
				//$book_page->created_date = date("d/n/Y", strtotime($row->fbdata_postedtime));
				$filename                 = $fbdata->source;
				$nPath                    = explode("/", $filename);
				$nPath                    = $this->config->item('image_upload') . "/" . $nPath[4];
				$exif_data                = exif_read_data($nPath);
				if (!empty($exif_data['FileDateTime'])) {
					$book_page->created_date = date("Y,m,d,h,i,s", $exif_data['FileDateTime']);
				} else if (file_exists($nPath)) {
					$book_page->created_date = date("Y,m,d,h,i,s", filectime($nPath));
				}
				$book_page->fb_username = $author_name;
				$pages[]                = $book_page;
			}
		}
		$data->book_pages = $pages;
		$ret              = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data,
			'sql' => $sql
		);
		return $ret;
	}
	//josh get book owner
	function get_book_owner($book_id) {
		$sql     = "SELECT * FROM  `book_info` WHERE  `book_info_id` =$book_id LIMIT 1";
		$query   = $this->db->query($sql);
		$results = $query->result();
		return $results[0]->facebook_id;
	}
	//josh check if signedfb
	function CheckIfSigned() {
	    require 'server/facebook.php';
        $facebook = new Facebook(array(
            'appId' => $this->config->item('fb_appkey'),
            'secret' => $this->config->item('fb_appsecret')
        ));
		$signed_request_data = $facebook->getSignedRequest();
		//die(print_r( $signedrequest ));
		//$data['signed_data'] = $signed_request_data;
		$fbid                = '';
		if (@array_key_exists('user_id', $signed_request_data)) {
			$fbid  = $signed_request_data['user_id'];
			$token = $signed_request_data['oauth_token'];
			setcookie("hardcover_fbid", $fbid, time() + 86400, '/');
			setcookie("hardcover_token", $token, time() + 86400, '/'); //expires in 2hrs
			return $signed_request_data;
		} else {
			//let clear the cookie everytime the user visit the dashboard
			setcookie("hardcover_fbid", "", time() - 3600);
			setcookie("hardcover_token", "", time() - 3600);
			return false;
		}
	}
	//josh get books again
	function select_books_again() {
		$fbid = $_COOKIE['hardcover_fbid'];
		//return $fbid;
		if ($fbid):
			$param                    = new stdClass();
			$param->facebook_id       = $fbid;
			$user_status              = $this->main_model->is_returning_user($param);
			$fb_user                  = $this->main_model->get_book_creator($param);
			$data['dashboard_detils'] = $this->main_model->get_dashboard_detils($fbid);
			foreach ($data['dashboard_detils'] as $k => $v) {
				$data['user_detils']                                    = $this->main_model->get_users_detils($v->book_info_id);
				$data['dashboard_detils'][$k]->book_name                = $data['user_detils'][0]->book_name;
				$data['dashboard_detils'][$k]->front_cover              = $data['user_detils'][0]->front_cover;
				$data['dashboard_detils'][$k]->total_pages              = $data['user_detils'][0]->total_pages;
				$data['dashboard_detils'][$k]->book_owner_facebook_id   = $data['user_detils'][0]->facebook_id;
				$data['user_name']                                      = $this->main_model->get_users_fb_name($data['user_detils'][0]->facebook_id);
				$data['dashboard_detils'][$k]->book_owner_facebook_name = $data['user_name'][0]->fname . ' ' . $data['user_name'][0]->lname;
			}
		//error_reporting(-1);
			$data['booklist']    = $this->main_model->get_booklist($fbid);
			$data['booked_info'] = $this->main_model->get_book_info_by_user_id($fbid);
			$data['fb_user']     = $fb_user;
		//echo 'booklist';

		//print_r($data['booklist']);
		endif;
		if ($data['booklist'])
			return $this->load->view("new-home", $data, TRUE);
		else
			return $this->load->view('book-no-book', $data, TRUE);
	}
	//josh add for top 10
	function get_top_unique_book_content($param) {
		$status       = 0;
		$msg          = '';
		$data         = false;
		$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$param->book_info_id'";
		$q1           = $this->db->query($txt1);
		$row_settings = $q1->result();
		if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'
					ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		} else {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'
					ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		}
		$sql_author = "select book_author,front_cover_page,back_cover_page,book_name from book_info where book_info_id='$param->book_info_id'";
		$res_author = $this->db->query($sql_author);
		foreach ($res_author->result() as $row_author) {
			$book_info->author_name      = $row_author->book_author;
			$book_info->front_cover_page = $row_author->front_cover_page;
			$book_info->back_cover_page  = $row_author->back_cover_page;
			$book_info->book_name        = $row_author->book_name;
			$filename                    = $row_author->front_cover_page;
			$nPath                       = explode("/", $filename);
			$nPath                       = $this->config->item('image_upload') . "/" . $nPath[4];
			$exif_data                   = exif_read_data($nPath);
			if (!empty($exif_data['DateTimeOriginal'])) {
				$book_info->front_created_date = date("j/n/Y", strtotime($exif_data['DateTimeOriginal']));
			}
		}
		$data->book_info = $book_info;
		//die(print_r($sql));
		$query           = $this->db->query($sql); //echo $query->num_rows(); exit;
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				unset($book_page);
				$fbdata                   = unserialize($row->fbdata);
				$book_page->fb_dataid     = $row->fb_dataid;
				$book_page->image_url     = $fbdata->source;
				$book_page->message       = $fbdata->message;
				$book_page->book_pages_id = $row->book_pages_id;
				//$book_page->created_date = date("d/n/Y", strtotime($row->fbdata_postedtime));
				$filename                 = $fbdata->source;
				$nPath                    = explode("/", $filename);
				$nPath                    = $this->config->item('image_upload') . "/" . $nPath[4];
				$exif_data                = exif_read_data($nPath);
				if (!empty($exif_data['DateTimeOriginal'])) {
					$book_page->created_date = date("d/m/Y H:i:s", strtotime($exif_data['DateTimeOriginal']));
				}
				$pages[] = $book_page;
			}
		}
		$data->book_pages = $pages;
		$ret              = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data,
			'sql' => $sql
		);
		return $ret;
	}
	//this will get the book pages and its corresponding comments
	function get_book_content_uni($param) {
		$status       = 0;
		$msg          = '';
		$data         = false;
		$txt1         = "SELECT bs.*  FROM book_settings bs   WHERE bs.book_info_id = '$param->book_info_id'";
		$q1           = $this->db->query($txt1);
		$row_settings = $q1->result();
		if ($row_settings[0]->who_can_contribute != 'friends_of_friends' and $row_settings[0]->content_approval == 1) {
			$sql = sprintf("SELECT * FROM book_pages WHERE  ( (facebook_id!=%s and ready_to_share=1 ) or facebook_id=%s) and book_info_id = '%d'
				ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", $_COOKIE['hardcover_book_fbid'], $_COOKIE['hardcover_book_fbid'], mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
			//$fbid= $_COOKIE['hardcover_fbid'];
		} else {
			$sql = sprintf("SELECT * FROM book_pages WHERE book_info_id = '%d'
				ORDER BY thumb_view_order,fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		}
		//echo $sql;
		$query = $this->db->query($sql); //echo $query->num_rows(); exit;
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				unset($book_page);
				// print_r($row);
				// End
				$fbdata                   = unserialize($row->fbdata);
				$book_page->fb_dataid     = $row->fb_dataid;
				$book_page->image_url     = $fbdata->source;
				/*$book_page->image_url = '<a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=$fbdata->source" onclick="
				popUp=window.open(
				\'https://www.facebook.com/sharer/sharer.php?u=\'+encodeURIComponent($fbdata->source),
				\'popupwindow\',
				\'scrollbars=yes,width=626,height=436\');popUp.focus();
				return false;" target="_blank" id="new_test_share"><img src="../../images/facebook.png" width="16" height="16" /></a>';*/
				$book_page->message       = $fbdata->message;
				$book_page->book_pages_id = $row->book_pages_id;
				$sql_comment              = sprintf("SELECT bc.* FROM  book_comment bc
                        WHERE bc.book_info_id=%d AND bc.fb_dataid='%s'
                        AND bc.status!='deleted' ORDER BY fbdata_postedtime ", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($row->fb_dataid));
				$query_comment            = $this->db->query($sql_comment);
				//$row->comment = '';
				unset($comments);
				foreach ($query_comment->result() as $row_comment) {
					unset($comment);
					unset($from);
					$comment_obj                = unserialize($row_comment->comment_obj);
					$comment['book_comment_id'] = $row_comment->book_comment_id;
					$from->name                 = $comment_obj->from->name;
					$from->id                   = $comment_obj->from->id;
					$comment['from']            = $from;
					$comment['message']         = $comment_obj->message;
					//$comment['commend_obj'] = $comment_obj;
					$comments[]                 = $comment;
				}
				if ($query_comment->num_rows())
					$book_page->comment = $comments;
				$data[] = $book_page;
			}
		}
		//print_r($data); exit;
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		return $ret;
	}
	public function insert_for_album_cover($data) {
		if ($data['type'] == 'front') {
			if ($data['ids'] != '') {
				echo $sql1 = sprintf("UPDATE book_info SET front_cover_page=%d WHERE book_info_id=%d", mysql_real_escape_string($data['ids']), $_COOKIE['hardcover_book_info_id']);
				$this->db->query($sql1);
			}
		} else if ($data['type'] == 'back') {
			if ($data['ids'] != '') {
				echo $sql1 = sprintf("UPDATE book_info SET back_cover_page=%d WHERE book_info_id=%d", mysql_real_escape_string($data['ids']), $_COOKIE['hardcover_book_info_id']);
				$this->db->query($sql1);
			}
		}
		return true;
	}
	public function insert_for_album($data) {
		if ($data['type'] == 'front') {
			if ($data['ids'] != '') {
				echo $sql1 = sprintf("UPDATE book_pages SET select_for_album=0 WHERE book_info_id=%d", mysql_real_escape_string($data['book_info_id']));
				$this->db->query($sql1);
				echo $sql = sprintf("UPDATE book_pages SET select_for_album=1 WHERE book_info_id=%d AND book_pages_id IN(%s)", mysql_real_escape_string($data['book_info_id']), mysql_real_escape_string($data['ids']));
				$this->db->query($sql);
			} else {
				$sql1 = sprintf("UPDATE book_pages SET select_for_album=0 WHERE book_info_id=%d ", mysql_real_escape_string($data['book_info_id']));
				$this->db->query($sql1);
			}
		} else if ($data['type'] == 'back') {
			if ($data['ids'] != '') {
				echo $sql1 = sprintf("UPDATE book_pages SET select_for_back_album=0 WHERE book_info_id=%d", mysql_real_escape_string($data['book_info_id']));
				$this->db->query($sql1);
				echo $sql = sprintf("UPDATE book_pages SET select_for_back_album=1 WHERE book_info_id=%d AND book_pages_id IN(%s)", mysql_real_escape_string($data['book_info_id']), mysql_real_escape_string($data['ids']));
				$this->db->query($sql);
			} else {
				$sql1 = sprintf("UPDATE book_pages SET select_for_back_album=0 WHERE book_info_id=%d ", mysql_real_escape_string($data['book_info_id']));
				$this->db->query($sql1);
			}
		}
		return true;
	}
	public function get_comment($param) {
		$sql_comment   = sprintf("SELECT bc.* FROM  book_comment bc
                        WHERE bc.book_info_id=%d
                        AND bc.status!='deleted' ORDER BY fbdata_postedtime ", mysql_real_escape_string($param->book_info_id));
		$query_comment = $this->db->query($sql_comment);
		//echo $sql_comment;
		unset($comments);
		foreach ($query_comment->result() as $row_comment) {
			$comment_obj              = unserialize($row_comment->comment_obj);
			$row_comment->comment_obj = $comment;
			$comments[]               = $comment_obj;
		}
		return $comments;
	}
	//this will get the book pages and its corresponding comments
	function get_book_pages($param) {
		$status = 0;
		$msg    = '';
		$data   = false;
		if ($param->page_num_start == -1 || $param->page_num_end == -1 || empty($param->page_num_start)) {
			$sql = sprintf("SELECT book_info_id,fb_dataid, fbdata, page_layout,page_num,connection,page_col FROM book_pages
							WHERE book_info_id=%d ORDER BY page_num", mysql_real_escape_string($param->book_info_id));
		} else {
			$sql = sprintf("SELECT book_info_id,fb_dataid, fbdata, page_layout,page_num,connection,page_col FROM book_pages
							WHERE book_info_id=%d AND (page_num>=%d AND page_num<=%d) ORDER BY page_num", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($param->page_num_start), mysql_real_escape_string($param->page_num_end));
		}
		$query = $this->db->query($sql);
		//echo $this->db->last_query();
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				$sql_comment   = sprintf("SELECT bc.* FROM  book_comment bc
						WHERE bc.book_info_id=%d AND bc.fb_dataid='%s' AND bc.status!='deleted' AND (bc.page_num>=%d AND bc.page_num<=%d)", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($row->fb_dataid), mysql_real_escape_string($param->page_num_start), mysql_real_escape_string($param->page_num_end));
				$query_comment = $this->db->query($sql_comment);
				if ($query_comment->num_rows())
					$row->comment = $query_comment->result();
				else
					$row->comment = '';
				$data[] = $row;
			}
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		return $ret;
	}
	//this will get the book pages and its corresponding comments
	function get_book_pages_for_testpage($param) {
		$status    = 0;
		$msg       = '';
		$data      = false;
		$add_where = " ";
		//get book owner
		$sql       = sprintf("SELECT facebook_id FROM book_info WHERE book_info_id = %d", mysql_real_escape_string($param->book_info_id));
		$query     = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$row = $query->row('facebook_id');
			if ($row == $param->facebook_id) {
				$add_where = " AND ready_to_share = 1 AND is_removed != 1 ";
			} else {
				$add_where = " AND facebook_id = '$param->facebook_id' AND is_removed != 1 ";
			}
		}
		//this will retrieve all records for a specific book
		if ($param->limit < 1) {
			$sql = sprintf("SELECT * FROM all_book_pages WHERE book_info_id = '%d'" . $add_where . "ORDER BY page_num", mysql_real_escape_string($param->book_info_id));
		} else {
			$sql = sprintf("SELECT * FROM all_book_pages WHERE book_info_id = '%d'" . $add_where . "ORDER BY fbdata_postedtime,fb_dataid,page_num LIMIT %d OFFSET %d ", mysql_real_escape_string($param->book_info_id), $param->limit, $param->offset);
		}
		$query = $this->db->query($sql);
		//echo $sql;
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			foreach ($query->result() as $row) {
				$sql_comment   = sprintf("SELECT bc.* FROM  book_comment bc
						WHERE bc.book_info_id=%d AND bc.fb_dataid='%s' AND bc.status!='deleted' ORDER BY fbdata_postedtime ", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($row->fb_dataid));
				$query_comment = $this->db->query($sql_comment);
				$row->comment  = '';
				unset($comments);
				foreach ($query_comment->result() as $row_comment) {
					$comment_obj              = unserialize($row_comment->comment_obj);
					$row_comment->comment_obj = $comment_obj;
					$comments[]               = $row_comment;
				}
				if ($query_comment->num_rows())
					$row->comment = $comments;
				$fbdata      = unserialize($row->fbdata);
				$row->fbdata = $fbdata;
				$data[]      = $row;
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
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		return $ret;
	}
	function get_total_objects_chapter($param) {
		// get total number of objects
		$total = mysql_query("SELECT COUNT( * )  FROM book_pages WHERE book_info_id = $param->book_info_id AND facebook_id = $param->facebook_id");
		$total = mysql_fetch_array($total);
		return $total[0];
	}
	function get_total_objects($param) {
		// get total number of objects
		$total = mysql_query("SELECT COUNT( * )  FROM book_pages WHERE book_info_id = $param->book_info_id");
		$total = mysql_fetch_array($total);
		return $total[0];
	}
	function get_lastrun_fbdata_updater($facebook_id, $token) {
		$status = 0;
		$msg    = '';
		$query  = $this->db->get_where('fbdata_updater_log', array(
			'facebook_id' => $facebook_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$row  = $query->result();
				$data = $row[0]->lastrun_date;
			} else {
				if ($facebook_id && $token)
					$this->db->insert('fbdata_updater_log', array(
						'facebook_id' => $facebook_id,
						'token' => $token
					));
				$data = false;
			}
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		return $ret;
	}
	//get the friends of the user
	function get_friends($param, $limit, $offset) {
		$query = $this->db->get_where('friends_raw_data', array(
			"facebook_id" => $param->facebook_id
		), $limit, $offset);
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
		} else
			$data = false;
		return $data;
	}
	function get_fb_friends_by_name($param) {
		$status = 0;
		$msg    = '';
		$data   = '';
		$this->db->like('friends_name', $param->first_name, 'after');
		$query = $this->db->get_where('friends_raw_data', array(
			"facebook_id" => $param->facebook_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$data .= ';' . $row->friends_fbid . ':' . $row->friends_name;
				}
				$data = substr($data, 1);
			} else {
				$status = 2;
				$msg    = 'no result';
			}
		}
		//status = 0 = no error
		//status = 1 = with error
		//status = 2 = no data
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		return $ret;
	}
	//retrieve all location information of the
	function get_fbuser_location() {
	}
	////////////////////////////////////////////////////inserts function
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function set_to_new_comment_to_active($param) {
		$status = 0;
		$msg    = '';
		$sql    = sprintf("UPDATE `book_comment` SET status='active' WHERE book_info_id=%d AND status='%s' AND (datediff(curdate(),fbdata_postedtime)>0)", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($param->current_status));
		$this->db->query($sql);
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => ''
		);
		return $ret;
	}
	function cleanse($string) {
		$test1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $string);
		return ($test1);
	}
	//mychelle
	function set_name_book_creator($param) {
		$status           = 0;
		$msg              = '';
		$id               = 0;
		$st               = $this->cleanse($param->book_name);
		$param->book_name = $st;
		$query            = $this->db->get_where('book_info', array(
			                 'book_name' => $st,
			                 'book_desc' => $param->book_desc,
			                 'book_size_id' => $param->book_size,
			                 'facebook_id' => $param->facebook_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				return false;
			} else {
				$param->created_date = date('Y-m-j H:i:s');
				$this->db->insert('book_info', $param);
				$id = $this->db->insert_id();
			}
		}
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
		return $ret;
	}
	//this will update or insert to create a new book
	function set_book_info($param) {
		$status = 0;
		$msg    = '';
		$id     = 0;
		$query  = $this->db->get_where('book_info', array(
			'book_info_id' => $param->book_info_id
		));
		if ($this->db->_error_message()) {
			$msg    = $thset_book_is->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$this->db->where('book_info_id', $param->book_info_id);
				$this->db->update('book_info', $param);
				$id = $query->row_array()->book_info_id;
			} else {
				$param->created_date = date('Y-m-j H:i:s');
				$this->db->insert('book_info', $param);
				$id = $this->db->insert_id();
			}
		}
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
		return $ret;
	}
	//this will insert the friend being ask for fb data or set the status of the friends request to shrae their data if it will be pending or approved
	function set_friends_being_askfor_fbdata($param) {
		$status = 0;
		$msg    = '';
		$id     = 0;
		if ($param->friends_fbid) {
			$query = $this->db->get_where('friends_being_askfor_fbdata', array(
				'book_info_id' => $param->book_info_id,
				'friends_fbid' => $param->friends_fbid
			));
			if ($this->db->_error_message()) {
				$msg    = $this->db->_error_message();
				$status = 1;
			} else {
				if ($query->num_rows() > 0) {
					$row = $query->row();
					$this->db->where('friends_being_askfor_fbdata_id', $row->friends_being_askfor_fbdata_id);
					$this->db->update('friends_being_askfor_fbdata', $param);
					$id = $row->friends_being_askfor_fbdata;
				} else {
					$this->db->insert('friends_being_askfor_fbdata', $param);
					$id = $this->db->insert_id();
				}
			}
		} else {
			$status = 1;
			$msg    = 'no friends_fbid posted';
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
		return $ret;
	}
	//this will assign the page layout on a specific page
	function set_page_layout($param) {
		$status = 0;
		$msg    = '';
		$this->db->where('book_info_id', $param->book_info_id);
		$this->db->where('page_num', $param->page_num);
		$this->db->update('book_pages', array(
			'page_layout' => $param->page_layout
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => ''
		);
		return $ret;
	}
	//
	function set_book_cover($param) {
		$status        = 0;
		$msg           = '';
		$book_cover_id = 0;
		$created_date  = date('Y-m-j H:i:s');
		$book_cover    = array(
			'book_info_id' => $param->book_info_id,
			'friends_fbid' => $param->friends_fbid,
			'created_date' => $created_date
		);
		$this->db->insert('book_cover', $book_cover);
		$book_cover_id = $this->db->insert_id();
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_cover_id
		);
		return $ret;
	}
	//mychelle changed this
	function set_book_filter($param) {
		$status = 0;
		$msg    = '';
		$data   = '';
		$query  = $this->db->get_where('book_filter', array(
			'book_info_id' => $param->book_info_id,
			'facebook_id' => $param->facebook_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$data = (array) $param;
				$this->db->where('book_filter_id', $query->row()->book_filter_id);
				$this->db->update('book_filter', $data);
				$id = $query->row_array()->book_filter_id;
			} else {
				$param->created_date = date('Y-m-j H:i:s');
				$data                = (array) $param;
				$this->db->insert('book_filter', $data);
				$id = $this->db->insert_id();
			}
			//echo $this->db->last_query();
		}
		//echo $this->db->last_query();
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
		return $ret;
	}
	function set_book_filter_for_chapter($param) {
		$status = 0;
		$msg    = '';
		$data   = '';
		$query  = $this->db->get_where('book_filter', array(
			'book_info_id' => $param->book_info_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$data = (array) $param;
				$this->db->where('book_info_id', $param->book_info_id);
				$this->db->update('book_filter', $data);
				$id = $query->row_array()->book_filter_id;
			} else {
				$param->created_date = date('Y-m-j H:i:s');
				$data                = (array) $param;
				$this->db->insert('book_filter', $data);
				$id = $this->db->insert_id();
			}
			//echo $this->db->last_query();
		}
		//echo $this->db->last_query();
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
		return $ret;
	}
	//this will add the border size to the fbdata object
	function set_book_page_image_border($param) {
		$status = 0;
		$msg    = '';
		$id     = '';
		$query  = $this->db->get_where('book_pages', array(
			'fb_dataid' => $param->fb_dataid
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$row                 = $query->result();
				$fbdata              = unserialize($row[0]->fbdata);
				$fbdata->border_size = $param->border_size;
				$data                = array(
					'fbdata' => serialize($fbdata)
				);
				$this->db->where('book_pages_id', $row[0]->book_pages_id);
				$this->db->update('book_pages', $data);
				$id = $row[0]->book_pages_id;
			}
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
		return $ret;
	}
	//this will revert the image to original
	function revert_image_to_original($param) {
		$status         = 0;
		$msg            = '';
		$original_image = '';
		$query          = $this->db->get_where('book_pages', array(
			'fb_dataid' => $param->fb_dataid
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$row            = $query->result();
				$fbdata         = unserialize($row[0]->fbdata);
				$fbdata->source = $fbdata->original_image;
				$original_image = $fbdata->original_image;
				$data           = array(
					'fbdata' => serialize($fbdata)
				);
				$this->db->where('book_pages_id', $row[0]->book_pages_id);
				$this->db->update('book_pages', $data);
			}
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $original_image
		);
		return $ret;
	}
	//this will save the page number of the book pages and comment table
	function set_pagenum($param, $arr_fbdata, $arr_comment) {
		$status = 0;
		$msg    = '';
		//update the book_pages page number
		foreach ($arr_fbdata as $key => $val) {
			$pagenum_pagelayout = explode(':', $val);
			$data_pages[]       = array(
				'page_num' => $pagenum_pagelayout[0],
				'page_layout' => $pagenum_pagelayout[1],
				'fb_dataid' => $key
			);
		}
		if (count($data_pages) > 0) {
			$this->db->where('book_info_id', $param->book_info_id);
			$this->db->update_batch('book_pages', $data_pages, 'fb_dataid');
		}
		//update the book_comment page number
		foreach ($arr_comment as $key => $val) {
			$data_comment[] = array(
				'page_num' => $val,
				'book_comment_id' => $key
			);
		}
		if (count($data_comment) > 0) {
			$this->db->where('book_info_id', $param->book_info_id);
			$this->db->update_batch('book_comment', $data_comment, 'book_comment_id');
		}
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		//$data = $this->db->last_query();
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => ''
		);
		return $ret;
	}
	// mychelle 7/25/2012
	// this will copy a current book pages to a new book
	function set_clone_book_pages($param) {
		$param->created_date = date('Y-m-j H:i:s');
		$sql                 = "INSERT INTO book_pages (book_info_id,facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)
		(SELECT '{$param->new_book_info_id}',facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
		FROM book_pages
		WHERE book_info_id = {$param->old_book_info_id})";
		$this->db->query($sql);
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
			echo $sql . '==' . $msg;
			die;
			break;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_cover_id
		);
		return $ret;
	}
	// this will copy a current book page comments to a new book
	function set_clone_book_comments($param) {
		$param->created_date = date('Y-m-j H:i:s');
		$sql                 = "INSERT INTO book_comment (book_info_id,`connection`,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime)
		(SELECT '{$param->new_book_info_id}',connection,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime
		FROM book_comment
		WHERE book_info_id = {$param->old_book_info_id})";
		$this->db->query($sql);
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
			echo $sql . '==' . $msg;
			die;
			break;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_cover_id
		);
		return $ret;
	}
	//this will add book contents
	function set_book_pages_original_($param) {
		$status              = 0;
		$msg                 = '';
		$param->created_date = date('Y-m-j H:i:s');
		//cleanup previous book pages as filter has been set again
		$this->db->where('connection !=', 'photo_from_pc');
		$this->db->delete('book_pages', array(
			'book_info_id' => $param->book_info_id
		));
		//cleanup previous book comment as filter has been set again
		$this->db->delete('book_comment', array(
			'book_info_id' => $param->book_info_id
		));
		if ($param->album_content == 'all') {
			$sql = "INSERT INTO book_comment(book_info_id,connection,fb_dataid,comment_id,comment_obj,page_num,text_size,status,fbdata_postedtime)
					(SELECT '{$param->book_info_id}',book_rc.connection,book_rc.fb_dataid,book_rc.comment_id,book_rc.comment_obj,book_rc.page_num,book_rc.text_size,book_rc.status,book_rc.fbdata_postedtime
					FROM `book_raw_comment` book_rc
					WHERE book_rc.facebook_id='{$param->facebook_id}' AND
					book_rc.comment_id NOT IN
					(SELECT comment_id FROM book_comment WHERE book_info_id = $param->book_info_id))";
			$this->db->query($sql);
		}
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
			echo $sql . '==' . $msg;
			die;
			break;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_cover_id
		);
		return $ret;
	}
	// set book pages for chapter
	//this will add book contents
	function set_book_pages($param) {
		$status              = 0;
		$msg                 = '';
		$param->created_date = date('Y-m-j H:i:s');
		//cleanup previous book pages as filter has been set again; excluded clean up photo's being uploaded manually
		$this->db->where('connection !=', 'photo_from_pc');
		$this->db->delete('book_pages', array(
			'book_info_id' => $param->book_info_id,
			'facebook_id' => $param->facebook_id
		));
		//echo "param->table):";
		//print_r($param->table);
		foreach ($param->table as $table) {
			//insert all data into the bookpages
			$where = empty($param->where[$table]) ? '' : $param->where[$table];
			if ($where) {
				$sql = "INSERT INTO book_pages(book_info_id,facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)";
				//photos table has width and height in their column
				if ($table == 'album_photos_raw_data' || $table == 'photos_raw_data') {
					$sql .= "
						(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
						FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
				} else {
					$sql .= "
						(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,0,0,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
						FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
				}
				//echo $sql; exit;
				$this->db->query($sql);
				//echo "last query: " . $this->db->last_query();
				if ($this->db->_error_message()) {
					$msg    = $this->db->_error_message();
					$status = 1;
					break;
				}
			}
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_cover_id
		);
		return $ret;
	}
	function set_book_pages_cover($param) {
		$status              = 0;
		$msg                 = '';
		$param->created_date = date('Y-m-j H:i:s');
		//cleanup previous book pages as filter has been set again; excluded clean up photo's being uploaded manually
		//$this->db->where('connection !=','photo_from_pc');
		//$this->db->delete('book_pages', array('book_info_id' => $param->book_info_id, 'facebook_id' => $param->facebook_id));
		//echo "param->table):";
		//print_r($param->table);
		foreach ($param->table as $table) {
			//insert all data into the bookpages
			$where = empty($param->where[$table]) ? '' : $param->where[$table];
			if ($where) {
				$sql = "INSERT INTO book_cover_pages(book_info_id,facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)";
				//photos table has width and height in their column
				if ($table == 'album_photos_raw_data' || $table == 'photos_raw_data') {
					$sql .= "
						(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
						FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
				} else {
					$sql .= "
						(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,0,0,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
						FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
				}
				//echo $sql; exit;
				$this->db->query($sql);
				//echo "last query: " . $this->db->last_query();
				if ($this->db->_error_message()) {
					$msg    = $this->db->_error_message();
					$status = 1;
					break;
				}
			}
			$d = '';
			$r = $this->db->insert_id();
			if (isset($_COOKIE['coverpage_data']) and (!isset($r) or $r == 0)) {
				//$d = $this->db->insert_id();
			} else {
				$d = $this->db->insert_id();
				setcookie("coverpage_data", $d, time() + 3600 * 24, '/');
			}
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_cover_id
		);
		return $ret;
	}
	function set_book_pages_cover_unique($param) {
		$status              = 0;
		$msg                 = '';
		$param->created_date = date('Y-m-j H:i:s');
		//cleanup previous book pages as filter has been set again; excluded clean up photo's being uploaded manually
		$this->db->where('connection !=', 'photo_from_pc');
		$this->db->delete('book_pages', array(
			'book_info_id' => $param->book_info_id,
			'facebook_id' => $param->facebook_id
		));
		//echo "param->table):";
		//print_r($param->table);
		foreach ($param->table as $table) {
			//insert all data into the bookpages
			$where = empty($param->where[$table]) ? '' : $param->where[$table];
			if ($where) {
				$sql = "INSERT INTO book_pages(book_info_id,facebook_id,`connection`,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,created_date)";
				//photos table has width and height in their column
				if ($table == 'album_photos_raw_data' || $table == 'photos_raw_data') {
					$sql .= "
						(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,width,height,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
						FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
				} else {
					$sql .= "
						(SELECT '{$param->book_info_id}',facebook_id,connection,fb_dataid,fbdata,0,0,fbdata_postedtime,friends_that_like,friends_that_commented,'{$param->created_date}'
						FROM $table WHERE facebook_id='{$param->facebook_id}' $where)";
				}
				//echo $sql; exit;
				$this->db->query($sql);
				//echo "last query: " . $this->db->last_query();
				if ($this->db->_error_message()) {
					$msg    = $this->db->_error_message();
					$status = 1;
					break;
				}
			}
			$d = '';
			if (isset($_COOKIE['coverpage_data'])) {
				$d = $_COOKIE['coverpage_data'] . "," . $this->db->insert_id();
			} else {
				$d = $this->db->insert_id();
			}
			setcookie("coverpage_data", $d, time() + 3600 * 24);
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_cover_id
		);
		return $ret;
	}
	//saves information of the book creator
	function set_book_creator($param) {
		$status = 0;
		$msg    = '';
		$data   = '';
		$query  = $this->db->get_where('book_creator', array(
			'facebook_id' => $param->facebook_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$data = (array) $param;
                //die(print_r($data));
				$this->db->where('facebook_id', $param->facebook_id);
				$this->db->update('book_creator', $data);
				$id = $query->row_array()->book_creator_id;
			} else {
				$param->created_date = date('Y-m-j H:i:s');
				$data                = (array) $param;
				$this->db->insert('book_creator', $data);
				$id = $this->db->insert_id();
			}
			//echo $this->db->last_query();
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
        //die(print_r($ret));
		return $ret;
	}
	function set_lastrun_fbdata_updater($facebook_id, $token) {
		$status = 0;
		$msg    = '';
		$data   = 0;
		if ($facebook_id && $token) {
			$cdate   = date('Y-m-d H:i:s');
			$db_data = array(
				'lastrun_date' => $cdate,
				'token' => $token
			);
			$this->db->where('facebook_id', $facebook_id);
			$this->db->update('fbdata_updater_log', $db_data);
			if ($this->db->_error_message()) {
				$msg    = $this->db->_error_message();
				$status = 1;
			} else
				$data = $this->db->insert_id();
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		return $ret;
	}
	function set_new_image($param) {
		$status = 0;
		$msg    = '';
		$data   = '';
		$query  = $this->db->get_where('book_pages', array(
			'book_info_id' => $param->book_info_id,
			'fb_dataid' => $param->fb_dataid
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$row                     = $query->result();
				$fbdata                  = unserialize($row[0]->fbdata);
				$fbdata->previous_image  = $fbdata->source;
				$fbdata->previous_width  = $fbdata->width;
				$fbdata->previous_height = $fbdata->height;
				$size                    = getimagesize($param->new_image_url);
				$fbdata->source          = $param->new_image_url;
				$fbdata->width           = $size[0];
				$fbdata->height          = $size[1];
				$data                    = array(
					'fbdata' => serialize($fbdata),
					'width' => $size[0],
					'height' => $size[1]
				);
				$this->db->where('book_pages_id', $row[0]->book_pages_id);
				$this->db->update('book_pages', $data);
				$id = $row[0]->book_pages_id;
			} else {
				$msg = 'invalid fb_dataid pass';
				$id  = 0;
			}
			//echo $this->db->last_query();
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
		return $ret;
	}
	// check if user is a return user or new user
	//param = facebook_id
	function is_returning_user($param) {
		$status      = 0;
		$msg         = '';
		$data        = '';
		$user_status = 0;
		$query       = $this->db->get_where('book_creator', array(
			'facebook_id' => $param->facebook_id
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			if ($query->num_rows() > 0) {
				$user_status = 1;
			} else {
				$user_status = 0;
			}
		}
		//0 = new user
		//1 = returning user
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $user_status
		);
		return $ret;
	}
	//////////////////////////////////////////////delete
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function delete_allbooks($fbid) {
		$query = $this->db->get_where('book_info', array(
			"facebook_id" => $fbid
		));
		while ($row = mysql_fetch_object($sql)) {
			$book_info_id = $row->book_info_id;
			$this->db->delete('book_pages', array(
				'book_info_id' => $book_info_id
			));
			sleep(1);
			$this->db->delete('book_cover', array(
				'book_info_id' => $book_info_id
			));
			sleep(1);
			$this->db->delete('book_filter', array(
				'book_info_id' => $book_info_id
			));
			sleep(1);
			$this->db->delete('book_comment', array(
				'book_info_id' => $book_info_id
			));
			sleep(1);
			$this->db->delete('friends_being_askfor_fbdata', array(
				'book_info_id' => $book_info_id
			));
			sleep(1);
		}
		$this->db->delete('fbdata_updater_log', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		$this->db->delete('book_info', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		$this->db->delete('albums_raw_data', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		$this->db->delete('album_photos_raw_data', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		if ($this->db->_error_message()) {
			echo $this->db->_error_message();
		}
		$this->db->delete('feed_raw_data', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		$this->db->delete('photos_raw_data', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		$this->db->delete('statuses_raw_data', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		$this->db->delete('book_raw_comment', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		$this->db->delete('friends_raw_data', array(
			'facebook_id' => $fbid
		));
		sleep(1);
		$this->db->delete('fbdata_updater_log', array(
			'facebook_id' => $fbid
		));
		sleep(1);
	}
	function delete_book($book_info_id) {
		$this->db->delete('book_info', array(
			'book_info_id' => $book_info_id
		));
		$this->db->delete('book_pages', array(
			'book_info_id' => $book_info_id
		));
		$this->db->delete('book_cover', array(
			'book_info_id' => $book_info_id
		));
		$this->db->delete('book_filter', array(
			'book_info_id' => $book_info_id
		));
		$this->db->delete('book_comment', array(
			'book_info_id' => $book_info_id
		));
	}
	function delete_all_rawdata($fbid) {
		$this->db->delete('albums_raw_data', array(
			'facebook_id' => $fbid
		));
		$this->db->delete('album_photos_raw_data', array(
			'facebook_id' => $fbid
		));
		$this->db->delete('book_raw_comment', array(
			'facebook_id' => $fbid
		));
		$this->db->delete('feed_raw_data', array(
			'facebook_id' => $fbid
		));
		$this->db->delete('friends_raw_data', array(
			'facebook_id' => $fbid
		));
		$this->db->delete('photos_raw_data', array(
			'facebook_id' => $fbid
		));
		$this->db->delete('statuses_raw_data', array(
			'facebook_id' => $fbid
		));
	}
	//mychelle - start
	function set_share_chapter($param) {
		$status = 0;
		$msg    = '';
		$sql    = sprintf("UPDATE book_pages SET is_chapter = 1 WHERE book_info_id = %d AND facebook_id = %s", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($param->facebook_id));
		$this->db->query($sql);
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => ''
		);
		return $ret;
	}
	function set_is_edited_dao() {
		$status = 0;
		$msg    = '';
		$id     = 0;
		$query  = $this->db->get_where('book_pages', array(
			"fb_dataid" => $param->fb_dataid
		));
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		} else {
			$this->db->where('fb_dataid', $param->fb_dataid);
			$this->db->update('book_pages', $param->fb_dataid);
			$id = $query->row()->fb_dataid;
		}
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $id
		);
		return $ret;
	}
	function set_save_edited_photos_dao($param) {
		$status = 0;
		$msg    = '';
		$data   = '';
		$sql    = sprintf("select * from edited_photos where book_info_id = %d AND origin_id = %d", mysql_real_escape_string($param->book_info_id), mysql_real_escape_string($param->origin_id));
		$query  = $this->db->query($sql);
		if ($query->num_rows() <= 0) {
			$param->date_created = date('Y-m-j H:i:s');
			$sql                 = "INSERT INTO edited_photos(book_info_id, facebook_id, origin, origin_id, original_url, edited_url, date_created)
					VALUES ($param->book_info_id, $param->facebook_id, '$param->origin', $param->origin_id, '$param->original_url', '$param->edited_url', '$param->date_created')";
			$this->db->query($sql);
			if ($this->db->_error_message()) {
				$msg    = $this->db->_error_message();
				$status = 1;
			}
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => ''
		);
		return $ret;
	}
	/////////////////////////////////////////miscelaneous
	function initialize_book_cover($param, $limit, $offset = 0) {
		$status              = 0;
		$msg                 = '';
		$param->created_date = date('Y-m-j H:i:s');
		$sql                 = "INSERT INTO book_cover(book_info_id,friends_fbid,created_date)
				(SELECT '{$param->book_info_id}',friends_fbid,'{$param->created_date}'
				FROM friends_raw_data WHERE facebook_id='{$param->facebook_id}') LIMIT $limit OFFSET $offset";
		$this->db->query($sql);
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 1;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => ''
		);
		return $ret;
	}
	//gets the book creator using the facebook id
	function iss_friend_ask_for_book_data($param) {
		$query = $this->db->get_where('friends_being_askfor_fbdata', array(
			"book_info_id" => $param['book_info_id'],
			"friends_fbid" => $param['friend_fbid']
		));
		if ($query->num_rows() > 0)
			return true;
		else
			return false;
	}
	//gets the book creator using the facebook id
	function get_book_pages_dev($param) {
		$query = $this->db->get_where('book_pages', array(
			"book_info_id" => $param->book_info_id
		));
		if ($query->num_rows() > 0) {
			$row = $query->result();
			return $row;
		} else
			return false;
	}
	function get_dashboard_detils($id) {
		$txt = "SELECT * FROM friends_being_askfor_fbdata WHERE friends_fbid = '$id'  ORDER BY friends_being_askfor_fbdata_id DESC";
		$q   = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			$result = $q->result();
			foreach ($result as $k => $v) {
				$book_info_id            = $v->book_info_id;
				$ret                     = $this->get_total_pages($book_info_id);
				$v->total_pages          = $ret['data'];
				$sql_front               = "select * from book_info_vw where book_info_id = '$book_info_id' AND status = 'active'";
				$query_front             = $this->db->query($sql_front);
				$result_front            = $query_front->result();
				$v->front_cover_location = $result_front[0]->front_cover_location;
				$v->publish              = $result_front[0]->publish;
			}
			return $result;
		} else {
			return false;
		}
	}
	function get_friends_books($id) {
		$sql       = "SELECT * FROM book_settings";
		$query_set = $this->db->query($sql);
		$data      = false;
		if ($query_set->num_rows() > 0) {
			foreach ($query_set->result() as $row) {
				$selected_ids = $row->select_can_see_ids;
				$book_info_id = $row->book_info_id;
				if ($selected_ids) {
					$select_ids = explode(",", $selected_ids);
					if (in_array($id, $select_ids)) {
						$sql        = "SELECT * FROM book_info WHERE book_info_id = '$book_info_id'";
						$query_info = $this->db->query($sql);
						if ($query_info->num_rows() > 0) {
							$result                          = $query_info->result();
							$param->facebook_id              = $result[0]->facebook_id;
							$fb_user                         = $this->get_book_creator($param);
							$result[0]->fb_username          = $fb_user->fb_username;
							$ret                             = $this->get_total_pages($book_info_id);
							$result[0]->total_pages          = $ret['data'];
							$sql_front                       = "select * from book_info_vw where book_info_id = '$book_info_id' AND status = 'active'";
							$query_front                     = $this->db->query($sql_front);
							$result_front                    = $query_front->result();
							$result[0]->front_cover_location = $result_front[0]->front_cover_location;
							$data[]                          = $result[0];
						}
					}
				}
			}
		}
		return $data;
	}
	function get_friends_collab($id) {
		$sql       = "SELECT * FROM book_settings";
		$query_set = $this->db->query($sql);
		$data      = false;
		if ($query_set->num_rows() > 0) {
			foreach ($query_set->result() as $row) {
				$selected_ids = $row->select_ids;
				$book_info_id = $row->book_info_id;
				if ($selected_ids) {
					$select_ids = explode(",", $selected_ids);
					if (in_array($id, $select_ids)) {
						$sql        = "SELECT * FROM book_info WHERE book_info_id = '$book_info_id'";
						$query_info = $this->db->query($sql);
						if ($query_info->num_rows() > 0) {
							$result                          = $query_info->result();
							$param->facebook_id              = $result[0]->facebook_id;
							$fb_user                         = $this->get_book_creator($param);
							$result[0]->fb_username          = $fb_user->fb_username;
							$ret                             = $this->get_total_pages($book_info_id);
							$result[0]->total_pages          = $ret['data'];
							$sql_front                       = "select * from book_info_vw where book_info_id = '$book_info_id' AND status = 'active'";
							$query_front                     = $this->db->query($sql_front);
							$result_front                    = $query_front->result();
							$result[0]->front_cover_location = $result_front[0]->front_cover_location;
							$data[]                          = $result[0];
						}
					}
				}
			}
		}
		return $data;
	}
	function get_users_detils($uid) {
		$txt = "SELECT * FROM book_info WHERE book_info_id = '$uid'";
		$q   = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			return $q->result();
		} else {
			return false;
		}
	}
	function get_users_name($uid) {
		$txt = "SELECT * FROM friends_raw_data WHERE friends_fbid = '$uid' LIMIT 1";
		$q   = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			return $q->result();
		} else {
			return false;
		}
	}
	function get_users_fb_name($uid) {
		$txt = "SELECT * FROM book_creator WHERE facebook_id = '$uid' LIMIT 1";
		$q   = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			return $q->result();
		} else {
			return false;
		}
	}
	function cover_page_author($params) {
		$temp  = '';
		$query = $this->db->get_where('book_cover_design', array(
			'book_info_id' => $params->book_info_id
		));
		if ($query->num_rows() > 0) {
			$row  = $query->result();
			$temp = $row[0]->front_cover_id;
		}
		return $temp;
	}
	function setGhostWriter($ghost_writer_id, $book_info_id) {
		//set only one ghost writer
		$query = $this->db->get_where('ghost_writer', array(
			'book_info_id' => $book_info_id
		));
		if ($query->num_rows() > 0) {
			$this->db->where('book_info_id', $book_info_id);
			$this->db->update('ghost_writer', array(
				'ghost_writer_id' => $ghost_writer_id
			));
		} else {
			$this->db->insert('ghost_writer', array(
				'ghost_writer_id' => $ghost_writer_id,
				'book_info_id' => $book_info_id
			));
		}
		if ($this->db->_error_message()) {
			$msg    = $this->db->_error_message();
			$status = 400;
		} else {
			$msg    = 'Success';
			$status = 0;
		}
		$ret = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $book_info_id
		);
		return $ret;
	}
    
    function isGhostWriter($ghost_writer_id, $book_info_id){
        //set only one ghost writer
        $query = $this->db->get_where('ghost_writer', array(
            'book_info_id' => $book_info_id,'ghost_writer_id'=>$ghost_writer_id
        ));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    //josh
    function updatePageInfo($data){
        $page_id = $data['book_pages_id'];
        if($page_id){
            $arr = array(
    			'title' => $data['title'],
	    		'description' => $data['description']
    		);
            $this->db->where('book_pages_id', $page_id);
            $this->db->update('book_pages', $arr);
        }
    }

}
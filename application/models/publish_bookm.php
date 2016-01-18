<?php
class Publish_bookm extends CI_Model {
 
    public function __construct(){
        parent::__construct();
        $this->load->model("main_model");
	}
	
	
	function publish($data) {
		$whoCanSee = $data['whocansee'];
		$collaborative = $data['collaborative'];
		$collaborateWith = ($data['collaborate_with']) ? $data['collaborate_with'] : 'none';
		$bookUrl = $data['book_url'];
		$shareToFacebook = ($data['share_facebook']) ? $data['share_facebook'] : 0;
		$userData = $data['user_data'];
		$userDataSee = $data['user_data_see'];
	 	$bookInfoId = $data['book_info_id'];
	 	$content_approval =  $data['content_appoval'];
        $ghostFriend = $data['user_data_ghost'];
	 	 
        $bs_id = $data['ds_id']; 
		$msg = '';
		$data = array(
			'book_info_id' => $bookInfoId,
			'who_can_see' => $whoCanSee,
			'collaborative' => $collaborative,
			'who_can_contribute' => $collaborateWith,
			'unique_url' => $bookUrl,
 			'share_to_facebook' => $shareToFacebook
		);

		if($collaborateWith=='all' or $collaborateWith=='friends')
			$data['content_approval'] = $content_approval;
		 
		if($data['who_can_contribute']=='select' and $data['collaborative']=='1' and $userData !='')
		   $data['select_ids']=$userData;
		 
		if($data['who_can_see'] == 'some_friends' )		 
		   $data['select_can_see_ids']=$userDataSee;
          
		if ($this->_book_exists($bs_id)) {  
			$this->_update_book_settings($bs_id, $data);
			$msg = 1;
		} else {   
 			// insert
			$this->db->insert('book_settings', $data);			  
		     
			$msg = 2;
		}
        //insert or update a ghost writer
        $query = $this->db->get_where('ghost_writer', array('book_info_id'=>$bookInfoId));
        if ($query->num_rows() > 0) {
            $this->db->where('book_info_id',$bookInfoId);
            $this->db->update('ghost_writer', array('ghost_writer_id'=>$ghostFriend));
        }else {
            $this->db->insert('ghost_writer', array('ghost_writer_id'=>$ghostFriend, 'book_info_id'=>$bookInfoId));
        }

        //update the share status of the book
		$update_data = array();
		$update_data['sharing_status'] =  'shared';
        $update_data['publish'] = 1;
		$this->db->where('book_info_id', $bookInfoId);
        $this->db->update('book_info', $update_data);
        //josh process images
        //$reply = "TEST";
        $reply = $this->processImages($bookInfoId);
		return $reply;
	}

	//josh mod for ask permissions
	function ask_photo_permission($data){        
        $askAddId = $data['ask_add_ids'];
        $bsId = $data['bs_id'];
        if ($this->_ask_book_exists($bsId)) {
            $query = $this->db->get_where('book_asking_permission', array('bs_id' => $bsId));
            foreach ($query->result() as $row){                
                $ex_add_ids = explode(",",$row->ask_add_ids);
                if(count($ex_add_ids) > 1){
                    foreach($ex_add_ids as $add_id){
                        if($add_id == $askAddId) $new_add_ids = $row->ask_add_ids;
                        else $new_add_ids = $row->ask_add_ids.", ".$askAddId;
                    }
                } else {
                    if($row->ask_add_ids == $askAddId) $new_add_ids = $row->ask_add_ids;
                    else $new_add_ids = $row->ask_add_ids.", ".$askAddId;
                }
            }            
            $this->db->set('ask_add_ids', $new_add_ids);
            $this->db->where('bs_id', $bsId);
            $this->db->update('book_asking_permission');
		} else {
			$this->db->insert('book_asking_permission', $data);
		}
		return true;           
	}
    function ask_see_permission($data){
        $askSeeId = $data['ask_see_ids'];        
        $bsId = $data['bs_id'];
        if ($this->_ask_book_exists($bsId)) {
            $query = $this->db->get_where('book_asking_permission', array('bs_id' => $bsId));
            foreach ($query->result() as $row){
                $ex_see_ids = explode(",",$row->ask_see_ids);
                if(count($ex_see_ids) > 1){
                    foreach($ex_see_ids as $see_id){
                        if($see_id == $askSeeId) $new_see_ids = $row->ask_see_ids;
                        else $new_see_ids = $row->ask_see_ids.", ".$askSeeId;
                    }
                } else {
                    if($row->ask_see_ids == $askSeeId) $new_see_ids = $row->ask_see_ids;
                    else $new_see_ids = $row->ask_see_ids.", ".$askSeeId;
                }                
            }
            $this->db->set('ask_see_ids', $new_see_ids);            
            $this->db->where('bs_id', $bsId);
            $this->db->update('book_asking_permission');
		} else {
			$this->db->insert('book_asking_permission', $data);
		}
		return true;           
	}
    function _ask_book_exists($id) {
		$txt = "SELECT bs_id FROM book_asking_permission WHERE bs_id = '$id'";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			return true;
		}		
		return false;
	}
    //josh
    function processImages($book_info_id) {
        $offset               = 0;
        $limit                = 100000;
        $param = new stdClass();
        $param->book_info_id   = $book_info_id;
        $param->limit          = $limit;
        $param->offset         = $offset;
        $book_pages            = $this->main_model->get_unique_book_content($param);
        $front_cover = $book_pages['data']->book_info->front_cover_page;
        if($front_cover)
            $this->doUploadSizes($front_cover);
        $back_cover = $book_pages['data']->book_info->back_cover_page;
        if($back_cover)
            $this->doUploadSizes($back_cover);
        $pages = $book_pages['data']->book_pages;
        $pCount = count($pages);
        $arr = FALSE;
        for($x=0;$x<$pCount;$x++){
            $filename = $pages[$x]->image_url;
            if($filename){
                $reply = $this->doUploadSizes($filename);
                if($reply != FALSE){
                    $arr[] = $reply;
                    continue;
                } else
                    break;
            }
        }
        return $arr;
    }
    function doUploadSizes($fileName) {
        $arr = "";
        // Create image for 1920x1440
        $reply = $this->convert_images($fileName, 1920, 1440);
		if($reply != FALSE){
		    $arr[] = $reply;
            $reply = $this->convert_images($fileName, 1680, 1050);
    		// Create image for 1680x1050
	    	if($reply != FALSE){
	    	    $arr[] = $reply;
                $reply = $this->convert_images($fileName, 1440, 900);
    	    	// Create image for 1440x900
	    	    if($reply != FALSE){
	    	        $arr[] = $reply;
                    $reply = $this->convert_images($fileName, 1366, 768);
            		// Create image for 1366x768
            		if($reply != FALSE){
            		    $arr[] = $reply;
            		    return $arr;
            		}else {
                       return FALSE;
                    }
                }else {
                   return FALSE;
                }
            }else {
               return FALSE;
            }
        }else {
            return FALSE;
        }

		//// Create image for 1280x1024
//		$this->convert_images($fileName, 1280, 1024);
//
//		// Create image for 1024x768
//		$this->convert_images($fileName, 1024, 768);
//
//		// Create image for 640x480
//		$this->convert_images($fileName, 640, 480);
//
//		// Create image for 1920x1440
//		$this->convert_images($fileName, 480, 320);
//
//		// Create image for 1920x1440
//		$this->convert_images($fileName, 320, 240);
//
//		// Create image for 150x150
//		$this->convert_images($fileName, 150, 150);
    }
	function convert_images($image_filename = '', $image_width=320, $image_height=240) {
		$image_source = $this->config->item('book_images_dir') . "/uploads/" . $image_filename;
		$image_size = $image_width . 'x' . $image_height;
		$image_filename_new = pathinfo($image_source);
		$image_filename_new = $image_filename_new['filename'] . '.' . $image_filename_new['extension'];
		$image_destination = $this->config->item('book_images_dir') . "/uploads/" . $image_size . '/' . $image_filename_new;
		//exec('(convert' . ' "'.$image_source.'" -resize "'.$image_size.'" "'.$image_destination.'") > /dev/null 2>/dev/null &');
		//exec('convert' . ' "'.$image_source.'" -resize "'.$image_size.'" "'.$image_destination.'"', $o, $r);
		//echo 'convert' . ' "'.$image_source.'" -resize "'.$image_size.'" "'.$image_destination.'"';
		//print_r($o);
		//print_r($r);
        $file = $image_destination;
      if (file_exists($file) == FALSE) {
        $image = new Imagick( $image_source );
        if($image->resizeImage( $image_width, $image_height,  imagick::FILTER_CATROM, 1 )){
            $image->setImageFormat( "jpeg" );
            $image->writeImage( $image_destination );
            $image->clear();
            $image->destroy();
            return $file." ~OK~ ";
        } else
            return FALSE;
      } else{
        return $file." ~EXISTING~ ";
      }
	}
    //end josh
	function _book_exists($id) {
		$txt = "SELECT bs_id FROM book_settings WHERE bs_id = '$id'";
		$q = $this->db->query($txt);
		if ($q->num_rows() > 0) {
			return true;
		}
		
		return false;
	}
	
	function _update_book_settings($id, $data) {
	   //josh add for merging if already there
        $sql = "select * from book_settings where bs_id = '$id'";
        $query = $this->db->query($sql);
        $result = $query->result();                
        
        $all_see = explode(",",$result[0]->select_can_see_ids);
        $search_see = explode(",",$data['select_can_see_ids']);
        $add_see = array_diff($search_see, $all_see);
        $imp_see = implode(",",array_merge((array)$all_see, (array)$add_see));
        $data['select_can_see_ids'] = $imp_see;
        
        $all = explode(",",$result[0]->select_ids);
        $search_this = explode(",",$data['select_ids']);
        $add = array_diff($search_this, $all);
        $imp = implode(",",array_merge((array)$all, (array)$add));
        $data['select_ids'] = $imp;
       //end
		$this->db->where('bs_id', $id);
		$this->db->update('book_settings', $data);
		return true;
	}
}

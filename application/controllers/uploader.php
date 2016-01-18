<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

class Uploader extends CI_Controller {
	public function __construct(){
        parent::__construct();		
		$this->load->model("upload_model");	
        $this->load->model("main_model");	
		//$this->load->model("book_cover_model");
	}
	
	function test(){
		echo 'test';
	}
	
	function upload_from_pc_unique(){
		
        $this->upload_from_pc();
	} 
	
	
	
		
	function upload_from_pc_cover($book_id , $fb_id){  
		//print_r($book_id); exit;
		if($book_id == "")
			$book_id = $_COOKIE['hardcover_book_info_id'];
	

		// Settings
		define(DIRECTORY_SEPARATOR,'/');
		$targetDir = $this->config->item('book_image_dir') . '/uploads';
		
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		
		// Uncomment this one to fake upload time
		// usleep(5000);
		
		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
        
		// Clean the fileName for security reasons
		$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
		
		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);
		
			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;
		
			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}
		
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		//die($filePath);
		// Create target dir
		if (!file_exists($targetDir))
			@mkdir($targetDir);
		
		// Remove old temp files
		if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
		
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}
		
			closedir($dir);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		
		
		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
		
		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];
		
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");
		
					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		} else {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");
		
				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		
				fclose($in);
				fclose($out);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename("{$filePath}.part", $filePath);
		}		
		
		list($img_width,$img_height) = getimagesize($filePath);
		$fbdata = new stdClass();
		$fbdata->source = $this->config->item('book_images_url') . "/uploads/$fileName";
		$fbdata->width = $img_width;
		$fbdata->height = $img_height;

		$param = new stdClass();
		$param->book_info_id = $_COOKIE['hardcover_book_info_id'];
		
		$param->facebook_id = $_COOKIE['hardcover_fbid'];
		$param->connection = 'photo_from_pc';
		$param->fbdata = serialize($fbdata);
		$param->width = $img_width;
		$param->height = $img_height;
		
		$ret = $this->upload_model->save_uploaded_to_book_pages_for_cv($param); 
		$d = '';
		if(isset($_COOKIE['coverpage_data']))
			{
				$d = $ret['data'];
			}else{
				$d =  $ret['data'];
			}
		setcookie("coverpage_data",$d,time()+3600*24,'/');
		echo json_encode($ret);
		// Return JSON-RPC response
		//die('{"jsonrpc" : "2.0", "result" : null, "id" : "id","file_path":'.$fileName.'}');
		
	}
	

	
	function upload_from_pc($book_id , $fb_id){
		//print_r($book_id); exit;
		if($book_id == "")
			$book_id = $_COOKIE['hardcover_book_info_id'];
	

		// Settings
		define(DIRECTORY_SEPARATOR,'/');
		$targetDir = $this->config->item('book_images_dir') . '/uploads';
		
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		
		// Uncomment this one to fake upload time
		// usleep(5000);
		
		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
		
        
		// Clean the fileName for security reasons
		$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
		
		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);
		
			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;
		
			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}
		
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		
		// Create target dir
		if (!file_exists($targetDir))
			@mkdir($targetDir);
		
		// Remove old temp files
		if ( $cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir)) ) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
		
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}
		
			closedir($dir);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		
		
		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
		
		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];
		
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");
		
					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		} else {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");
		
				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		
				fclose($in);
				fclose($out);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename("{$filePath}.part", $filePath);
		}
		
		list($img_width,$img_height) = getimagesize($filePath);
		$fbdata = new stdClass();
		$fbdata->source = $this->config->item('book_images_url') . "/uploads/$fileName";
		$fbdata->width = $img_width;
		$fbdata->height = $img_height;

        $param = new stdClass();
        
        $book_owner_id       = $this->main_model->get_book_owner($_COOKIE['hardcover_book_info_id']);
        //$is_ghost_writer     = $this->main_model->isGhostWriter($_COOKIE['hardcover_fbid'],$_COOKIE['hardcover_book_info_id']);
        
        
        $param->book_info_id = $_COOKIE['hardcover_book_info_id'];      
        $param->facebook_id = $book_owner_id;
        $param->from_facebook_id = $_COOKIE['hardcover_fb_user_id']?$_COOKIE['hardcover_fb_user_id']:'';
        
        //We will tag this uploaded content as for approval and not based on the settings set by the book owner
        $is_for_approval = 0;
        
        if ($param->facebook_id != $param->from_facebook_id){ 
            $book_settings = $this->main_model->get_settings_unique($param->book_info_id);
            if ($book_settings){
                $is_for_approval = $book_settings->content_approval;
            }
        }
        
		$param->connection = 'photo_from_pc';
		$param->fbdata = serialize($fbdata);
		$param->width = $img_width;
		$param->height = $img_height;
        $param->is_for_approval = $is_for_approval;
		
		$ret = $this->upload_model->save_uploaded_to_book_pages($param);
		$d = '';
		if(isset($_COOKIE['coverpage_data']))
			{
				$d = $_COOKIE['coverpage_data'].",".$ret['data'];
			}else{
				$d =  $ret['data'];
			}
		setcookie("coverpage_data",$d,time()+3600*24,'/');
		echo json_encode($ret);
		// Return JSON-RPC response
		//die('{"jsonrpc" : "2.0", "result" : null, "id" : "id","file_path":'.$fileName.'}');

	}
	function fb_upload() {
		if (isset($_POST['submit'])) {
			$ids = $_POST['pics'];
			$fb_id = $_POST['fb_id'];
			$file_path = 'uploads/'.$fb_id.'/';
			$thumb_path= 'uploads/thumbnail/'.$fb_id.'/';
			$file_path .= (!in_array(substr($file_path, -1), array('\\','/') ) )?DIRECTORY_SEPARATOR:'';//normalize path
			$thumb_path .= (!in_array(substr($thumb_path, -1), array('\\','/') ) )?DIRECTORY_SEPARATOR:'';//normalize path
						
			if(!file_exists($file_path) && !empty($file_path)) {
				mkdir($file_path, 0777, true);
			}
			
			if(!file_exists($thumb_path) && !empty($thumb_path)) {
				mkdir($thumb_path, 0777, true);
			}
			
			$i = 0;
			foreach ($ids as $id) {
				$i++;
				$dat = $this->uploadm->album_photos_hd($id);
				$thumb_url = $dat->small;
				$file_url = $dat->hd;
				
				$t_path = $thumb_path.time().$i.'.jpg';
				$f_path = $file_path.time().$i.'.jpg';
				file_put_contents($t_path, file_get_contents($thumb_url));
				//sleep(5);
				file_put_contents($f_path, file_get_contents($file_url));
				
				$this->uploadm->aprdd_write($id);
			}
		}
		redirect(base_url().'images_uploader');
	}
}

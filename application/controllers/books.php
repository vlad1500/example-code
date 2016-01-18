<?php
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
class Books extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("main_model");
        $this->load->model("AlbumModel");
    }
    public function set_cookie_value() {
        $cookie_firstname = $_POST['first_name'];
        $cookie_fbuser_id = $_POST['facebook_id'];
        if ($cookie_firstname != "")
            setcookie('first_name', $cookie_firstname, time() + 86400, '/');
        else
            setcookie("first_name", "", time() - 3600, '/');
        if ($cookie_fbuser_id != "")
            setcookie('hardcover_fb_user_id', $cookie_fbuser_id, time() + 86400, '/');
        else
            setcookie("hardcover_fb_user_id", "", time() - 3600, '/');
        echo "cookies set";
    }
    function array2object($data) {
        if (!is_array($data))
            return $data;
        $object = new stdClass();
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = $this->array2object($value);
                }
            }
        }
        return $object;
    }
    function post_on_wall() {
        $CI =& get_instance();
        $CI->config->load("facebook", TRUE);
        $config               = $CI->config->item('facebook');
        $config['fileUpload'] = true;
        $this->load->library('Facebook', $config);
        //*$this->load->library('Facebook');
        $access_token = $this->facebook->getAccessToken();
        $userId       = $this->facebook->getUser();
        $this->facebook->setFileUploadSupport(true);
        $message = $_POST['msg'];
        //$this->config->item("image_url").
        $img_url = $_POST['url'];
        if (strpos($img_url, 'sphotos-b.xx.fbcdn.net') !== false) {
            // re-write the facebook image to localimage
            $url            = $img_url;
            $save_name      = time() . "fbimg.png";
            $save_directory = "/storage/www/codebase/apps/devhardcover/uploads/";
            if (is_writable($save_directory)) {
                file_put_contents($save_directory . $save_name, file_get_contents($url));
            } else {
                exit("Failed to write to directory " . $save_directory);
            }
            // re-write the facebook image to localimage
            //echo  file_get_contents($data['book_pages'][0]->image_url);
            $img_url = 'http://hardcover.me/uploads/' . $save_name;
        }
        $photo_crop = strrpos($img_url, '/');
        $p_length   = strlen($img_url);
        $p_url      = substr($img_url, $photo_crop + 1, $p_length);
        $photo      = '/storage/www/codebase/apps/devhardcover/uploads/' . $p_url;
        echo $ret_obj = $this->facebook->api('/me/photos', 'POST', array(
            'source' => '@' . realpath($photo),
            'message' => $_POST['bname'] . " - " . $_POST['msg'] . ' ' . ' ' . $_POST['unique'],
            'link' => 'http://www.google.com'
        ));
        exit;
    }
    function uniqueUrl($book_owner_fb_username = '', $book_name = '') {
        if ($book_owner_fb_username == "top10"):
        else:
            parse_str($_SERVER['QUERY_STRING'], $_REQUEST);
            $CI =& get_instance();
            $CI->config->load("facebook", TRUE);
            $config = $CI->config->item('facebook');
            $this->load->library('Facebook', $config);
            $access_token = $this->facebook->getAccessToken();
            $userId       = $this->facebook->getUser();
            $fuser        = array();
            if ($userId == 0) {
                $fuser['id'] = "";
            } else {
                $fuser['id'] = $userId;
            }
            $offset               = 0;
            $limit                = 100000;
            $params               = new stdClass();
            $data['isMobile']     = $this->check_user_agent('mobile');
            $data['user_details'] = $this->main_model->get_book_creator_id($book_owner_fb_username);
            $owner_id             = $data['user_details']->facebook_id;
            $bid                  = $this->main_model->get_book_info_by_user_id($owner_id);
            $param                = new stdClass();
            $param->facebook_id   = $owner_id;
            $param->limit         = $limit;
            $param->offset        = $offset;
            $bCount               = count($bid['data']);
            for ($x = 0; $x < $bCount; $x++) {
                $book_info_id            = $bid['data'][$x]->book_info_id;
                //dennis remove it as this method is present in the main_model and its more optimize
                //$b_name = $this->AlbumModel->get_book_cover($book_info_id);
                $b_name                  = $this->main_model->get_book_cover($book_info_id);
                $param->book_info_id     = $book_info_id;
                //$b_name = $b_name->book_name;
                $data['book_url'][$x]    = "/" . $book_owner_fb_username . "/" . strtolower(str_replace(' ', '_', $b_name->book_name));
                $book_pages              = $this->main_model->get_unique_book_content($param);
                $data['front_cover'][$x] = $book_pages['data']->book_info->front_cover_page;
                $data['back_cover'][$x]  = $book_pages['data']->book_info->back_cover_page;
            }
            //die(print_r($data['front_cover']));
            if ($book_name == "top_10"):
                if ($_REQUEST['debug'] == 1) {
                    $this->load->view("top_10_mobile", $data);
                } else if (!$data['isMobile']) {
                    $this->load->view("top_10", $data);
                } else {
                    $this->load->view("top_10_mobile", $data);
                }
            else:
                $params->fb_username = $book_owner_fb_username;
                $params->book_name   = $book_name;
                $bid                 = $this->main_model->get_book_info_by_book_name($book_name);
                if ($bid['data']->publish == 0) {
                    $this->load->view("unique_url_404");
                    return false;
                }
                $data['bname']         = $book_name;
                $params->fb_id         = $bid['data']->facebook_id;
                $book_info_id          = $bid['data']->book_info_id;
                $param                 = new stdClass();
                $param->book_info_id   = $book_info_id;
                $b_name                = $this->main_model->get_book_cover($book_info_id);
                $_COOKIE['book_name']  = $b_name->book_name;
                $param->facebook_id    = $bid['data']->facebook_id;
                $param->limit          = $limit;
                $param->offset         = $offset;
                $data['user_details']  = $this->main_model->get_book_creator($param);
                //dennis: commented as I dont see being use
                //$paramed->book_info_id = $book_info_id;
                //$paramed->facebook_id = $bid['data']->facebook_id;
                //$paramed->limit = $limit;
                //$paramed->offset = $offset;
                $book_pages            = $this->main_model->get_unique_book_content($param);
                $total_pages           = $this->main_model->get_total_pages($param->book_info_id);
                $data['book_settings'] = $this->main_model->get_settings_unique($bid['data']->book_info_id);
                //dennis: commented and reuse the above fetch result by 'get_book_info_by_book_name'
                //$data['book_info'] = $this->main_model->get_book_info($bid['data']->book_info_id);
                $data['book_info']     = $bid;
                $data['book_info_id']  = $book_info_id;
                $data['booked_data']   = $book_pages['data'];
                $data['msg']           = $book_pages['msg'];
                $data['sql']           = $book_pages['sql'];
                $data["creator_fname"] = $book_creator["fname"];
                $data["creator_fbid"]  = $bid['data']->facebook_id;
                $data['book_owner']    = 0;
                $data['leftside']      = 0;
                $data['rightside']     = 1;
                $data['contributors']  = 0;
                $data['collaborative'] = 1;
                $data['login']         = 0;
                $data['onlyfriends']   = 0;
                $data['fname']         = '';
                if (isset($_COOKIE['first_name']))
                    $data['fname'] = $_COOKIE['first_name'];
                $data['perm']      = 0; // added by josh to check what the current permission.
                $data['show_book'] = 1;
                $current_fb_user   = $_COOKIE["hardcover_fb_user_id"];
                if ($current_fb_user == "")
                    $data['login'] = 1;
                $data['user_id']       = $current_fb_user;
                $data['can_add_photo'] = 1;
                //TODO: Move the condition filters in a function that would return $data
                //condition 1 - josh
                if ($data['book_settings']->who_can_see == 'some_friends') {
                    $data['perm'] .= ", 1";
                    $data['show_book'] = 0;
                    $who_ids           = explode(',', $data['book_settings']->select_can_see_ids);
                    if (in_array($current_fb_user, $who_ids) || $current_fb_user == $data["creator_fbid"]) {
                        $data['show_book'] = 1;
                    }
                }
                //condition 2 - josh
                if ($data['book_settings']->collaborative == 1 && $data['book_settings']->who_can_contribute == 'select') {
                    $data['perm'] .= ", 2";
                    $data['can_add_photo'] = 0;
                    $data['rightside']     = 0;
                    $who_ids               = explode(',', $data['book_settings']->select_ids);
                    if (in_array($current_fb_user, $who_ids) || $current_fb_user == $data["creator_fbid"]) {
                        $data['can_add_photo'] = 1;
                        $data['rightside']     = 1;
                    }
                }
                //condition 3 - josh
                if ($data['book_settings']->collaborative == 1 && $data['book_settings']->who_can_contribute == 'all') {
                    $data['perm'] .= ", 3";
                    $data['can_add_photo'] = 1;
                    $data['rightside']     = 1;
                }
                if ($data['book_settings']->who_can_see == 'friends') {
                    $data['onlyfriends'] = 1;
                }
                if ($current_fb_user == $data["creator_fbid"]) {
                    $data['book_owner'] = 1;
                }
                if ($data['book_settings']->collaborative == 0) {
                    $data['collaborative'] = 0;
                }
                $data['see_book']        = 1;
                $data['cont_book']       = 1;
                //dennis: commented as we can reuser the above $b_name variable
                //$data['book_data'] = $this->AlbumModel->get_book_cover($book_info_id);
                $data['book_data']       = $b_name;
                $data['timeCreatedDate'] = date("Y,m,d", strtotime($data['book_info']['data']->created_date));
                if ($_REQUEST['debug'] == 1) {
                    $this->load->view("unique_url_album_mobile", $data);
                } else if ($_REQUEST['debug'] == 2) {
                    $this->load->view("unique_url_slideshow", $data);
                } else if ($data['isMobile']) {
                    $this->load->view("unique_url_album_mobile", $data);
                } else {
                    $this->load->view("unique_url_album", $data);
                }
                setcookie('hardcover_book_info_id', $book_info_id, time() + 86400, '/');
                setcookie('hardcover_fbid', $params->fb_id, time() + 86400, '/');
            endif;
        endif;
    }
    /* USER-AGENTS
    ================================================== */
    function check_user_agent($type = NULL) {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if ($type == 'bot') {
            // matches popular bots
            if (preg_match("/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent)) {
                return true;
                // watchmouse|pingdom\.com are "uptime services"
            }
        } else if ($type == 'browser') {
            // matches core browser types
            if (preg_match("/mozilla\/|opera\//", $user_agent)) {
                return true;
            }
        } else if ($type == 'mobile') {
            // matches popular mobile devices that have small screens and/or touch inputs
            // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
            // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
            if (preg_match("/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent)) {
                // these are the most common
                return true;
            } else if (preg_match("/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent)) {
                // these are less common, and might not be worth checking
                return true;
            }
        }
        return false;
    }
    private function getBookCreator($params) {
        $book_creator = $this->main_model->get_book_creator_by_fb_id($params);
        $book_creator = (array) $book_creator;
        return $book_creator;
    }
    private function getFBDetailsByFBId($params) {
        $friend_details = $this->main_model->getFBDetailsByFBId($param);
        $friend_details = (array) $friend_details;
        return $friend_details;
    }
}

<?php

error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);

require_once('connect.php');
require_once('config.php');
include_once('common_functions.php');

$book_info_id = empty($argv[1])?$_GET['book_info_id']:$argv[1];
$fb_username = empty($argv[2])?$_GET['fb_username']:$argv[2];

//logme("book_info_id:$book_info_id;fb_username:$fb_username",'static_pages');

$url = $config['base_url']."/uniqueurl/create_static_pages?book_info_id=$book_info_id&fb_username=$fb_username";
logme($url,'static_pages');
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HEADER, FALSE); 		
curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE); 
curl_exec($ch);
?>
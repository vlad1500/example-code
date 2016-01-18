<?php
function parse_signed_request($signed_request, $secret) {
	list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

	// decode the data
	$sig = base64_url_decode($encoded_sig);
	$data = json_decode(base64_url_decode($payload), true);

	if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
		error_log('Unknown algorithm. Expected HMAC-SHA256');
		return null;
	}

	// check sig
	$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
	if ($sig !== $expected_sig) {
		error_log('Bad Signed JSON signature!');
		return null;
	}
  return $data;
}

function base64_url_decode($input) {
	return base64_decode(strtr($input, '-_', '+/'));
}

function updateFBData($fbid,$token){
	$CI =& get_instance();
	//update the fb data statuses
	$updater_url = $this->config->item('tools')."/fbstatus_updater.php $fbid $token";
	$command = " php -f $updater_url";
	exec("$command > ".$this->config->item('updater_log_folder') . " 2>&1 &",$arrOutput);
	log_message('info','fbstatus_updater: '.$arrOutput);
	
	//update the fb data feed
	$updater_url = $this->config->item('tools')."/fbfeed_updater.php $fbid $token";
	$command = " php -f $updater_url";
	exec("$command > ".$this->config->item('updater_log_folder') . " 2>&1 &",$arrOutput);
	log_message('info','fbfeed_updater: '.$arrOutput);
	
	//update the fb data photo
	$updater_url = $this->config->item('tools')."/fbphoto_updater.php $fbid $token";
	$command = " php -f $updater_url";
	exec("$command > ".$this->config->item('updater_log_folder') . " 2>&1 &",$arrOutput);
	log_message('info','fbphoto_updater: '.$arrOutput);
	
	//update the fb data photo
	$updater_url = $this->config->item('tools')."/fbalbumphoto_updater.php $fbid $token";
	$command = " php -f $updater_url";
	exec("$command > ".$this->config->item('updater_log_folder') . " 2>&1 &",$arrOutput);
	log_message('info','fbfriends_updater: '.$arrOutput);

	//update user fb friends
	$updater_url = $this->config->item('tools')."/fbfriends_updater.php $fbid $token";
	$command = " php -f $updater_url";
	exec("$command > ".$this->config->item('updater_log_folder') . " 2>&1 &",$arrOutput);
	log_message('info','fbfriends_updater: '.$arrOutput);
}
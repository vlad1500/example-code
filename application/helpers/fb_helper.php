<?php
function getAccessToken($params) {
    $token_url  = trim("https://graph.facebook.com/oauth/access_token?" . "client_id=" . $params->app_id . "&redirect_uri=" . urlencode($params->redirect_url) . "&client_secret=" . $params->app_secret . "&code=" . $params->code);
    $response   = file_get_contents($token_url, false);
    $token_info = null;
    parse_str($response, $token_info);
    return (object) $token_info;
}
function getFacebookUserDetails($params) {
    //$graph_url = "https://graph.facebook.com/me?access_token=$access_token";
    //
    // 	$ch = curl_init();
    // 	curl_setopt($ch, CURLOPT_URL, $graph_url);
    // 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // 	$user = @json_decode(curl_exec($ch));
    //	curl_close($ch);
    //    return $user;
    //die(print_r(realpath(dirname(__FILE__))));
    
    //echo '8888';
    //$CI =& get_instance();
    //echo '7777';
    //$CI->load->library('facebook');
    //die( base_url() );
    //echo 'base...'.$params->base_dir;
    require_once($params->base_dir.'/server/facebook.php');
    
    $facebook = new Facebook(array(
        'appId' => $params->app_id,
        'secret' => $params->app_secret
    ));

    $user     = $facebook->getUser();
    if ($user) {
        try {
            // Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $facebook->api('/me');
        }
        catch (FacebookApiException $e) {
            error_log($e);
            $user = null;
        }
        return $user_profile;
    }
}
function getFriends() {
    $graph_url = "https://graph.facebook.com/me/friends?access_token=$access_token";
    $friends   = @json_decode(file_get_contents($graph_url));
    return $friends;
}

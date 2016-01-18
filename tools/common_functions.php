<?php

function logme($data,$logfile=''){
	global $config;
	
	if (empty($logfile)) $logfile = 'fbupdater';
	
	$file = $config['tools'] . "/script_logs/$logfile.log"; 
	$cdate = date('n/j/Y h:i:s a');
	$handle = fopen($file, 'ab');	
	fwrite($handle, "$data => $cdate \n"); 
	fclose($handle); 
	echo "\n\n $data";
}

function get_time(){
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	return $mtime; 
}

function get_filename($currentFile){	
	$parts = Explode('/', $currentFile);
	return $parts[count($parts) - 1];
}

function get_graphapi_data($graph_url){
 	$ch = curl_init();
 	curl_setopt($ch, CURLOPT_URL, $graph_url);
 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 	$data = @json_decode(curl_exec($ch));
	return $data;
}

?>
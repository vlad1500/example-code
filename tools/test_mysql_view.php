<?php

//initialize php settings run-time
error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);


require_once('connect.php');
require_once('config.php');

$sql = "SELECT * FROM SAMPLE_VW LIMIT 10";
$result = mysql_query($sql) or die(mysql_error()); 

while ($row = mysql_fetch_object($result)){
	$data = unserialize($row->fbdata);
	echo "<br/>".$data->images[1]->source;
}
?>
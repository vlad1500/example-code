<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?php

//initialize php settings run-time
error_reporting(1);
ini_set("display_errors", 1);
ini_set('memory_limit', '64M');
set_time_limit(0);


require_once('connect.php');
require_once('config.php');
if (isset($_POST['retrieve'])) { 
	$sql=$_POST['sql_string']; 
	$sql = trim($sql);
	//$sql = "SELECT * FROM SAMPLE_VW LIMIT 10";
	$result = mysql_query($sql) or die(mysql_error()); 
	
	while ($row = mysql_fetch_array($result)){
		print_r($row);
	}
}

?>

<body>
<form method=post action = select.php > 
<TEXTAREA COLS=50 ROWS=1 NAME="sql_string" WRAP=VIRTUAL><?php print trim ($sql_string);?></TEXTAREA> 
<br>
<input name="retrieve" type="submit" value="Retrieve">
</form>
</body>
</html>

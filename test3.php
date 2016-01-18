<!DOCTYPE html>

<html>

<head>
  <title>Hello!</title>
</head>

<body>
<?php
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
		define('PROTOCOL','https');
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
        define('PROTOCOL','https');
	else
		define('PROTOCOL','http');
function full_url($s) {
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
    return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
}
$absolute_url = full_url($_SERVER);
echo "<h1>full URL: ".$absolute_url."</h1><br/>";
echo "<h1>Current protocol: ".PROTOCOL."</h1><br/>";
echo "<h1>server port: ".$_SERVER['SERVER_PORT']."</h1><br/>";
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
echo "<h1>server protocol: ".$_SERVER['SERVER_PROTOCOL']."</h1><br/>";
$file = '/storage/www/codebase/apps/book_images/uploads/1366x768/o_18hpad719jcgrvq1maps2i12dbj.jpg'; // 'images/'.$file (physical path)

if (file_exists($file) != FALSE) {
    echo "The file $file exists";
} else {
    echo "The file $file does not exist";
}
?>
</body>
</html>
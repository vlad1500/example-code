<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

//hardcover constants
define('ALBUM_FOR_ME','1');
define('ALBUM_FOR_FRIENDS','2');
define('GROUP_GIFT','3');

define('ENTIRE_TIMELINE','1');
define('SPECIFIC_DATE_RANGE','2');

define('MY_STATUS_UPDATES','1');
define('FRIENDS_COMMENTED_ON_MY_STATUS','2');
define('FRIENDS_LIKE_MY_STATUS_UPDATE','3');
define('I_COMMENTED_ON_FRIENDS_STATUS_UPDATE','4');

define('POST_I_LIKE_ALL','1');
define('POST_I_LIKE_PHOTOS','2');
define('POST_I_LIKE_COMMENTS','3');
define('POST_I_LIKE_ARTICLES','4');

define('PHOTOS_I_WAS_TAGGED_IN','1');
define('ONLY_PHOTOS_FRIENDS_COMMENTED_ON','2');
define('MY_PHOTOS_THAT_MY_FRIENDS_LIKE','3');

define('HD','1');
define('MEDIUM','2');
define('SMALL','3');

//Upload constant
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
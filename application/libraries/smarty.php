<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/smarty3/libs/Smarty.class.php');

class MY_Smarty extends Smarty {

    public function __construct()
    {
		// It bitches without this
		//date_default_timezone_set('America/Phoenix');

		parent::__construct();

		$this->template_dir = APPPATH.'views'; // CI Views folder - Store templates here
		$this->compile_dir  = APPPATH.'libraries/smarty3/templates_c'; // Must be writable to apache!
		$this->config_dir   = APPPATH.'libraries/smarty3/configs'; // Store variables in a file (to include)!
		$this->cache_dir    = APPPATH.'libraries/smarty3/cache'; // Must be writable to apache!

		// $this->caching = Smarty::CACHING_LIFETIME_CURRENT; // Does something :)
		$this->caching = 0;
		$this->force_compile = 1;

		$this->assign('app_title', 'Hardcover'); // Assign app name here
		$this->assign('base_url', 'http://apps.facebook.com/hardcoverdev'); // Assign app name here

		log_message('debug', "Smarty Class Initialized");
    }

	public function display ($t) 
	{
		// if (strpos($t, '.') === FALSE && strpos($t, ':' === FALSE)) { 
		if (strpos($t, '.') === FALSE && strpos($t, ':') === FALSE) { 
			$t .= '.html'; 
		} // Add extension

		parent::display($t);
	}

	public function display_str ($s)
	{
		parent::display("eval:$s");
	}
}

?>
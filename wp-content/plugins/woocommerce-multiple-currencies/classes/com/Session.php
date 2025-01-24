<?php 
namespace WCMC\classes\com;

class Session
{
	var $current_session_id;
	public function __construct()
	{
		
	}
	public function init_session()
	{
		if(empty($this->current_session_id))
		{
			return $this->create_session();
		}
		
		return $this->current_session_id;
	}
	private function create_session($wc_session = null)
	{
		
		try {
			@session_unset();     
			@session_destroy();
			@session_start();
		}catch (Exception $e) {}
		
		$this->current_session_id = session_id(); //new 
	}
	public function set_currency($value)
	{
		
		
		setcookie("wcmc_currency", $value, time() + (259200 * 30), "/"); //259200: 3 days
	}
	public function get_currency()
	{
		
		return wcmc_get_value_if_set($_COOKIE, 'wcmc_currency', false);
	}
	public function set_lang($value)
	{
		
		
		setcookie("wcmc_lang", $value, time() + (259200 * 30), "/"); //259200: 3 days
	}
	public function get_lang()
	{
		
		return wcmc_get_value_if_set($_COOKIE, 'wcmc_lang', false);
	}
}
?>
<?php 
namespace WCMC\classes\com;

class Customer
{
	var $current_currency;
	var $switch_currency_for_language = false;
	public function __construct()
	{
		add_action('wp_ajax_wcmc_set_customer_currency', array(&$this, 'ajax_set_currency'));
		add_action('wp_ajax_nopriv_wcmc_set_customer_currency', array(&$this, 'ajax_set_currency'));
		add_action('init', array(&$this, 'check_url_params'));
		add_action('wp', array(&$this, 'force_currency'));
	}
	public function get_geo_location($country_name = false)
	{
		return false;
		
		try 
		{
			$location = \WC_Geolocation::geolocate_ip();
			return $country_name ? WC()->countries->countries[ $location['country']] : $location['country'];
		}
		catch (Exception $e) {}	
	}
	public function force_currency()
	{	
		global  $wcmc_option_model;
		$options = $wcmc_option_model->get_options();
		if(!wcmc_get_value_if_set($options, 'force_base_currency_on_checkout_page', false))
			return;
		
		global $wcmc_currency_model;
		if((function_exists('is_checkout') && @is_checkout()) /* || (function_exists('is_cart') && @is_cart()) */)
		{
			$this->set_currency($wcmc_currency_model->get_base_currency());
			return;
		}
	}
	public function check_url_params()
	{
		global $wcmc_wpml_model, $wcmc_session_model;
		if(isset($_GET['currency']))
		{
			$this->set_currency($_GET['currency']);
		}
		if($wcmc_wpml_model->wpml_is_active())
		{
			$prev_lang = $wcmc_session_model->get_lang();
			$current_lang = $wcmc_wpml_model->get_current_language_code();
			$wcmc_session_model->set_lang($current_lang);
			if($prev_lang != $current_lang)
				$this->switch_currency_for_language = true;
		}
	}
	public function ajax_set_currency()
	{
		if(isset($_POST['currency_code']))
		{
			$this->set_currency($_POST['currency_code']);
		}
		wp_die();
	}
	public function get_currency()
	{
		global $wcmc_currency_model, $wcmc_session_model, $wcmc_wpml_model;
		
		/* $session_handler = WC()->session;  //WC_Session_Handler
		if($session_handler == null)
			return $wcmc_currency_model->get_base_currency();
		
		$currency = $session_handler->get( 'wcmc_user_currency' );  */
		
		$default_currency_for_user_language = false; 
		
		$default_currency_for_user_location =  $wcmc_currency_model->get_default_currency_for_location($this->get_geo_location());
		$currency = isset($this->current_currency) ? $this->current_currency : $wcmc_session_model->get_currency();
		if($wcmc_wpml_model->wpml_is_active())
		{
			$default_currency_for_user_language = $wcmc_currency_model->get_default_currency_for_language($wcmc_wpml_model->get_current_language_code());
		}
		//In case any currency has been selected, the plugin will select the one associated (if any) to the current location
		$currency = $currency == false && $default_currency_for_user_location != false ? $default_currency_for_user_location : $currency;
		$currency = $this->switch_currency_for_language && $default_currency_for_user_language != false ? $default_currency_for_user_language : $currency;
		$result = $currency == false || !$wcmc_currency_model->is_currency_available($currency) ? $wcmc_currency_model->get_base_currency() : $currency ;
		
		return $result;
	}
	public function set_currency($currency_code )
	{
		global $wcmc_session_model;
		/* $session_handler = WC()->session;
		if($session_handler == null)
			return;
		
		$session_handler->set( 'wcmc_user_currency' , $currency_code ); */
		
		$this->current_currency = $currency_code;
		$wcmc_session_model->set_currency($currency_code);
	}
}
?>
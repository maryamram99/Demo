<?php 
namespace WCMC\classes\com;

class Option
{
	//rplc: AfterShip, woocommerce-multiple-currencies, wcmc, WCMC
	
	var $company_list;
	var $company_list_by_slug;
	var $options_cache;
	var $currency_options_cache;
	public function __construct()
	{
		
	}
	public function save_options($data)
	{
		$this->options_cache = null;
		$this->currency_options_cache = null;
		update_option('wcmc_options', $data);
	}
	public function save_currency_options($data)
	{
		$this->currency_options_cache = null;
		update_option('wcmc_currency_options', $data);
	}
	private function escape_text($data)
	{
		foreach($data as $index => $content)
		{
			if(is_string($content))
				$data[$index] = stripcslashes($content);
			else if(is_array($content))
				$data[$index] = $this->escape_text($content);
		}
		return $data;
	}
	
	public function get_options($option_name = null, $default_value = null)
	{
		$result = null;
		
		$options = isset($this->options_cache ) ? $this->options_cache  : get_option('wcmc_options');
		$this->options_cache = $options;
		if($option_name != null)
		{
			$result = wcmc_get_value_if_set($options, $option_name ,$default_value);
		}
		else 
			$result = $options;
		
		return $result;
	}
	public function get_currency_options($option_name = null, $default_value = null)
	{
		global $wcmc_currency_model;
		if(!isset($this->currency_options_cache))
		{
			$general_options = $this->get_options();
			$selected_currencies = wcmc_get_value_if_set($general_options, 'selected_currency', array());
			$options = get_option('wcmc_currency_options');
			
			$result = array('frequency' => wcmc_get_value_if_set($options, 'frequency', "manually"),
							'currency_data' => array());
			
			//default values 
			$is_first_run = $options == false || !isset($options) || empty($options);
			$options = $is_first_run ? array() : $options;
			
			
			//init
			//1. Sort according on how the user sorted them through the Rates options menu
			if(wcmc_get_value_if_set( $options, 'currency_data', false) != false)
			{
				//remove unexisting currencies before sorting
				foreach($options['currency_data'] as $currency_code => $value)
					if(!isset($selected_currencies[$currency_code]))
						unset($options['currency_data'][$currency_code]);
					
				$selected_currencies = array_merge(array_flip(array_keys ($options['currency_data'])), $selected_currencies);
			}
			
			//2. Data fill
			foreach($selected_currencies as $currency_code => $currency_info)
			{
				$result['currency_data'][$currency_code] = wcmc_get_value_if_set( $options, array('currency_data', $currency_code), array('rate' => null, 'base_currency' => false));
			}
			
			//3. Set base currency if none is selected
			//if(!empty($result))
			{
				$exists_base = false;
				$base_currency_key = $wcmc_currency_model->get_woocommerce_default_currency();
				//If at least one currency has been selected
				foreach($result['currency_data'] as $key => $option)
				{
					$exists_base = !$exists_base && isset($option['base_currency']) && $option['base_currency'] ? true : $exists_base;
					
				}
				
			}
		}
		else 
			$result = $this->currency_options_cache;
		
		$this->currency_options_cache = $result;
		
		
		if($option_name != null)
		{
			$result = wcmc_get_value_if_set($result, $option_name, $default_value);
		}
		
		/* Output:
		array(2) {
		  ["frequency"]=>
		  string(6) "hourly"
		  ["currency_data"]=>
		  array(3) {
			["BRL"]=>
			array(1) {
			  ["rate"]=>
			  string(7) "5.06309"
			}
			["GIP"]=>
			array(2) {
			  ["rate"]=>
			  string(1) "1"
			  ["base_currency"]=>  //<---------------------- Key exists only for base currency
			  string(4) "true"
			}
			["INR"]=>
			array(1) {
			  ["rate"]=>
			  string(8) "90.01448"
			}
		  }
		}
		*/
		
		return $result;
	}
}
?>
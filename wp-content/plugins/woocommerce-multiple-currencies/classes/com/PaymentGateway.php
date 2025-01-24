<?php 
namespace WCMC\classes\com;

class PaymentGateway
{
	function __construct()
	{
		add_filter('woocommerce_available_payment_gateways', array($this, 'gateways_available_on_checkout'), 10, 1 ); 
	}
	public function get_available_payment_gateways()
	{
		return WC()->payment_gateways()->payment_gateways(); //get_available_payment_gateways() to get only the available
	}
	public function gateways_available_on_checkout($available_gateways)
	{
		global $wcmc_customer_model, $wcmc_option_model;
		if(function_exists ('is_checkout') && @is_checkout())
		{
			$current_currency_code = $wcmc_customer_model->get_currency();
			$currency_data = $wcmc_option_model->get_currency_options();
			$gateways = wcmc_get_value_if_set($currency_data, array('currency_data', $current_currency_code, 'available_gateway'), array()); 
			
			if(!empty($gateways))
				foreach($available_gateways as $gateway_code => $gateway_obj)
				{
					if(!in_array($gateway_code, $gateways))
						unset($available_gateways[$gateway_code]);
				}
		}
		return  $available_gateways ;
	}
}
?>
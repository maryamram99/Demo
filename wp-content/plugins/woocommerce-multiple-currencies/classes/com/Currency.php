<?php 
namespace WCMC\classes\com;

class Currency
{
	var $table_rates_url = "https://vanquishplugins.com/currency/compute_rate.php?base=";
	var $currency_list_cache;
	var $woocommerce_checkout_order_creation_start = false;
	function __construct()
	{
		add_action('wp_ajax_wcmc_load_currency_rates', array(&$this, 'ajax_load_currency_rates'));	
		add_action('init', array(&$this, 'init'));
		//hooks list 
		/*
		woocommerce_currency
		woocommerce_currency_symbols //to inject custom symbols, no need
		woocommerce_currency_symbol */
		
		
		//Frontend display
		add_filter('woocommerce_currency', array(&$this, 'override_woocommerce_currency'), 99, 1);
		add_filter('woocommerce_currency_symbol', array(&$this, 'override_woocommerce_currency_symbol'), 99,2);
		/* add_filter('woocommerce_product_get_price', array(&$this, 'override_woocommerce_price'), 10,2); //used during checkout process. Otherwise it is ignored because the 'raw_woocommerce_price' already triggers the handler
		add_filter('woocommerce_product_regular_price', array(&$this, 'override_woocommerce_price'), 10,2); 
		add_filter('woocommerce_product_sale_price', array(&$this, 'override_woocommerce_price'), 10,2); 
		add_filter('woocommerce_get_variation_price', array(&$this, 'override_woocommerce_price'), 10,2); 
		add_filter('woocommerce_get_variation_sale_price', array(&$this, 'override_woocommerce_price'), 10,2); 
		add_filter('woocommerce_get_variation_regular_price', array(&$this, 'override_woocommerce_price'), 10,2);  */
		add_filter('raw_woocommerce_price', array(&$this, 'override_woocommerce_raw_price'), 99, 1);
		//add_filter('woocommerce_checkout_process', array(&$this, 'override_woocommerce_price'));
		//add_action( 'woocommerce_before_calculate_totals', array(&$this,'custom_cart_items_prices'), 10, 1 );
		add_filter( 'wc_get_price_decimals', array(&$this, 'override_woocommerce_number_of_decimals'), 99, 1 );  //decimals
		add_filter( 'pre_option_woocommerce_currency_pos', array(&$this, 'override_woocommerce_currency_position'), 99);  //symbol position
		
		
		//Post checkout actions
		add_filter('woocommerce_create_order', array(&$this, 'woocommerce_checkout_order_creation_start'), 10, 2); //triggered when creating the order after the "place order" button is pressed
		//add_filter('woocommerce_checkout_create_order', array(&$this, 'woocommerce_checkout_create_order'), 10, 2); 
		
		
	}
	public function init()
	{
		global $wcmc_customer_model, $wcmc_option_model;
		
		$options = $wcmc_option_model->get_options();
		if(wcmc_get_value_if_set($options, 'place_order_using_base_currency', false) || wcmc_get_value_if_set($options, 'force_base_currency_on_checkout_page', false) )
			return;
		
		add_filter('woocommerce_cart_get_shipping_total',  array(&$this, 'override_cart_prices'));
		add_filter('woocommerce_cart_get_discount_total',  array(&$this, 'override_cart_prices'));
		add_filter('woocommerce_cart_get_discount_tax',  array(&$this, 'override_cart_prices'));
		add_filter('woocommerce_cart_get_cart_contents_tax',  array(&$this, 'override_cart_prices'));
		add_filter('woocommerce_cart_get_fee_tax',  array(&$this, 'override_cart_prices'));
		add_filter('woocommerce_cart_get_shipping_tax',  array(&$this, 'override_cart_prices'));
		add_filter('woocommerce_cart_get_total',  array(&$this, 'override_cart_prices')); 
		add_action( 'woocommerce_checkout_create_order_line_item', array(&$this, 'woocommerce_checkout_create_order_line_item'),  10,4);
		add_action( 'woocommerce_checkout_create_order_fee_item', array(&$this, 'woocommerce_checkout_create_order_fee_item'), 10,4);
		add_action( 'woocommerce_checkout_create_order_shipping_item', array(&$this, 'woocommerce_checkout_create_order_shipping_item'), 10,4);
		add_action( 'woocommerce_checkout_create_order_tax_item', array(&$this, 'woocommerce_checkout_create_order_tax_item'), 10,3);
		add_action( 'woocommerce_checkout_create_order_coupon_item', array(&$this, 'woocommerce_checkout_create_order_coupon_item'), 10,4);	
	}
	private function is_current_page_to_be_exluded()
	{
		global $post, $wp;
		
		if(!$this->exists_base_currency())
			return true;
		
		//Orders list page and ajax request to retrieve order data
		if((wcmc_get_value_if_set($_GET,'post_type', false) == 'shop_order') || (wcmc_get_value_if_set($_GET, 'action', false) == 'woocommerce_get_order_details'))
			return true;
		
		if(isset($post) && $post->post_type == 'shop_order')
			return true;
	
		//WooCommerce settings page
		if(wcmc_get_value_if_set($_GET,'page', false) == 'wc-settings')
			return true;
		
		//Frontend "order-received", "order list" "view-order" endpoints
		$end_points = array(get_option('woocommerce_checkout_order_received_endpoint'), 
							get_option('woocommerce_myaccount_view_order_endpoint'), 
							get_option('woocommerce_myaccount_orders_endpoint'));
		
		if(!is_admin() && $wp && is_object($wp) && is_array($wp->query_vars))
			foreach ( $wp->query_vars as $key => $value ) 
				if(in_array($key, $end_points) /* && $value == "" */)
				{
					/* For debug purpose, un comment the following:
						wcmc_var_dump($wp->query_vars);	
						wcmc_var_dump($key);	
						wcmc_var_dump($end_points);	 
					*/
					return true;	
				}
		
		return false;
		
	}
	
	//Post checkout management
	public function woocommerce_checkout_order_creation_start($null, $obj)
	{
		$this->woocommerce_checkout_order_creation_start = true;
	}
	public function override_cart_prices($price)
	{
		//wcmc_var_dump($this->woocommerce_checkout_order_creation_start);
		if(!$this->woocommerce_checkout_order_creation_start)
			return $price;
		
		return $this->override_woocommerce_price($price);
	}
	public function override_woocommerce_raw_price($price, $product = null)
	{
		if($this->woocommerce_checkout_order_creation_start)
			return $price;
		
		return $this->override_woocommerce_price($price);
	}
	public function woocommerce_checkout_create_order_line_item($item, $cart_item_key, $values, $order )
	{
		$item->set_subtotal($this->override_woocommerce_price($item->get_subtotal()));
		$item->set_total($this->override_woocommerce_price($item->get_total()));
		$item->set_subtotal_tax($this->override_woocommerce_price($item->get_subtotal_tax()));
		$item->set_total_tax($this->override_woocommerce_price($item->get_total_tax()));
		$tax_data = $item->get_taxes();
		
		if(is_array($tax_data))
		{
			$tax_data_result = array();
			foreach($tax_data as $index => $value)
			{
				
				$tax_data_result[$index] = array();
				foreach((array)$value as $index2 => $value2)
				{
					//wcmc_var_dump($index2);
					$tax_data_result[$index][$index2] = $this->override_woocommerce_price($value2);
				} 
			}
			$item->set_taxes($tax_data_result);
		}
		return $item;
	}
	public function woocommerce_checkout_create_order_fee_item($item, $fee_key, $fee, $order)
	{
		$item->set_amount($this->override_woocommerce_price($item->get_amount()));
		$item->set_total($this->override_woocommerce_price($item->get_total()));
		$item->set_total_tax($this->override_woocommerce_price($item->get_total_tax()));
		
		$tax_data = $item->get_taxes();
		if(is_array($tax_data) && isset($tax_data['total']))
		{
			$tax_data_result = array('total' => $this->override_woocommerce_price($tax_data['total']));
			$item->set_taxes($tax_data_result);
		}
		return $item;
	}
	public function woocommerce_checkout_create_order_shipping_item($item, $package_key, $package, $order )
	{
		$item->set_total($this->override_woocommerce_price($item->get_total()));
		
		$tax_data = $item->get_taxes();
		if(is_array($tax_data) && isset($tax_data['total']))
		{
			$tax_data_result = array('total' => $this->override_woocommerce_price($tax_data['total']));
			$item->set_taxes($tax_data_result);
		}
		return $item;
	}
	public function woocommerce_checkout_create_order_tax_item($item, $tax_rate_id, $order )
	{
		$item->set_tax_total($this->override_woocommerce_price($item->get_tax_total()));
		$item->set_shipping_tax_total($this->override_woocommerce_price($item->get_shipping_tax_total()));
		
		return $item;
	}
	public function woocommerce_checkout_create_order_coupon_item($item, $code, $coupon, $order)
	{
		if(method_exists($item, 'set_total') && method_exists($item, 'get_total'))
			$item->set_total($this->override_woocommerce_price($item->get_total()));
		if(method_exists($item, 'set_discount') && method_exists($item, 'get_coupon_discount_amount'))
			$item->set_discount($this->override_woocommerce_price($item->get_coupon_discount_amount( $code )));
		if(method_exists($item, 'set_discount_tax') && method_exists($item, 'get_coupon_discount_amount'))
			$item->set_discount_tax($this->override_woocommerce_price($item->get_coupon_discount_amount( $code )));
		
		
		return $item;
	} 
	//End checkout management 
	
	//Overrides
	public function override_woocommerce_currency($woocommerce_currency)
	{
		global $wcmc_customer_model, $wcmc_option_model;
		
		$options = $wcmc_option_model->get_options();
		if($this->woocommerce_checkout_order_creation_start && wcmc_get_value_if_set($options, 'place_order_using_base_currency', false))
			return $this->get_base_currency();
		
		if($this->is_current_page_to_be_exluded())
			return $woocommerce_currency;
		
		//If admin area, base currency is used
		return is_admin() ? $this->get_base_currency() : $wcmc_customer_model->get_currency();
	}
	public function override_woocommerce_currency_symbol( $currency_symbol, $currency )
	{
		global $wcmc_customer_model, $wcmc_option_model;
		
		$options = $wcmc_option_model->get_options();
		if($this->woocommerce_checkout_order_creation_start && wcmc_get_value_if_set($options, 'place_order_using_base_currency', false))
			return $this->get_base_currency();
		
		if($this->is_current_page_to_be_exluded())
			return $currency_symbol;
		
		$currencies_data = $this->get_currencies_list();
		$currency_data = $wcmc_option_model->get_currency_options();
		
		//If admin area, base currency is used
		$current_currency_code = is_admin() ? $this->get_base_currency() : $wcmc_customer_model->get_currency();
		$symbol = wcmc_get_value_if_set($currency_data, array('currency_data', $current_currency_code, 'show'), "symbol") ==  "symbol" ? 
				  wcmc_get_value_if_set($currencies_data, array($current_currency_code, 'currencySymbol'), " ".$current_currency_code ) : " ".$current_currency_code;
		return $symbol;
	}
	
	public function override_woocommerce_number_of_decimals( $original_option ) 
	{ 
		global $wcmc_customer_model, $wcmc_option_model;
		
		$currency_data = $wcmc_option_model->get_currency_options();
		$current_currency_code = is_admin() ? $this->get_base_currency() : $wcmc_customer_model->get_currency();
		
		$decimals = wcmc_get_value_if_set($currency_data, array('currency_data', $current_currency_code, 'decimals'), $original_option);
		return $decimals != "" ? $decimals : $original_option; 
		
	}
	public function override_woocommerce_currency_position($original_position)
	{
		global $wcmc_customer_model, $wcmc_option_model;
		
		if(is_admin())
			return $original_position;
		
		$currency_data = $wcmc_option_model->get_currency_options();
		$current_currency_code = is_admin() ? $this->get_base_currency() : $wcmc_customer_model->get_currency();
		
		$symbol_position = wcmc_get_value_if_set($currency_data, array('currency_data', $current_currency_code, 'symbol_position'), 'default');
		
		if($symbol_position != 'default')
			return $symbol_position;
		
		return $original_position; 
		
	}
	public function override_woocommerce_price($price, $product = null  )
	{
		global $wcmc_customer_model, $wcmc_option_model;
		
		if($this->is_current_page_to_be_exluded() || !isset($price) || !is_numeric($price))
			return $price;
	
		$currencies_data = $this->get_currencies_list();
		//If admin area, base currency is used
		$current_currency_code = is_admin() ? $this->get_base_currency() : $wcmc_customer_model->get_currency();
		$currency_data = $wcmc_option_model->get_currency_options();
		
		$current_rate =  wcmc_get_value_if_set( $currency_data, array('currency_data', $current_currency_code, 'rate'), false);
		
		if($current_rate == false || $current_rate == 0 || !is_numeric($current_rate))
			return $price;
		
		$new_price = $price * $current_rate;
		
		//moved to override_woocommerce_number_of_decimals()
		//$decimals = wcmc_get_value_if_set($currency_data, array('currency_data', $current_currency_code, 'decimals'), get_option( 'woocommerce_price_num_decimals', 2 ));
	
		return wc_format_decimal($new_price/* , $decimals */);
	}
	public function ajax_load_currency_rates()
	{
		if(isset($_POST['base_currency']))
		{
			$result = $this->update_currency_rates($_POST['base_currency']);
			
			if($result['code'] == 0)
				echo "error";
			else 
				echo json_encode($result['data']['currency_data']);
		}
		wp_die();
	}
	public function get_woocommerce_default_currency()
	{
		return get_option('woocommerce_currency');
	}
	public function exists_base_currency()
	{
		global $wcmc_option_model;
		$options = $wcmc_option_model->get_currency_options();
		
		foreach($options['currency_data'] as $currency_code => $currency_data)
			if(wcmc_get_value_if_set($currency_data, 'base_currency', false))
			{
				return true;
			}
			
		return false;
	}
	public function get_base_currency()
	{
		global $wcmc_option_model;
		$options = $wcmc_option_model->get_currency_options();
		
		foreach($options['currency_data'] as $currency_code => $currency_data)
			//if($currency_data['base_currency'])
			if(wcmc_get_value_if_set($currency_data, 'base_currency', false))
			{
				//wcmc_var_dump($currency_code);
				return $currency_code;
			}
			
		$default_currency = $this->get_woocommerce_default_currency();
		
		//If none has been selected, the default set via WooCommerce is used	
		return $default_currency;
	}
	function is_currency_available($currency_code)
	{ 
		return !isset($currency_code) || $currency_code == "" ? false : array_key_exists($currency_code, $this->get_available_currencies_info());
	}
	function get_default_currency_for_location($location)
	{
		$currencies_info = $this->get_available_currencies_info();
		foreach($currencies_info as $currency_code => $currency_data)
		{
			if(isset($currency_data['default_for_country']) && in_array($location, $currency_data['default_for_country']))
				return $currency_code;
		}
		
		return false;
	}
	function get_default_currency_for_language($lang_code)
	{
		$currencies_info = $this->get_available_currencies_info();
		foreach($currencies_info as $currency_code => $currency_data)
		{
			if(isset($currency_data['available_for_language']) && in_array($lang_code, $currency_data['available_for_language']))
				return $currency_code;
		}
		
		return false;
	}
	function get_available_currencies_info()
	{
		global $wcmc_option_model;
		$currency_options = $wcmc_option_model->get_currency_options(); //rates and base currency
		$currencies_data = $this->get_currencies_list(); //currency list with symbols and name
		$result = array();
		//wcmc_var_dump($currency_options);
		foreach($currency_options['currency_data'] as $currency_code => $currency_data)
		{
			$currencies_data[$currency_code]['flag'] = '<img class="currency-flag currency-flag-'.strtolower($currency_code).' currency-flag-sm"/>';
			//all info are copied from the main source to the final one in which there may be additional info like flags
			foreach($currency_data as $key => $value)
				$currencies_data[$currency_code][$key] = $value;
			$result[$currency_code] = $currencies_data[$currency_code];
		}								
		
		/*
		array(3) {
		  ["BRL"]=>
		  array(5) {
			["currencyName"]=>
			string(14) "Brazilian Real"
			["currencySymbol"]=>
			string(2) "R$"
			["id"]=>
			string(3) "BRL"
			["flag"]=>
			string(63) ""
			["rate"]=>
			string(7) "5.06309"
		  }
		  ["GIP"]=>
		  array(6) {
			["currencyName"]=>
			string(15) "Gibraltar Pound"
			["currencySymbol"]=>
			string(2) "£"
			["id"]=>
			string(3) "GIP"
			["flag"]=>
			string(63) ""
			["rate"]=>
			string(1) "1"
			["base_currency"]=>    //<---------------------- Key exists only for base currency
			string(4) "true",
			["default_for_country"]=>  //<---------------------- Key exists only in at least one country has been selected
			  array(2) {
				[0]=>
				string(2) "DE"
				[1]=>
				string(2) "IT"
			  }
		  }
		  ["INR"]=>
		  array(5) {
			["currencyName"]=>
			string(12) "Indian Rupee"
			["currencySymbol"]=>
			string(3) "₹"
			["id"]=>
			string(3) "INR"
			["flag"]=>
			string(63) ""
			["rate"]=>
			string(8) "90.01448"
		  }
		}
		*/
		
	    return $result;
	}
	public function update_currency_rates($base_currency, $update_settings = false)
	{
		global $wcmc_option_model;
		
		$currency_options = $wcmc_option_model->get_currency_options();		
		if($base_currency == 'detect')
		{
			if(!is_bool(get_woocommerce_currency()) && $this->is_currency_available(get_woocommerce_currency()))
				$base_currency = get_woocommerce_currency();
			else if(count($currency_options["currency_data"] ) > 1)
				$base_currency= array_key_first($currency_options["currency_data"]);
			else 
				return array("code" => 0, "data" => array());
		}  
		
		if(!$this->is_currency_available($base_currency))
		{
			return array("code" => 0, "data" => array());
		}
		//1. load from remote the json with rates
		//$result = file_get_contents($this->table_rates_url.$base_currency);
		$result = wcmc_get_content($this->table_rates_url.$base_currency);
		$result = json_decode($result, true);
		if($result == false)
			return array("code" => 0, "data" => array());
		
		//wcmc_var_dump($result['results']);
		
		
		//2. update rates
		foreach($result['results'] as $currency_data)
		{
			if(!isset($currency_options['currency_data'][$currency_data['to']]))
				continue;
		
			$currency_options['currency_data'][$currency_data['to']]['rate'] = round ($currency_data['val'], 5);
			$currency_options['currency_data'][$base_currency]['base_currency'] = false; 
		}		
		$currency_options['currency_data'][$base_currency]['rate'] = 1; //base country
		$currency_options['currency_data'][$base_currency]['base_currency'] = true; 
		
		//3. optionally settings can be updated
		if($update_settings)
			$wcmc_option_model->save_currency_options($currency_options);
		
		return array("code" => 1, "data" => $currency_options);		
	}
	public function get_currencies_list()
	{
		if(isset($this->currency_list_cache))
			return $this->currency_list_cache;
		
		$currency_to_remove = array(/* 'BYR', 'SDG','TMT', */ 'XDR', 'BTC',/* , 'XOF',  'LSL', 'PAB', 'LVL', 'XAF' */); 
		$result = file_get_contents(WCMC_PLUGIN_ABS_PATH."/assets/currencies.json"); //https://free.currencyconverterapi.com/api/v6/currencies?apiKey=sample-api-key
		//$result = wcmc_get_content(WCMC_PLUGIN_ABS_PATH."/assets/currencies.json"); //https://free.currencyconverterapi.com/api/v6/currencies?apiKey=sample-api-key
		$result = json_decode($result, true);
		if(isset($result['results']))
		{
			ksort($result['results']);
			$result = $result['results'];
			foreach($currency_to_remove as $to_remove)
				if(isset($result[$to_remove]))
					unset($result[$to_remove]);
			
		}
		else 
			$result = array();
		
		$this->currency_list_cache = $result;
		
		/* wcmc_var_dump($result); */
		return $result;
	}
}
?>
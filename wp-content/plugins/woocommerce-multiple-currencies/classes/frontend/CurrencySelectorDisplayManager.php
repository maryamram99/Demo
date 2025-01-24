<?php
namespace WCMC\classes\frontend;

class CurrencySelectorDisplayManager
{
	function __construct()
	{
		
		add_action('wp', array(&$this, 'manage_currency_selector_rendering')); //init

	}
	public function manage_currency_selector_rendering()
	{
		global $wcmc_option_model, $wcmc_html_model,$wcmc_currency_model;
		$options = $wcmc_option_model->get_options();
		
		if(!$wcmc_currency_model->exists_base_currency())
			return;
		
		if(wcmc_get_value_if_set($options, 'force_base_currency_on_checkout_page', false) && (function_exists('is_checkout') && @is_checkout()) )
			return;
		
		if(wcmc_get_value_if_set($options, array('selector_position', 'theme','wp_footer'), false))
		
		{
			add_action( 'wp_footer', array(&$this, 'render_footer_currency_controller'));
		}
		
		
		add_filter( 'wp_get_nav_menu_items', array(&$this, 'add_currency_selector_to_menu'), 20 , 2 ); //reference: https://www.daggerhart.com/dynamically-add-item-to-wordpress-menus/
		
	}
	function render_footer_currency_controller()
	{
		global $wcmc_html_model;
		
		$wcmc_html_model->render_currency_controller(false);
	}
	function add_currency_selector_to_menu($items, $menu) 
	{
		if(is_admin())
			return $items;
		
		/*
			object(WP_Term)#4125 (10) {
			  ["term_id"]=>
			  int(8)
			  ["name"]=>
			  string(6) "Menu 1"
			  ["slug"]=>
			  string(6) "menu-1"
			  ["term_group"]=>
			  int(0)
			  ["term_taxonomy_id"]=>
			  int(8)
			  ["taxonomy"]=>
			  string(8) "nav_menu"
			  ["description"]=>
			  string(0) ""
			  ["parent"]=>
			  int(0)
			  ["count"]=>
			  int(7)
			  ["filter"]=>
			  string(3) "raw"
			}
		*/
		
		global  $wcmc_option_model, $wcmc_html_model, $wp, $wcmc_currency_model, $wcmc_customer_model, $wcmc_wpml_model;
		$options = $wcmc_option_model->get_options();
		$current_url = home_url( add_query_arg( array(), $wp->request ) );
		$currency_data = $wcmc_currency_model->get_available_currencies_info();
		$base_currency = $wcmc_customer_model->get_currency();
	
		$menu_term_id = $wcmc_wpml_model->get_original_id($menu->term_id, 'nav_menu');
		$position_where_render = array('before' => wcmc_get_value_if_set($options, array('selector_position', 'menu', $menu_term_id, 'before'), false),
									   'after'=> wcmc_get_value_if_set($options, array('selector_position', 'menu', $menu_term_id, 'after'), false));
		
		if(wcmc_get_value_if_set($currency_data, $base_currency, false) == false)
			return $items;
		
		wp_enqueue_style( 'wcmc-currency-flags', WCMC_PLUGIN_PATH.'/css/vendor/flags/currency-flags.css');
		wp_enqueue_style( 'wcmc-frontend-currency-selector-menu', WCMC_PLUGIN_PATH.'/css/frontend-currency-selector-menu.css');
		wp_enqueue_script( 'wcmc-frontend-currency-selector-menu', WCMC_PLUGIN_PATH.'/js/frontend-currency-selector-menu.js');
			
			
		foreach($position_where_render as $position => $render)
		{
			if(!$render)
				continue;
			
			$base_priority = 200+rand(1,20234324);
			$counter = 0;

			//First element
			$title = wcmc_get_value_if_set($currency_data, array($base_currency, 'currencySymbol'), "") != "" ? $currency_data[$base_currency]['id']."  ". $currency_data[$base_currency]['currencySymbol'] : $currency_data[$base_currency]['id'];
			//$title =  $currency_data[$base_currency]['flag'].$title;
			$title = $wcmc_html_model->format_switcher_element($currency_data[$base_currency], true);
			
			$top = $wcmc_html_model->create_custom_nav_menu_item( $title, add_query_arg( 'currency', $base_currency, $current_url ), $base_priority );
			$result = array();
			$result[] = $top;
			//Other elements
			foreach($currency_data as $currency_code => $currency_info)
			{
				//If rate is not available, currency won't be rendered
				if(!is_numeric($currency_info['rate']) || $currency_info['rate'] == 0 || $currency_code == $base_currency)
							continue;
						
				$title = wcmc_get_value_if_set($currency_info, 'currencySymbol', "") != "" ? $currency_info['id']."  ". $currency_info['currencySymbol'] : $currency_info['id'];
				$title = $wcmc_html_model->format_switcher_element($currency_info, true);
				
				$result[] = $wcmc_html_model->create_custom_nav_menu_item( $title , add_query_arg( 'currency', $currency_code, $current_url ), $base_priority+(++$counter), $top->ID );
			}
			
			$items = wcmc_merge_arrays($items, $result, $position);
		}

		return $items;
	}
}
?>
<?php 
namespace wcmc\classes\com;

class Html
{
	public function __construct()
	{
		
	}
	public function create_custom_nav_menu_item( $title, $url, $order, $parent = 0 )
	{
		  $item = (object) array(); //it means new stdClass()
		  $item->ID = 3000000 + $order + $parent;
		  $item->db_id = $item->ID;
		  $item->title = $title;
		  $item->url = $url;
		  $item->menu_order = $order;
		  $item->menu_item_parent = $parent;
		  $item->type = '';
		  $item->object = '';
		  $item->object_id = '';
		  $item->classes = array('wcmc_menu_element');
		  $item->target = '';
		  $item->attr_title = '';
		  $item->description = '';
		  $item->xfn = '';
		  $item->status = '';
		  return $item;
	}
	public function format_switcher_element($currency_info, $is_nav_menu_element = false)
	{
		global $wcmc_option_model;
		$options = $wcmc_option_model->get_options();
		$title = wcmc_get_value_if_set($options, array('selector_element_format'), "%flag %currency_code %currency_symbol");
		$title = str_replace("%flag", !$is_nav_menu_element ? "%js_flag" : $currency_info['flag'], $title);
		$title = str_replace("%currency_code", $currency_info['id'], $title);
		$title = str_replace("%currency_symbol", wcmc_get_value_if_set($currency_info,'currencySymbol', ""), $title);
		$title = str_replace("%currency_name", $currency_info['currencyName'], $title);
		return $title; 
	}
	public function render_currency_controller($full_width = true)
	{
		global $wcmc_currency_model, $wcmc_customer_model;
		
		if(!$wcmc_currency_model->exists_base_currency())
			return;
		
		$prefix = $full_width ? "wcmc_fw_" : "wcmc_ic_";
		
		wp_register_script( 'wcmc-currency-selector', WCMC_PLUGIN_PATH.'/js/frontend-currency-controller.js', array('jquery'));
		$js_option = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'prefix' => $prefix
		);
		wp_localize_script( 'wcmc-currency-selector' , 'wcmc_currency_selector', $js_option );
		wp_enqueue_script( 'wcmc-currency-selector');
		//to use the WooCommerce selecto woo: wp_enqueue_script( 'selectWoo');
		wp_enqueue_script( 'wcmc-select2', WCMC_PLUGIN_PATH.'/js/vendor/select2/select2.full.min.js', array('jquery'));	
		
		wp_enqueue_style( 'wcmc-select2', WCMC_PLUGIN_PATH.'/css/vendor/select2/select2.css');
		wp_enqueue_style( 'wcmc-currency-flags', WCMC_PLUGIN_PATH.'/css/vendor/flags/currency-flags.css');
		if($full_width)
			wp_enqueue_style( 'wcmc-currency-selector-full-width', WCMC_PLUGIN_PATH.'/css/frontend-currency-selector-full-width.css');
		else 
			wp_enqueue_style( 'wcmc-currency-selector-inline-centered', WCMC_PLUGIN_PATH.'/css/frontend-currency-selector-inline-centered.css');
		  
		$currency_data = $wcmc_currency_model->get_available_currencies_info();
		$base_currency = $wcmc_customer_model->get_currency();
		
		$unique_id = time() + rand(12302, 999999); 	
		
		?>
		<div class="<?php echo $prefix; ?>content" id="wcmc_content_<?php echo $unique_id;?>">
			<div class="<?php echo $prefix; ?>currency_selector_container" id="wcmc_selector_container_<?php echo $unique_id;?>">
				<select class="<?php echo $prefix; ?>currency_selector wcmc_currency_selector" id="wcmc_currency_selector_<?php echo $unique_id;?>" data-id="<?php echo $unique_id;?>">
					<?php foreach($currency_data as $currency_code => $currency_info): 
						//If rate is not available, currency won't be rendered
						if(!is_numeric($currency_info['rate']) || $currency_info['rate'] == 0)
							continue;
						$title = wcmc_get_value_if_set($currency_info, 'currencySymbol', "") != "" ? $currency_info['id']."  ". $currency_info['currencySymbol'] : $currency_info['id']; 
						$selected = $base_currency == $currency_code ? 'selected = "selected" ' : '';
						?>
						<option value="<?php echo $currency_code ?>" data-flag="<?php echo strtolower($currency_code) ?>" <?php echo $selected; ?>><?php echo  $this->format_switcher_element($currency_info); /* $title; */ ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<img id="wcmc_loader_<?php echo $unique_id;?>" class="<?php echo $prefix; ?>loader" src="<?php echo WCMC_PLUGIN_PATH; ?>/img/loader.gif"></img>
		</div>
		<?php 
	}
}
?>
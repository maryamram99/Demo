<?php 
namespace WCMC\classes\admin;

class SettingsPage
{
	public function __construct()
	{
		
	}
	
	//rplc: woocommerce-multiple-currencies, wcmc, WCMC
	public function render_page()
	{
		global $wcmc_option_model, $wcmc_currency_model, $wcmc_wpml_model;
		
		//Assets
		wp_enqueue_style( 'wcmc-admin-common', WCMC_PLUGIN_PATH.'/css/admin-common.css');
		wp_enqueue_style( 'wcmc-admin-settings-page', WCMC_PLUGIN_PATH.'/css/admin-settings-page.css');
		wp_enqueue_style( 'wcmc-admin-currency-flags', WCMC_PLUGIN_PATH.'/css/vendor/flags/currency-flags.css');
		
		//Save
		$api_key_is_not_valid = false;
		if(isset($_POST['wcmc_options']))
		{
			$wcmc_option_model->save_options($_POST['wcmc_options']);
			$wcmc_currency_model->update_currency_rates( $wcmc_currency_model->exists_base_currency() ? $wcmc_currency_model->get_base_currency() : 'detect', true);
		}
		
		//Load
		$options = $wcmc_option_model->get_options();
		$currencies_data = $wcmc_currency_model->get_currencies_list();
		$menu_locations = wp_get_nav_menus(); //Other locations: get_nav_menu_locations() and get_registered_nav_menus();

		?>
		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
			<div class="notice notice-success is-dismissible">
				 <p><?php _e('Saved successfully!', 'woocommerce-multiple-currencies'); ?></p>
			</div>
		<?php endif; ?>
		<div class="wrap white-box">
			<!-- <form action="options.php" method="post" > -->
				<form action="" method="post" >
				<?php //settings_fields('wcmc_options_group'); ?> 
					<h2 class="wcmc_section_title wcmc_no_margin_top"><?php _e('Options', 'woocommerce-multiple-currencies');?></h3>
					
									
					<h3><?php _e('Footer currency switcher', 'woocommerce-multiple-currencies');?></h3>
					<p><?php _e("Currency switcher can be automatically rendered in the theme footer.", 'woocommerce-multiple-currencies');?></p>
					<div class="wcmc_option_group">
						
						<!--<?php  $selected = wcmc_get_value_if_set($options, array('selector_position','wp_nav_menu_items_before'), false) ? " checked='checked' " : " "; ?>
						<div class="wcmc_checkbox_container">
							<input type="checkbox" name="wcmc_options[selector_position][wp_nav_menu_items_before]" <?php echo $selected; ?> class="wcmc_option_checbox_field" value="true"><?php _e('Menu (before other elements)', 'woocommerce-multiple-currencies'); ?></input>
						</div>	
						
						<?php  $selected = wcmc_get_value_if_set($options, array('selector_position','wp_nav_menu_items_after'), false) ? " checked='checked' " : " "; ?>
						<div class="wcmc_checkbox_container">
							<input type="checkbox" name="wcmc_options[selector_position][wp_nav_menu_items_after]" <?php echo $selected; ?> class="wcmc_option_checbox_field" value="true"><?php _e('Menu (after other elements)', 'woocommerce-multiple-currencies'); ?></input>
						</div>	
						
						<?php  $selected = wcmc_get_value_if_set($options, array('selector_position', 'theme', 'wp_head'), false) ? " checked='checked' " : " "; ?>
						 <div class="wcmc_checkbox_container">
							<input type="checkbox" name="wcmc_options[selector_position][theme][wp_head]" <?php echo $selected; ?> class="wcmc_option_checbox_field" value="true"><?php _e('Header', 'woocommerce-multiple-currencies'); ?></input>
						</div>	-->
						
						<?php  $selected = wcmc_get_value_if_set($options, array('selector_position', 'theme','wp_footer'), false) ? " checked='checked' " : " "; ?>
						<div class="wcmc_checkbox_container">
							<input type="checkbox" name="wcmc_options[selector_position][theme][wp_footer]" <?php echo $selected; ?> class="wcmc_option_checbox_field" value="true"><?php _e('Footer', 'woocommerce-multiple-currencies'); ?></input>
						</div>	
						
					</div>
					
					<h3><?php _e('Menu currency switcher', 'woocommerce-multiple-currencies');?></h3>
					<p><?php _e("Currency switcher can be automatically rendered in any menu. In the case of WPML, the currency selector will be displayed in the selected menus and their translations.", 'woocommerce-multiple-currencies');?></p>
					<div class="wcmc_option_group">
						
						<?php 
							foreach($menu_locations as $location_name => $location_id)
							{
								$menu_data = wp_get_nav_menu_object($location_id);
								$menu_term_id = $wcmc_wpml_model->get_original_id($menu_data->term_id, 'nav_menu');
								$data = get_term($menu_term_id);
								$menu_locations_without_translation[$menu_term_id] = $data->name;
							}
							
							
							/* Old method that just retrieved the available locations: 
							  foreach($menu_locations as $location_name => $location_id):
								//wcmc_var_dump(wp_get_nav_menu_object($location_id)); 
								$menu_data = wp_get_nav_menu_object($location_id);
								$menu_term_id = $wcmc_wpml_model->get_original_id($menu_data->term_id, 'nav_menu');
								$selected_before = wcmc_get_value_if_set($options, array('selector_position', 'menu', $menu_term_id, 'before'), false) ? " checked='checked' " : " ";
								$selected_after = wcmc_get_value_if_set($options, array('selector_position', 'menu', $menu_term_id, 'after'), false) ? " checked='checked' " : " ";
								$menu_name = $menu_data->name;
								
								*/
							//New method that retrieves only master language menus:
							foreach($menu_locations_without_translation as $menu_term_id => $menu_name):
								$selected_before = wcmc_get_value_if_set($options, array('selector_position', 'menu', $menu_term_id, 'before'), false) ? " checked='checked' " : " ";
								$selected_after = wcmc_get_value_if_set($options, array('selector_position', 'menu', $menu_term_id, 'after'), false) ? " checked='checked' " : " ";
								
								?>
								<div class="wcmc_checkbox_container">
									<input type="checkbox" name="wcmc_options[selector_position][menu][<?php echo $menu_term_id; ?>][before]" <?php echo $selected_before; ?> class="wcmc_option_checbox_field" value="true"><?php echo $menu_name." ".__('- Before other items', 'woocommerce-multiple-currencies'); ?></input>
								</div>	
								<div class="wcmc_checkbox_container">
									<input type="checkbox" name="wcmc_options[selector_position][menu][<?php echo $menu_term_id; ?>][after]" <?php echo $selected_after; ?> class="wcmc_option_checbox_field" value="true"><?php echo $menu_name." ".__('- After other items', 'woocommerce-multiple-currencies'); ?></input>
								</div>
						<?php endforeach; ?>
						
					</div>
					
						
					<h3><?php _e('Switcher elements format', 'woocommerce-multiple-currencies');?></h3>
					<p><?php _e("Set how the switcher elements will look like. The default format is <strong>%flag %currency_code %currency_symbol</strong>, You can use the following elements:", 'woocommerce-multiple-currencies');?></p>
					<ul>
						<li><?php _e("<strong>%flag:</strong> currency flag", 'woocommerce-multiple-currencies');?></li>
						<li><?php _e("<strong>%currency_code:</strong> currency code (ex: EUR, USD, GBP, etc.)", 'woocommerce-multiple-currencies');?></li>
						<li><?php _e("<strong>%currency_symbol:</strong> currency symbol (ex: €, $, £, etc.)", 'woocommerce-multiple-currencies');?></li>
						<li><?php _e("<strong>%currency_name:</strong> currency name (ex: Euro, United States Dollar , British Pound, etc.)", 'woocommerce-multiple-currencies');?></li>
					</ul>
					<div class="wcmc_option_group">
							<label class="wcmc_input_label"><?php _e('Format', 'woocommerce-multiple-currencies');?></label>
							<?php  $selector_element_format = wcmc_get_value_if_set($options, array('selector_element_format'), "%flag %currency_code %currency_symbol"); ?>
							<input type="input" name="wcmc_options[selector_element_format]" class="wcmc_input" required="required" placeholder="<?php _e("Default: %flag %currency_code %currency_symbol", 'woocommerce-multiple-currencies');?>" value="<?php echo $selector_element_format; ?>"></input>
					
					</div>
					
					<div class="wcmc_info">
						<h3><?php _e('Info', 'woocommerce-multiple-currencies');?></h3>
						<p><?php echo sprintf(__("Currency selector can be displayed using the special Widget you find in the <a href='%s' target='_blank'><strong>Appearance -> Widget</strong></a> menu. You can also use the custom <strong>[wcmc_currency_selector]</strong> shortcode or the following PHP snippet:", 'woocommerce-multiple-currencies'), admin_url('widgets.php'));?></p> 
						<code>
							<?php echo "&#60;?php wcmc_currency_selector(); ?&#62;"; ?>
						</code>
					</div>
									
					<h3><?php _e('Currecies', 'woocommerce-multiple-currencies');?></h3>
					<p><?php _e("Select available currencies. Once selected, go to the <strong>Exchange rates</strong> menu and <strong>make sure that the current base currency is the one you need</strong>. If you have not previously selected one, the plugin tries to detect the base currency using the store base currency.", 'woocommerce-multiple-currencies');?></p>
					<div class="wcmc_inline_block">
						<?php foreach($currencies_data as $currency_code => $currency_data):
						$selected = wcmc_get_value_if_set($options, array('selected_currency', $currency_code), false) ? " checked='checked' " : " "; ?>
						<div class="wcmc_checkbox_container wcmc_currency_checkbox_text">
							<input type="checkbox" name="wcmc_options[selected_currency][<?php echo $currency_code; ?>]" <?php echo $selected; ?> class="wcmc_option_checbox_field" value="true">
								<img class="currency-flag currency-flag-<?php echo strtolower($currency_code);?> currency-flag-sm"/>
								<?php echo$currency_data['id']." - ".$currency_data['currencyName']/* .": ".$currency_data['currencySymbol'] */; ?>
							</input>
						</div>
						<?php endforeach; ?>
					</div>	

					<h3><?php _e('Place orders using base currency', 'woocommerce-multiple-currencies');?></h3>
					<p><?php _e("By default, the orders are placed via frontend according to the currency selected by the user. If the following option is enabled, the orders will be always placed using base currency set via the <strong>Excange rates</strong> menu. This may be useful in case you are using a 3rd party payment gateway plugins that are not properly managing product prices according to the selected currency.", 'woocommerce-multiple-currencies');?></p>
					<div class="wcmc_inline_block">	
						<?php $is_checked = wcmc_get_value_if_set($options, 'place_order_using_base_currency', false) ? "checked='checked'" : ""; ?>	
						<div class="wcmc_checkbox_container">
							<input type="checkbox" name="wcmc_options[place_order_using_base_currency]" <?php echo $is_checked; ?> class="wcmc_option_checbox_field" value="true"><?php _e('Place orders using base currency and prices', 'woocommerce-multiple-currencies'); ?></input>
						</div>	
					</div>	
					
					<h3><?php _e('Force base currency usage on the Checkout page', 'woocommerce-multiple-currencies');?></h3>
					<p><?php _e("This option is stronger that the previous. It forces the base currency usage by automatically switching the current currency to the base currency when the customer reaches the Checkout page. Note that when enabling this option the currency selectors won't be displayed. This may be helpful in case some payment gateways are preventing placing orders due to unsupported currencies. <strong>The orders will be placed using the base currency</strong>.", 'woocommerce-multiple-currencies');?></p>
					<div class="wcmc_inline_block">	
						<?php $is_checked = wcmc_get_value_if_set($options, 'force_base_currency_on_checkout_page', false) ? "checked='checked'" : ""; ?>	
						<div class="wcmc_checkbox_container">
							<input type="checkbox" name="wcmc_options[force_base_currency_on_checkout_page]" <?php echo $is_checked; ?> class="wcmc_option_checbox_field" value="true"><?php _e('Force base currency usage on checkout page', 'woocommerce-multiple-currencies'); ?></input>
						</div>	
					</div>	
					
					<h3><?php _e('Disable "?currency=" URL parameter', 'woocommerce-multiple-currencies');?></h3>
					<p><?php _e("By default, the plugin adds the <strong>?currency=</strong> parameter to the site URL. This prevents caching plugins to serve pages with a currency different by the selected one. <strong>Disabling this option may cause unexpected behavior when using caching plugins</strong>.", 'woocommerce-multiple-currencies');?></p>
					<div class="wcmc_inline_block">	
						<?php $is_checked = wcmc_get_value_if_set($options, 'disable_currency_url_parameter', false) ? "checked='checked'" : ""; ?>	
						<div class="wcmc_checkbox_container">
							<input type="checkbox" name="wcmc_options[disable_currency_url_parameter]" <?php echo $is_checked; ?> class="wcmc_option_checbox_field" value="true"><?php _e('Disable ?currency= parameter', 'woocommerce-multiple-currencies'); ?></input>
						</div>	
					</div>	
					
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save', 'woocommerce-multiple-currencies'); ?>" />
				</p>
			</form>			
		</div>
		<?php 
	}
}
?>
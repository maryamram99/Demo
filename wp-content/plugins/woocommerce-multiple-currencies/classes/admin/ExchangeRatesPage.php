<?php 
namespace WCMC\classes\admin;

class ExchangeRatesPage
{
	public function __construct()
	{
		
	}
	
	//rplc: woocommerce-multiple-currencies, wcmc, WCMC
	public function render_page()
	{
		global $wcmc_option_model, $wcmc_currency_model, $wcmc_customer_model, $wcmc_payment_gateway_model, $wcmc_wpml_model;
		
		//Assets
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_style( 'wcmc-admin-common', WCMC_PLUGIN_PATH.'/css/admin-common.css');
		wp_enqueue_style( 'wcmc-select2', WCMC_PLUGIN_PATH.'/css/vendor/select2/select2.css');
		wp_enqueue_style( 'wcmc-admin-exchange-rates-page', WCMC_PLUGIN_PATH.'/css/admin-exchange-rates-page.css');
		
		
		wp_register_script( 'wcmc-admin-exchange-rates-page', WCMC_PLUGIN_PATH.'/js/admin-exchange-rates-page.js', array('jquery'));
		$options = array(
			'update_ok_message' => esc_html__( 'Update process succeed!', 'woocommerce-multiple-currencies' ),
			'update_error_message' => esc_html__( 'Error during the update process. Please retry again later or manually insert values', 'woocommerce-multiple-currencies' ),
			'base_currency_changed_message' => esc_html__( 'Base currency has changed!<br/>Please update the rates (manually or through the "Update rates" button).', 'woocommerce-multiple-currencies' ),
			'updating_message' => esc_html__( 'Updating rates, please wait...', 'woocommerce-multiple-currencies' ),
			'select_base_currency_message' => esc_html__( 'Select the base currency!', 'woocommerce-multiple-currencies' )			
		);
		wp_localize_script( 'wcmc-admin-exchange-rates-page', 'wcmc', $options );

		wp_enqueue_script('wcmc-admin-exchange-rates-page');
		wp_enqueue_script('selectWoo');
		
		//Save
		$api_key_is_not_valid = false;
		if(isset($_POST['wcmc_currency_options']))
		{
			$wcmc_option_model->save_currency_options($_POST['wcmc_currency_options']);
		}
		$exists_base_currency = $wcmc_currency_model->exists_base_currency();
		
		//Load
		$currency_options = $wcmc_option_model->get_currency_options();
		$currencies_data = $wcmc_currency_model->get_currencies_list();
		$countries =  WC()->countries->countries;
		$available_gateways = $wcmc_payment_gateway_model->get_available_payment_gateways();
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
			<div class="notice notice-success is-dismissible">
				 <p><?php esc_html_e('Saved successfully!', 'woocommerce-multiple-currencies'); ?></p>
			</div>
		<?php endif; ?>
		<div class="wrap white-box">
			<form action="" method="post"id="wcmc_exchange_rates_form">
				<h2 class="wcmc_section_title wcmc_no_margin_top"><?php esc_html_e('Options', 'woocommerce-multiple-currencies');?></h3>
				
								
				<h3><?php esc_html_e('Update frequency', 'woocommerce-multiple-currencies');?></h3>
				<p><?php esc_html_e("Select frequencies by which the rates will be updated.", 'woocommerce-multiple-currencies');?></p>
				<div class="wcmc_option_group">
					<label><?php esc_html_e('Frequency', 'woocommerce-multiple-currencies');?></label>
					<?php  $selected = wcmc_get_value_if_set($currency_options, array('frequency'), "manually"); ?>
					<select name="wcmc_currency_options[frequency]" class="wcmc_select2 wcmc_frequency_selector">
						<option value="hourly" <?php selected( $selected, "hourly"); ?>><?php esc_html_e('Hourly', 'woocommerce-multiple-currencies');?></option>
						<option value="wcmc_15_minutes" <?php selected( $selected, "wcmc_15_minutes"); ?>><?php esc_html_e('Every 15 minutes', 'woocommerce-multiple-currencies');?></option>
						<option value="wcmc_30_minutes" <?php selected( $selected, "wcmc_30_minutes"); ?>><?php esc_html_e('Every 30 minutes', 'woocommerce-multiple-currencies');?></option>
						<option value="twicedaily" <?php selected( $selected, "twicedaily"); ?>><?php esc_html_e('Twice a day', 'woocommerce-multiple-currencies');?></option>
						<option value="daily" <?php selected( $selected, "daily"); ?>><?php esc_html_e('Daily', 'woocommerce-multiple-currencies');?></option>
						<option value="manually" <?php selected( $selected, "manually"); ?>><?php esc_html_e('Manually', 'woocommerce-multiple-currencies');?></option>
					</select>
					
				</div>
				
				<h3><?php esc_html_e('Rates', 'woocommerce-multiple-currencies');?></h3>
			
					<ul>
						<li><?php wcmc_html_escape_allowing_special_tags(__("The <strong>Default for countries</strong> option will automatically select the associated currency at fist access for any user coming from the seleted locations. User location is detected by the WooCommerce geolocator. Leave empty to ignore the option.", 'woocommerce-multiple-currencies'));?></li>
						<li><?php wcmc_html_escape_allowing_special_tags(__("Leave <strong>Number of decimals</strong> option empty to use the current WooCommerce number of decimals option (set through the WooCommerce -> Settings -> General -> Currency option area).", 'woocommerce-multiple-currencies'));?></li>
						<li><?php wcmc_html_escape_allowing_special_tags(__("<strong>Symbol position</strong> Default will use the setting defined via the WooCommerce -> Settings -> General -> Currency position option. This option will not affect the Admin area.", 'woocommerce-multiple-currencies'));?></li>
						<li><?php wcmc_html_escape_allowing_special_tags(__("<strong>Payment gateways</strong> Select which payment gateways are available for a currency. By default, all are available.", 'woocommerce-multiple-currencies'));?></li>
						<?php if($wcmc_wpml_model->wpml_is_active()): ?>
						<li><?php wcmc_html_escape_allowing_special_tags(__("<strong>Default for language</strong> Select for which language the currency is the default currency.", 'woocommerce-multiple-currencies'));?></li>
						<?php endif; ?>
						<li><?php wcmc_html_escape_allowing_special_tags(__("The <strong>Show</strong> option sets if it has to be displayed the currency symbol (example: €) or the currency code (ex.: EUR).", 'woocommerce-multiple-currencies'));?></li>
					</ul>
				
				<div class="wcmc_option_group" id="wcmc_rates_table">
					<table>
					 <tr>
						<th><?php esc_html_e('Currency', 'woocommerce-multiple-currencies');?></th>
						<th><?php esc_html_e('Rate', 'woocommerce-multiple-currencies');?></th> 
						<th><?php esc_html_e('Is base currency', 'woocommerce-multiple-currencies');?></th>
						<th><?php esc_html_e('Default for countries', 'woocommerce-multiple-currencies');?></th>
						<th><?php esc_html_e('Payment gateways', 'woocommerce-multiple-currencies');?></th>
						<?php if($wcmc_wpml_model->wpml_is_active()): ?>
						<th><?php esc_html_e('Default for language', 'woocommerce-multiple-currencies');?></th>
						<?php endif; ?>
						<th><?php esc_html_e('Symbol position', 'woocommerce-multiple-currencies');?></th>
						<th><?php esc_html_e('Number of decimals', 'woocommerce-multiple-currencies');?></th>
						<th><?php esc_html_e('Show', 'woocommerce-multiple-currencies');?></th>
					  </tr>
						<tbody class="wcmc_sortable">
						<?php 
								//wcmc_var_dump($currency_options);
								$first_run = true;
								foreach($currency_options['currency_data'] as $currency_code => $currency_data): 
									$is_checked = wcmc_get_value_if_set($currency_data, 'base_currency', false) /* || (!$exists_base_currency && $first_run) */ ? "checked='checked'" : "";
									$first_run = false;
									$default_for_country = wcmc_get_value_if_set($currency_data, array('default_for_country'), array());	
									$available_gateway = wcmc_get_value_if_set($currency_data, array('available_gateway'), array());	
									$available_for_language = wcmc_get_value_if_set($currency_data, array('available_for_language'), array());	
									$symbol_position = wcmc_get_value_if_set($currency_data, 'symbol_position', 'default');	
									$decimals = wcmc_get_value_if_set($currency_data, 'decimals', "");
									$show = wcmc_get_value_if_set($currency_data, 'show', "symbol");
								?>
						
							 <tr>
								 <td>
									<span class="dashicons dashicons-sort wcmc_sort_button"></span>
									<span class="wcam_currency_name"><?php   
									
									echo $currency_code; ?></span>
								 </td>
								 <td>
									<input name="wcmc_currency_options[currency_data][<?php echo $currency_code; ?>][rate]" id="wcmc_<?php echo $currency_code; ?>_input" type="number" class="wcmc_rate_input" required="required" min="0" step="0.00001" value="<?php echo $currency_options['currency_data'][$currency_code]['rate']; ?>"></input>
								 </td>
								 <td>
									<label class="switch">
									  <input type="checkbox" class="wcmc_toggle" name="wcmc_currency_options[currency_data][<?php echo $currency_code; ?>][base_currency]" value="true" data-id="<?php echo $currency_code; ?>" <?php echo $is_checked; ?>>
									  <span class="slider"></span>
									</label>
								 </td>
								
								<td>
									<select class="wcmc_country_selection wcmc_select2" name="wcmc_currency_options[currency_data][<?php echo $currency_code; ?>][default_for_country][]" multiple="multiple">
										<?php foreach($countries as $country_code => $country_name): 
											?>
											<option value="<?php echo $country_code;?>" <?php selected(in_array($country_code, $default_for_country), true); ?>><?php echo $country_name;?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td>
									<select class="wcmc_gateway_selection wcmc_select2" name="wcmc_currency_options[currency_data][<?php echo $currency_code; ?>][available_gateway][]" multiple="multiple">
										<?php foreach($available_gateways as $gateway_code => $gateway_obj): 
											?>
											<option value="<?php echo $gateway_code;?>" <?php selected(in_array($gateway_code, $available_gateway), true); ?>><?php echo $gateway_obj->get_method_title();?></option>
										<?php endforeach; ?>
									</select>
								</td>
								
								<?php if($wcmc_wpml_model->wpml_is_active()):
										$langs =  $wcmc_wpml_model->get_langauges_list();
								?>
								<td>
									<select class="wcmc_country_selection wcmc_select2" name="wcmc_currency_options[currency_data][<?php echo $currency_code; ?>][available_for_language][]" multiple="multiple">
										<?php foreach($langs as $lang_data): 
											?>
											<option value="<?php echo $lang_data['language_code'];?>" <?php selected(in_array($lang_data['language_code'], $available_for_language), true); ?>><?php echo $lang_data['translated_name'];?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<?php endif; ?>
								<td>
									<select name="wcmc_currency_options[currency_data][<?php echo $currency_code; ?>][symbol_position]">
										<option value="default" <?php selected($symbol_position, 'default'); ?>><?php esc_html_e('Default', 'woocommerce-multiple-currencies');?></option>
										<option value="left" <?php selected($symbol_position, 'left'); ?>><?php esc_html_e('Left', 'woocommerce-multiple-currencies');?></option>
										<option value="right" <?php selected($symbol_position, 'right', true); ?>><?php esc_html_e('Right', 'woocommerce-multiple-currencies');?></option>
									</select>
									
								</td>
								<td>
									<input name="wcmc_currency_options[currency_data][<?php echo $currency_code; ?>][decimals]" type="number" class="wcmc_rate_input" min="0" step="1" value="<?php echo $decimals; ?>"></input>
								</td>
								<td>
									<select name="wcmc_currency_options[currency_data][<?php echo $currency_code; ?>][show]">
										<option value="symbol" <?php selected($show, 'symbol'); ?>><?php esc_html_e('Symbol', 'woocommerce-multiple-currencies');?></option>
										<option value="code" <?php selected($show, 'code'); ?>><?php esc_html_e('Code', 'woocommerce-multiple-currencies');?></option>
									</select>
									
								</td>
							</tr>
						<?php endforeach; ?>
						<tbody>
					</table>
					<button class="button-primary" id="wcmc_update_rates"><?php esc_html_e('Update rates', 'woocommerce-multiple-currencies'); ?></button>
					<img id="wcmc_loader" src="<?php echo WCMC_PLUGIN_PATH; ?>/img/loader.gif"></img>
					<div id="wcmc_rate_update_status"></div>
				</div>				
				
				
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_html_e('Save', 'woocommerce-multiple-currencies'); ?>" />
				</p>
			</form>			
		</div>
		<?php 
	}
}
?>
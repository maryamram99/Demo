<?php
/*
Plugin Name: WooCommerce Multiple Currencies
Description: Multiple currencies management.
Author: Lagudi Domenico
Version: 6.2
*/


define('WCMC_PLUGIN_PATH', rtrim(plugin_dir_url(__FILE__), "/") ) ;
define('WCMC_PLUGIN_LANG_PATH', basename( dirname( __FILE__ ) ) . '/languages' ) ;
define('WCMC_PLUGIN_ABS_PATH', dirname( __FILE__ ) );


if ( !defined('WP_CLI') && ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ||
					   (is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option('active_sitewide_plugins') ))
					 )	
	)
{
	//For some reasons the theme editor in some installtion won't work. This directive will prevent that.
	if(isset($_POST['action']) && $_POST['action'] == 'edit-theme-plugin-file')
		return;
	
	if(isset($_REQUEST ['context']) && $_REQUEST['context'] == 'edit') //rest api
		return;
		
	if(isset($_POST['action']) && strpos($_POST['action'], 'health-check') !== false) //health check
		return;
	
	$wcmc_id = 23590806;
	$wcmc_name = "WooCommerce Multiple Currencies";
	$wcmc_activator_slug = "wcmc-activator";
	$wcmc_prefix = "wcmc_";
	$wcmc_prefix_upper = "WCMC_";
	
	include 'classes/com/Globals.php';
	require_once('classes/admin/ActivationPage.php');
	
	add_action('init', 'wcmc_init');
	add_action('admin_menu', 'wcmc_init_act');
	if(defined('DOING_AJAX') && DOING_AJAX)
			wcmc_init_act();
	add_action('admin_notices', 'wcmc_admin_notices' ); 
} 
function wcmc_admin_notices()
{
	global $lcmc, $wcmc_name, $wcmc_activator_slug;
	if($lcmc && (!isset($_GET['page']) || $_GET['page'] != $wcmc_activator_slug))
	{
		 ?>
		<div class="notice notice-success">
			<p><?php echo wcmc_html_escape_allowing_special_tags(sprintf(__( 'To complete the <span style="color:#96588a; font-weight:bold;">%s</span> plugin activation, you must verify your purchase license. Click <a href="%s">here</a> to verify it.', 'woocommerce-multiple-currencies' ), $wcmc_name, get_admin_url()."admin.php?page=".$wcmc_activator_slug)); ?></p>
		</div>
		<?php
	}
}
function wcmc_init()
{
	load_plugin_textdomain('woocommerce-multiple-currencies', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
function wcmc_init_act()
{
	global $wcmc_activator_slug, $wcmc_name, $wcmc_id;
	new WCMC\classes\admin\ActivationPage($wcmc_activator_slug, $wcmc_name, 'woocommerce-multiple-currencies', $wcmc_id, WCMC_PLUGIN_PATH);
}
function wcmc_eu()
{
	add_action('admin_menu', 'wcmc_init_admin_panel');
	
	global $wcmc_option_model, $wcmc_currency_model, $wcmc_html_model, $wcmc_customer_model, $wcmc_session_model, 
		  $wcmc_payment_gateway_model, $wcmc_wpml_model;
		
	//com
	include 'classes/com/WidgetCurrencySelector.php';
	
	if(!class_exists('WCMC\classes\com\Wpml'))
	{
		require_once('classes/com/Wpml.php');
		$wcmc_wpml_model = new WCMC\classes\com\Wpml(); 
	}
	if(!class_exists('WCMC\classes\com\Option'))
	{
		require_once('classes/com/Option.php');
		$wcmc_option_model = new WCMC\classes\com\Option(); 
	}
	if(!class_exists('WCMC\classes\com\Currency'))
	{
		require_once('classes/com/Currency.php');
		$wcmc_currency_model = new WCMC\classes\com\Currency(); 
	}
	if(!class_exists('WCMC\classes\com\Customer'))
	{
		require_once('classes/com/Customer.php');
		$wcmc_customer_model = new WCMC\classes\com\Customer(); 
	}
	if(!class_exists('WCMC\classes\com\Html'))
	{
		require_once('classes/com/Html.php');
		$wcmc_html_model = new WCMC\classes\com\Html(); 
	}
	if(!class_exists('WCMC\classes\com\Shortcode'))
	{
		require_once('classes/com/Shortcode.php');
		new WCMC\classes\com\Shortcode(); 
	}
	if(!class_exists('WCMC\classes\com\Cron'))
	{
		require_once('classes/com/Cron.php');
		new WCMC\classes\com\Cron(); 
	}
	if(!class_exists('WCMC\classes\com\URLManager'))
	{
		require_once('classes/com/URLManager.php');
		new WCMC\classes\com\URLManager(); 
	}
	if(!class_exists('WCMC\classes\com\Session'))
	{
		require_once('classes/com/Session.php');
		$wcmc_session_model = new WCMC\classes\com\Session(); 
	}
	if(!class_exists('WCMC\classes\com\PaymentGateway'))
	{
		require_once('classes/com/PaymentGateway.php');
		$wcmc_payment_gateway_model = new WCMC\classes\com\PaymentGateway(); 
	}
	
	//admin
	if(!class_exists('Option'))
		require_once('classes/admin/SettingsPage.php');
	if(!class_exists('ExchangeRatesPage'))
		require_once('classes/admin/ExchangeRatesPage.php');
	
	
	//frontend 
	if(!class_exists('WCMC\classes\frontend\CurrencySelectorDisplayManager'))
	{
		require_once('classes/frontend/CurrencySelectorDisplayManager.php');
		new WCMC\classes\frontend\CurrencySelectorDisplayManager(); 
	}

}
function wcmc_init_admin_panel()
{
	if(!current_user_can('manage_woocommerce'))
		return;
	
	$place = wcmc_get_free_menu_position(59 , .1);
	$cap = 'manage_woocommerce';
	
	add_menu_page( 'WooCommerce Multiple Currencies', esc_html__('WooCommerce Multiple Currencies', 'woocommerce-multiple-currencies'), $cap, 'wcmc-woocommerce-multiple-currencies', null,  WCMC_PLUGIN_PATH."/img/menu-icon.png" , (string)$place);
	add_submenu_page( 'wcmc-woocommerce-multiple-currencies', esc_html__('WooCommerce Multiple Currencies - General settings', 'woocommerce-multiple-currencies'),  esc_html__('General settings', 'woocommerce-multiple-currencies'), $cap, 'woocommerce-multiple-currencies-settings-page', 'wcmc_render_admin_page' );	
	add_submenu_page( 'wcmc-woocommerce-multiple-currencies', esc_html__('WooCommerce Multiple Currencies - Exchange rates & Currency settings', 'woocommerce-multiple-currencies'),  esc_html__('Exchange rates & Currency settings', 'woocommerce-multiple-currencies'), $cap, 'woocommerce-multiple-currencies-exchange-rates-page', 'wcmc_render_admin_page' );	
	remove_submenu_page( 'wcmc-woocommerce-multiple-currencies', 'wcmc-woocommerce-multiple-currencies');
}
function wcmc_render_admin_page()
{
	if(!isset($_REQUEST['page']))
		return;
	switch($_REQUEST['page'])
	{
		case 'woocommerce-multiple-currencies-settings-page':
			$settings_page = new WCMC\classes\admin\SettingsPage();
			$settings_page->render_page();
		break;
		case 'woocommerce-multiple-currencies-exchange-rates-page':
			$settings_page = new WCMC\classes\admin\ExchangeRatesPage();
			$settings_page->render_page();
		break;
	}
}
function wcmc_get_free_menu_position($start, $increment = 0.1)
{
	foreach ($GLOBALS['menu'] as $key => $menu) {
		$menus_positions[] = $key;
	}
	
	if (!in_array($start, $menus_positions)) return $start;

	/* the position is already reserved find the closet one */
	while (in_array($start, $menus_positions)) {
		$start += $increment;
	}
	return $start;
}
?>
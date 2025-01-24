<?php 
namespace WCMC\classes\com;

class URLManager
{

	function __construct()
	{
		add_action( 'template_redirect', array( $this, 'add_currency_parameter' ) );
	}
	//Exceuted after the check_url_params() method defined on the Customer model.
	public function add_currency_parameter()
	{
		global $wcmc_customer_model, $wcmc_option_model;
		
		$options = $wcmc_option_model->get_options();
		$disable_currency_url_parameter = wcmc_get_value_if_set($options, 'disable_currency_url_parameter', false);
		
		//Adds the parameter if it missing and the option to disable it is not enabled
		if(!$disable_currency_url_parameter && !isset($_GET['currency']) && !is_admin())
		{
			$current_uri = $_SERVER['REQUEST_URI'];
			$result = add_query_arg( 'currency', $wcmc_customer_model->get_currency(), $current_uri );
			wp_safe_redirect( $result, 302 );
		}
		//Remove parameter in case the the option to disable has been enabled
		if($disable_currency_url_parameter && isset($_GET['currency']))
		{
			$current_uri = $_SERVER['REQUEST_URI'];
			$result = remove_query_arg( 'currency' );
			wp_safe_redirect( $result, 302 );
		}
	}
}
?>
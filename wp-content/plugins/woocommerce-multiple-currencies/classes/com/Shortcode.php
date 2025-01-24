<?php 
namespace WCMC\classes\com;

class Shortcode
{
	public function __construct()
	{
		add_shortcode( 'wcmc_currency_selector', array(&$this, 'display_currency_selector' ));
	}
	public function display_currency_selector($atts)
	{
		global $wcmc_html_model;
		
		ob_start();
			$wcmc_html_model->render_currency_controller(false);
		return ob_get_clean();
	}
}
?>
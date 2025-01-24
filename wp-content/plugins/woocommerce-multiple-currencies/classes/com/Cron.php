<?php 
namespace WCMC\classes\com;

class Cron
{
	public function __construct()
	{
		add_action( 'wp_loaded', array(&$this,'schedule_rates_update') );	//wp: fiered only when accessin frontend
		add_action( 'wcmc_cron_update_rates', array(&$this, 'update_rates' ));
		add_action( 'cron_schedules', array(&$this, 'cron_schedules' ));
	}
	function cron_schedules($schedules)
	{
		if(!isset($schedules["wcmc_15_minutes"]))
		{
			$schedules["wcmc_15_minutes"] = array(
			'interval' => 15*60, //15 minutes
			'display' => __('Once every 15 Minutes'));
		}
		if(!isset($schedules["wcmc_30_minutes"]))
		{
			$schedules["wcmc_30_minutes"] = array(
			'interval' => 30*60, 
			'display' => __('Once every 30 Minutes'));
		}
		
		return $schedules;
	}
	function schedule_rates_update() 
	{
		global $wcmc_option_model;
		
		$currency_options = $wcmc_option_model->get_currency_options();
		$update_frequency = wcmc_get_value_if_set($currency_options, array('frequency'), "manually");
		
		if($update_frequency != 'manually')
		{
			wp_next_scheduled( 'wcmc_cron_update_rates' );
			if ( !wp_next_scheduled( 'wcmc_cron_update_rates' ) ) 
			{
				wp_schedule_event( time(), $update_frequency, 'wcmc_cron_update_rates' ); //seconds
			}
		}
		else 
			wp_clear_scheduled_hook( 'wcmc_cron_update_rates' );
		
	}
	function update_rates()
	{
		global $wcmc_currency_model;
		
		//wcmc_var_dump("update_rates");
		//wcmc_write_log($wcmc_currency_model->exists_base_currency() ? 'true': 'false');
		if($wcmc_currency_model->exists_base_currency())
			$wcmc_currency_model->update_currency_rates( $wcmc_currency_model->get_base_currency(), true);
	}
}
?>
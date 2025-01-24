<?php 
function wcmc_get_woo_version_number() 
{
        // If get_plugins() isn't available, require it
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        // Create the plugins folder and file variables
	$plugin_folder = get_plugins( '/' . 'woocommerce' );
	$plugin_file = 'woocommerce.php';
	
	// If the plugin version number is set, return it 
	if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
		return $plugin_folder[$plugin_file]['Version'];

	} else {
	// Otherwise return null
		return NULL;
	}
}
$wcmc_result = get_option("_".$wcmc_id);
$wcmc_notice = !$wcmc_result || $wcmc_result != md5($_SERVER['SERVER_NAME']); 
function wcmc_get_value_if_set($data, $nested_indexes, $default)
{
	if(!isset($data))
		return $default;
	
	$nested_indexes = is_array($nested_indexes) ? $nested_indexes : array($nested_indexes);
	//$current_value = null;
	foreach($nested_indexes as $index)
	{
		if(!isset($data[$index]))
			return $default;
		
		$data = $data[$index];
		//$current_value = $data[$index];
	}
	
	return $data;
}
function wcmc_is_request_to_rest_api()
{
	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return false;
	}
	// Check if our endpoint.
	$woocommerce = false !== strpos( $_SERVER['REQUEST_URI'], 'wp-json/wc/' );
	// Allow third party plugins use our authentication methods.
	$third_party = false !== strpos( $_SERVER['REQUEST_URI'], 'wp-json/wc-' );
	
	return apply_filters( 'woocommerce_rest_is_request_to_rest_api', $woocommerce || $third_party );
}
function wcmc_currency_selector()
{
	global $wcmc_html_model;
		
	$wcmc_html_model->render_currency_controller(true);
}
function wcmc_get_content($URL)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $URL);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
function wcmc_write_log ( $log )  
{
  if ( is_array( $log ) || is_object( $log ) ) 
  {
	 error_log( print_r( $log, true ) );
  }
  else 
  {
	if(is_bool($log))
	{
		echo $log ? 'true' : 'false';
	}
	else
	 error_log( $log );
  }
}
function wcmc_merge_arrays($array1, $array2, $position = 'before')
{
	$result = array();
	if($position == 'before')
	{
		foreach($array2 as $elem)
			$result[] = $elem;
		foreach($array1 as $elem)
			$result[] = $elem;
	}
	else 
	{
		foreach($array1 as $elem)
			$result[] = $elem;
		foreach($array2 as $elem)
			$result[] = $elem;
	}
	
	return $result;
}
$b0=get_option("_".$wcmc_id);$lcmc=!$b0||($b0!=md5(wcmc_ghob($_SERVER['SERVER_NAME']))&&$b0!=md5($_SERVER['SERVER_NAME'])&&$b0!=md5(wcmc_dasd($_SERVER['SERVER_NAME'])));$lcmc=false;if(!$lcmc)wcmc_eu();function wcmc_ghob($o3){$g4=strtolower(trim($o3));$w5=substr_count($g4,'.');if($w5===2){if(strlen(explode('.',$g4)[1])>3)$g4=explode('.',$g4,2)[1];}else if($w5>2){$g4=wcmc_ghob(explode('.',$g4,2)[1]);}if(($x6=strpos($g4,'.'))!==false){$g4=substr($g4,0,$x6);}return $g4;}function wcmc_dasd($o3){$x7=explode(".",$o3);return(array_key_exists(count($x7)-2,$x7)?$x7[count($x7)-2]:"").".".$x7[count($x7)-1];}
function wcmc_html_escape_allowing_special_tags($string, $echo = true)
{
	$allowed_tags = array('strong' => array(), 
						  'i' => array(), 
						  'bold' => array(),
						  'h4' => array(), 
						  'span' => array('class'=>array(), 'style' => array()), 
						  'br' => array(), 
						  'a' => array('href' => array()),
						  'ol' => array(),
						  'ul' => array(),
						  'li'=> array());
	if($echo) 
		echo wp_kses($string, $allowed_tags);
	else 
		return wp_kses($string, $allowed_tags);
}
function wcmc_var_dump($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
?>
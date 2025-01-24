<?php 
namespace WCMC\classes\admin;

class ActivationPage
{
	var $page;
	var $page_slug;
	var $plugin_name;
	var $plugin_slug;
	var $plugin_id;
	var $plugin_path;
	
	//rplc: woocommerce-multiple-currencies
	public function __construct($page_slug, $plugin_name, $plugin_slug, $plugin_id, $plugin_path)
	{
		$this->page_slug = $page_slug;
		$this->plugin_name = $plugin_name;
		$this->plugin_slug = $plugin_slug;
		$this->plugin_id = $plugin_id;
		$this->plugin_path = $plugin_path;
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action( 'wp_ajax_vanquish_activation_'.$this->plugin_id, array($this, 'process_activation') );
		
		$this->add_page();
	}
	function add_allowed_origins($origins) 
	{
		$origins[] = 'https://vanquishplugins.com';
		return $origins;
	}
	function add_headers_meta()
	{
		header("Access-Control-Allow-Origin: *");
	}
	public function process_activation()
	{
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$domain = isset($_POST['domain']) ? $_POST['domain'] : 'none';
		if($id != 0 && $domain != 'none')
		{
			update_option("_".$id, md5($domain));
		}
		wp_die();
	}
	public function add_page($cap = "manage_woocommerce" )
	{
		if(defined('DOING_AJAX') && DOING_AJAX)
			return;
		$this->page = add_submenu_page( null,
										esc_html__($this->plugin_name.' Activator', 'woocommerce-multiple-currencies'), 
										esc_html__($this->plugin_name.' Activator', 'woocommerce-multiple-currencies'), 
										  $cap, 
										  $this->page_slug, 
										  array($this, 'render_page'));
		
		add_action('load-'.$this->page,  array($this,'page_actions'),9);
		add_action('admin_footer-'.$this->page,array($this,'footer_scripts'));
	}
	function footer_scripts(){
		//To make it work it needs: script_tag postboxes.add_postbox_toggles(pagenow); script_tag
		?>
		
		<?php
	}
	
	function page_actions()
	{
		do_action('add_meta_boxes_'.$this->page, null);
		do_action('add_meta_boxes', $this->page, null);
	}
	public function render_page()
	{
		global $pagenow;
		
		add_screen_option('layout_columns', array('max' => 1, 'default' => 1) );
		
		wp_register_script('vanquish-activator', $this->plugin_path.'/js/vendor/vanquish/activator.js', array('jquery'));
		 $js_settings = array(
				'purchase_code_invalid' => esc_html__( 'Purchase code is invalid!', 'woocommerce-multiple-currencies' ),
				'buyer_invalid' => esc_html__( 'Buyer name is invalid!', 'woocommerce-multiple-currencies' ),
				'item_id_invalid' => esc_html__( 'Item id is invalid!', 'woocommerce-multiple-currencies' ),
				'num_domain_reached' => esc_html__( 'Max number of domains reached! You have to purchase a new license. The current license has been activated in the following domains: ', 'woocommerce-multiple-currencies' ),
				'status_default_message' => esc_html__( 'Verifing, please wait...', 'woocommerce-multiple-currencies' ),
				'db_error' => esc_html__( 'There was an error while verifing the code. Please retry in few minutes!', 'woocommerce-multiple-currencies' ),
				'purchase_code_valid' => esc_html__( 'Activation successfully completed!', 'woocommerce-multiple-currencies' ),
				'empty_fields_error' => esc_html__( 'Buyer and Purchase code fields must be filled!', 'woocommerce-multiple-currencies' ),
				'verifier_url' => 'https://vanquishplugins.com/activator/verify.php'
			);
		wp_localize_script( 'vanquish-activator', 'vanquish_activator_settings', $js_settings );
		wp_enqueue_script('vanquish-activator'); 
		wp_enqueue_script('postbox');
		
		
		wp_enqueue_style('vanquish-activator',  $this->plugin_path.'/css/vendor/vanquish/activator.css');
		
		?>
		<div class="wrap">
			 <?php //screen_icon(); ?>
			<h2><?php esc_html_e($this->plugin_name.' Activator','woocommerce-multiple-currencies'); ?></h2>
	
			<form id="post"  method="post">
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-<?php echo 1 /* 1 == get_current_screen()->get_columns() ? '1' : '2' */; ?>">
						<div id="post-body-content">
						</div>
						
						<div id="postbox-container-1" class="postbox-container">
							<?php do_meta_boxes($this->plugin_slug.'-activator','side',null); ?>
						</div>
						
						<div id="postbox-container-2" class="postbox-container">
							  <?php do_meta_boxes($this->plugin_slug.'-activator','normal',null); ?>
							  <?php do_meta_boxes($this->plugin_slug.'-activator','advanced',null); ?>
							  
						</div> 
					</div> <!-- #post-body -->
				</div> <!-- #poststuff -->
				
			</form>
		</div> <!-- .wrap -->
		<?php 
	}
	
	function add_meta_boxes()
	{
		
		 add_meta_box( 'vanquish_activation', 
					esc_html__('Activation','woocommerce-multiple-currencies'), 
					array($this, 'render_product_fields_meta_box'), 
					$this->plugin_slug.'-activator', 
					'normal' //side
			); 
			
	
	}
	function render_product_fields_meta_box()
	{
		$domain = $_SERVER['SERVER_NAME'];
		$result = get_option("_".$this->plugin_id);
		$result = !$result || $result != md5($domain);
		?>
			<div id="activator_main_container">
				<?php if($result): ?>
					<div id="activation_fields_container">
						<p class="activatior_description">
							<?php _e( 'The plugin can be activate in only <strong>two</strong> domains and they cannot be unregistered. Please enter the following data and hit the activation button', 'woocommerce-multiple-currencies' ); ?>
						</p>
						<div class="fields_blocks_container">
							<div class="inline-container">
								<input type="hidden" id="domain" value="<?php echo $domain;?>"></input>
								<input type="hidden" id="item_id" value="<?php echo $this->plugin_id;?>"></input>
								<label><?php _e( 'Buyer', 'woocommerce-multiple-currencies' ); ?></label>
								<p  class="field_description"><?php _e( 'Insert the Envato username used to purchase the plugin.', 'woocommerce-multiple-currencies' ); ?></p>
								<input type="text" value="" id="input_buyer" class="input_field" placeholder="<?php _e( 'Example: vanquish', 'woocommerce-multiple-currencies' ); ?>"></input>
							</div>
							<div class="inline-container">
								<label><?php _e( 'Purchase code', 'woocommerce-multiple-currencies' ); ?></label>
								<p  class="field_description"><?php _e( 'Insert the purchase code. It can be downloaded from your CodeCanyon "Downloads" profile page.', 'woocommerce-multiple-currencies' ); ?></p>
								<input type="text" value="" class="input_field" id="input_purchase_code" placeholder="<?php _e( 'Example: 7d7c3rt8-f512-227c-8c98-fc53c3b212fe', 'woocommerce-multiple-currencies' ); ?>"></input>
							</div>
							<button class="button button-primary" id="activation_button"><?php _e( 'Activate', 'woocommerce-multiple-currencies' ); ?></button>
						</div>
						<div id="status"><?php _e( 'Verifing, please wait...', 'woocommerce-multiple-currencies' ); ?></div>
					</div>
				<?php else: ?>
					<p class="activatior_description"><?php _e( 'The plugin has been successfully activated!', 'woocommerce-multiple-currencies' ); ?></p>
				<?php endif; ?>
			</div>
		<?php
	}
	
	function render_save_button_meta_box()
	{
	
	}
}
?>
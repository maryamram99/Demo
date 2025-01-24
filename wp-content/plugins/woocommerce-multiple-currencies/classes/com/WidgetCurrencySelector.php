<?php 

// Register and load the widget
function wcmc_load_currency_selector_widget() {
    register_widget( 'wcmc_currency_selector_widget' );
}
add_action( 'widgets_init', 'wcmc_load_currency_selector_widget' );
 
// Creating the widget 
class wcmc_currency_selector_widget extends WP_Widget 
{
 
	function __construct() 
	{
		parent::__construct(
		 
		// Base ID of your widget
		'wcmc_currency_selector_widget', 
		 
		// Widget name will appear in UI
		esc_html__('WooCommerce Currency Selector', 'woocommerce-multiple-currencies'), 
		 
		// Widget description
		array( 'description' => esc_html__( 'Currency selector', 'woocommerce-multiple-currencies' ) ) 
		);
	}
	 
	// Creating widget front-end
	 
	public function widget( $args, $instance ) 
	{
		global $wcmc_html_model;
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		 
		$wcmc_html_model->render_currency_controller(true);
		
		
		echo $args['after_widget'];
	}
			 
	// Widget Backend 
	public function form( $instance ) 
	{
		if ( isset( $instance[ 'title' ] ) ) 
		{
		$title = $instance[ 'title' ];
		}
		else {
		$title = esc_html__( 'Select currency', 'woocommerce-multiple-currencies' );
		}
		// Widget admin form
		?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<?php 
	}
		 
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
} // Class wpb_widget ends here
?>
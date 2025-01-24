<?php
/**
 * Required plugins
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$junotoys_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'junotoys' ),
	'page_builders' => esc_html__( 'Page Builders', 'junotoys' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'junotoys' ),
	'socials'       => esc_html__( 'Socials and Communities', 'junotoys' ),
	'events'        => esc_html__( 'Events and Appointments', 'junotoys' ),
	'content'       => esc_html__( 'Content', 'junotoys' ),
	'other'         => esc_html__( 'Other', 'junotoys' ),
);
$junotoys_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'junotoys' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'junotoys' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $junotoys_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'junotoys' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'junotoys' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $junotoys_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'junotoys' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'junotoys' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $junotoys_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'junotoys' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'junotoys' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $junotoys_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'junotoys' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'junotoys' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'woocommerce.png',
		'group'       => $junotoys_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'junotoys' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'junotoys' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'elegro-payment.png',
		'group'       => $junotoys_theme_required_plugins_groups['ecommerce'],
	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'junotoys' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'junotoys' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $junotoys_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'junotoys' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'junotoys' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $junotoys_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $junotoys_theme_required_plugins_groups['events'],
	),
	'quickcal'                     => array(
		'title'       => esc_html__( 'QuickCal', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'quickcal.png',
		'group'       => $junotoys_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $junotoys_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'junotoys' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'junotoys' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $junotoys_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => junotoys_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $junotoys_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'logo'        => junotoys_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $junotoys_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => junotoys_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $junotoys_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => junotoys_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $junotoys_theme_required_plugins_groups['ecommerce'],
	),
	'woo-smart-quick-view'                  => array(
		'title'       => esc_html__( 'WPC Smart Quick View for WooCommerce', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => junotoys_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.png' ),
		'group'       => $junotoys_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => junotoys_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $junotoys_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $junotoys_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'junotoys' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'revslider.png',
		'group'       => $junotoys_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'junotoys' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'junotoys' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $junotoys_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'junotoys' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'junotoys' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $junotoys_theme_required_plugins_groups['other'],
	),
	'gdpr-framework'         => array(
		'title'       => esc_html__( 'The GDPR Framework', 'junotoys' ),
		'description' => esc_html__( "Tools to help make your website GDPR-compliant. Fully documented, extendable and developer-friendly.", 'junotoys' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'gdpr-framework.png',
		'group'       => $junotoys_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'junotoys' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'junotoys' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $junotoys_theme_required_plugins_groups['other'],
	),
);

if ( JUNOTOYS_THEME_FREE ) {
	unset( $junotoys_theme_required_plugins['js_composer'] );
	unset( $junotoys_theme_required_plugins['booked'] );
	unset( $junotoys_theme_required_plugins['quickcal'] );
	unset( $junotoys_theme_required_plugins['the-events-calendar'] );
	unset( $junotoys_theme_required_plugins['calculated-fields-form'] );
	unset( $junotoys_theme_required_plugins['essential-grid'] );
	unset( $junotoys_theme_required_plugins['revslider'] );
	unset( $junotoys_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $junotoys_theme_required_plugins['trx_updater'] );
	unset( $junotoys_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
junotoys_storage_set( 'required_plugins', $junotoys_theme_required_plugins );

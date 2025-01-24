<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'junotoys_essential_grid_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'junotoys_essential_grid_theme_setup9', 9 );
	function junotoys_essential_grid_theme_setup9() {
		if ( junotoys_exists_essential_grid() ) {
			add_action( 'wp_enqueue_scripts', 'junotoys_essential_grid_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_essential_grid', 'junotoys_essential_grid_frontend_scripts', 10, 1 );
			add_filter( 'junotoys_filter_merge_styles', 'junotoys_essential_grid_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'junotoys_filter_tgmpa_required_plugins', 'junotoys_essential_grid_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'junotoys_essential_grid_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('junotoys_filter_tgmpa_required_plugins',	'junotoys_essential_grid_tgmpa_required_plugins');
	function junotoys_essential_grid_tgmpa_required_plugins( $list = array() ) {
		if ( junotoys_storage_isset( 'required_plugins', 'essential-grid' ) && junotoys_storage_get_array( 'required_plugins', 'essential-grid', 'install' ) !== false && junotoys_is_theme_activated() ) {
			$path = junotoys_get_plugin_source_path( 'plugins/essential-grid/essential-grid.zip' );
			if ( ! empty( $path ) || junotoys_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => junotoys_storage_get_array( 'required_plugins', 'essential-grid', 'title' ),
					'slug'     => 'essential-grid',
					'source'   => ! empty( $path ) ? $path : 'upload://essential-grid.zip',
					'version'  => '2.2.4.2',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'junotoys_exists_essential_grid' ) ) {
	function junotoys_exists_essential_grid() {
		return defined( 'EG_PLUGIN_PATH' ) || defined( 'ESG_PLUGIN_PATH' );
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'junotoys_essential_grid_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'junotoys_essential_grid_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_essential_grid', 'junotoys_essential_grid_frontend_scripts', 10, 1 );
	function junotoys_essential_grid_frontend_scripts( $force = false ) {
		junotoys_enqueue_optimized( 'essential_grid', $force, array(
			'css' => array(
				'junotoys-essential-grid' => array( 'src' => 'plugins/essential-grid/essential-grid.css' ),
			)
		) );
	}
}

// Merge custom styles
if ( ! function_exists( 'junotoys_essential_grid_merge_styles' ) ) {
	//Handler of the add_filter('junotoys_filter_merge_styles', 'junotoys_essential_grid_merge_styles');
	function junotoys_essential_grid_merge_styles( $list ) {
		$list[ 'plugins/essential-grid/essential-grid.css' ] = false;
		return $list;
	}
}

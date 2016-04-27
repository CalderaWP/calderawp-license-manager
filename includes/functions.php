<?php
/**
 * CalderaWP licensing helper functions
 *
 * @package   calderawp_license_manager
 * @author    David Cramer for CalderaWP LLC<David@CalderaWP.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 David Cramer for CalderaWP LLC<David@CalderaWP.com>
 */

/**
 * Register a plugin as a licence product.
 *
 * @since 0.0.1
 *
 */
function cwp_license_manager_register_licensed_product( $params ){

	$defaults = array(
		'type'	=>	'plugin'
	);

	$params = array_merge( $defaults, (array) $params );

	$register = CalderaWP_License_Manager::get_instance();
	$register->register_product( $params );

}

function cwp_license_manager_is_product_licensed( $plugin ){
	$calderawp_license_manager = CalderaWP_License_Manager_Options::get_single( 'calderawp_license_manager' );
	if( empty( $calderawp_license_manager ) || !isset( $calderawp_license_manager['licensed'] ) ){
		return false;
	}
	return in_array( $plugin, $calderawp_license_manager['licensed'] );
}


/**
 * Get featured plugins via API
 */
add_action( 'wp_ajax_cwp_license_manager_featured', function() {
	$key = 'cwp_license_manager_featured_api_feed';
	if ( false == ( $data = get_transient( $key ) ) ) {
		$data = CalderaWP_License_Manager_Feed::get_data( 'products/featured' );

		set_transient( $data, HOUR_IN_SECONDS );
	}

	die( $data );
});

add_action( 'wp_ajax_cwp_license_manager_signups', function(){
	$key = 'cwp_license_manager_signups_api_feed';
	if ( false == ( $data = get_transient( $key ) ) ) {
		$data = CalderaWP_License_Manager_Feed::get_data( 'util' );
		die( $data );

		set_transient( $data, HOUR_IN_SECONDS );
	}

	die( $data );
});

/**
 * Load dismissable notices -- functions.php has logic to prevent double load
 *
 * @uses "plugins_loaded"
 *
 * @since 1.2.1
 */
function cwp_license_manager_load_dismissible_notices(){
	include_once( dirname( __FILE__ ) . '/dismissible-notice/functions.php' );
}

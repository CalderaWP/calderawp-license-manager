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

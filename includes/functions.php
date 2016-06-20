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
 * Load dismissable notices -- functions.php has logic to prevent double load
 *
 * @uses "plugins_loaded"
 *
 * @since 1.2.1
 */
function cwp_license_manager_load_dismissible_notices(){
	include_once( dirname( __FILE__ ) . '/dismissible-notice/functions.php' );
}



function cwp_license_manager_install_plugin( $download_link ) {


	include_once ABSPATH . 'wp-admin/includes/admin.php';
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	include_once ABSPATH . 'wp-includes/update.php';
	require_once ( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
	include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

	$upgrader = new Plugin_Upgrader();

	$result = $upgrader->install( $download_link );
	if ( is_wp_error( $result ) ){
		return $result;
	} elseif ( ! $result ) {
		return new WP_Error( 'plugin-install', __( 'Unknown error installing plugin.', 'calderawp-license-manager' ) );
	}


	return true;
	
}


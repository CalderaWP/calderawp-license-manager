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


function cwp_license_manager_response( $code = 302, $type, $message = '', $error = false ){
	status_header( $code );
	$url = \calderawp\licensemanager\ui\ui::tab_url( $type );
	if( ! empty( $message ) ){
		$url = add_query_arg( 'cwp-lm-message', urlencode( $message ), $url );
	}
	
	if( $error ){
		$url = add_query_arg( 'cwp-lm-error', 1, $url );
	}

	cwp_license_manager_redirect(  $url );
	exit;
}


function cwp_license_manager_redirect( $location, $status = 302 ) {
	if ( ! headers_sent() ) {
		wp_redirect( $location, $status );
		die();
	}else {
		die( '<script type="text/javascript">'
		     . 'document.location = "' . str_replace( '&amp;', '&', esc_js( $location ) ) . '";'
		     . '</script>' );
	}

}

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


/**
 * Check if plugin has license active, if not return false and we will throw a nag
 *
 * @since 0.0.1
 *
 * @param $plugin
 *
 * @return bool
 */
function cwp_license_manager_is_product_licensed( $plugin ){
	return   CalderaWP_License_Manager::get_instance()->is_license_active( $plugin );


}

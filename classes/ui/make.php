<?php
/**
 * Make the admin UI go
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager\ui;


use calderawp\licensemanager\base;

class make extends base{

	public static  function menu_parent(){
		add_thickbox();
		if( defined( '-CFCORE_VER' ) ) {
			$page = 'caldera-forms';
		}else {
			$page = 'options-general.php';
		}

		return $page;

	}

	public static function the_ui(){
		include CALDERA_WP_LICENSE_MANAGER_PATH . '/ui/main.php';
	}
	
	public  function register_script(){
		wp_register_script( self::plugin_slug, CALDERA_WP_LICENSE_MANAGER_URL . 'assets/js/admin.js', array( 'jquery' ) );
		wp_localize_script( self::plugin_slug, 'CWP_LM', array(
			'tabs' => array_keys( ui::tabs() ),
			'nonce' => wp_create_nonce( self::plugin_slug ),
			'api' => esc_url_raw( rest_url( 'cwp-lm/v2' ) ),
			'rest_nonce' => wp_create_nonce( 'wp_rest' )
		));
	}

	public  function ajax_tab(){
		if( isset( $_GET[ 'nonce' ] ) && wp_verify_nonce( $_GET[ 'nonce' ], self::plugin_slug ) ){
			if( current_user_can( 'manage_options' ) ){
				if( file_exists( CALDERA_WP_LICENSE_MANAGER_PATH . '/ui/' . $_GET[ 'tab' ] . '.php' ) ){
					ob_start();
					include CALDERA_WP_LICENSE_MANAGER_PATH . '/ui/' . $_GET[ 'tab' ] . '.php';
					return ob_get_clean();
				}

			}

		}
		
	}

}
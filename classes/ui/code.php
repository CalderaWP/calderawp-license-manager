<?php
/**
 * Handler for updating license key store via HTTP requests
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
namespace calderawp\licensemanager\ui;


class code {

	CONST CODE_ARG = 'cwp-lm-license-code';

	CONST NAME_ARG = 'cwp-lm-license-code-name';
	
	const ACTION_ARG = 'cwp-lm-license-action';

	CONST NONCE_ARG = 'cwp-lm-license-code-nonce';

	/**
	 * Handle license writing activation/ deactivation option
	 *
	 * @uses "admin_init"
	 *
	 * @since 2.0.0
	 */
	public static function handle(){
		if( ! isset( $_GET[ self::ACTION_ARG ] ) || ! isset( $_GET[ self::NONCE_ARG ] ) ){
			cwp_license_manager_response( 302, 'account', __( 'Error with license activation or deactivation.', 'calderawp-license-manager' ) );
		}
		
		if( ! wp_verify_nonce( $_GET[ self::NONCE_ARG ], self::CODE_ARG ) ){
			cwp_license_manager_response( 302, 'account', __( 'Error with license activation or deactivation.', 'calderawp-license-manager' ) );
		}
		
		
		if( 'activate' == $_GET[ self::ACTION_ARG ] && isset( $_GET[ self::CODE_ARG ] ) ){
			$activated = \CalderaWP_License_Manager::get_instance()->activate_license( strip_tags( $_GET[ self::NAME_ARG ] ), strip_tags( $_GET[ self::CODE_ARG ] ) );
			if( $activated ){
				cwp_license_manager_response( 302, 'account', __( 'Plugin license activated', 'calderawp-license-manager' ) );
			}
		}elseif ( 'deactivate' == $_GET[ self::ACTION_ARG ] ){
			$deactivated = \CalderaWP_License_Manager::get_instance()->deactivate_license( strip_tags( $_GET[ self::NAME_ARG ] ) );
			if( $deactivated ){
				cwp_license_manager_response( 302, 'account', __( 'Plugin license deactivated', 'calderawp-license-manager' ) );
			}
		}else{
			cwp_license_manager_response( 302, 'account', __( 'Error with license activation or deactivation.', 'calderawp-license-manager' ) );
		}
		
		
	}

	/**
	 * Create activation/deactivate links
	 *
	 * @since 2.0.0
	 *
	 * @param string $code License code
	 * @param string $plugin_name Name of plugin
	 * @param string $action activate\deactivate
	 *
	 * @return string
	 */
	public static function link( $code, $plugin_name, $action = 'activate' ){
		if( ! in_array( $action, array( 'activate', 'deactivate' ) ) ){
			$action = 'activate';
		}
		
		return add_query_arg(
			array(
				self::ACTION_ARG => $action,
				self::CODE_ARG  => urlencode( $code ),
				self::NAME_ARG  => urlencode( $plugin_name ),
				self::NONCE_ARG => wp_create_nonce( self::CODE_ARG )
			),
			admin_url()
		);

	}
	
}
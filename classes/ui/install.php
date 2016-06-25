<?php
/**
 * Handler for plugin installation HTTP requests
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager\ui;


use calderawp\licensemanager\lm;

class install {
	
	const ARG = 'cwp-lm-install';

	const DOWNLOAD_ARG = 'cwp-lm-install-download';
	
	const NONCE_ARG = 'cwp-lm-install-nonce';

	const CODE_ARG = 'cwp-lm-code';

	/**
	 * @param int $input For first request is license ID -- for second is file for download
	 * @param bool|int  $download_id  Download ID
	 * @param bool|string $nonce Nonce
	 */
	public static function do_install( $input, $download_id = false, $nonce = false ){
		if( false == $nonce && isset( $_GET[ self::NONCE_ARG ]) ) {
			$nonce = $_GET[ self::NONCE_ARG ];
			
		}

		if( false == $download_id && isset( $_GET[ self::DOWNLOAD_ARG ] ) ){
			$download_id = absint( $_GET[ self::DOWNLOAD_ARG ] );
		}

		if( wp_verify_nonce( $nonce, self::ARG ) ){
			//check if we have license ID not file (license id of 1 is a sign that we had a problem with URL encode on file path.
			if( is_numeric( $input ) && 1 < absint( $input ) ){
				$license = lm::get_instance()->plugin->get_license( $input );
				
				if( isset( $_GET[ self::CODE_ARG ] ) ){
					$code = trim( strip_tags( $_GET[ self::CODE_ARG ] ) );
				}elseif( is_object( $license ) ){
					$code = $license->code;
				}else{
					$code = false;
				}
				
				$file = self::get_download_link( $input, $download_id );
				if( filter_var( $file, FILTER_VALIDATE_URL ) ){
					
					wp_safe_redirect( self::link( $file, $download_id, true, $code )  );
					exit;
				}else{
					cwp_license_manager_response( 403, esc_html__( 'Could not install. Please try again.', 'calderawp-license-manager' ), true );
					exit;
				}
			//valid install file?
			}elseif ( filter_var( $input, FILTER_VALIDATE_URL ) ){
				$installed = self::install_plugin( $input );
				if( $installed ) {
					activate_plugin( $installed );
					if ( isset( $_GET[ self::CODE_ARG ] ) ) {
						$plugin = lm::get_instance()->plugin->get_plugin_by_id( 607 );
						
						if ( is_object( $plugin ) ) {
							$activation_link = code::link( $_GET[ self::CODE_ARG ], $plugin->name, 'activate' );
							if( filter_var( $activation_link, FILTER_VALIDATE_URL ) ){
								cwp_license_manager_redirect( $activation_link );
							}
						}

					}

				}else{
					wp_die( $installed );
				}
			}else{
				$x = 1;
			}
		}else{
			cwp_license_manager_response( 403, 'caldera-forms', esc_html__( 'Could not install. Please try again.', 'calderawp-license-manager' ), true );
			exit;
		}
		
		
	}

	/**
	 * Create install link
	 *
	 * @since 2.0.0
	 *
	 * @param int|string $input ID of license ($file should be false) or File URL ($file should be true)
	 * @param int $download_id Download ID
	 * @param bool $file Optional. If is link for file. Default is false
	 * @param string|bool Optional. License code. Default is false.
	 *
	 * @return string
	 */
	public static function link( $input, $download_id, $file = false, $licence_code = false ){
		if( $file ){
			$input = urlencode( $input );
		}else{
			$input = absint( $input );
		}

		$args = array(
			self::ARG          => $input,
			self::NONCE_ARG    => self::nonce(),
			self::DOWNLOAD_ARG => absint( $download_id )
		);

		if( $licence_code ){
			$args[ self::CODE_ARG ] = urlencode( $licence_code );
		}
		
		return add_query_arg( $args, admin_url() );
	}

	/**
	 * Create a nonce for these requests
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public static function nonce(){
		return wp_create_nonce( self::ARG );
	}

	/**
	 * Install a plugin
	 *
	 * @since 2.0.0
	 *
	 * @param string $download_link Link to zip to install from
	 *
	 * @return bool|\WP_Error|string If installed should return basename of plugin for activation.
	 */
	public static function install_plugin( $download_link ) {
		include_once ABSPATH . 'wp-admin/includes/admin.php';
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		include_once ABSPATH . 'wp-includes/update.php';
		require_once ( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$upgrader =  new \Plugin_Upgrader();

		$result = $upgrader->install( $download_link );

		if ( is_wp_error( $result ) ){
			return $result;
		} elseif ( ! $result ) {
			return new \WP_Error( 'plugin-install', __( 'Unknown error installing plugin.', 'calderawp-license-manager' ) );
		}

		$plugins = get_plugins();
		foreach ( $plugins as $basename => $plugin ){
			$parts = explode( '/', $basename );
			if( $upgrader->result[ 'destination_name'] == $parts[0] ){
				return $basename;
			}
		}
		
		return true;

	}

	/**
	 * Get download link from remote API
	 *
	 * @since 2.0.0
	 *
	 * @param $license_id
	 * @param $download_id
	 *
	 * @return mixed
	 */
	public static function get_download_link( $license_id, $download_id ){
		$api = lm::get_instance()->sl_api;
		$error = esc_html__( 'Could not install. Please try again.', 'calderawp-license-manager' );
		$r = $api->activate_license( $license_id, home_url(), $download_id );
		if( is_object( $r ) && ! is_wp_error( $r ) && property_exists( $r, 'success' ) && $r->success ){
			$r = $api->get_file( $license_id, home_url(), $download_id );
			if( is_object( $r ) && ! is_wp_error( $r ) && property_exists( $r, 'link' ) ){
				return $r->link;
			}elseif ( is_string( $error ) ){
				$error = $r;
			}

		}elseif ( is_string( $r ) ){
			$error = $r;
		}

		cwp_license_manager_response( 403, 'caldera-forms', $error, true );
		exit;

	}

}
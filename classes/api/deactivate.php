<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/23/16
 * Time: 6:23 PM
 */

namespace calderawp\licensemanager\api;


use calderawp\licensemanager\lm;

class deactivate {

	const ARG = 'cwp-lm-deactivate';

	const DOWNLOAD_ARG = 'cwp-lm-deactivate-download';

	const SITE_ARG = 'cwp-lm-site-arg';
	
	const NONCE_ARG = 'cwp-lm-deactivate-nonce';

	/**
	 * Do a deactivation of license
	 * 
	 * @param bool $license_id
	 * @param bool $download_id
	 * @param bool $site_url
	 *
	 * @return bool
	 */
	public static function do_deactivation( $license_id = false, $download_id = false, $site_url = false ){
		if ( wp_verify_nonce( $_GET[ self::NONCE_ARG ], self::ARG ) ) {
			if ( false == $license_id && isset( $_GET[ self::ARG ] ) ) {
				$license_id = absint( $_GET[ self::ARG ] );
			}

			if ( false == $download_id && isset( $_GET[ self::DOWNLOAD_ARG ] ) ) {
				$download_id = absint( $_GET[ self::DOWNLOAD_ARG ] );
			}

			if ( false == $site_url && isset( $_GET[ self::SITE_ARG ] ) ) {
				$site_url = esc_url_raw( $_GET[ self::SITE_ARG ] );
			}


			if ( ! $license_id || ! $download_id || ! $site_url ) {
				cwp_license_manager_response( 500, 'account', __( 'Error, Could Not Deactivate', 'calderawp-license-manger' ), true );
				exit;
			}

			$deactivated = self::deactivate( $license_id, $download_id, $site_url );

			if ( true == $deactivated  ) {
				cwp_license_manager_response( 200, 'account', __( 'License Deactivated', 'calderawp-license-manger' ), true );

			} else {
				cwp_license_manager_response( 500, 'account', __( 'Error, Could Not Deactivate', 'calderawp-license-manger' ), true );

			}

			exit;
		}else{
			cwp_license_manager_response( 500, 'account', __( 'Error, Could Not Deactivate', 'calderawp-license-manger' ), true );
		}
		
	}

	/**
	 * Create a deactivation link
	 * 
	 * @param $license_id
	 * @param $download_id
	 * @param $site_url
	 *
	 * @return string
	 */
	public static function link( $license_id, $download_id, $site_url ){
		$args = array(
			self::ARG => absint( $license_id ),
			self::DOWNLOAD_ARG => absint( $download_id ),
			self::SITE_ARG => urlencode( $site_url ),
			self::NONCE_ARG => self::nonce()
		);

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
	 * Deactivate a license
	 * 
	 * @since 2.0.0
	 * 
	 * @param int $license_id
	 * @param int $download_id
	 * @param string $site_url
	 *
	 * @return bool
	 */
	protected static function deactivate( $license_id, $download_id, $site_url ){
	
		

		if( ! $license_id || ! $download_id || ! $site_url ){
			cwp_license_manager_response( 500, 'account', __( 'Error, Could Not Deactivate', 'calderawp-license-manger'), true );
			exit;
		}
		
		$deactivated = lm::get_instance()->sl_api->deactivate_license( $license_id, $site_url, $download_id );


		return $deactivated;
	}
	
	

}
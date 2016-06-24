<?php
/**
 * EDD SL API client
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager\api;


class sl extends base {

	protected $endpoint = 'edd-sl-api/v1';

	/**
	 * Get all licensed plugins for current user
	 *
	 * @since 2.0.0
	 *
	 * @param bool $details
	 *
	 * @return int|object|string
	 */
	public function get_licensed( $details = true ){
		$url = $this->endpoint . '/licenses';
		if( $details ){
			$url = add_query_arg( 'return', 'full', $url );
		}

		$plugins =  $this->request( $url );

		return $plugins;
	}

	/**
	 * Activate a license
	 *
	 * @since 2.0.0
	 *
	 * @param int $license_id ID of license
	 * @param string $url URL of site
	 * @param int $download_id Download ID
	 *
	 * @return array|int|mixed|string|\WP_Error
	 */
	public function activate_license( $license_id, $url, $download_id ){
		return $this->update_license( $license_id, $url, $download_id, true );
	}

	/**
	 * Deactivate a license
	 *
	 * @since 2.0.0
	 *
	 * @param int $license_id ID of license
	 * @param string $url URL of site
	 * @param int $download_id Download ID
	 *
	 * @return array|int|mixed|string|\WP_Error
	 */
	public function deactivate_license( $license_id, $url, $download_id ){
		return $this->update_license( $license_id, $url, $download_id, false );
	}

	/**
	 * Get an install file
	 *
	 * @since 2.0.0
	 *
	 * @param int $license_id ID of license
	 * @param string $url URL of site
	 * @param int $download_id Download ID
	 *
	 * @return int|object|string
	 */
	public function get_file( $license_id, $url, $download_id ){
		$endpoint = $this->endpoint . '/licenses/' . absint( $license_id ) . '/file';
		return $this->request( $endpoint, array( 'url' => urlencode( $url ), 'download' => absint( $download_id )  ) );
	}

	/**
	 * Activate or deactivate a license
	 *
	 * @since 2.0.0
	 *
	 * @param int $license_id ID of license
	 * @param string $url URL of site
	 * @param int $download_id Download ID
	 * @param bool $activate Optional. If true, license is activated, if false, license is deactivated.
	 *
	 * @return array|int|mixed|string|\WP_Error
	 */
	protected function update_license( $license_id, $url, $download_id, $activate = true ) {
		$args = array(
			'url'      => urlencode( $url ),
			'download' => absint( $download_id ),
		);

		if( $activate ){
			$args[ 'action' ] = 'activate';
		}else{
			$args[ 'action' ] = 'deactivate';
		}

		$endpoint = $this->endpoint . '/licenses/' . absint( $license_id );

		return $this->request( $endpoint, $args, 'POST' );

	}

}
<?php
/**
 * Get data from CalderaWP.com API
 *
 * @package   CalderaWP_License_Manager
 * @author   Josh Pollock for CalderaWP LLC <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock for CalderaWP LLC <Josh@CalderaWP.com>
 */

class CalderaWP_License_Manager_Feed {

	/**
	 * URL for the API
	 *
	 * @since 1.0.1
	 *
	 * @var string
	 */
	protected static  $api_url = 'https://calderaforms.com/wp-json/calderawp_api/v2/';

	/**
	 * Get the data for the request.
	 *
	 * @param string $endpoint Endpoint for request.
	 * @param null|array $args Optional. Additional query vars to add to request.
	 *
	 * @return string|JSON
	 */
	public static function get_data( $endpoint, $args = null ) {
		$url = self::$api_url . $endpoint;
		if ( is_array( $args ) ) {
			$url = add_query_arg( $args, $url );
		}
		$request = wp_remote_get( $url );
		$data = '';
		if ( ! is_wp_error( $request ) ) {
			$data = wp_remote_retrieve_body( $request );
			$_data[ 'items' ] = json_decode( $data );
			$data = json_encode( $_data );
		}

		return $data;

	}



}

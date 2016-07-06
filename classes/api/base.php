<?php

namespace calderawp\licensemanager\api;

/**
 * Base clase for API client
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
abstract class base {

	/**
	 * JWT Auth token
	 * 
	 * @since 2.0.0
	 * 
	 * @var string|bool
	 */
	protected $token;

	/**
	 * The last status code returned by $this API cleint
	 * 
	 * @since 2.0.0
	 * 
	 * @var int
	 */
	protected $last_status = 0;
	/**
	 * base constructor.
	 *
	 * @since 2.0.0
	 *
	 * 
	 * @param string $root API Root
	 * @param bool|string $token Optional. JWT Auth token. Required for authenticated requests. Default is false.
	 */
	public function __construct( $root, $token = false ){
		$this->root = trailingslashit( $root );
		$this->token = $token;
	}

	/**
	 * Make remote request
	 * 
	 * @since 2.0.0
	 * 
	 * @param string $url Request URL 
	 * @param array $args Optional. Request args. 
	 * @param string $method Optional. Default is 'GET'. Options: GET|POST|DELETE|PUT
	 * @param array $headers Optional. Headers. If token property is set, authorization header is added automatically.
	 *
	 * @return object|string|int
	 */
	public function request( $url, array $args = array(), $method = 'GET', array $headers = array() ){
		$url = $this->root . $url;
		if( ! empty( $this->token ) && ! isset( $headers [ 'Authorization' ]) ){
			$headers = $this->add_auth_header( $headers );
		}
		
		switch( $method ){
			case 'POST' :
				$request = wp_remote_post( $url, array(
					'headers' => $headers,
					'body' => $args,
				));
				break;
			case 'PUT' :
				$request = wp_remote_request( $url, array(
					'headers' => $headers,
					'method' => 'PUT',
					'body' => wp_json_encode( $args ),
				));
				break;
			case 'DELETE' :
				$request = wp_remote_request( $url, array(
					'headers' => $headers,
					'method' => 'DELETE',
					'body' => wp_json_encode( $args ),
				));
				break;
			default :
				if( ! empty( $args ) ){
					$url = add_query_arg( $args, $url );
				}
				$request = wp_remote_get( $url, array(
					'headers' => $headers,
				));
				break;
		}

		if( ! is_wp_error( $request ) ){
			$this->last_status = wp_remote_retrieve_response_code( $request );
			if( 200 == wp_remote_retrieve_response_code( $request ) ) {
				if( ! is_object( $results = json_decode( wp_remote_retrieve_body( $request ) ) ) ){
					$results = wp_remote_retrieve_body( $request );
				}

				return $results;
			}else{
				$body = wp_remote_retrieve_body( $request );
				if( is_string( $body ) && is_object( $json = json_decode( $body ) ) ){
					$body = (array) $json;
				}

				if( isset( $body['error'] ) && ! empty( $body[ 'error' ] ) ){
					return $body[ 'error' ];
				}elseif( isset( $body['message'] ) && ! empty( $body[ 'message' ] ) ){
					return $body[ 'message' ];
				}else{
					return wp_remote_retrieve_response_code( $request );
				}

			}

		}

		$this->last_status = 500;

		return $request;

	}

	/**
	 * Get the status code from last request
	 * 
	 * @since 2.0.0
	 * 
	 * @return int
	 */
	public function get_last_status(){
		return $this->last_status;
	}

	/**
	 * Authorization header
	 * 
	 * BTW: This is in a separate method to decouple subclass from JWT if needed.
	 * 
	 * @since 2.0.0
	 * 
	 * @param array $headers
	 *
	 * @return array
	 */
	protected function add_auth_header( array $headers ) {
		$headers['Authorization'] = 'Bearer ' . $this->token;

		return $headers;
	}
	
}
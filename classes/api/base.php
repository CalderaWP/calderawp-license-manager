<?php

namespace calderawp\licensemanager\api;

/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/18/16
 * Time: 5:00 PM
 */
abstract class base {


	protected $token;

	public function __construct( $root, $token = false ){
		$this->root = trailingslashit( $root );
		$this->token = $token;
	}


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

			if( 200 == wp_remote_retrieve_response_code( $request ) ) {
				$results = wp_remote_retrieve_body( $request );
				return json_decode( $results );
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

		return $request;

	}

	/**
	 * @param array $headers
	 *
	 * @return array
	 */
	protected function add_auth_header( array $headers ) {
		$headers['Authorization'] = 'Bearer ' . $this->token;

		return $headers;
	}


}
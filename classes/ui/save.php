<?php
/**
 * Handle account update
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager\ui;


use calderawp\licensemanager\account\remote_user;
use calderawp\licensemanager\api\auth;
use calderawp\licensemanager\lm;

class save{

	protected $type;

	protected $nonce;

	public function __construct(){
		if(  'account' == $_POST[ 'type' ] ) {

			$this->type = $_POST[ 'type' ];
			$this->nonce = $_POST[ 'cwp-lm-save' ];

			if( ! $this->verify_nonce() ){
				$this->response( 403 );
			}

			
			$this->account();
		}

		$this->response( 500 );
	}

	public function verify_nonce(){
		return wp_verify_nonce( $this->nonce, $this->type );
	}

	public function account(){
		if( isset( $_POST[ 'username' ], $_POST[ 'password' ] ) ){
			$auth = new auth( CALDERA_WP_LICENSE_MANAGER_API );
			$token = $auth->get_token( $_POST[ 'username' ], $_POST[ 'password' ] );
			if( is_object( $token ) ){
				lm::get_instance()->store_token( $token );
				$this->response( 200, esc_html__( 'You are now logged in to CalderaWP', 'calderawp-license-manager' ) );
				

			}elseif( is_string( $token ) ){
				$this->response( 403, esc_html( $token ), true );
			}else{
				$this->response( 403, esc_html__( 'Could not log on to CalderaWP', 'calderawp-license-manager' ), true );
			}
		
			
		}
	}
	
	public function logout(){
		lm::get_instance()->account->logout();
		$this->response( 200, esc_html__( 'You are now logged out of CalderaWP', 'calderawp-license-manager') );
	}
	
	public function response( $code = 200, $message = '', $error = false ){
		return cwp_license_manager_response( $code, $this->type, $message, $error );
	}


}
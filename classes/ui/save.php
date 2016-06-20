<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/18/16
 * Time: 9:09 PM
 */

namespace calderawp\licensemanager\ui;


use calderawp\licensemanager\account\remote_user;
use calderawp\licensemanager\api\auth;
use calderawp\licensemanager\plugin;
use calderawp\licensemanager\plugins;

class save{

	protected $type;

	protected $nonce;

	public function __construct(){
		if(  'logout' != $_POST[ 'type' ] || ! in_array( $_POST[ 'type' ], ui::tabs() )  ){

			$this->type = $_POST[ 'type' ];
			$this->nonce = $_POST[ 'cwp-lm-save' ];

			if( ! $this->verify_nonce() ){
				$this->response( 403 );
			}

			call_user_func( array( $this, $this->type ) );
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
				plugin::get_instance()->account->set_token( $token->token );
				plugin::get_instance()->account->set_token_cookie();
				plugin::get_instance()->account->set_displayname( $token->user_display_name );
				plugin::get_instance()->account->set_displayname_cookie();
				$this->response( 200, esc_html__( 'You are now logged in to CalderaWP', 'calderawp-license-manager' ) );
				

			}elseif( is_string( $token ) ){
				$this->response( 403, esc_html( $token ), true );
			}else{
				$this->response( 403, esc_html__( 'Could not log on to CalderaWP', 'calderawp-license-manager' ), true );
			}
		
			
		}
	}
	
	public function logout(){
		plugin::get_instance()->account->clear_cookies();
		$this->response( 200, esc_html__( 'You are now logged out of CalderaWP', 'calderawp-license-manager') );
	}
	
	public function response( $code = 200, $message = '', $error = false ){
		status_header( 200 );
		$url = ui::tab_url( $this->type );
		if( ! empty( $message ) ){
			$url = add_query_arg( 'cwp-lm-message', urlencode( $message ), $url );
		}
		if( $error ){
			$url = add_query_arg( 'cwp-lm-error', 1, $url );
		}
		
		wp_redirect(  $url );
		exit;
	}


}
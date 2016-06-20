<?php
namespace calderawp\licensemanager\account;
use calderawp\licensemanager\base;

/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/18/16
 * Time: 4:45 PM
 */
class account extends base {

	/**
	 * @var string
	 */
	protected $token;
	
	protected $displayname;



	public function __construct( $token = null ){
		if( is_string( $token ) ){
			$this->token = $token;
		}else{
			$this->get_token_cookie();
		}
		
		if( ! empty( $this->token ) ){
			$this->get_displayname_cookie();
		}
	}
	
	public function set_token( $token ){
		$this->token = $token;
	}
	
	public function set_displayname( $displayname ){
		$this->displayname = $displayname;
	}

	public function get_token(){
		return $this->token;
	}
	
	public function get_displayname(){
		if( ! $this->displayname  ){
			$this->get_displayname_cookie();
		}
		return $this->displayname;
	}
	

	protected function get_token_cookie(){
	
		if( isset( $_COOKIE ) ) {
			$this->token =  isset( $_COOKIE[ self::plugin_slug ]) ? $cookie = $_COOKIE[ self::plugin_slug ] : $cookie = false;
		}
		
		
		
	}

	public function set_token_cookie(){
		setcookie( self::plugin_slug, $this->token, time()+3600*24*100, COOKIEPATH, COOKIE_DOMAIN, false);

	}
	
	public function set_displayname_cookie(){
		if( empty( $this->token ) ){
			return;
		}
		$key = $this->displayname_cookie_key();
		setcookie( $key, $this->displayname, time()+3600*24*100, COOKIEPATH, COOKIE_DOMAIN, false);



	}
	
	protected function get_displayname_cookie(){
		if( empty( $this->token ) ){
			return;
		}

		$key = $this->displayname_cookie_key();
		if( isset( $_COOKIE ) ) {
			$this->displayname =  isset( $_COOKIE[ $key ] ) ? $cookie = $_COOKIE[ $key ] : $cookie = false;
		}

		
	}
	
	public function clear_cookies(){
		$key = $this->displayname_cookie_key();
		if( isset( $_COOKIE, $_COOKIE[ $key ])){
			unset( $_COOKIE[ $key ] );
			$this->displayname = '';
			setcookie( $key, '', time(), COOKIEPATH, COOKIE_DOMAIN, false );
		}
		
		if( isset( $_COOKIE, $_COOKIE[ self::plugin_slug ] ) ){
			unset( $_COOKIE[ self::plugin_slug ] );
			$this->token = '';
			setcookie( self::plugin_slug, '', time(), COOKIEPATH, COOKIE_DOMAIN, false);
			
		}
		
	}

	/**
	 * @return string
	 */
	protected function displayname_cookie_key() {
		$key = self::plugin_slug . md5( $this->token );

		return $key;
	}


}
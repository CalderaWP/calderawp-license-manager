<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/19/16
 * Time: 2:31 PM
 */

namespace calderawp\licensemanager;


use calderawp\licensemanager\account\account;

use calderawp\licensemanager\api\cwp;
use calderawp\licensemanager\api\sl;

class plugin extends base {

	/**
	 * @var sl
	 */
	protected $sl_api;

	/**
	 * @var cwp
	 */
	protected $cwp_api;

	/**
	 * @var plugins
	 */
	protected $plugins;

	/**
	 * @var plugin
	 */
	protected static $instance;
	
	protected $loaded;

	/**
	 * @var account
	 */
	protected $account;
	
	

	public function __construct(){
		$this->init();
	}
	
	public function __get( $prop ){
		if( is_object( $this->$prop ) ){
			return $this->$prop;
		}
	}

	/**
	 * Get main plugin instance
	 * 
	 * Is not a true singleton, but if initialized twice, will need to set props manually.
	 * 
	 * @return plugin
	 */
	public static function get_instance(){
		if( null == self::$instance ){
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public function get_token(){
		$token = $this->account->get_token();
		if ( ! empty($token ) ){
			return $token;
		}
		return false;
	}
	
	public function logged_in(){
		return is_string( $this->get_token() );
	}

	
	public function init(){
		if( true !== $this->loaded ){
			$this->account = new account( null );

			$this->sl_api = new sl( CALDERA_WP_LICENSE_MANAGER_API, $this->account->get_token() );
			$this->cwp_api = new cwp( CALDERA_WP_LICENSE_MANAGER_API, $this->account->get_token()  );

			$this->plugins = new plugins( $this->cwp_api, $this->sl_api );
			$this->loaded = true;
		}
		
	}

}
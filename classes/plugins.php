<?php

namespace calderawp\licensemanager;


use calderawp\licensemanager\api\cwp;
use calderawp\licensemanager\api\sl;

class plugins {

	/**
	 * @var cwp
	 */
	protected $cwp_api;

	/**
	 * @var sl
	 */
	protected $sl_api;

	/**
	 * @var array
	 */
	protected $plugins = array(
		'cf' => array(),
		'search' => array(),
		'licensed' => array(),
		'installed' => array()
	);
	
	

	/**
	 * @var bool string
	 */
	protected $token;
	
	public function __construct( cwp $cwp_api, sl $sl_api ) {
		$this->cwp_api = $cwp_api;
		$this->sl_api = $sl_api;
		
		$this->get_remote();
		$this->get_licensed();
		$this->find_installed_plugins();

	}

	public function get_plugins( $type = 'cf' ){
		if( ! isset( $this->plugins[ $type ] ) ){
			return false;
		}elseif( ! empty( $this->plugins[ $type ] ) ){
			return $this->plugins[ $type ];
		}else{
			return false;
		}

	}
	
	public function get_plugins_array(){
		return $this->plugins;
	}



	

	protected function find_installed_plugins(){
		$this->plugins[ 'installed' ] = get_plugins();
	}


	protected function get_remote(){

		if( false == ( $this->plugins[ 'cf' ]  = get_transient( $this->cache_key( 'xcf' ) ) ) || is_string( $this->plugins[ 'cf' ] ) ){
			$this->plugins[ 'cf' ] = $this->cwp_api->cf_addons();
			if( is_wp_error( $this->plugins[ 'cf' ] ) ){
				$this->plugins[ 'cf' ] = false;
			}else{
				set_transient( $this->cache_key( 'xcf' ), $this->plugins[ 'cf' ], WEEK_IN_SECONDS );
			}


		}

		if ( is_object(  $this->plugins[ 'cf' ] ) ) {
			$this->plugins[ 'cf' ] = (array) $this->plugins[ 'cf' ];
		}

		if( false == ( $this->plugins[ 'search' ]  = get_transient( $this->cache_key( 'psearch' ) ) ) || is_string( $this->plugins[ 'search' ]) ){
			$this->plugins[ 'search' ] = $this->cwp_api->caldera_search();
			if( is_wp_error( $this->plugins[ 'search' ] ) ){
				$this->plugins[ 'search' ] = false;
			}else{
				set_transient( $this->cache_key( 'psearch' ), $this->plugins[ 'search' ], WEEK_IN_SECONDS );
			}


		}

		if( is_object( $this->plugins[ 'search' ] ) ){
			$this->plugins[ 'search' ] = (array) $this->plugins[ 'search' ];
		}




	}

	protected function get_licensed(){
		
		$plugins = $this->sl_api->get_licensed();
		if( ! is_wp_error( $plugins ) && ! is_string( $plugins )  ){

			$this->plugins[ 'licensed' ]  =  $plugins;
			if ( ! empty( $this->plugins[ 'licensed' ] ) ){
				$_plugins = (array) $this->plugins[ 'licensed' ];
				$this->plugins[ 'licensed' ] = array();
				foreach ( $_plugins as $i => $plugin ){
					if( is_array( $plugin ) ){
						$plugin = (object) $plugin;
					}
					$_plugins[ $i ] = new license( $plugin );
				}

				$this->plugins[ 'licensed' ] = $_plugins;
			}
			
		}else{

			$this->plugins[ 'licensed' ]  =  false;
		}
			
		

	}
	
	protected function cache_key( $type ){
		
		if( ! $this->token ){
			return false;
		}
		
		return md5( 'cwplmplugins' . $this->token . $type );
	}




}
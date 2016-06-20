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
	
	protected $names;

	/**
	 * @var bool string
	 */
	protected $token;
	
	public function __construct( cwp $cwp_api, sl $sl_api ) {
		$this->cwp_api = $cwp_api;
		$this->sl_api = $sl_api;
		
		$this->get_remote();
		$this->get_licensed();
		$this->get_installed_plugins();

	}

	public function get_plugins( $cf = true ){
		if( $cf && ! empty( $this->plugins[ 'cf' ] ) ){
			return $this->plugins[ 'cf' ];
		}elseif( ! empty( $this->plugins[ 'search' ] ) ){
			return $this->plugins[ 'search' ];
		}


		return false;
	}

	public function is_installed( $name ){
		if( null == $this->names ){
			$this->set_names( );
		}

		if( empty( $this->names[ 'installed' ] ) ){
			return false;
		}

		if( array_key_exists( $name, $this->names[ 'installed' ] ) ){
			return $this->names[ 'installed' ][ $name ];
		}

		return false;

	}

	public function is_active( $basename ){

	}

	public function activate( $basename ){

	}

	public function install( $slug ){
		$id =  $this->find_id( $slug );
		$file = $this->sl_api->get_file( $id );
	}

	protected function find_id( $slug ){
		return 42;
	}

	protected function get_installed_plugins(){
		$this->plugins[ 'installed' ] = get_plugins();
	}




	protected function get_remote(){

		if( false == ( $this->plugins[ 'cf' ]  = get_transient( $this->cache_key( 'cf' ) ) ) || is_string( $this->plugins[ 'cf' ] ) ){
			$this->plugins[ 'cf' ] = $this->cwp_api->cf_addons();
			if( is_wp_error( $this->plugins[ 'cf' ] ) ){
				$this->plugins[ 'cf' ] = false;
			}else{
				set_transient( $this->cache_key( 'cf' ), $this->plugins[ 'cf' ], WEEK_IN_SECONDS );
			}


		}

		if ( is_object(  $this->plugins[ 'cf' ] ) ) {
			$this->plugins[ 'cf' ] = (array) $this->plugins[ 'cf' ];
		}

		if( false == ( $this->plugins[ 'search' ]  = get_transient( $this->cache_key( 'psearch' ) ) ) || is_string( $this->plugins[ 'search' ]) ){
			$this->plugins[ 'search' ] = $this->cwp_api->caldera_search();
			if( is_wp_error( $this->plugins[ 'search' ] ) ){
				$this->plugins[ 'cf' ] = false;
			}else{
				set_transient( $this->cache_key( 'psearch' ), $this->plugins[ 'search' ], WEEK_IN_SECONDS );
			}


		}

		if( is_object( $this->plugins[ 'cf' ] ) ){
			$this->plugins[ 'cf' ] = (array) $this->plugins[ 'search' ];
		}




	}

	protected function get_licensed(){
		
		$plugins = $this->sl_api->get_licensed();
		if( ! is_wp_error( $plugins ) ){

			$this->plugins[ 'licensed' ]  =  $plugins;
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


	protected function set_names() {
		if( ! empty( $this->plugins[ 'installed' ] ) ){
			$this->names[ 'installed' ] = array_flip( wp_list_pluck( $this->plugins[ 'installed' ], 'Name' ) );
		}else{
			$this->names[ 'installed' ] = array();
		}
		foreach( array( 'cf', 'search' ) as $key ){
			if( ! empty( $this->plugins[ $key ]) ){
				$this->names[ $key ]        = wp_list_pluck( $this->plugins[ 'cf' ], 'name' );
			}else{
				$this->names[ $key ] = array();
			}
		}

	}


}
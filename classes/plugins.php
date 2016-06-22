<?php
/**
 * Organizes data about all plugins
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
namespace calderawp\licensemanager;


use calderawp\licensemanager\api\cwp;
use calderawp\licensemanager\api\sl;

class plugins {

	/*@var cwp */
	protected $cwp_api;

	/** @var sl  */
	protected $sl_api;

	/** @var array  */
	protected $plugins = array(
		'cf' => array(),
		'search' => array(),
		'licensed' => array(),
		'installed' => array()
	);


	/* @var string */
	protected $token;

	/**
	 * plugins constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param cwp $cwp_api CWP API client
	 * @param sl $sl_api EDD SL API client
	 */
	public function __construct( cwp $cwp_api, sl $sl_api ) {
		$this->cwp_api = $cwp_api;
		$this->sl_api = $sl_api;
		
		$this->find_remote();
		$this->find_licensed();
		$this->find_installed_plugins();

	}

	/**
	 * Get plugins of a specific type
	 *
	 * @since 2.0.0
	 *
	 * @param string $type cf|search|installed|licensed
	 *
	 * @return bool|array
	 */
	public function get_plugins( $type = 'cf' ){
		if( ! isset( $this->plugins[ $type ] ) ){
			return false;
		}elseif( ! empty( $this->plugins[ $type ] ) ){
			return $this->plugins[ $type ];
		}else{
			return false;
		}

	}

	/**
	 * Get all collected plugins in one big array
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_plugins_array(){
		return $this->plugins;
	}

	/**
	 * Find the installed plugins
	 *
	 * @since 2.0.0
	 *
	 */
	protected function find_installed_plugins(){
		$this->plugins[ 'installed' ] = get_plugins();
	}

	/**
	 * Collect info about plugins via CWP API
	 *
	 * @since 2.0.0
	 */
	protected function find_remote(){

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

		if( false == ( $this->plugins[ 'search' ]  = get_transient( $this->cache_key( 'psearch' ) ) ) || is_string( $this->plugins[ 'search' ] ) ){
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

	/**
	 * Find licensed plugins via SL API
	 *
	 * @since 2.0.0
	 *
	 */
	protected function find_licensed(){
		if( empty( $this->token ) ){
			$this->plugins['licensed'] = false;
			return;
		}

		if( false == ( $this->plugins[ 'licensed' ]  = get_transient( $this->cache_key( 'licensed' ) ) ) || is_string( $this->plugins[ 'licensed' ] ) ) {
			$plugins = $this->sl_api->get_licensed();
			if ( ! is_wp_error( $plugins ) && ! is_string( $plugins ) ) {

				$this->plugins['licensed'] = $plugins;
				if ( ! empty( $this->plugins['licensed'] ) ) {
					$_plugins                  = (array) $this->plugins['licensed'];
					$this->plugins['licensed'] = array();
					foreach ( $_plugins as $i => $plugin ) {
						if ( is_array( $plugin ) ) {
							$plugin = (object) $plugin;
						}
						$_plugins[ $i ] = new license( $plugin );
					}

					$this->plugins['licensed'] = $_plugins;
					set_transient( $this->cache_key( 'licensed'), $this->plugins[ 'licensed' ], 59 );
				}

			} else {
				$this->plugins['licensed'] = false;
			}

		}

	}

	/**
	 * Construct a cache key
	 *
	 * @since 2.0.0
	 *
	 * @param $type
	 *
	 * @return string
	 */
	protected function cache_key( $type ){
		
		if( ! $this->token ){
			$token = 42;
		}else{
			$token = $this->token;
		}

		
		return md5( 'cwplmplugins' . $token . $type );
	}




}
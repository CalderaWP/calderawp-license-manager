<?php
/**
 * CalderaWP_License_Manager.
 *
 * NOTE: This used to be the main class, In 2.0.0+ just manages the licensing on this site -- IE storing and checking the key store option. Does not interact with remote API in anyway
 *  It is not namespaced because this way we can have backwards compatibility.
 *
 * @package   CalderaWP_License_Manager
 * @author    David Cramer for CalderaWP LLC<David@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 David Cramer for CalderaWP LLC<David@CalderaWP.com>
 */

/**
 * Plugin class.
 * @package CalderaWP_License_Manager
 * @author  David Cramer for CalderaWP LLC<David@CalderaWP.com>
 */
class CalderaWP_License_Manager {



	/**
	 * Holds class isntance
	 *
	 * @since 1.0.0
	 *
	 * @var      object|CalderaWP_License_Manager
	 */
	protected static $instance = null;

	/**
	 * Holds the registered products
	 *
	 * @since 1.0.0
	 *
	 * @var      array
	 */
	protected $products = array();

	

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function __construct() {

		// add edd update check
		add_action( 'calderawp_license_manager_setup_update_check-edd', array( $this, 'edd_update_setup' ) );

		// add EDD actions
		add_action( 'calderawp_license_manager_validate_key-edd', array( $this, 'check_edd_license') );

		// add license
		add_filter( 'calderawp_license_manager_get_single', array( $this, 'populate_plugins_themes') );

	}

	/**
	 * Get a plugin license params for a plugin registered through this class
	 *
	 * @since 2.0.0
	 *
	 * @param string $name Plugin name
	 *
	 * @return array|void License params if found. Void if not.
	 */
	public function get_plugin( $name ){
		if( isset( $this->products[ $name ] ) ){
			return $this->products[ $name ];
		}
	}

	/**
	 * Get all plugin license params for all plugins registered through this class
	 *
	 * @since 2.0.0
	 *
	 * @return array|
	 */
	public function get_products(){
		return $this->products;
	}


	/**
	 * Sets up possible things to update
	 *
	 * @since 1.0.0
	 *
	 * @param $config
	 *
	 * @return mixed
	 */
	public function populate_plugins_themes( $config ){

		if( !empty( $this->products ) ){
			$config['plugins'] = $this->products;
			ksort( $config['plugins'] );
		}
		if( !empty( $this->products['theme'] ) ){
			$config['themes'] = $this->products['theme'];
			ksort( $config['themes'] );
		}

		return $config;
	}


	/**
	 * Handles license activation for plugin RE this site, not remotely.
	 *
	 * @since 2.0.0
	 *
	 * @param string $name name of plugin. Must be in registered by this class.
	 * @param string $license License code.
	 *
	 * @return bool
	 */
	public function activate_license( $name, $license ){
		$plugin = $this->get_plugin( $name );
		if( ! is_object( $plugin ) ){
			return false;
		}
		
		$key_store = $this->key_store( $plugin );
		return update_option( $key_store, $license );
		

	}

	/**
	 * Find option name for a plugin
	 * 
	 * @since 2.0.0
	 *
	 * @param array $plugin
	 *
	 * @return mixed
	 */
	protected function key_store( $plugin ){
		$name = $plugin->name;
		if( ! empty( $this->products[ $name ][ 'key_store' ] ) ){
			return $this->products[ $name ][ 'key_store' ];
		}
		
	}

	/**
	 * Setup plugin updates
	 * 
	 * @since 0.1.0
	 */
	public function setup_updates(){

		if( ! empty( $this->products ) ){
			$plugins = $this->products;

			foreach( $plugins as $plugin_key => $plugin ){

				if( empty( $plugin[ 'updater' ] ) ){
					continue;
				}
				
				// do actions
				do_action( 'calderawp_license_manager_setup_update_check', $plugin );
				do_action( 'calderawp_license_manager_setup_update_check-' . $plugin[ 'updater' ], $plugin );

			}

		}

	}

	/**
	 * Prepare to update via EDD
	 *
	 * @since 0.0.1
	 *
	 * @param array $plugin
	 */
	public function edd_update_setup( $plugin ){

		//get the key
		$plugin['license_key'] = trim( get_option( $this->key_store( $plugin ) ) );
		// setup the updater
		new \calderawp\licensemanager\updater\edd(
			$plugin[ 'url' ],
			$plugin[ 'file' ],
			array(
				'version'	=> $plugin[ 'version' ],
				'license'	=> $plugin[ 'license_key' ],
				'item_name'	=> $plugin[ 'name' ],
				'url'		=> home_url()
			)
		);
	}


	/**
	 * Adds a product to the list of products
	 *
	 * @param array $params License params
	 * 
	 * @since 1.0.0
	 *
	 */
	public function register_product( $params ) {

		// needs at least name, url and key_store
		if( empty( $params['name'] ) || empty( $params['url'] ) ){
			return;
		}

		if( ! isset( $this->products[ $params['name'] ] ) ){
			$this->products[ $params[ 'name' ] ] = $params;
		}

	}

	/**
	 * Check if license for plugin is active locally
	 * 
	 * @since 2.0.0
	 * 
	 * @param $plugin
	 *
	 * @return bool
	 */
	public function is_license_active( $plugin ){
		if( is_string( $plugin ) ){
			$plugin = $this->get_plugin( $plugin );
		}
		
		return is_array( $plugin ) && is_string( get_option( $this->key_store( $plugin ) ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object|CalderaWP_License_Manager    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	

}















<?php
/**
 * Base class -- the master singleton
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager;


use calderawp\licensemanager\account\account;

use calderawp\licensemanager\api\cwp;
use calderawp\licensemanager\api\deactivate;
use calderawp\licensemanager\api\sl;
use calderawp\licensemanager\ui\install;

class lm extends base {

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
	 * @var lm
	 */
	protected static $instance;
	
	/** @var  bool */
	protected $loaded;

	/**
	 * @var account
	 */
	protected $account;

	/**
	 * @var plugin
	 */
	protected $plugin;

	/**
	 * __construct
	 * 
	 * @since 2.0.0
	 */
	public function __construct(){
		$this->init();
		
	}

	/**
	 * Allow for getting objects of other classes contained in this object
	 * 
	 * @since 2.0.0
	 * 
	 * @param string $prop Property name
	 *
	 * @return object
	 */
	public function __get( $prop ){
		if( is_object( $this->$prop ) ){
			return $this->$prop;
		}
	}

	/**
	 * Listen for our various requests
	 * 
	 * @since 2.0.0
	 * 
	 * @uses "admin_init"
	 */
	public function listeners(){

		if( isset( $_GET[ install::ARG ] ) ){
			install::do_install( $_GET[ install::ARG ] );
		}elseif( isset( $_POST, $_POST[ 'cwp-lm-save' ] ) ){
			new \calderawp\licensemanager\ui\save();
		}elseif( isset( $_GET[ deactivate::ARG ] ) ){
			deactivate::do_deactivation();
		}
	}

	/**
	 * Get main plugin instance
	 * 
	 * Is not a true singleton, but if initialized twice, will need to set props manually.
	 * 
	 * @since 2.0.0
	 * 
	 * @return lm
	 */
	public static function get_instance(){
		if( null == self::$instance ){
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	/**
	 * Get auth token
	 * 
	 * @since 2.0.0
	 * 
	 * @return bool|string
	 */
	public function get_token(){
		$token = $this->account->get_token();
		if ( ! empty($token ) ){
			return $token;
		}
		return false;
	}

	/**
	 * Are we logged in to CalderaWP?
	 * 
	 * @since 2.0.0
	 * 
	 * @return bool
	 */
	public function logged_in(){
		return is_string( $this->get_token() );
		
	}

	/**
	 * Init if not already
	 * 
	 * @since 2.0.0
	 */
	public function init(){
		if( true !== $this->loaded ){
			add_action( 'admin_init', array( $this, 'listeners' ) );
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$this->account = new account( null );

			$this->sl_api = new sl( CALDERA_WP_LICENSE_MANAGER_API, $this->account->get_token() );
			$this->cwp_api = new cwp( CALDERA_WP_LICENSE_MANAGER_API, $this->account->get_token()  );

			$this->plugins = new plugins( $this->cwp_api, $this->sl_api, $this->account->get_token() );
			$this->plugin = new plugin( $this->plugins );

			\CalderaWP_License_Manager::get_instance();

			$this->loaded = true;
		}
		
	}

}
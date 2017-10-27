<?php
/**
 * CalderaWP_License_Manager Setting.
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
class CalderaWP_License_Manager_Settings extends CalderaWP_License_Manager{


	/**
	 * Constructor for class
	 *
	 * @since 1.0.0
	 */
	public function __construct(){

		// add admin page
		add_action( 'admin_menu', array( $this, 'add_settings_pages' ), 25 );
		// save config
		add_action( 'wp_ajax_blkbr_save_config', array( $this, 'save_config') );

	}


	/**
	 * Saves a config
	 *
	 * @uses "wp_ajax_blkbr_save_config" hook
	 *
	 * @since 0.0.1
	 */
	public function save_config(){

		if( empty( $_POST[ 'calderawp_license_manager-setup' ] ) || ! wp_verify_nonce( $_POST[ 'calderawp_license_manager-setup' ], 'calderawp_license_manager' ) ){
			if( empty( $_POST['config'] ) ){
				return;
			}
		}

		if( !empty( $_POST[ 'calderawp_license_manager-setup' ] ) && empty( $_POST[ 'config' ] ) ){
			$config = stripslashes_deep( $_POST['config'] );

			set_site_transient( 'update_plugins', null );
			
			CalderaWP_License_Manager_Options::update( $config );

			wp_redirect( '?page=calderawp_license_manager&updated=true' );
			exit;
		}

		if( !empty( $_POST['config'] ) ){

			set_site_transient( 'update_plugins', null );
			
			$config = json_decode( stripslashes_deep( $_POST['config'] ), true );

			if(	wp_verify_nonce( $config['calderawp_license_manager-setup'], 'calderawp_license_manager' ) ){
				CalderaWP_License_Manager_Options::update( $config );
				wp_send_json_success( $config );
			}

		}

		// nope
		wp_send_json_error( $config );

	}

	/**
	 * Array of "internal" fields not to mess with
	 *
	 * @since 0.0.1
	 *
	 * @return array
	 */
	public function internal_config_fields() {
		return array( '_wp_http_referer', 'id', '_current_tab' );
	}


	/**
	 * Deletes an item
	 *
	 *
	 * @uses 'wp_ajax_blkbr_create_calderawp_license_manager' action
	 *
	 * @since 0.0.1
	 */
	public function delete_calderawp_license_manager(){

		$deleted = CalderaWP_License_Manager_Options::delete( strip_tags( $_POST['block'] ) );

		if ( $deleted ) {
			wp_send_json_success( $_POST );
		}else{
			wp_send_json_error( $_POST );
		}



	}

	/**
	 * Create a new item
	 *
	 * @uses "wp_ajax_blkbr_create_calderawp_license_manager"  action
	 *
	 * @since 0.0.1
	 */
	public function create_new_calderawp_license_manager(){
		$new = CalderaWP_License_Manager_Options::create( $_POST[ 'name' ], $_POST[ 'slug' ] );

		if ( is_array( $new ) ) {
			wp_send_json_success( $new );
		}else {
			wp_send_json_error( $_POST );
		}

	}


	/**
	 * Add options page
	 *
	 * @since 1.0.0
	 *
	 * @uses "admin_menu" hook
	 */
	public function add_settings_pages(){

		if ( defined( 'CFCORE_VER' ) ) {
			$this->plugin_screen_hook_suffix['calderawp_license_manager'] = add_submenu_page(
				'caldera-forms',
				__( 'CalderaWP License Manager', 'calderawp-license-manger' ),
				__( 'CalderaWP Licenses', 'calderawp-license-manger' ),
				'manage_options', 'calderawp_license_manager',
				array( $this, 'create_admin_page' )
			);
		}else{
			$this->plugin_screen_hook_suffix['calderawp_license_manager'] = add_submenu_page(
				'options-general.php',
				__( 'CalderaWP License Manager', 'calderawp-license-manger' ),
				__( 'CalderaWP Licenses', 'calderawp-license-manger' ),
				'manage_options', 'calderawp_license_manager',
				array( $this, 'create_admin_page' )
			);

		}


		add_action( 'admin_print_styles-' . $this->plugin_screen_hook_suffix[ 'calderawp_license_manager'], array( $this, 'enqueue_admin_stylescripts' ) );

	}

	/**
	 * Options page callback
	 *
	 * @since 1.0.0
	 */
	public function create_admin_page(){
		// Set class property        
		$screen = get_current_screen();
		$base = array_search($screen->id, $this->plugin_screen_hook_suffix);
			
		// include main template
		include CALDERA_WP_LICENSE_MANAGER_PATH .'includes/edit.php';

		// php based script include
		if( file_exists( CALDERA_WP_LICENSE_MANAGER_PATH .'assets/js/inline-scripts.php' ) ){
			echo "<script type=\"text/javascript\">\r\n";
				include CALDERA_WP_LICENSE_MANAGER_PATH .'assets/js/inline-scripts.php';
			echo "</script>\r\n";
		}

	}


}

if( is_admin() ) {
	global $settings_calderawp_license_manager;
	$settings_calderawp_license_manager = new CalderaWP_License_Manager_Settings();
}

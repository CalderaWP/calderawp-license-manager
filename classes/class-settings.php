<?php
/**
 * BlackBriar Setting.
 *
 * @package   BlackBriar
 * @author    David <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 David <david@digilab.co.za>
 */

/**
 * Plugin class.
 * @package BlackBriar
 * @author  David <david@digilab.co.za>
 */
class BlackBriar_Settings extends BlackBriar{


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

		if( empty( $_POST[ 'blackbriar-setup' ] ) || ! wp_verify_nonce( $_POST[ 'blackbriar-setup' ], 'blackbriar' ) ){
			if( empty( $_POST['config'] ) ){
				return;
			}
		}

		if( !empty( $_POST[ 'blackbriar-setup' ] ) && empty( $_POST[ 'config' ] ) ){
			$config = stripslashes_deep( $_POST['config'] );

			set_site_transient( 'update_plugins', null );
			
			BlackBriar_Options::update( $config );

			wp_redirect( '?page=blackbriar&updated=true' );
			exit;
		}

		if( !empty( $_POST['config'] ) ){

			set_site_transient( 'update_plugins', null );
			
			$config = json_decode( stripslashes_deep( $_POST['config'] ), true );

			if(	wp_verify_nonce( $config['blackbriar-setup'], 'blackbriar' ) ){
				BlackBriar_Options::update( $config );
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
	 * @uses 'wp_ajax_blkbr_create_blackbriar' action
	 *
	 * @since 0.0.1
	 */
	public function delete_blackbriar(){

		$deleted = BlackBriar_Options::delete( strip_tags( $_POST['block'] ) );

		if ( $deleted ) {
			wp_send_json_success( $_POST );
		}else{
			wp_send_json_error( $_POST );
		}



	}

	/**
	 * Create a new item
	 *
	 * @uses "wp_ajax_blkbr_create_blackbriar"  action
	 *
	 * @since 0.0.1
	 */
	public function create_new_blackbriar(){
		$new = BlackBriar_Options::create( $_POST[ 'name' ], $_POST[ 'slug' ] );

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
		// This page will be under "Settings"
		
	
			$this->plugin_screen_hook_suffix['blackbriar'] =  add_submenu_page( 'options-general.php', __( 'BlackBriar', $this->plugin_slug ), __( 'BlackBriar', $this->plugin_slug ), 'manage_options', 'blackbriar', array( $this, 'create_admin_page' ) );
			add_action( 'admin_print_styles-' . $this->plugin_screen_hook_suffix['blackbriar'], array( $this, 'enqueue_admin_stylescripts' ) );

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
		include BLKBR_PATH .'includes/edit.php';

		// php based script include
		if( file_exists( BLKBR_PATH .'assets/js/inline-scripts.php' ) ){
			echo "<script type=\"text/javascript\">\r\n";
				include BLKBR_PATH .'assets/js/inline-scripts.php';
			echo "</script>\r\n";
		}

	}


}

if( is_admin() ) {
	global $settings_blackbriar;
	$settings_blackbriar = new BlackBriar_Settings();
}

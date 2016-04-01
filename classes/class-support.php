<?php
/**
 * @TODO What this does.
 *
 * @package   @TODO
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 Josh Pollock
 */
class CalderaWP_License_Manager_Support {

	/**
	 * URL for where forms are served from
	 *
	 * @var string
	 */
	protected static $remote_url = 'https://calderawp.com';

	/**
	 * Root for remote API
	 *
	 * @var string
	 */
	protected static $api_root = 'http://local.wordpress-trunk/wp-json/';

	/**
	 * Holds form IDs
	 *
	 * @var array
	 */
	protected static $form_ids = [];

	/**
	 * Render page
	 */
	public static function page(){
		self::get_form_ids();
		printf( '<div id="forms">%s</div>', Caldera_Forms::render_form( self::$form_ids[ 'support' ] ) );


		printf( '<div id="debug-info">%s</div>', self::debug_info() );

	}

	/**
	 * Get IDs of the forms
	 */
	protected static function get_form_ids(){
		$url = self::$api_root . 'calderawp_api/v2/support/forms';
		$defaults = array(
			'support'   => 'CF56fdf3fdc929d',
			'login'     => 'CF56fdf52cd5940'
		);

		self::$form_ids = wp_parse_args( self::request( $url ), $defaults );
	}

	/**
	 * Make remote GET request
	 *
	 * @param $url
	 *
	 * @return bool|mixed|string
	 */
	protected static function request( $url ){
		$form = false;
		if( ! WP_DEBUG || false == ( $form = get_transient( __METHOD__ . $url ) ) ){
			$r = wp_safe_remote_get( $url );
			if( ! is_wp_error( $r ) ) {
				$form = wp_remote_retrieve_body( $r );
				set_transient( __METHOD__, $form, DAY_IN_SECONDS );
			}
		}

		return $form;
	}

	/**
	 * Get support form or login form
	 *
	 * @param bool|true $main
	 *
	 * @return bool|mixed|string
	 */
	protected static function get_form( $main = true ){
		if( empty( self::$form_ids ) ){
			self::get_form_ids();
		}
		if( $main ){
			$id = self::$form_ids[ 'support' ];
		}else{
			$id = self::$form_ids[ 'login' ];
		}

		$url = sprintf( '%s/cf-api/%s', self::$remote_url, $id );
		return self::request( $url );
	}

	/**
	 * Debug Information
	 *
	 * @param bool $html Optional. Return as HTML or not
	 *
	 * @return string
	 */
	protected static  function debug_info( $html = true ) {
		global $wp_version, $wpdb;
		$wp      = $wp_version;
		$php     = phpversion();
		$mysql   = $wpdb->db_version();
		$plugins = array();
		$all_plugins = get_plugins();
		foreach ( $all_plugins as $plugin_file => $plugin_data ) {
			if ( is_plugin_active( $plugin_file ) ) {
				$plugins[ $plugin_data[ 'Name' ] ] = $plugin_data[ 'Version' ];
			}
		}
		$stylesheet    = get_stylesheet();
		$theme         = wp_get_theme( $stylesheet );
		$theme_name    = $theme->get( 'Name' );
		$theme_version = $theme->get( 'Version' );
		$opcode_cache = array(
			'Apc'       => function_exists( 'apc_cache_info' ) ? 'Yes' : 'No',
			'Memcached' => class_exists( 'eaccelerator_put' ) ? 'Yes' : 'No',
			'Redis'     => class_exists( 'xcache_set' ) ? 'Yes' : 'No',
		);
		$object_cache = array(
			'Apc'       => function_exists( 'apc_cache_info' ) ? 'Yes' : 'No',
			'Apcu'      => function_exists( 'apcu_cache_info' ) ? 'Yes' : 'No',
			'Memcache'  => class_exists( 'Memcache' ) ? 'Yes' : 'No',
			'Memcached' => class_exists( 'Memcached' ) ? 'Yes' : 'No',
			'Redis'     => class_exists( 'Redis' ) ? 'Yes' : 'No',
		);
		$versions = array(
			'WordPress Version'             => $wp,
			'PHP Version'                   => $php,
			'MySQL Version'                 => $mysql,
			'Server Software'               => $_SERVER[ 'SERVER_SOFTWARE' ],
			'Your User Agent'               => $_SERVER[ 'HTTP_USER_AGENT' ],
			'Session Save Path'             => session_save_path(),
			'Session Save Path Exists'      => ( file_exists( session_save_path() ) ? 'Yes' : 'No' ),
			'Session Save Path Writeable'   => ( is_writable( session_save_path() ) ? 'Yes' : 'No' ),
			'Session Max Lifetime'          => ini_get( 'session.gc_maxlifetime' ),
			'Opcode Cache'                  => $opcode_cache,
			'Object Cache'                  => $object_cache,
			'WPDB Prefix'                   => $wpdb->prefix,
			'WP Multisite Mode'             => ( is_multisite() ? 'Yes' : 'No' ),
			'WP Memory Limit'               => WP_MEMORY_LIMIT,
			'Currently Active Theme'        => $theme_name . ': ' . $theme_version,
			'Currently Active Plugins'      => $plugins
		);
		if ( $html ) {
			$debug = '';
			foreach ( $versions as $what => $version ) {
				$debug .= '<p><strong>' . $what . '</strong>: ';
				if ( is_array( $version ) ) {
					$debug .= '</p><ul class="ul-disc">';
					foreach ( $version as $what_v => $v ) {
						$debug .= '<li><strong>' . $what_v . '</strong>: ' . $v . '</li>';
					}
					$debug .= '</ul>';
				} else {
					$debug .= $version . '</p>';
				}
			}
			return $debug;
		} else {
			return $versions;
		}
	}



	/**
	 * Load assets for the form
	 */
	public static function load_assets(){
		wp_enqueue_script( 'calderawp_license_manager-support', CALDERA_WP_LICENSE_MANAGER_URL . '/assets/js/support.js', array( 'jquery' ) );
		wp_localize_script( 'calderawp_license_manager-support', 'CWP_SUPPORT', array(
			'debug' => wp_json_encode( self::debug_info( false ) ),
			'api_url' => esc_url_raw( self::$api_root ),
			'forms_url' => esc_url_raw( self::$remote_url ),
			'forms' => self::get_form_ids()
		));

		wp_enqueue_script( 'js-cookie', '//cdn.rawgit.com/js-cookie/js-cookie/master/src/js.cookie.js', array( 'jquery' ) );
		wp_enqueue_style( 'cf-field-styles' );

		$style_includes = get_option( '_caldera_forms_styleincludes' );
		$style_includes = apply_filters( 'caldera_forms_get_style_includes', $style_includes);

		if(!empty($style_includes['grid'])){
			wp_enqueue_style( 'cf-grid-styles' );
		}
		if(!empty($style_includes['form'])){
			wp_enqueue_style( 'cf-form-styles' );
		}
		if(!empty($style_includes['alert'])){
			wp_enqueue_style( 'cf-alert-styles' );
		}
	}
}

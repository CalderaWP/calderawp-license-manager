<?php

/**
 * Setup Github updates for CF and this plugin
 *
 * @package CalderaWP_License_Manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CaderaWP LLC
 */
class CalderaWP_License_Manager_Plugin_Updates {


	/**
	 * @since 1.1.0
	 */
	public function __construct(){
		$this->init_updaters();
		add_action( 'wp_ajax_cf_beta_state', array( $this, 'beta_state' ) );
	}

	/**
	 * Initialize Github updater classes
	 *
	 * @since 1.1.0
	 */
	protected function init_updaters(){
		if( defined( 'CFCORE_BASENAME' ) ){
			if( CalderaWP_License_Manager_Update_Options::always_update_cf() || isset( $_GET[ 'cf-beta-update' ] ) ) {
				new CalderaWP_License_Manager_WP_GitHub_Updater( $this->cf_args() );
			}
			new CalderaWP_License_Manager_WP_GitHub_Updater( $this->cwplm_args() );
		}


	}

	/**
	 * Args for CF beta updater
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	protected function cf_args(){
		$config = array(
			'slug' => CFCORE_BASENAME,
			'proper_folder_name' => dirname( CFCORE_BASENAME ),
			'api_url' => 'https://api.github.com/repos/desertsnowman/caldera-forms',
			'raw_url' => 'https://raw.github.com/desertsnowman/caldera-forms/current-dev',
			'github_url' => 'https://github.com/desertsnowman/caldera-forms',
			'zip_url' => 'https://github.com/Desertsnowman/Caldera-Forms/archive/current-dev.zip',
			'sslverify' => true,
			'requires' => '4.2',
			'tested' => '7.0',
			'readme' => 'readme.txt',
			'access_token' => '',
		);

		return $config;

	}

	/**
	 * Args for license manager beta updater
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	protected function cwplm_args(){
		$config = array(
			'slug' => CALDERA_WP_LICENSE_MANAGER_BASENAME,
			'proper_folder_name' => dirname( CALDERA_WP_LICENSE_MANAGER_BASENAME ),
			'api_url' => 'https://api.github.com/repos/CalderaWP/calderawp-license-manager',
			'raw_url' => 'https://raw.github.com/CalderaWP/calderawp-license-manager/master',
			'github_url' => 'https://github.com/CalderaWP/calderawp-license-manager',
			'zip_url' => 'https://github.com/CalderaWP/calderawp-license-manager/archive/master.zip',
			'sslverify' => true,
			'requires' => '4.2',
			'tested' => '7.0',
			'readme' => 'readme.txt',
			'access_token' => '',
		);

		return $config;

	}

	/**
	 * Change CF beta always state via AJAX
	 *
	 * @uses "wp_ajax_cf_beta_state" action
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	public  function beta_state(){
		if( isset( $_POST[ '_nonce' ], $_POST[ 'state' ] ) && wp_verify_nonce( $_POST[ '_nonce' ], 'cf-always-beta' ) && current_user_can( 'manage_options' ) ){
			$always = (int) $_POST[ 'state' ];
			$always = ! $always;
			if( ! is_bool( $always ) || ! in_array( $always, array( 0,1 ) ) ){
				$always = 0;
			}
			CalderaWP_License_Manager_Update_Options::update_cf_beta_options(
				array(
					'always' =>  $always
				)
			);

			wp_send_json_success( CalderaWP_License_Manager_Update_Options::always_update_cf() );
		}
	}

	/**
	 * Get URL for update Caldera Forms to beta
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static  function update_url(){
		if( ! defined( 'CFCORE_BASENAME' ) ){
			wp_die( __( 'Beta updates for Caldera Forms are only supported in Caldera Forms 1.3.5 or later', 'calderawp-license-manager' ) );
		}

		$action = 'upgrade-plugin_'. CFCORE_BASENAME;
		$url = add_query_arg( array(
			'action' => 'upgrade-plugin',
			'plugin' => urlencode( CFCORE_BASENAME ),
			'_wpnonce' => wp_create_nonce( $action ),
			'cf-beta-update' => true

		), self_admin_url(  'update.php' ) );
		return $url;
	}

}

<?php
/**
 * Options for Github updates for CF
 *
 * @package CalderaWP_License_Manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CaderaWP LLC
 */
class CalderaWP_License_Manager_Update_Options extends CalderaWP_License_Manager_Options {

	/**
	 * Option prefix
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public static $option_name = 'calderawp_license_manager_updates';

	/**
	 * Default CF beta options
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected static $cf_defaults = array(
		'always' => false
	);

	/**
	 * Get CF beta options
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	public static function get_cf_beta_options(){
		$options = self::get_single( 'cf-beta' );
		return wp_parse_args( $options, self::$cf_defaults );
	}

	/**
	 * Update CF beta options
	 *
	 * @since 1.1.0
	 */
	public static function update_cf_beta_options( $options ){
		$options = wp_parse_args( $options, self::$cf_defaults );
		self::save_single( 'cf-beta', $options );
	}

	/**
	 * Is CF in always beta update mode?
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	public static function always_update_cf(){
		$settings = CalderaWP_License_Manager_Update_Options::get_cf_beta_options();
		return $settings[ 'always' ];
	}

}

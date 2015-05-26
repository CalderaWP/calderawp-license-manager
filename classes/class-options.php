<?php
/**
 * BlackBriar Options.
 *
 * @package   BlackBriar
 * @author    David <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 David <david@digilab.co.za>
 */

/**
 * BlackBriar_Options class.
 *
 * @package BlackBriar
 * @author  David <david@digilab.co.za>
 */
class BlackBriar_Options {

	public static $option_name = 'blackbriar';

	/**
	 * Create a new blackbriar.
	 *
	 * @since 0.0.1
	 *
	 * @param string $name Name for blackbriar.
	 * @param string $slug Slug for blackbriar.
	 *
	 * @return array|void Config array for new blackbriar if it exists. Void if not.
	 */
	public static function create( $name, $slug ) {
		$name = sanitize_text_field( $name );
		$slug = sanitize_title_with_dashes( $slug );
		$item_id = self::create_unique_id();
		$registry = self::get_registry();

		if( ! isset( $registry[ $item_id ] ) ){
			$new = array(
				'id'		=>	$item_id,
				'name'		=>	$name,
				'slug'		=>	$slug,
			);

			$registry[ $item_id ] = $new;

			self::update( $new, $registry );

			return $new;

		}

	}

	/**
	 * Get an individual item by its ID.
	 *
	 * @since 0.0.1
	 *
	 * @param string $id blackbriar ID
	 *
	 * @return bool|array blackbriar config or false if not found.
	 */
	public static function get_single( $id ) {
		$option_name = self::item_option_name( $id );
		$blackbriar = get_option( $option_name, false );
		
		// try slug based lookup
		if( false === $blackbriar ){
			$registry = self::get_registry();
			foreach( $registry as $single_id => $single ){
				if( $single['slug'] === $id ){
					$option_name = self::item_option_name( $single_id );
					$blackbriar = get_option( $option_name, false );
					break;
				}
			}
		}

		/**
		 * Filter a blackbriar config before returning
		 *
		 * @param array $blackbriar The config to be returned
		 * @param string $option_name The name of the option it was stored in.
		 *
		 * @since 0.0.1
		 */
		return apply_filters( 'blackbriar_get_single', $blackbriar, $option_name );

	}

	/**
	 * Get the registry of blackbriar.
	 *
	 * @since 0.0.1
	 *
	 * @return mixed|void
	 */

	public static function get_registry() {
		$registry = get_option( self::registry_name(), array() );

		/**
		 * Filter the registry before returning
		 *
		 * @since 0.0.1
		 */
		return apply_filters( 'blackbriar_get_registry', $registry );

	}

	/**
	 * Update both a single item and the registry
	 *
	 * @since 0.0.1
	 *
	 * @param array $config Single item config.
	 * @param array|bool. Optional. If false, current registry will be used, if is array, that reg
	 */
	public static function update( $config, $update_registry = false ) {
		if ( ! is_array( $update_registry ) ) {
			$update_registry = self::get_registry();
		}

		if( isset( $config['id'] ) && !empty( $update_registry[ $config['id'] ] ) ){
			$update = array(
				'id'	=>	$config['id'],
				'name'	=>	$config['name'],
				'slug'	=>	$config['slug'],

			);

			// add search form to registery
			if( ! empty( $config['search_form'] ) ){
				$updated_registery['search_form'] = $config['search_form'];
			}

			$update_registry[ $config[ 'id' ] ] = $update;

		}

		self::save_registry( $update_registry );

		self::save_single( $config['id'], $config );

	}

	/**
	 * Delete an item and clear it from the registry
	 *
	 * @since 0.0.1
	 *
	 * @param string $id Item ID
	 *
	 * @return bool True on success.
	 */
	public static function delete( $id ) {
		$deleted = delete_option( self::item_option_name( $id ) );
		if ( $deleted ) {
			$registry = self::get_registry();
			if ( isset( $registry[ $id ] ) ) {
				unset( $registry[ $id ] );
				return self::save_registry( $registry );

			}
		}

	}

	/**
	 * Save the registry of items.
	 *
	 * @since 0.0.1
	 *
	 * @param array $registry The registry
	 */
	protected static function save_registry( $registry ) {
		return update_option( self::registry_name(), $registry );

	}

	/**
	 * Save an individual item.
	 *
	 * @param string $id blackbriar ID
	 * @param array $config blackbriar config
	 */
	protected static function save_single( $id, $config ) {
		return update_option( self::item_option_name( $id ), $config );

	}



	/**
	 * Get the name to use for an individual item option.
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @param string $id
	 *
	 * @return string
	 */
	protected static function item_option_name( $id ) {
		$name = self::$option_name . '_' . $id;
		if ( 50 < strlen( $name ) ) {
			$name = md5( $name );
		}

		return $name;

	}

	/**
	 * Get the name used for the registry option
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @return string
	 */
	protected static function registry_name() {
		return '_' . self::$option_name . '_registry';

	}

	/**
	 * Create unique ID
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @return string
	 */
	protected static function create_unique_id() {
		$slug_parts = explode( '_', 'blackbriar' );
		$slug = '';

		foreach ( $slug_parts as $slug_part ) {
			$slug .= substr( $slug_part, 0,1 );
		}

		$slug = strtoupper( $slug );

		$item_id = uniqid( $slug ) . rand( 100, 999 );

		return $item_id;

	}


}

<?php
/**
 * Utility function for working with plugins
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager;


class plugin {

	/* @var plugins */
	protected $plugins;

	/* @var array */
	protected $names = array(
		'cf' => array(),
		'search' => array(),
		'licensed' => array(),
		'installed' => array(),
	);

	/**
	 * plugin constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param plugins $plugins
	 */
	public function __construct( plugins $plugins ) {
		$this->plugins = $plugins;
		$this->set_names();
	}

	/**
	 * Is plugin active?
	 *
	 * @since 2.0.0
	 *
	 * @param string $basename File path for plugin
	 *
	 * @return bool
	 */
	public function is_active( $basename ){
		return is_plugin_active( $basename );
	}

	/**
	 * Check by name, if plugin is installed
	 *
	 * @since 2.0.0
	 *
	 * @param string $name Plugin name
	 *
	 * @return bool
	 */
	public function is_installed( $name ){


		if( empty( $this->names[ 'installed' ] ) ){
			return false;
		}
		
		$name = sanitize_key( $name );

		if( array_key_exists( $name, $this->names[ 'installed' ] ) ){
			return $this->names[ 'installed' ][ $name ];
		}

		$name = str_replace( 'add-on', '', $name );

		if( array_key_exists( $name, $this->names[ 'installed' ] ) ){
			return $this->names[ 'installed' ][ $name ];
		}

		return false;

	}

	/**
	 * Get license object for a plugin, if we have one, by name
	 *
	 * @since 2.0.0
	 *
	 * @param string $name Plugin name
	 *
	 * @return license|bool
	 */
	public function get_license( $name ){
		$id = $this->has_license( $name );
		if( $id ){

			$licenses = $this->plugins->get_plugins( 'licensed' );
			if( isset( $licenses[ $id ] ) ){
				return $licenses[ $id ];
			}

			
		}
		
	}

	/**
	 * Check if there is a license for a plugin, by name
	 *
	 * @since 2.0.0
	 *
	 * @param string $name Plugin name
	 *
	 * @return int|bool The ID of license if found, else false.
	 */
	public function has_license( $name ){
		if( empty( $this->names[ 'licensed' ] ) ){
			return 0;
		}

		$name = sanitize_key( $name );

		if(  array_key_exists( $name, $this->names[ 'licensed'  ] ) ){
			return $this->names[ 'licensed' ][ $name ];
		}else{
			return 0;
		}
		
	}

	/**
	 * Get remaining activations
	 *
	 * @since 2.0.0
	 *
	 * @param license $license License object
	 *
	 * @return int|string|void
	 */
	public function activations_remaining( license $license ){
		if( true == $license->unlimited || -1 == $license->limit ){
			$remaining =  __( 'Unlimited Activations', 'calderawp-license-manager');
		}else{
			$remaining = $license->license - $license->activations;
			$remaining = sprintf( __( '%d Activations Remaining', $remaining ),  'calderawp-license-manager' );
		}
		
		return $remaining;
		
		
	}

	/**
	 * Check if we have license activations remaining
	 *
	 * @since 2.0.0
	 *
	 * @param license $license license object
	 *
	 * @return bool
	 */
	public function has_activations( license $license ){
		if( false != $license->at_limit ){
			return true;
		}
		
		return false;

	}

	/**
	 * Find a plugin by CWP download ID
	 * 
	 * Not a terribly reliable method
	 * 
	 * @since 2.0.0
	 * 
	 * @param int $download_id ID of download
	 *
	 * @return bool|int|string
	 */
	public function find_basename( $download_id ){
		$plugin = false;
		$plugins = $this->plugins->get_plugins_array();
		if( ! empty( $plugins[ 'cf' ] ) ){
			$ids = array_keys( $plugins[ 'cf' ] );
			if( in_array( $download_id, $ids  ) ){
				$the_plugins = array_values( $plugins[ 'cf' ] );
				$key = (int) array_search( (string) $download_id, $ids );
				if( isset( $the_plugins[ $key ] ) ){
					$plugin = $the_plugins[ $key ];
				}
				
			}
			
		}

		if( ! is_object( $plugin ) && ! empty( $plugins[ 'search' ] ) ){
			$ids = array_keys( $plugins[ 'search' ] );
			if( in_array( $download_id, $ids  ) ){
				$the_plugins = array_values( $plugins[ 'search' ] );
				$key = (int) array_search( (string) $download_id, $ids );
				if( isset( $the_plugins[ $key ] ) ){
					$plugin = $the_plugins[ $key ];
				}
				
			}

		}

		if( is_object( $plugin ) && ! empty( $plugins[ 'installed' ] ) ){
			$name = str_replace( 'Add-on', '', $plugin->name );
			foreach ( $plugins[ 'installed' ] as $basename => $installed_plugin ){
				$installed_name = $installed_plugin[ 'Name' ];
				if( $name == $installed_name ){
					return $basename;
				}
				
			}
			
		}
		
		return false;

	}

	/**
	 * Set names property
	 *
	 * @since 2.0.0
	 */
	protected function set_names() {
		$all = $this->plugins->get_plugins_array();

		foreach( array( 'cf', 'search', 'licensed', 'installed' ) as $key ){
			if( 'installed' == $key ){
				$name_field = 'Name';
			}elseif( 'licensed' == $key ){
				$name_field = 'title';
			}else{
				$name_field = 'name';
			}

			if( ! empty( $all[ $key ] ) ){
				foreach ( $all[ $key ] as $id => $item ){
					if( is_object( $item ) && property_exists( $item, $name_field ) ){
						$this->names[ $key ][ $id ] = sanitize_key( $item->$name_field );
					}elseif ( is_array( $item ) && isset( $item[ $name_field ] ) ){
						$this->names[ $key ][ $id ] = sanitize_key( $item[ $name_field ] );
					}

				}

				$this->names[ $key ] = array_flip( $this->names[ $key ] );

			}else{
				$this->names[ $key ] = array();
			}   

		}


	}
	

	/**
	 * Get WordPress dot Org slug for a plugin
	 * 
	 * @since 2.0.0
	 * 
	 * @param int $id CWP Download ID
	 *
	 * @return string|void Slug if found
	 */
	public function dot_org_slug( $id ){
		$plugins = array(
			8241 => 'caldera-forms-edd',
			1934 => 'caldera-forms-run-action',
			1923 => 'caldera-form-metabox',
			1950 => 'slack-integration-for-caldera-forms',
			1940 => 'verify-email-for-caldera-forms',
			4960 => 'postmatic-for-caldera-forms',
			3221 => 'conditional-fail-for-caldera-forms'
		);
		
		if( isset( $plugins[ $id ] ) ){
			return $plugins[ $id ];
		}

	}
	
}
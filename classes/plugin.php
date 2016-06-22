<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/20/16
 * Time: 9:16 PM
 */

namespace calderawp\licensemanager;


class plugin {

	/**
	 * @var plugins
	 */
	protected $plugins;

	protected $names = array(
		'cf' => array(),
		'search' => array(),
		'licensed' => array(),
		'installed' => array(),
	);
	
	public function __construct( plugins $plugins ) {
		$this->plugins = $plugins;
		$this->set_names();
	}

	public function is_active( $basename ){
		return is_plugin_active( $basename );
	}
	
	public function is_installed( $name ){


		if( empty( $this->names[ 'installed' ] ) ){
			return false;
		}
		
		$name = sanitize_key( $name );


		if( array_key_exists( $name, $this->names[ 'installed' ] ) ){
			return $this->names[ 'installed' ][ $name ];
		}

		return false;

	}

	public function get_license( $name ){
		$id = $this->has_license( $name );
		if( $id ){

			$licenses = $this->plugins->get_plugins( 'licensed' );
			if( isset( $licenses[ $id ] ) ){
				return $licenses[ $id ];
			}

			
		}
		
	}
	
	public function get_basename( $name ){
		
	}
	
	

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
	
	public function activations_remaining( license $license ){
		if( true == $license->unlimited || -1 == $license->limit ){
			$remaining =  __( 'Unlimited Activations', 'calderawp-license-manager');
		}else{
			$remaining = $license->license - $license->activations;
			$remaining = sprintf( __( '%s Activations Remaining', 'calderawp-license-manager' ) );
		}
		
		return $remaining;
		
		
	}

	public function has_activations( license $license ){
		if( false != $license->at_limit ){
			return true;
		}
		
		return false;

	}

	public function install( $slug ){
		$id =  $this->find_id( $slug );

	}

	protected function find_id( $slug ){
		return 42;
	}

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
					}

				}

				$this->names[ $key ] = array_flip( $this->names[ $key ] );

			}else{
				$this->names[ $key ] = array();
			}
		}

	}
	
}
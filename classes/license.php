<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/20/16
 * Time: 9:59 PM
 */

namespace calderawp\licensemanager;


class license {
	
	
	public function __construct( \stdClass $obj = null ) {
		if( null !== $obj ){
			$this->set_from_std( $obj );
		}
	}
	
	public function set_from_std( \stdClass $obj ){
		
		foreach( array_keys( get_object_vars( $this ) ) as $prop ){
			if( property_exists( $obj, $prop ) ){
				$this->$prop = $obj->$prop;
			}
			
		}
		
	}
	

	public $title;
	
	public $download;
	
	public $slug;
	
	public $code;
	
	public $sites;
	
	public $activations;
	
	public $at_limit;

	public $license;
	
	public $unlimited;
	
	public $limit;
	

}
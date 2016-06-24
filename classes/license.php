<?php
/**
 * License object
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
namespace calderawp\licensemanager;


class license {

	/**
	 * license constructor.
	 *
	 * @since 2.0.
	 * 
	 * @param \stdClass|null $obj If is stdClass properties will be set from it
	 */
	public function __construct( \stdClass $obj = null ) {
		if( null !== $obj ){
			$this->set_from_std( $obj );
		}
	}

	/**
	 * Set object properties from a stdClass object
	 * 
	 * @since 2.0.0.
	 * 
	 * @param \stdClass $obj
	 */
	public function set_from_std( \stdClass $obj ){
		$this->obj = $obj;
		foreach( array_keys( get_object_vars( $this ) ) as $prop ){
			if( property_exists( $obj, $prop ) ){
				$this->$prop = $obj->$prop;
			}
			
		}
		
	}

	/**
	 * ID of license
	 *
	 * @since 2.0.0
	 *
	 * @var int
	 */
	public $license;

	/**
	 * The actual license code
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $code;

	/**
	 * Name of download license is for
	 * 
	 * @since 2.0.0
	 * 
	 * @var string
	 */
	public $title;

	/**
	 * ID of download license is for
	 *
	 * @since 2.0.0
	 *
	 * @var int
	 */
	public $download;

	/**
	 * Slug of download license is for
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $slug;
	
	/**
	 * Sites that license is active on
	 *
	 * @since 2.0.0
	 *
	 * @var object
	 */
	public $sites;

	/**
	 * Number of sites license is active on.
	 *
	 * @since 2.0.0
	 *
	 * @var int
	 */
	public $activations;

	/**
	 * Is license at limit?
	 *
	 * @since 2.0.0
	 *
	 * @var int
	 */
	public $at_limit;

	/**
	 * Is license unlimited?
	 *
	 * @since 2.0.0
	 *
	 * @var int
	 */
	public $unlimited;

	/**
	 * License activation limit?
	 *
	 * @since 2.0.0
	 *
	 * @var int
	 */
	public $limit;

	/**
	 * @var \stdClass
	 */
	public $obj;
	

}
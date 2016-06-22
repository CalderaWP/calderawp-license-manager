<?php
/**
 * CalderaWP API client
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager\api;


class cwp extends base {

	/**
	 * Get all plugins
	 *
	 * @since 2.0.0
	 *
	 * @return int|object|string
	 */
	public function all(){
		$plugins = $this->request( 'calderawp_api/v2/products', array(
			'per_page' => 1000
		) );
		return $plugins;
	}

	/**
	 * Get all cf add-ons
	 *
	 * @since 2.0.0
	 *
	 * @return int|object|string
	 */
	public function cf_addons(){
		$plugins = $this->request( 'calderawp_api/v2/products/cf-addons', array(
			'per_page' => 1000
		) );
		return $plugins;
	}

	/**
	 * Get all plugins in Caldera Search bundle
	 *
	 * @since 2.0.0
	 *
	 * @return int|object|string
	 */
	public function caldera_search(){
		$plugins = $this->request( 'calderawp_api/v2/products/caldera-search' );
		return $plugins;
	}

	/**
	 * Get cf bundles
	 *
	 * @since 2.0.0
	 *
	 * @return int|object|string
	 */
	public function cf_bundles(){
		$plugins = $this->request( 'calderawp_api/v2/products/cf-bundles' );
		return $plugins;

	}

}
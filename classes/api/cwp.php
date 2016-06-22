<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/18/16
 * Time: 9:22 PM
 */

namespace calderawp\licensemanager\api;


class cwp extends base {

	public function all(){
		$plugins = $this->request( 'calderawp_api/v2/products', array(
			'per_page' => 1000
		) );
		return $plugins;
	}

	public function cf_addons(){
		$plugins = $this->request( 'calderawp_api/v2/products/cf-addons', array(
			'per_page' => 1000
		) );
		return $plugins;
	}
	
	public function caldera_search(){
		$plugins = $this->request( 'calderawp_api/v2/products/caldera-search' );
		return $plugins;
	}

	public function cf_bundles(){
		$plugins = $this->request( 'calderawp_api/v2/products/cf-bundles' );
		return $plugins;

	}

}
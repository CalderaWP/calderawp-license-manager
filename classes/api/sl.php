<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/18/16
 * Time: 9:32 PM
 */

namespace calderawp\licensemanager\api;


class sl extends base {

	protected $endpoint = 'edd-sl-api/v1';
	
	public function get_licensed( ){
		$plugins =  $this->request( $this->endpoint . '/licenses' );
		return $plugins;
	}
	
	public function get_file( $download_id ){
		return $this->request( sprintf( '/%s/%d/file', $this->endpoint, $download_id ) );
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/19/16
 * Time: 2:45 PM
 */

namespace calderawp\licensemanager\api;


class auth extends base {


	public function get_token( $username, $password ){
		return $this->request( 'jwt-auth/v1/token', array(
			'username' => $username,
			'password' => $password,
		), 'POST' );

	}

}
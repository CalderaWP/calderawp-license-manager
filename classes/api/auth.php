<?php
/**
 * API client for authenticating against CalderaWP
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
namespace calderawp\licensemanager\api;


class auth extends base {

	/**
	 * Get JWT auth token
	 * 
	 * @since 2.0.0
	 * 
	 * @param string $username Username
	 * @param string $password Password
	 *
	 * @return object|string|int Hopefully an object containing the token we need.
	 */
	public function get_token( $username, $password ){
		return $this->request( 'jwt-auth/v1/token', array(
			'username' => $username,
			'password' => $password,
		), 'POST' );

	}

}
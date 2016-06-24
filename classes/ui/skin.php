<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/23/16
 * Time: 8:20 PM
 */

namespace calderawp\licensemanager\ui;


class skin extends \Plugin_Installer_Skin{

	var $feedback;
	var $error;

	function error( $error ) {
		$this->error = $error;
	}

	function feedback( $feedback ) {
		$this->feedback = $feedback;
	}

	function before() { }

	function after() { }

	function header() { }

	function footer() { }

	function add_strings() { }
}
<?php
/**
 * CalderaWP licensing helper functions
 *
 * @package   blackbriar
 * @author    David <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 David <david@digilab.co.za>
 */

/**
 * Register a plugin as a licence product.
 *
 * @since 0.0.1
 *
 */
function register_licensed_product( $params ){

	$defaults = array(
		'type'	=>	'plugin'
	);

	$params = array_merge( $defaults, (array) $params );

	$register = BlackBriar::get_instance();
	$register->register_product( $params );

}
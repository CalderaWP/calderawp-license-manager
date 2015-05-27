<?php
/**
 * @package   BlackBriar
 * @author    David <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 David <david@digilab.co.za>
 *
 * @wordpress-plugin
 * Plugin Name: BlackBriar License Manager
 * Plugin URI:  http://CalderaWP.com
 * Description: License Manager for Plugins managed by Easy Digital Downloads Licensing
 * Version:     1.0.0
 * Author:      David <david@digilab.co.za>
 * Author URI:  http://digilab.co.za/
 * Text Domain: blackbriar
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( defined( 'BLKBR_VER' ) ){
	return;
}

define('BLKBR_PATH',  plugin_dir_path( __FILE__ ) );
define('BLKBR_URL',  plugin_dir_url( __FILE__ ) );
define('BLKBR_VER',  '1.0.0' );

// load internals
require_once( BLKBR_PATH . 'classes/class-blackbriar.php' );
require_once( BLKBR_PATH . 'classes/class-options.php' );
require_once( BLKBR_PATH . 'classes/class-settings.php' );
require_once( BLKBR_PATH . 'includes/functions.php' );

// Load instance
add_action( 'plugins_loaded', array( 'BlackBriar', 'get_instance' ) );

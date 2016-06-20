<?php
/**
 * @package   CalderaWP_License_Manager
 * @author    David Cramer for CalderaWP LLC<David@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 David Cramer for CalderaWP LLC<David@CalderaWP.com>
 *
 * @wordpress-plugin
 * Plugin Name: CalderaWP License Manager
 * Plugin URI:  http://CalderaWP.com
 * Description: License manager for CalderaWP Plugins
 * Version:     2.0.0-a-1
 * Author:      CalderaWP LLC
 * Author URI:  https://CalderaWP.com/
 * Text Domain: calderawp-license-manager
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( defined( 'CALDERA_WP_LICENSE_MANAGER_VER' ) ){
	return;
}

define( 'CALDERA_WP_LICENSE_MANAGER_PATH', plugin_dir_path( __FILE__ ) );
define( 'CALDERA_WP_LICENSE_MANAGER_URL', plugin_dir_url( __FILE__ ) );
define( 'CALDERA_WP_LICENSE_MANAGER_VER', '2.0.0-a-1' );
define( 'CALDERA_WP_LICENSE_MANAGER_BASENAME', plugin_basename( __FILE__ ) );

if( ! defined( 'CALDERA_WP_LICENSE_MANAGER_API' ) ){
	define( 'CALDERA_WP_LICENSE_MANAGER_API', 'https://calderawp.com/wp-json' );
}

/**
 * Load plugin
 */
//@TODO PHP version check
include CALDERA_WP_LICENSE_MANAGER_PATH . '/bootstrap.php';




function wpdocs_my_custom_submenu_page_callback() {
	return \calderawp\licensemanager\ui\make::the_ui();
}

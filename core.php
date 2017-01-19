<?php
/**

 * @wordpress-plugin
 * Plugin Name: CalderaWP License Manager
 * Plugin URI:  http://CalderaWP.com
 * Description: License manager for CalderaWP Plugins
 * Version:     1.2.8
 * Author:      CalderaWP
 * Author URI:  http://CalderaWP.com
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
define( 'CALDERA_WP_LICENSE_MANAGER_VER', '1.2.8' );
define( 'CALDERA_WP_LICENSE_MANAGER_BASENAME', plugin_basename( __FILE__ ) );

// load internals
require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/class-core.php' );
require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/class-options.php' );
require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/class-feed.php' );

require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/class-settings.php' );
require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'includes/functions.php' );

require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/updater/class-updater.php' );
require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/updater/class-plugin-updates.php' );
require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/updater/class-updater-options.php' );

// Load instance
add_action( 'plugins_loaded', array( 'CalderaWP_License_Manager', 'get_instance' ) );

//load dissmissable notices
add_action( 'plugins_loaded', 'cwp_license_manager_load_dismissible_notices', 0 );

<?php


/**
 * Include files
 */
add_action( 'plugins_loaded', function(){
	require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'includes/functions.php' );

	//require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/updater/class-updater.php' );
	//require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/updater/class-plugin-updates.php' );
	//require_once( CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/updater/class-updater-options.php' );

	cwp_license_manager_load_dismissible_notices();
}, 1 );

/**
 * Register autoloader
 */
add_action( 'plugins_loaded', function(){
	spl_autoload_register( function ($class) {


		$prefix = 'calderawp\\licensemanager\\';

		// base directory for the namespace prefix
		$base_dir = CALDERA_WP_LICENSE_MANAGER_PATH . 'classes/';

		// does the class use the namespace prefix?
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {
			// no, move to the next registered autoloader
			return;
		}

		// get the relative class name
		$relative_class = substr($class, $len);

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php
		$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

		// if the file exists, require it
		if (file_exists($file)) {
			require $file;
		}else{
			$x = 1;
		}
	});
}, 2 );

/**
 * Add our menu
 */
add_action( 'admin_menu', function() {
	if( defined( '-CFCORE_VER' ) ) {
		$page = 'caldera-forms';
	}else {
		$page = 'options-general.php';
	}

	add_submenu_page(
		$page,
		__( 'CalderaWP License Manager', 'calderawp-license-manger' ),
		__( 'CalderaWP Licenses', 'calderawp-license-manger' ),
		'manage_options',
		'cwp-lm',
		function(){
			return \calderawp\licensemanager\ui\make::the_ui();
		}
	);
});

/**
 * Make Plugin go
 */
add_action( 'admin_init', function(){
	$make = new \calderawp\licensemanager\ui\make();
	add_action( 'admin_enqueue_scripts', array( $make, 'register_script' ), 35 );
	\calderawp\licensemanager\plugin::get_instance();
	
	if( isset( $_POST, $_POST[ 'cwp-lm-save' ] ) ){
		new \calderawp\licensemanager\ui\save();
	}
});


add_action( '---rest_api_init', function(){
	$api = new \calderawp\licensemanager\api\internal\views();
	$api->add_routes();
});

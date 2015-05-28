CalderaWP_License_Manager
=============

### Plugin License Manager for WordPress

To add CalderaWP_License_Manager support to your plugin:

for EDD:
```php
if( function_exists( 'cwp_license_manager_register_licensed_product' ) ){
	$product_params = array(
		'name'		=>	'Plugin Name',
		'slug'		=>	'plugin-slug',
		'url'		=>	'https://calderawp.com',
		'type'		=>	'plugin', // themes will be supported if this works out
		'updater'	=>	'edd',
		'version'	=>	'1.0.0',
		'key_store'	=>	'myplugin_license',
		'file'		=> __FILE__
	);		
	cwp_license_manager_register_licensed_product( $product_params );
}
```
for FooPlugins
```php
if( function_exists( 'cwp_license_manager_register_licensed_product' ) ){
	$product_params = array(
		'name'		=>	'Plugin',
		'slug'		=>	'plugin-slug',
		'url'		=>	'http://fooplugins.com/api/my-plugin/check',
		'type'		=>	'plugin',
		'updater'	=>	'foo',
		'version'	=>	'1.0.0',
		'file'		=> __FILE__
	);		
	cwp_license_manager_register_licensed_product( $product_params );
}
```

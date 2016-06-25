<?php
/**
 * Caldera Forms tab
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
namespace calderawp\licensemanager\ui;
if( ! defined( 'ABSPATH' ) ){
	exit;
}

$plugins = \calderawp\licensemanager\lm::get_instance()->plugins->get_plugins( 'cf' );
if( is_array( $plugins ) && ! empty( $plugins )) {
	foreach ( $plugins as $id => $plugin ){
		echo ui::plugin_view( $plugin, $id );
	}
}

?>
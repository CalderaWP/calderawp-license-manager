<?php
namespace calderawp\licensemanager\ui;
$plugins = \calderawp\licensemanager\lm::get_instance()->plugins->get_plugins( 'cf' );
if( $plugins ) {
	foreach ( $plugins as $plugin ){
		echo ui::plugin_view( $plugin );
	}
}

?>
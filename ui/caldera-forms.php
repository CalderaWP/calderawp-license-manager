<?php
namespace calderawp\licensemanager\ui;
$plugins = \calderawp\licensemanager\plugin::get_instance()->plugins->get_plugins( true );
if( $plugins ) {
	foreach ( $plugins as $plugin ){
		echo ui::plugin_view( $plugin );
	}
}

?>
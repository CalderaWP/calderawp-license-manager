<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/18/16
 * Time: 8:48 PM
 */

namespace calderawp\licensemanager\api\internal;


use calderawp\licensemanager\ui\ui;

class views{
	
	public function add_routes(){
		register_rest_route( 'cwp-lm/v2' . '/views', array(
			'method' => 'GET',
			'callback' => array( $this, 'get_view' ),
			'args' => array(
				'view' => array(
					'default' => 'account'
				)
			)
			
		) );
	}
	
	public function get_view( \WP_REST_Request $request ){
		$tab = $request[ 'view' ];
		if( ! in_array( $tab, ui::tabs() ) ){
			$tab = 'account';
		}

		
		if( file_exists( CALDERA_WP_LICENSE_MANAGER_PATH . '/ui/' . $tab . '.php' ) ){
			ob_start();
			include CALDERA_WP_LICENSE_MANAGER_PATH . '/ui/' . $tab . '.php';
			$view = ob_get_clean();
			return rest_ensure_response( array( 'view' => $view ) );
		}
		
		
	}
	
	public function permissions(){
		return current_user_can( 'manage_options' );
	}

}
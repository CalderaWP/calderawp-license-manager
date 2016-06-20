<?php

namespace calderawp\licensemanager\ui;
use calderawp\licensemanager\base;
use calderawp\licensemanager\plugin;
use calderawp\licensemanager\plugins;

/**
 * Created by PhpStorm.
 * User: josh
 * Date: 6/18/16
 * Time: 4:28 PM
 */
class ui extends base {
    
    public static function submit_url(){
	  
        return admin_url( );
    }
    
    public static function main_url(){
        $url = add_query_arg( 'page', 'cwp-lm', admin_url( make::menu_parent() ) );
        return apply_filters( 'calderawp_license_manage_main_url', $url );
    }

    public static function tab_url( $tab ){
        $url = self::main_url();
        if( array_key_exists( $tab, self::tabs() ) ){
            $url = add_query_arg( 'cwp-lm-tab', $tab, $url );
        }
        
        return $url;
        
    }
    
    public static function tabs(){

        $tabs = array(
            'account' => __( 'Account', 'calderawp-license-manager' ),
            'caldera-forms' => __( 'Caldera Forms Add-ons', 'calderawp-license-manager' ),
            'caldera-other' =>  __( 'Other CalderaWP Plugins', 'calderawp-license-manager' ),
            'beta' =>  __( 'Beta', 'calderawp-license-manager' ),
        );
        return $tabs;
    }
	
	
	public static function plugin_view( $plugin ){
		$installed = plugin::get_instance()->plugins->is_installed( $plugin->name );
		if ( $installed ) {
			$basename = $installed;
			$installed = false;
			$active = plugin::get_instance()->plugins->is_active( $installed );
		} else {
			$active = false;
		}
		
		ob_start();
		include CALDERA_WP_LICENSE_MANAGER_PATH . 'ui/plugin.php';
		$template = ob_get_clean();
		foreach( array( 'image_src', 'tagline', 'link' ) as $sub ){
			$template = str_replace( '{{' . $sub . '}}', $plugin->$sub, $template );
		}

		$price_html = esc_html__( 'Free', 'calderawp-license-manager' );
		$price = 0;
		if( ! empty( $plugin->prices ) ){
			foreach ( $plugin->prices as $v_price ) {
				if( property_exists( $v_price, 'amount' ) && $v_price->amount > $price ){
					$price = $v_price->amount;
				}
			}
		}

		if( 0 < $price ){
			$price_html = sprintf( '%s %s', esc_html__( 'From:', 'calderawp-license-manager' ), '$' . $price );
		}

		$template = str_replace( '{{button}}', self::button( $price_html, $plugin->link ), $template );
		
		return $template;
	}

	protected static function button( $text, $link, $right = true, $primary = false ){
		ob_start();
		include CALDERA_WP_LICENSE_MANAGER_PATH . 'ui/button.php';
		$template = ob_get_clean();
		$template = str_replace( '{{link}}', esc_url( $link ), $template );
		$template = str_replace( '{{text}}', esc_html( $text ), $template );
		if( $right ){
			$direction = 'right';
		}else{
			$direction = 'left';
		}
		$template = str_replace( '{{direction}}', $direction, $template );
		return $template;




	}
	
}

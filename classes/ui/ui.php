<?php

namespace calderawp\licensemanager\ui;
use calderawp\licensemanager\base;
use calderawp\licensemanager\license;
use calderawp\licensemanager\lm;
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
		if( ! is_object( $plugin ) || ! property_exists( $plugin, 'name' ) ){
			return;
		}

		$activations = $active = $license = $has_license = $has_activations = false;
		$installed = lm::get_instance()->plugin->is_installed( $plugin->name );

		if ( $installed ) {
			$basename = $installed;
			$installed = false;
			$active = lm::get_instance()->plugin->is_active( $basename );
		}else{
			$license         = lm::get_instance()->plugin->get_license( $plugin->name );
			$has_activations = lm::get_instance()->plugin->has_license( $plugin->name );
			if ( $has_activations ) {
				$activations = lm::get_instance()->plugin->activations_remaining( $license );
			}

		}
		

		ob_start();
		include CALDERA_WP_LICENSE_MANAGER_PATH . 'ui/plugin.php';
		$template = ob_get_clean();
		foreach( array( 'image_src', 'tagline', 'link' ) as $sub ){
			$template = str_replace( '{{' . $sub . '}}', $plugin->$sub, $template );
		}

		$button = self::button_markup( $plugin, $installed, $active, $license, $has_activations, $activations );


		$template = str_replace( '{{button}}', $button, $template );
		
		return $template;
	}

	protected static function button_markup( $plugin, $installed, $active, license $license = null, $has_activations, $activations ){
		if( ! $installed && null !== $license ){
			$button = self::button(
				sprintf( __( 'Install %s', 'calderawp-license-manager' ), $plugin->name ),
				install::link( $license->license, $license->download, false ),
				true,
				true
			);

		}elseif( $active ){
			$button = self::button( __( 'Plugin Active', 'calderawp-license-manager' ), $plugin->link );
		}else{
			$price_html = esc_html__( 'Free', 'calderawp-license-manager' );
			$price = 0;
			if( ! empty( $plugin->prices ) ){
				foreach ( $plugin->prices as $v_price ) {
					if( 0 == $price || ( property_exists( $v_price, 'amount' ) && $v_price->amount < $price ) ){
						$price = $v_price->amount;
					}
				}
			}

			if( 0 != $price ){
				$price_html = sprintf( '%s %s', esc_html__( 'From:', 'calderawp-license-manager' ), '$' . $price );
			}

			
			if( 0 == $price ){
				$other = self::install_button( $plugin, null );
			}elseif( is_object( $license ) ){
					$other = self::install_button( $plugin, $license );

			}else{
				$other = '';
			}

			$button = self::button( $price_html, $plugin->link, true, false, $other );

		}

		return $button;
	}
	
	protected static function install_button( $plugin, license $license = null ){
		if( null !== $license ){
			$link = install::link( $license->license, $license->download, false );
			return self::button( 'Install', $link, false, true );
		}else{
			$link = add_query_arg(
				array(
					'plugin'    => 'postmatic-for-caldera-forms',
					'tab'       => 'plugin-information',
					'TB_iframe' => true,
					'width'     => 600,
					'height'    => 550
				),
				network_admin_url( 'plugin-install.php' )
			);

			return sprintf( '<a href="%s" title="%s" class="thickbox button left">%s</a>',
				esc_url( $link ),
				esc_attr( sprintf( 'Install %s', $plugin->name ), 'calderawp-license-manager' ),
				esc_html__( 'Install', 'calderawp-license-manager' )
			);
		}


	}

	protected static  function activation_link( $plugin ){
		return home_url();
	}

	protected static function button( $text, $link, $right = true, $primary = false, $other = '' ){
		ob_start();
		include CALDERA_WP_LICENSE_MANAGER_PATH . 'ui/button.php';
		$template = ob_get_clean();
		$template = str_replace( '{{link}}', esc_url( $link ), $template );
		$template = str_replace( '{{text}}', esc_html( $text ), $template );

		$class = 'button';
		if( $right ){
			$class .= ' right';
		}else{
			$class .= ' left';
		}

		if( $primary ){
			$class .= ' primary button-primary';
		}

		$template = str_replace( '{{other}}', $other, $template );
		$template = str_replace( '{{class}}', $class, $template );
		return $template;




	}
	
}

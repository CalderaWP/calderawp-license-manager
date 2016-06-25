<?php
/**
 * Helper functions for constructing our UI
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager\ui;
use calderawp\licensemanager\api\deactivate;
use calderawp\licensemanager\base;
use calderawp\licensemanager\license;
use calderawp\licensemanager\lm;



class ui extends base {

	/**
	 * URL to submit admin form against
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
    public static function submit_url(){
	  
        return admin_url( );
    }

	/**
	 * URl for main admin
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
    public static function main_url(){
        $url = add_query_arg( 'page', 'cwp-lm', admin_url( make::menu_parent() ) );

	    /**
	     * Filter URL for main admin
	     *
	     * @since 2.0.0
	     */
        return apply_filters( 'calderawp_license_manage_main_url', $url );
    }

	/**
	 * URL for a tab
	 *
	 * @since 2.0.0
	 *
	 * @param string $tab Tab name
	 *
	 * @return string
	 */
    public static function tab_url( $tab ){
        $url = self::main_url();
        if( array_key_exists( $tab, self::tabs() ) ){
            $url = add_query_arg( 'cwp-lm-tab', $tab, $url );
        }
        
        return $url;
        
    }

	/**
	 * Get tab_name => tab description
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
    public static function tabs(){

	    $tabs = array(
		    'account'       => __( 'Account', 'calderawp-license-manager' ),
		    'caldera-forms' => __( 'Caldera Forms Add-ons', 'calderawp-license-manager' ),
		    'caldera-other' => __( 'Other CalderaWP Plugins', 'calderawp-license-manager' ),
		    'beta'          => __( 'Beta', 'calderawp-license-manager' ),
	    );

	    return $tabs;
    }

	/**
	 * Get HTML for a plugin in admin UI
	 *
	 * @since 2.0.0
	 *
	 * @param \stdClass $plugin Plugin object
	 * @param int $id CWP download ID
	 *
	 * @return mixed|string|void
	 */
	public static function plugin_view( $plugin, $id  ){
		if( ! is_object( $plugin ) || ! property_exists( $plugin, 'name' ) ){
			return;
		}

		$plugin->id = $id;

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

		if( false == $license ){
			$license = null;
		}

		$button = self::button_markup( $plugin, $installed, $active, $license, $has_activations, $activations );


		$template = str_replace( '{{button}}', $button, $template );
		
		return $template;
	}

	/**
	 * Make the buttons
	 *
	 * @since 2.0.0
	 *
	 * @param \stdClass $plugin Plugin object
	 * @param bool $installed Is plugin installed?
	 * @param bool $active Is plugin active?
	 * @param license|null $license License object or null if not found.
	 * @param bool $has_activations Are activations available?
	 * @param string $activations Activations text
	 *
	 * @return mixed|string
	 */
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
			$price_html = esc_html__( 'Free: Learn More', 'calderawp-license-manager' );
			$price = 0;
			if( ! empty( $plugin->prices ) ){
				foreach ( $plugin->prices as $v_price ) {
					if( 0 == $price || ( property_exists( $v_price, 'amount' ) && $v_price->amount < $price ) ){
						$price = $v_price->amount;
					}
				}
			}

			if( 0 != $price ){
				//$price_html = sprintf( '%s %s', esc_html__( 'Learn More', 'calderawp-license-manager' ), '$' . $price );
				$price_html =  esc_html__( 'Learn More', 'calderawp-license-manager' );
			}

			
			if( 0 == $price ){
				$other = self::install_button( $plugin, null );
			}elseif( is_object( $license ) ){
				$other = self::install_button( $plugin, $license );

			}else{
				$other = self::buy_button( $plugin );
			}

			$button = self::button( $price_html, $plugin->link, true, false, $other );

		}

		return $button;
	}

	/**
	 * Make an install button HTML
	 *
	 * @since 2.0.0
	 *
	 * @param \stdClass $plugin Plugin object
	 * @param license|null $license Optional. License object or null if it isn't availble
	 * @param bool $right Optional. Align right? Default is false
	 *
	 * @return mixed|string
	 */
	protected static function install_button( $plugin, license $license = null, $right = false ){
		if( null !== $license ){
			$link = install::link( $license->license, $license->download, false );
			return self::button( 'Install', $link, $right, true );
		}else{
			$slug = 'caldera-forms';
			$_slug = lm::get_instance()->plugin->dot_org_slug( $plugin->id );
			if( is_string( $slug ) ){
				$slug = $_slug;
			}
			$link = add_query_arg(
				array(
					'plugin'    => $slug,
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

	/**
	 * Make a button
	 *
	 * @since 2.0.0
	 *
	 * @param string $text Text for link
	 * @param string $link URL
	 * @param bool $right Optional. If true, the default, button is aligned right. Else alignment is to the left.
	 * @param bool $primary Optional. If true, button is a primary button. Default is false.
	 * @param string $other Optional. Additional markup to put inside main element before link element.
	 *
	 * @return mixed|string
	 */
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

	/**
	 * Construct a license view
	 *
	 * @since 2.0.0
	 *
	 * @param license $license
	 *
	 * @return mixed|string
	 */
	public static function license( license $license ){
		ob_start();
		include CALDERA_WP_LICENSE_MANAGER_PATH . 'ui/license.php';
		$template = ob_get_clean();
		$sites = esc_html__( 'License not active on any site', 'calderawp-license-manger' );
		if( ! empty( $license->sites ) ){
			$sites = array();
			$pattern = '<li><a class="button" href="%s" title="%s"><pre style="display: inline">%s</pre> %s</a></li>';
			foreach( $license->sites as $site ){
				$sites[] = sprintf( $pattern,
					esc_url( deactivate::link( $license->license, $license->download, home_url() ) ),
					esc_attr__( 'Click to deactivate license', 'calderawp-license-manager' ),
					esc_url( $site ),
					esc_html__( 'Deactivate License On Site', 'calderawp-license-manager' )
				);
			}

			$sites = sprintf( '<h5>%s</h5><ul class="sites">%s</ul>',
				esc_html__( 'Sites License Is Active On', 'calderawp-license-manger' ),
				implode( "\n\n", $sites )
			);
		}

		$install_here = '';
		$installed = lm::get_instance()->plugin->is_installed( $license->title );
		if( ! $installed ){
			$install_here = self::install_button( $license->obj, $license, true );
		}
				
		$title = $license->title;
		
		foreach( array( 'title', 'install_here','sites' ) as $substitution ){
			$template = str_replace( '{{' . $substitution . '}}', $$substitution, $template );
		}
		
		return $template;
	}

	/**
	 * Create a buy button, adds to cart on CWP
	 *
	 * @since 2.0.0
	 *
	 * @param \stdClass $plugin
	 *
	 * @return string
	 */
	protected static function buy_button( \stdClass $plugin ){

		return sprintf( '<a target="_blank" href="%s" title="%s" class="button cwp-buy-button button-primary">%s</a>',
			esc_url( self::buy_link( $plugin->id ) ),
			esc_attr( sprintf( 'Click To Purchase %s', $plugin->name ), 'calderawp-license-manager' ),
			esc_html__( 'Purchase', 'calderawp-license-manager' )
		);


	}

	/**
	 * Create the link to add to cart on cwp
	 *
	 * @since 2.0.0
	 *
	 * @param int $id Download ID on CWP
	 *
	 * @return string
	 */
	protected static function buy_link( $id ){
		return add_query_arg( array(
			'edd_action' => 'add_to_cart',
			'download_id' => absint( $id )
		), 'https://calderawp.com/checkout' );
		
	}
	
}

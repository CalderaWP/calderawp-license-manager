<?php
/**
 * Main view 
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

$current_tab = isset( $_GET[ 'cwp-lm-tab' ]) ? $tab = $_GET[ 'cwp-lm-tab' ] : $current_tab = 'account';
$tabs        = ui::tabs();

$error = $message = false;
if( isset( $_GET[ 'cwp-lm-error' ] ) && 1 == $_GET[ 'cwp-lm-error' ] ) {
    $error = true;
}

if( isset( $_GET[ 'cwp-lm-message' ] ) ){
	$message = urldecode( $_GET[ 'cwp-lm-message' ] );
}



?>
<style>
    .cwp-lm-panel{
        margin: 10px; width: 95%; float: left; overflow: auto; border: 1px solid rgba(0, 0, 0, 0.15); box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); background:#fff;position: relative; padding:12px;
    }

	.cwp-lm-panel-footer {
		margin: 0px; padding: 6px 7px;
	}
</style>
<div class="wrap cwp-license-manager">
    

    <h2 class="nav-tab-wrapper">
        <?php
        foreach ( $tabs as $tab => $label ) {
            $class = '';
    
            if ( $tab == $current_tab ) {
                $class = ' nav-tab-active';
                
            }
    
            $url = ui::tab_url( $tab );
            ?>
            <a href="<?php echo esc_url( $url ); ?>" class="cwp-lm-tab nav-tab<?php echo esc_attr( $class ); ?>" data-tab="<?php echo esc_attr( $tab ); ?>">
                <?php echo esc_html($label); ?>
            </a>
            <?php
        }
        ?>
    </h2>
    <div id="calderawp-license-manager-message">
	    <?php if ( is_string( $message ) ) { ?>
        <div class="notice <?php if ( true == $error ) : echo 'notice-error'; endif;?>">
            <p><?php echo esc_html( $message ); ?></p>
        </div>
	    <?php } ?>
    </div>

    <div id="calderawp-license-manager-tabs">
        <?php
        printf( '<div class="calderawp-license-manager-tab" id="%s">', esc_attr( $tab ) );
        include  CALDERA_WP_LICENSE_MANAGER_PATH . 'ui/' . $current_tab . '.php';
        echo '</div>';
        ?>
    </div>
	
</div>

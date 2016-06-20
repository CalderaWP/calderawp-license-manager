<?php
namespace calderawp\licensemanager\ui;
//wp_enqueue_script( ui::plugin_slug );
use calderawp\licensemanager\plugin;

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

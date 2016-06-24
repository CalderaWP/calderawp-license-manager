<?php
/**
 * Account tab 
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\licensemanager\ui;

use calderawp\licensemanager\lm;

if( ! defined( 'ABSPATH' ) ){
	exit;
}

$token = lm::get_instance()->get_token();
if( ! $token ) :
?>
<form id="cwp-lm-account" action="<?php echo esc_url( ui::submit_url() ); ?>" method="post">
	<div class="calderawp_license_manager-config-group">
		<label for="cwp-lm-username" id="cwp-lm-username-label">
			<?php esc_html_e( 'CalderaWP Username', 'calderawp-license-manger' ); ?>
		</label>
		<input id="cwp-lm-username" name="username" aria-labelledby="cwp-lm-username-label" />
	</div>
	<div class="calderawp_license_manager-config-group">
		<label for="cwp-lm-password" id="cwp-lm-password-label">
			<?php esc_html_e( 'CalderaWP Password', 'calderawp-license-manger' ); ?>
		</label>
		<input id="cwp-lm-password" name="password" aria-labelledby="cwp-lm-pasaword-label" />
	</div>
	<?php wp_nonce_field( 'account', 'cwp-lm-save' ); ?>
	<input id="cwp-lm-type" type="hidden" value="account" name="type" />
	<div class="calderawp_license_manager-config-group">
		<input type="submit" value="<?php esc_attr_e( 'Login To CalderaWP','calderawp-license-manager' ); ?>" />


</form>
<?php
else :
	$display_name = lm::get_instance()->account->get_displayname();
?>
	<div class="calderawp_license_manager-config-group">
		<?php printf( 'You are logged in to CalderaWP as %s', $display_name ); ?>
	</div>

	<div class="calderawp_license_manager-config-group">
		<?php printf( 'You can view your full account history %s', sprintf(
			'<a href="https://calderawp.com/checkout/purchase-history/" title="%s" target="_blank">%s</a>',
			esc_attr__( 'View account history on CalderaWP.com', 'calderawp-license-manager' ),
			esc_html__( 'here', 'calderawp-license-manager' )
		) ); ?>
	</div>

	<form id="cwp-lm-account" action="<?php echo esc_url( ui::submit_url() ); ?>" method="post">
		<?php wp_nonce_field( 'logout', 'cwp-lm-save' ); ?>
		<input id="cwp-lm-type" type="hidden" value="logout" name="type" />
		<div class="calderawp_license_manager-config-group">
			<input type="submit" value="<?php esc_attr_e( 'Logout From CalderaWP','calderawp-license-manager' ); ?>" />


	</form>
	<div style="clear: both"></div>

	<?php
	$plugins = \calderawp\licensemanager\lm::get_instance()->plugins->get_plugins( 'licensed' );
	if( is_array( $plugins ) && ! empty( $plugins ) ) {
		foreach( $plugins as $licencse ){
			echo ui::license( $licencse );
		}

	}




endif;


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
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row">
				<label for="cwp-lm-username" id="cwp-lm-username-label">
					<?php esc_html_e( 'CalderaWP Username', 'calderawp-license-manger' ); ?>
				</label>
			</th>
			<td>
				<input id="cwp-lm-username" type="text" name="username" aria-labelledby="cwp-lm-username-label" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="cwp-lm-password" id="cwp-lm-password-label">
					<?php esc_html_e( 'CalderaWP Password', 'calderawp-license-manger' ); ?>
				</label>
			</th>
			<td>
				<input id="cwp-lm-password" name="password" type="password" aria-labelledby="cwp-lm-pasaword-label" class="regular-text" />
			</td>
		</tr>
		</tbody>
	</table>

	<input id="cwp-lm-type" type="hidden" value="account" name="type" />

	<?php wp_nonce_field( 'account', 'cwp-lm-save' ); ?>

	<p class="submit">
		<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Login To CalderaWP','calderawp-license-manager' ); ?>" />
	</p>

</form>
<?php
else :
	$display_name = lm::get_instance()->get_display_name();
?>
	<div class="cwp-lm-panel" style="padding-bottom: 0px;">
		<div>
			<?php printf( 'You are logged in to CalderaWP as %s', $display_name ); ?>
		</div>

		<?php printf( 'You can view your full account history %s', sprintf(
			'<a href="https://calderawp.com/checkout/purchase-history/" title="%s" target="_blank">%s</a>',
			esc_attr__( 'View account history on CalderaWP.com', 'calderawp-license-manager' ),
			esc_html__( 'here', 'calderawp-license-manager' )
		) ); ?>

		<div class="cwp-lm-panel-footer" style="padding-bottom: 0px;">

			<form id="cwp-lm-account" action="<?php echo esc_url( ui::submit_url() ); ?>" method="post">
				<?php wp_nonce_field( 'logout', 'cwp-lm-save' ); ?>
				<input id="cwp-lm-type" type="hidden" value="logout" name="type" />
				<p class="submit">
					<input type="submit" class="button button-secondary" value="<?php esc_attr_e( 'Logout Of CalderaWP','calderawp-license-manager' ); ?>" />

</p>
			</form>
		</div>

	</div>



	<div style="clear: both"></div>

	<?php
	$plugins = \calderawp\licensemanager\lm::get_instance()->plugins->get_plugins( 'licensed' );
	if( is_array( $plugins ) && ! empty( $plugins ) ) {
		foreach( $plugins as $licencse ){
			echo ui::license( $licencse );
		}

	}




endif;


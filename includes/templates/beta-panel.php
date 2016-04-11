<?php

	// Panel template for licensing
$always_status = (int) CalderaWP_License_Manager_Update_Options::always_update_cf();
$disable = __( 'Disable Beta Updates', 'calderawp_license_manager' );
$enable =  __( 'Enable Beta Updates', 'calderawp_license_manager' );
if( 1 != $always_status ) {
	$beta_button = $enable;
}else{
	$beta_button = $disable;
}
?>
<div class="calderawp_license_manager-config-group">

	<div style="display:inline-block; width:auto; margin-right:60px;">
		<input type="button" class="button" name="cf-always-beta" value="<?php echo esc_attr( $beta_button ); ?>" id="calderawp_license_manager-cf-always-beta" aria-describedby="calderawp_license_manager-cf-always-beta-description" data-beta-state="<?php echo esc_attr( $always_status ); ?>">
		<span style="float: none; margin: 0 0px -5px 10px;" class="spinner" id="calderawp_license_manager-cf-beta-spinner" aria-hidden="true"></span>

	</div>
	<p class="description" id="calderawp_license_manager-cf-always-beta-description">
		<?php esc_html_e( 'If checked WordPress will always use the latest beta when updating Caldera Forms', 'calderawp_license_manager' ); ?>
	</p>
</div>
<input id="calderawp_license_manager-cf-always-beta-nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'cf-always-beta' ) ); ?>" />
<input id="calderawp_license_manager-cf-always-beta-enabled-val" type="hidden" value="<?php echo esc_attr( $enable ); ?>" />
<input id="calderawp_license_manager-cf-always-beta-disabled-val" type="hidden" value="<?php echo esc_attr( $disable ); ?>" />

<div class="calderawp_license_manager-config-group">

	<div style="display:inline-block; width:auto; margin-right:60px;">
		<a href="<?php echo esc_url( CalderaWP_License_Manager_Plugin_Updates::update_url() ); ?>" type="button" class="button button-primary" name="cf-beta-now" value="1" id="calderawp_license_manager-cf-beta-now">
			<?php esc_html_e( 'Update To Latest Beta Now', 'calderawp_license_manager' ); ?>
		</a>
	</div>
	<p class="description">
		<?php esc_html_e( 'Force update to latest beta of Caldera Forms', 'calderawp_license_manager' ); ?>
	</p>
</div>

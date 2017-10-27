<div class="calderawp_license_manager-main-headercaldera">
	<h2>
		<?php _e( 'CalderaWP License Manager', 'calderawp-license-manager' ); ?>
		<span class="calderawp_license_manager-version">
			<?php echo CALDERA_WP_LICENSE_MANAGER_VER; ?>
		</span>
		<span style="position: absolute; top: 0;" id="calderawp_license_manager-save-indicator">
			<span style="float: none; margin: 10px 0px -5px 10px;" class="spinner"></span>
		</span>
	</h2>

	<div class="updated_notice_box">
		<?php _e( 'Updated Successfully', 'calderawp-license-manager' ); ?>
	</div>
	<div class="error_notice_box">
		<?php _e( 'Could not save changes.', 'calderawp-license-manager' ); ?>
	</div>

	<span class="wp-baldrick" id="calderawp_license_manager-field-sync" data-event="refresh" data-target="#calderawp_license_manager-main-canvas" data-callback="blkbr_canvas_init" data-type="json" data-request="#calderawp_license_manager-live-config" data-template="#main-ui-template"></span>
</div>

<div class="calderawp_license_manager-sub-headercaldera">
	<ul class="calderawp_license_manager-sub-tabs calderawp_license_manager-nav-tabs">
		<li class="{{#is _current_tab value="#calderawp_license_manager-panel-license"}}active {{/is}}calderawp_license_manager-nav-tab">
			<a href="#calderawp_license_manager-panel-license">
				<?php _e('Licenses', 'calderawp-license_manager') ; ?>
			</a>
		</li>

		<li class="{{#is _current_tab value="#calderawp_license_manager-panel-feed"}}active {{/is}}calderawp_license_manager-nav-tab">
			<a href="#calderawp_license_manager-panel-feed">
				<?php _e( 'Learn More About CalderaWP', 'calderawp-license-manager') ; ?>
			</a>
		</li>

		<li class="{{#is _current_tab value="#calderawp_license_manager-panel-feed"}}active {{/is}}calderawp_license_manager-nav-tab">
			<a href="#calderawp_license_manager-panel-beta">
				<?php _e( 'Beta Updates', 'calderawp-license-manager') ; ?>
			</a>
		</li>

		<!--
		<li class="calderawp_license_manager-nav-tab" style="margin-left: 4px">
			<?php printf( 'See your purchase history at %s', sprintf(
					'<a href="https://calderawp.com/checkout/purchase-history/" title=%2s" target="_blank">CalderaWP.com/checkout/purchase-history</a>',
						__( 'CalderaWP Purchase History', 'calderawp-license-manager' )
					)
				);
			?>
		</li>
		-->
	</ul>
</div>

<form class="caldera-main-form has-sub-nav" id="calderawp_license_manager-main-form" action="?page=calderawp_license_manager" method="POST">
	<?php wp_nonce_field( 'calderawp_license_manager', 'calderawp_license_manager-setup' ); ?>
	<input type="hidden" value="calderawp_license_manager" name="id" id="calderawp_license_manager-id">
	<input type="hidden" value="{{_current_tab}}" name="_current_tab" id="calderawp_license_manager-active-tab">

	<div id="calderawp_license_manager-panel-license" class="calderawp_license_manager-editor-panel" {{#is _current_tab value="#calderawp_license_manager-panel-license"}}{{else}} style="display:none;" {{/is}}>
		<h4>
			<?php _e('Add and Manage Licenses from CalderaWP', 'calderawp-license-manager') ; ?>
			<small class="description">
				<?php _e('Licenses', 'calderawp-license-manager') ; ?>
			</small>
		</h4>
		<?php
			// pull in the general settings template
			include CALDERA_WP_LICENSE_MANAGER_PATH . 'includes/templates/license-panel.php';
		?>
	</div>

	<?php
		//Pull in products, etc. via the API
	?>
	<div id="calderawp_license_manager-panel-feed" class="calderawp_license_manager-editor-panel" {{#is _current_tab value="#calderawp_license_manager-panel-feed"}}{{else}} style="display:none;" {{/is}}>

			<div id="calderawp_license_manager-extend" class="wp-baldrick" data-request="<?php esc_url( admin_url( 'admin-ajax.php') ); ?>" data-target="#calderawp_license_manager-featured-plugins" data-action="cwp_license_manager_featured" data-event="none" data-autoload="true" data-template="#featured-modal-tmpl"></div>
			<div id="calderawp_license_manager-featured-plugins"></div>
			<div class="clear"></div>
<!--
			<div id="calderawp_license_manager-signups" class="wp-baldrick" data-request="<?php //esc_url( admin_url( 'admin-ajax.php') ); ?>" data-target="#calderawp_license_manager-signups-forms" data-action="cwp_license_manager_signups" data-event="none" data-autoload="true" data-template="#signups-modal-tmpl"></div>
			<div id="calderawp_license_manager-signups-forms" style="background-color: white; padding: 10px 0 10px 3%; max-width: 830px; margin-left: 10px;"></div>
			<div class="clear"></div>
-->
	</div>

	<div id="calderawp_license_manager-panel-beta" class="calderawp_license_manager-editor-panel" {{#is _current_tab value="#calderawp_license_manager-panel-beta"}}{{else}} style="display:none;" {{/is}}>
	<h4>
		<?php
			esc_html_e(' Caldera Forms Beta', 'calderawp-license-manager') ;
		?>
		<small class="description">
			<?php esc_html_e('Get The Latest Caldera Forms', 'calderawp-license-manager') ; ?>
		</small>
	</h4>
	<?php
	// pull in the general settings template
	if(  defined( 'CFCORE_BASENAME' ) ){
		include CALDERA_WP_LICENSE_MANAGER_PATH . 'includes/templates/beta-panel.php';
	}else{
		esc_html_e(  'Beta updates are available for Caldera Forms Only.', 'calderawp-license-manager'  );
		printf( '<a href="https://CalderaWP.com/downloads/caldera-forms" target="_blank" title="%1s" ><img src="//200s1i13m4ae1a9s82cmbbxm-wpengine.netdna-ssl.com/wp-content/uploads/2016/06/cf-banner.png" alt="%2s" style="%3s" /></a>',
			esc_attr__( 'Learn More About Caldera Forms', 'calderawp-license-manger' ),
			esc_attr__(  'Caldera Forms Banner', 'calderawp-license-manger' ),
			'width:100%;height:auto;'

		);
	}

	?>
	</div>


	<div class="clear"></div>

</form>

{{#unless _current_tab}}
	{{#script}}
		jQuery(function($){
			$('.calderawp_license_manager-nav-tab').first().trigger('click').find('a').trigger('click');
		});
	{{/script}}
{{/unless}}
<script>
	jQuery( '#calderawp_license_manager-cf-always-beta' ).click(function(){
		jQuery( '#calderawp_license_manager-cf-beta-spinner' ).attr( 'aria-hidden', false ).css( 'visibility', 'visible' ).show();
		var nonce = jQuery('#calderawp_license_manager-cf-always-beta-nonce' ).val();
		var state = jQuery( this ).attr( 'data-beta-state' );
		var data = {
			action: 'cf_beta_state',
			_nonce: nonce,
			state: state
		};

		jQuery.post( ajaxurl, data, function(response) {
			jQuery( '#calderawp_license_manager-cf-beta-spinner' ).attr( 'aria-hidden', true ).css( 'visibility', 'hidden' ).hide();
			if( true == response.data ){
				jQuery( '#calderawp_license_manager-cf-always-beta' ).val( jQuery( '#calderawp_license_manager-cf-always-beta-enabled-val' ).val() );
				jQuery( '#calderawp_license_manager-cf-always-beta' ).attr( 'data-beta-state', 1 );
			}else{
				jQuery( '#calderawp_license_manager-cf-always-beta' ).val( jQuery( '#calderawp_license_manager-cf-always-beta-disabled-val' ).val() );
				jQuery( '#calderawp_license_manager-cf-always-beta' ).attr( 'data-beta-state', 0 );
			}
			
		});

	});
</script>




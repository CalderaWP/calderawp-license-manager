<div class="blackbriar-main-headercaldera">
		<h2>
		<?php _e( 'BlackBriar License Manager', 'blackbriar' ); ?> <span class="blackbriar-version"><?php echo BLKBR_VER; ?></span>
		<span style="position: absolute; top: 0;" id="blackbriar-save-indicator"><span style="float: none; margin: 10px 0px -5px 10px;" class="spinner"></span></span>
	</h2>
		<div class="updated_notice_box"><?php _e( 'Updated Successfully', 'blackbriar' ); ?></div>
	<div class="error_notice_box"><?php _e( 'Could not save changes.', 'blackbriar' ); ?></div>	

	<span class="wp-baldrick" id="blackbriar-field-sync" data-event="refresh" data-target="#blackbriar-main-canvas" data-callback="blkbr_canvas_init" data-type="json" data-request="#blackbriar-live-config" data-template="#main-ui-template"></span>
</div>
<div class="blackbriar-sub-headercaldera">
	<ul class="blackbriar-sub-tabs blackbriar-nav-tabs">
		<li class="{{#is _current_tab value="#blackbriar-panel-license"}}active {{/is}}blackbriar-nav-tab"><a href="#blackbriar-panel-license"><?php _e('Licenses', 'blackbriar') ; ?></a></li>
		<li class="{{#is _current_tab value="#blackbriar-panel-feed"}}active {{/is}}blackbriar-nav-tab"><a href="#blackbriar-panel-feed"><?php _e('Extend', 'blackbriar') ; ?></a></li>
	</ul>
</div>

<form class="caldera-main-form has-sub-nav" id="blackbriar-main-form" action="?page=blackbriar" method="POST">
	<?php wp_nonce_field( 'blackbriar', 'blackbriar-setup' ); ?>
	<input type="hidden" value="blackbriar" name="id" id="blackbriar-id">
	<input type="hidden" value="{{_current_tab}}" name="_current_tab" id="blackbriar-active-tab">

	<div id="blackbriar-panel-license" class="blackbriar-editor-panel" {{#is _current_tab value="#blackbriar-panel-license"}}{{else}} style="display:none;" {{/is}}>		
		<h4><?php _e('Add and Manage Licenses from CalderaWP', 'blackbriar') ; ?> <small class="description"><?php _e('Licenses', 'blackbriar') ; ?></small></h4>
		<?php
		// pull in the general settings template
		include BLKBR_PATH . 'includes/templates/license-panel.php';
		?>
	</div>

	<div id="blackbriar-panel-feed" class="blackbriar-editor-panel" {{#is _current_tab value="#blackbriar-panel-feed"}}{{else}} style="display:none;" {{/is}}>
		<div id="blackbriar-extend" class="wp-baldrick" data-request="https://api.calderaforms.com/1.0/marketing/extensions/?version=1.2.0" data-target="#blackbriar-extend" data-template="#extensions-modal-tmpl" data-event="none" data-autoload="true">
		</div>
	</div>

	
	<div class="clear"></div>
	<?php /*<div class="blackbriar-footer-bar">
		<button type="submit" class="button button-primary wp-baldrick" data-action="blkbr_save_config" data-active-class="none" data-callback="blkbr_handle_save" data-load-element="#blackbriar-save-indicator" data-before="blkbr_get_config_object" ><?php _e('Save Changes', 'blackbriar') ; ?></button>
	</div> */ ?>	

</form>

{{#unless _current_tab}}
	{{#script}}
		jQuery(function($){
			$('.blackbriar-nav-tab').first().trigger('click').find('a').trigger('click');
		});
	{{/script}}
{{/unless}}
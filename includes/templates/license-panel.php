<?php

	// Panel template for licensing

?>
<input id="active_calderawp_plugins" type="hidden" value="{{json plugins}}" name="plugins">
<input id="active_edit_license" data-live-sync="true" type="hidden" value="{{active_edit_license}}" name="active_edit_license">

<div class="calderawp_license_manager-module-side">

	
	<ul class="calderawp_license_manager-module-tabs calderawp_license_manager-group-wrapper" style="box-shadow: 0px 1px 0px rgb(207, 207, 207) inset;">
	{{#each license}}
		<li class="{{_id}} calderawp_license_manager-module-tab {{#is ../active_edit_license value=_id}}active{{/is}}">
			{{:node_point}}
			{{#unless config/license_name}}				
				<a><select style="width: 235px;" class="autofocus-input" id="calderawp_license_manager-plugin" data-id="{{_id}}" name="{{:name}}[config][license_name]" data-live-sync="true" value="{{config/license_name}}" id="caldera_todo-license_name-{{_id}}">
					<option value=""><?php echo __('Select Plugin to License', 'calderawp_license_manager'); ?></option>
					{{#each @root/plugins}}
						{{#find @root/licensed name}}
							<option value="{{this}}" disabled="disabled">{{this}}</option>
						{{else}}
							<option value="{{@key}}">{{name}}</option>
						{{/find}}
					{{/each}}
				</select></a>
			{{else}}
				<a href="#" class="sortable-item calderawp_license_manager-edit-license" data-id="{{_id}}">
					<span class="license_title_{{_id}}">{{config/license_name}}{{#if config/active}}<span class="dashicons {{#is config/active value="expired"}}dashicons-no-alt{{else}}dashicons-yes{{/is}}"></span>{{/if}}</span>
					{{#if config/active}}<p id="license_key_{{_id}}" class="description" style="{{#is @root/active_edit_license value=_id}}color: rgba(255, 255, 255, 0.6); {{/is}}font-size: 0.9em;">
						{{#is config/active value="expired"}}<?php echo __('Expired', 'calderawp_license_manager'); ?>{{else}}<?php echo __('Expires', 'calderawp_license_manager'); ?>: {{config/active}}{{/is}}</p>
					{{/if}}
					<input type="hidden" value="{{config/license_name}}" name="licensed[]">
				</a>
			{{/unless}}

			{{#is ../active_edit_license not=_id}}<input type="hidden" name="{{:name}}[config]" value="{{json config}}">{{/is}}
			{{#if new}}<input class="wp-baldrick" data-request="blkbr_record_change" data-autoload="true" data-live-sync="true" type="hidden" value="{{_id}}" name="active_edit_license">{{/if}}

		</li>
	{{/each}}
	{{#unless license}}
		<li class="calderawp_license_manager-module-tab">
			<p class="description" style="margin: 0px; padding: 9px 22px;">
				<?php _e( 'No Licenses', 'calderawp_license_manager' ); ?>
			</p>
		</li>
	{{/unless}}
		<li class="calderawp_license_manager-module-tab" style="text-align: center; padding: 12px 22px; background-color: rgb(225, 225, 225); box-shadow: -1px 0 0 #cfcfcf inset, 0 1px 0 #cfcfcf inset, 0 -1px 0 #cfcfcf inset;">
			<button style="width: 100%;" class="wp-baldrick button" data-node-default='{ "new" : "true" }' data-add-node="license" type="button">
				<?php _e( 'Add License', 'calderawp_license_manager' ); ?>
			</button>
		</li>
		<li class="calderawp_license_manager-module-tab" style="border-bottom: 1px solid rgb(207, 207, 207);"><a href="https://calderawp.com/checkout/purchase-history/" title="<?php _e( 'Purchase History', 'calderawp-license-manager' ); ?>" target="_blank"><?php _e( 'Purchase History at CalderaWP.com', 'calderawp-license-manager' ); ?></a></li>
	</ul>

</div>

{{#find license active_edit_license}}

	{{#if config/license_name}}
	
	<div class="calderawp_license_manager-field-config-wrapper {{_id}}" style="width:580px;">

		{{#unless config/active}}
			<button type="button" class="button cancle-button" data-remove-element=".{{_id}}" style="float: right;">
				<?php _e( 'Cancel', 'calderawp_license_manager' ); ?>
			</button>
		{{/unless}}
		
		<div style="border-bottom: 1px solid rgb(209, 209, 209); margin: 0px 0px 12px; padding: 3px 0px 12px;">
			<input type="hidden" name="{{:name}}[config][license_name]" value="{{config/license_name}}">
			<strong style="font-size: 15px;">{{config/license_name}}</strong>
		</div>

			<div class="calderawp_license_manager-config-group">
				{{#unless config/active}}
				<label for="calderawp_license_manager-key"><?php _e( 'License Key', 'calderawp_license_manager' ); ?>
					<span id="key-loading-{{_id}}">
						<span style="float: none; margin: 0;" class="spinner">

						</span>
					</span>
				</label>
				<input {{#find @root/plugins config/license_name}}data-url="{{url}}"{{/find}} data-name="{{:name}}[config][active]" style="width: 250px;" id="key-input-{{_id}}" data-item="{{config/license_name}}" placeholder="License key" class="wp-baldrick key-input" data-load-element="#key-loading-{{_id}}" data-target="#license-info-{{_id}}" data-event="sync" data-action="calderawp_license_manager_check_license" data-id="{{_id}}" type="text" name="{{:name}}[config][license_key]" value="{{config/license_key}}" data-sync="#license_key_{{_id}}" id="calderawp_license_manager-key" required>
				<button type="button" style="float:right;" class="button button-primary wp-baldrick" data-for="#key-input-{{_id}}"><?php _e( 'Verify License', 'calderawp_license_manager' ); ?></button>
				<div id="license-info-{{_id}}" style="margin-top: 12px;"></div>
				{{else}}
					<input id="current-active-plugin" type="hidden" data-live-sync="true" name="{{:name}}[config][active]" value="{{config/active}}">
					<input data-autoload="true" {{#find @root/plugins config/license_name}} data-url="{{url}}" {{/find}} {{#is config/active value="expired"}}data-nosave="true"{{/is}} data-refresh="true" style="width: 250px;" id="key-input-{{_id}}" data-name="{{:name}}[config][active]" data-item="{{config/license_name}}" placeholder="License key" class="wp-baldrick key-input" data-load-element="#key-loading-{{_id}}" data-target="#license-info-{{_id}}" data-event="sync" data-action="calderawp_license_manager_check_license" data-id="{{_id}}" type="hidden" name="{{:name}}[config][license_key]" value="{{config/license_key}}" data-sync="#license_key_{{_id}}" id="calderawp_license_manager-key" required>

					<div id="license-info-{{_id}}" style="margin-top: 12px;">
						<span id="key-loading-{{_id}}">
							<span style="float: none; margin: 0;" class="spinner"></span>
						</span>
					</div>
				{{/unless}}


			</div>


	</div>

	{{/if}}

{{/find}}



{{#script}}
jQuery('.calderawp_license_manager-edit-license').on('click', function(){
	var clicked = jQuery(this),
		active = jQuery('#active_edit_license');

		if( active.val() == clicked.data('id') ){
			active.val('').trigger('change');
		}else{
			active.val( clicked.data('id') ).trigger( 'change' );
		}
});
jQuery('.autofocus-input').focus().on('blur', function(){ 
	if( jQuery(this).val() == '' ){
		jQuery( '.' + jQuery(this).data('id') ).remove();
		blkbr_record_change();
	}
});
jQuery(document).on('click','.reset-current-key', function(){
	jQuery('#current-active-plugin').val('').trigger('change');
});
{{/script}}

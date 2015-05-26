<?php

	// Panel template for My Tasks

?>
<input id="active_calderawp_plugins" type="hidden" value="{{json plugins}}" name="plugins">
<input id="active_edit_license" data-live-sync="true" type="hidden" value="{{active_edit_license}}" name="active_edit_license">

<div class="blackbriar-module-side">

	
	<ul class="blackbriar-module-tabs blackbriar-group-wrapper" style="box-shadow: 0px 1px 0px rgb(207, 207, 207) inset;">
	{{#each license}}
		<li class="{{_id}} blackbriar-module-tab {{#is ../active_edit_license value=_id}}active{{/is}}">
			{{:node_point}}
			{{#unless config/license_name}}				
				<a><select style="width: 235px;" class="autofocus-input" id="blackbriar-plugin" data-id="{{_id}}" name="{{:name}}[config][license_name]" data-live-sync="true" value="{{config/license_name}}" id="caldera_todo-license_name-{{_id}}">
					<option value=""><?php echo __('Select Plugin to License', 'blackbriar'); ?></option>
					{{#each @root/plugins}}
						{{#find @root/licensed name}}
						<option value="{{this}}" disabled="disabled">{{this}}</option>
						{{else}}
						<option value="{{@key}}">{{name}}</option>
						{{/find}}
					{{/each}}
				</select></a>
			{{else}}
				<a href="#" class="sortable-item blackbriar-edit-license" data-id="{{_id}}">
					<span class="license_title_{{_id}}">{{config/license_name}}{{#if config/active}}<span class="dashicons dashicons-yes"></span>{{/if}}</span>
					{{#if config/active}}<p id="license_key_{{_id}}" class="description" style="{{#is @root/active_edit_license value=_id}}color: rgba(255, 255, 255, 0.6); {{/is}}font-size: 0.9em;"><?php echo __('Expires', 'blackbriar'); ?>: {{config/active}}</p>{{/if}}
					<input type="hidden" value="{{config/license_name}}" name="licensed[]">
				</a>
			{{/unless}}

			{{#is ../active_edit_license not=_id}}<input type="hidden" name="{{:name}}[config]" value="{{json config}}">{{/is}}
			{{#if new}}<input class="wp-baldrick" data-request="blkbr_record_change" data-autoload="true" data-live-sync="true" type="hidden" value="{{_id}}" name="active_edit_license">{{/if}}

		</li>
	{{/each}}
	{{#unless license}}
		<li class="blackbriar-module-tab"><p class="description" style="margin: 0px; padding: 9px 22px;"><?php _e( 'No Licenses', 'blackbriar' ); ?></p></li>
	{{/unless}}
		<li class="blackbriar-module-tab" style="text-align: center; padding: 12px 22px; background-color: rgb(225, 225, 225); box-shadow: -1px 0 0 #cfcfcf inset, 0 1px 0 #cfcfcf inset, 0 -1px 0 #cfcfcf inset;">
			<button style="width: 100%;" class="wp-baldrick button" data-node-default='{ "new" : "true" }' data-add-node="license" type="button"><?php _e( 'Add License', 'blackbriar' ); ?></button>
		</li>
	</ul>

</div>

{{#find license active_edit_license}}

	{{#if config/license_name}}
	
	<div class="blackbriar-field-config-wrapper {{_id}}" style="width:580px;">

		{{#unless config/active}}
			<button type="button" class="button cancle-button" data-remove-element=".{{_id}}" style="float: right;"><?php _e( 'Cancel', 'blackbriar' ); ?></button>
		{{/unless}}
		
		<div style="border-bottom: 1px solid rgb(209, 209, 209); margin: 0px 0px 12px; padding: 3px 0px 12px;">
			<input type="hidden" name="{{:name}}[config][license_name]" value="{{config/license_name}}"><strong style="font-size: 15px;">{{config/license_name}}</strong>
		</div>

			<div class="blackbriar-config-group">				
				{{#unless config/active}}
				<label for="blackbriar-key"><?php _e( 'License Key', 'blackbriar' ); ?><span id="key-loading-{{_id}}"> <span style="float: none; margin: 0;" class="spinner"></span></span></label>		
				<input {{#find @root/plugins config/license_name}}data-url="{{url}}"{{/find}} data-name="{{:name}}[config][active]" style="width: 250px;" id="key-input-{{_id}}" data-item="{{config/license_name}}" placeholder="License key" class="wp-baldrick key-input" data-load-element="#key-loading-{{_id}}" data-target="#license-info-{{_id}}" data-event="sync" data-action="blkbr_check_license" data-id="{{_id}}" type="text" name="{{:name}}[config][license_key]" value="{{config/license_key}}" data-sync="#license_key_{{_id}}" id="blackbriar-key" required>
				<button type="button" style="float:right;" class="button button-primary wp-baldrick" data-for="#key-input-{{_id}}"><?php _e( 'Verify License', 'blackbriar' ); ?></button>
				<div id="license-info-{{_id}}" style="margin-top: 12px;"></div>
				{{else}}
					<input type="hidden" name="{{:name}}[config][active]" value="{{config/active}}">
					<input data-autoload="true" {{#find @root/plugins config/license_name}} data-url="{{url}}" {{/find}} data-refresh="true" style="width: 250px;" id="key-input-{{_id}}" data-item="{{config/license_name}}" placeholder="License key" class="wp-baldrick key-input" data-load-element="#key-loading-{{_id}}" data-target="#license-info-{{_id}}" data-event="sync" data-action="blkbr_check_license" data-id="{{_id}}" type="hidden" name="{{:name}}[config][license_key]" value="{{config/license_key}}" data-sync="#license_key_{{_id}}" id="blackbriar-key" required>
					
					<div id="license-info-{{_id}}" style="margin-top: 12px;"><span id="key-loading-{{_id}}"> <span style="float: none; margin: 0;" class="spinner"></span></span></div>
				{{/unless}}

				

			</div>
			

		

		<!-- Add custom code here fields names are {{:name}}[config][field_name] -->

	</div>

	{{/if}}

{{/find}}



{{#script}}
jQuery('.blackbriar-edit-license').on('click', function(){
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

{{/script}}
<?php
$calderawp_license_manager = CalderaWP_License_Manager_Options::get_single( 'calderawp_license_manager' );

?>
<div class="wrap" id="calderawp_license_manager-main-canvas">
	<span class="wp-baldrick spinner" style="float: none; display: block;" data-target="#calderawp_license_manager-main-canvas" data-callback="blkbr_canvas_init" data-type="json" data-request="#calderawp_license_manager-live-config" data-event="click" data-template="#main-ui-template" data-autoload="true"></span>
</div>

<div class="clear"></div>

<input type="hidden" class="clear" autocomplete="off" id="calderawp_license_manager-live-config" style="width:100%;" value="<?php echo esc_attr( json_encode($calderawp_license_manager) ); ?>">

<script type="text/html" id="main-ui-template">
	<?php
		// pull in the join table card template
		include CALDERA_WP_LICENSE_MANAGER_PATH . 'includes/templates/main-ui.php';
	?>	
</script>


<script type="text/html" id="nav-items-tmpl">
	{{#each channels}}
		<li class="{{#is _current_tab value="#calderawp_license_manager-panel-license"}}active {{/is}}calderawp_license_manager-nav-tab"><a href="#calderawp_license_manager-panel-feed">{{name}}</a></li>
	{{/each}}
</script>


<script type="text/html" id="extensions-modal-tmpl">
{{#if extensions}}
	{{#each extensions}}
	<div {{#if slug}}class="panel_{{slug}}"{{/if}} style="margin: 10px; width: {{#if width}}{{width}}{{else}}200px{{/if}}; float: left; height: {{#if height}}{{height}}{{else}}200px{{/if}}; {{#if box}}overflow: auto; border: 1px solid rgba(0, 0, 0, 0.15); box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);{{/if}}{{#if background}} background:{{background}};{{/if}}{{#if color}} color:{{color}};{{/if}}position: relative;">
		{{#if banner}}<img src="{{banner}}" style="width:100%;vertical-align: top;">{{/if}}
		{{#if name}}<h2>{{name}}</h2>{{/if}}
		{{#if html}}<div style="margin: 0px; padding: 6px 7px;">{{{html}}}</div>{{/if}}
		{{#if buttons}}
		<div style="position: absolute; bottom: 0px; padding: 6px; background: none repeat scroll 0 0 rgba(0, 0, 0, 0.03); left: 0px; right: 0px; border-top: 1px solid rgba(0, 0, 0, 0.06);">
			{{#each buttons}}
				<a class="button {{#if class}}{{class}}{{/if}}" href="{{link}}" target="_blank">{{title}}</a>
			{{/each}}
		</div>
		{{/if}}
	</div>
	{{/each}}
{{else}}
{{#if message}}
	<div class="alert updated"><p>{{{message}}}</p></div>
{{else}}
	<div class="alert error"><p><?php echo __('Unable to connect or no extensions available.', 'caldera-forms'); ?></p></div>
{{/if}}
{{/if}}
</script>

<?php
use calderawp\helpers\vardump;

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

<!-- Template for Featured Plugins-->
<script type="text/html" id="featured-modal-tmpl">
	{{#if items}}
		{{#each items}}
			<div class="calderawp_license_manager-featured-plugin">
				{{#if image_src}}
					<a href="{{link}}" target="_blank">
						<img src="{{image_src}}" style="width:100%;vertical-align: top;">{{/if}}
					</a>

				{{#if tagline}}
					<div style="margin: 0px; padding: 6px 7px;">
						{{{tagline}}}
					</div>
				{{/if}}

				{{#if link}}
					<div style="position: absolute; bottom: 0px; padding: 6px; background: none repeat scroll 0 0 rgba(0, 0, 0, 0.03); left: 0px; right: 0px; border-top: 1px solid rgba(0, 0, 0, 0.06); display:inline-block; text-align: center;">
						<a href="{{link}}" target="_blank" style="text-align: center;" class="button">
							<?php _e( 'Learn More', 'calderawp-license-manager' ); ?>
						</a>
					</div>
				{{/if}}

			</div>
		{{/each}}
	{{else}}
		{{#if message}}
			<div class="alert updated">
				<p>{{{message}}}</p>
			</div>
		{{else}}
			<div class="alert error">
				<p>
					<?php echo __('Unable to connect or no extensions available.', 'calderawp-license-manger' ); ?>
				</p>
			</div>
		{{/if}}
	{{/if}}

</script>


<!-- Template for Signup forms (mailchimp/support/etc-->
<script type="text/html" id="signups-modal-tmpl">
	{{#if items}}
		{{#each items}}
			{{#if title}}
				<div class="signup-item">
					<div class="signup-pre">
						<h3>{{{title}}}</h3>
						{{#if message}}
							<p>{{{message}}}</p>
						{{/if}}
					</div>
					<div class="signup-form">
						{{{form}}}
					</div>
				</div>
			{{/if}}
		{{/each}}
	{{/if}}
</script>


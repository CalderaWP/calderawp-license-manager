<?php
/**
 * License view
 *
 * @package   calderawp-license-manager
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}
?>

<div class="cwp-lm-panel license-panel">
	<div class="license-sites" style="padding-bottom: 25px;">
		<h3>{{title}}</h3>
		{{sites}}
	</div>
	<div style="clear: both"></div>
	<div class="cwp-lm-panel-footer">
		{{install_here}}
	</div>
</div>

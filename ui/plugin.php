<?php
/**
 * Plugin view
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

<div class="panel plugin-panel" style="margin: 10px; width: 320px; float: left; height: 240px; overflow: auto; border: 1px solid rgba(0, 0, 0, 0.15); box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); background:#fff;position: relative;">
	<img src="{{image_src}}" style="width:100%;vertical-align: top;">

	<div style="margin: 0px; padding: 6px 7px;">
		{{tagline}}
		{{button}}
	</div>
</div>

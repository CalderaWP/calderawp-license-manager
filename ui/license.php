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

<div class="panel license-panel" style="margin: 10px; width: 320px; float: left; height: 240px; overflow: auto; border: 1px solid rgba(0, 0, 0, 0.15); box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); background:#fff;position: relative; padding:12px;">
		<h3>{{title}}</h3>
		{{sites}}
	<div style="margin: 0px; padding: 6px 7px;">
		{{install_here}}
	</div>
</div>

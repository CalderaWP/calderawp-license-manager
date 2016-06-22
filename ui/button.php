<?php
/**
 * Button view
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
<div style="bottom: 0px; padding: 6px; background: rgba(0, 0, 0, 0.03) none repeat scroll 0px 0px; left: 0px; right: 0px; border-top: 1px solid rgba(0, 0, 0, 0.06); position: absolute;">
	{{other}}
	<a target="_blank" href="{{link}}" class="{{class}}">
		{{text}}
	</a>
</div>


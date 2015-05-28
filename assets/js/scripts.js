var calderawp_license_manager_canvas = false,
	blkbr_get_config_object,
	blkbr_record_change,
	blkbr_canvas_init,
	blkbr_get_default_setting,
	blkbr_handle_save,	
	blkbr_rebuild_magics,
	init_magic_tags,
	config_object = {},
	magic_tags = [];

jQuery( function($){


	blkbr_handle_save = function( obj ){

		var notice;

		if( obj.data.success ){
			notice = $('.updated_notice_box');
		}else{
			notice = $('.error_notice_box');
		}

		notice.stop().animate({top: -5}, 200, function(){
			setTimeout( function(){
				notice.stop().animate({top: -75}, 200);
			}, 2000);
		});

		blkbr_record_change();

	}


	init_magic_tags = function(){
		//init magic tags
		var magicfields = jQuery('.magic-tag-enabled');

		magicfields.each(function(k,v){
			var input = jQuery(v);
			
			if(input.hasClass('magic-tag-init-bound')){
				var currentwrapper = input.parent().find('.magic-tag-init');
				if(!input.is(':visible')){
					currentwrapper.hide();
				}else{
					currentwrapper.show();
				}
				return;
			}
			var magictag = jQuery('<span class="dashicons dashicons-editor-code magic-tag-init"></span>'),
				wrapper = jQuery('<span style="position:relative;display:inline-block; width:100%;"></span>');

			if(input.is('input')){
				magictag.css('borderBottom', 'none');
			}

			if(input.hasClass('calderawp_license_manager-conditional-value-field')){
				wrapper.width('auto');
			}

			//input.wrap(wrapper);
			magictag.insertAfter(input);
			input.addClass('magic-tag-init-bound');
			if(!input.is(':visible')){
				magictag.hide();
			}else{
				magictag.show();
			}
		});

	}

	// internal function declarationas
	blkbr_get_config_object = function(el){
		// new sync first
		$('#calderawp_license_manager-id').trigger('change');
		var clicked 	= $(el),
			config 		= $('#calderawp_license_manager-live-config').val(),
			required 	= $('[required]'),
			clean		= true;

		for( var input = 0; input < required.length; input++ ){
			if( required[input].value.length <= 0 && $( required[input] ).is(':visible') ){
				$( required[input] ).addClass('calderawp_license_manager-input-error');
				clean = false;
			}else{
				$( required[input] ).removeClass('calderawp_license_manager-input-error');
			}
		}
		if( clean ){
			calderawp_license_manager_canvas = config;
		}
		clicked.data( 'config', config );
		return clean;
	}

	blkbr_record_change = function(){
		// hook and rebuild the fields list
		jQuery(document).trigger('record_change');
		jQuery('#calderawp_license_manager-id').trigger('change');
		if( config_object ){
			jQuery('#calderawp_license_manager-field-sync').trigger('refresh');
		}
	}
	
	blkbr_canvas_init = function(){

		if( !calderawp_license_manager_canvas ){
			// bind changes
			jQuery('#calderawp_license_manager-main-canvas').on('keydown keyup change','input, select, textarea', function(e) {
				config_object = jQuery('#calderawp_license_manager-main-form').formJSON(); // perhaps load into memory to keep it live.
				jQuery('#calderawp_license_manager-live-config').val( JSON.stringify( config_object ) ).trigger('change');
			});

			calderawp_license_manager_canvas = jQuery('#calderawp_license_manager-live-config').val();
			config_object = JSON.parse( calderawp_license_manager_canvas ); // perhaps load into memory to keep it live.
		}
		if( $('.color-field').length ){
			$('.color-field').wpColorPicker({
				change: function(obj){
					$('#calderawp_license_manager-id').trigger('change');
				}
			});
		}
		if( $('.calderawp_license_manager-group-wrapper').length ){
			$( ".calderawp_license_manager-group-wrapper" ).sortable({
				handle: ".sortable-item",
				update: function(){
					jQuery('#calderawp_license_manager-id').trigger('change');
				}
			});
			$( ".calderawp_license_manager-fields-list" ).sortable({
				handle: ".sortable-item",
				update: function(){
					jQuery('#calderawp_license_manager-id').trigger('change');
				}
			});
		}
		// live change init
		$('[data-init-change]').trigger('change');
		$('[data-auto-focus]').focus().select();

		// rebuild tags
		blkbr_rebuild_magics();
		jQuery(document).trigger('canvas_init');
	}
	blkbr_get_default_setting = function(obj){

		var id = 'nd' + Math.round(Math.random() * 99866) + Math.round(Math.random() * 99866),
			trigger = ( obj.trigger ? obj.trigger : obj.params.trigger ),
			sub_id = ( trigger.data('group') ? trigger.data('group') : 'nd' + Math.round(Math.random() * 99766) + Math.round(Math.random() * 99866) ),
			nodes;

		
		// add simple node
		if( trigger.data('addNode') ){
			// new node? add one
			var newnode = { "_id" : id };
			nodes = trigger.data('addNode').split('.');
			var node_point_record = nodes.join('.') + '.' + id,
				node_defaults = JSON.parse( '{ "_id" : "' + id + '", "_node_point" : "' + node_point_record + '" }' );
			if( trigger.data('nodeDefault') && typeof trigger.data('nodeDefault') === 'object' ){				
				$.extend( true, node_defaults, trigger.data('nodeDefault') );
			}			
			var node_string = '{ "' + nodes.join( '": { "') + '" : { "' + id + '" : ' + JSON.stringify( node_defaults );
			for( var cls = 0; cls <= nodes.length; cls++){
				node_string += '}';
			}
			var new_nodes = JSON.parse( node_string );
			$.extend( true, config_object, new_nodes );
		}
		// remove simple node (all)
		if( trigger.data('removeNode') ){
			// new node? add one
			if( config_object[trigger.data('removeNode')] ){
				delete config_object[trigger.data('removeNode')];
			}

		}

		switch( trigger.data('script') ){
			case "add-to-object":
				// add to core object
				//config_object.entry_name = obj.data.value; // ajax method

				break;
			case "add-field-node":
				// add to core object
				if( !config_object[trigger.data('slug')][trigger.data('group')].field ){
					config_object[trigger.data('slug')][trigger.data('group')].field = {};
				}
				config_object[trigger.data('slug')][trigger.data('group')].field[id] = { "_id": id, 'name': 'new field', 'slug': 'new_field' };
				config_object.open_field = id;
				break;				
		}

		jQuery('#calderawp_license_manager-live-config').val( JSON.stringify( config_object ) );
		jQuery('#calderawp_license_manager-field-sync').trigger('refresh');
	}
	// sutocomplete category
	$.widget( "custom.catcomplete", $.ui.autocomplete, {
		_create: function() {
			this._super();
			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
		},
		_renderMenu: function( ul, items ) {
			var that = this,
			currentCategory = "";
			$.each( items, function( index, item ) {
				var li;
				if ( item.category != currentCategory ) {
					ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
					currentCategory = item.category;
				}
				li = that._renderItemData( ul, item );
				if ( item.category ) {
					li.attr( "aria-label", item.category + " : " + item.label );
				}
			});
		}
	});
	blkbr_rebuild_magics = function(){

		function split( val ) {
			return val.split( / \s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}
		$( ".magic-tag-enabled" ).bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB && $( this ).catcomplete( "instance" ).menu.active ) {
				event.preventDefault();
			}
		}).catcomplete({
			minLength: 0,
			source: function( request, response ) {
				// delegate back to autocomplete, but extract the last term
				magic_tags = [];
				var category = '';
				// Search form fields
				if( config_object.search_form && config_object.form_fields ){
					// set internal tags
					var system_tags = [
						'autocomplete_item',
					];					
					category = $('#calderawp_license_manager-label-tags').text();
					for( f = 0; f < system_tags.length; f++ ){
						magic_tags.push( { label: '{' + system_tags[f] + '}', category: category }  );
					}							
				}
				
				response( $.ui.autocomplete.filter( magic_tags, extractLast( request.term ) ) );
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				//terms.push( "" );
				this.value = terms.join( " " );
				return false;
			}
		});
	}	

	// trash 
	$(document).on('click', '.calderawp_license_manager-card-actions .confirm a', function(e){
		e.preventDefault();
		var parent = $(this).closest('.calderawp_license_manager-card-content');
			actions = parent.find('.row-actions');

		actions.slideToggle(300);
	});

	// bind slugs
	$(document).on('keyup change', '[data-format="slug"]', function(e){

		var input = $(this);

		if( input.data('master') && input.prop('required') && this.value.length <= 0 && e.type === "change" ){
			this.value = $(input.data('master')).val().replace(/[^a-z0-9]/gi, '_').toLowerCase();
			if( this.value.length ){
				input.trigger('change');
			}
			return;
		}

		this.value = this.value.replace(/[^a-z0-9]/gi, '_').toLowerCase();
	});
	
	// bind label update
	$(document).on('keyup change', '[data-sync]', function(){
		var input = $(this),
			syncs = $(input.data('sync'));
		
		syncs.each(function(){
			var sync = $(this);

			if( sync.is('input') ){
				sync.val( input.val() ).trigger('change');
			}else{
				sync.text(input.val());
			}
		});
	});
	// bind toggles
	$(document).on('click', '[data-toggle]', function(){
		
		var toggle = $(this).data('toggle'),
			target = $(toggle);
		
		target.each(function(){
			var tog = $(this);
			if( tog.is(':checkbox') || tog.is(':radio') ){
				if( tog.prop('checked') ){
					tog.prop('checked', false);
				}else{
					tog.prop('checked', true);
				}
				blkbr_record_change();
			}else{
				tog.toggle();
			}
		});

	});	

	// bind tabs
	$(document).on('click', '.calderawp_license_manager-nav-tabs a', function(e){
		
		e.preventDefault();
		var clicked 	= $(this),
			tab_id 		= clicked.attr('href'),
			required 	= $('[required]'),
			clean		= true;

		for( var input = 0; input < required.length; input++ ){
			if( required[input].value.length <= 0 && $( required[input] ).is(':visible') ){
				$( required[input] ).addClass('calderawp_license_manager-input-error');
				clean = false;
			}else{
				$( required[input] ).removeClass('calderawp_license_manager-input-error');
			}
		}
		if( !clean ){
			return;
		}

		$('.calderawp_license_manager-editor-panel').hide();

		$('.calderawp_license_manager-nav-tabs .current').removeClass('current');
		$('.calderawp_license_manager-nav-tabs .active').removeClass('active');
		$('.calderawp_license_manager-nav-tabs .nav-tab-active').removeClass('nav-tab-active');
		if( clicked.parent().is('li') ){
			clicked.parent().addClass('active');			
		}else if( clicked.parent().is('div') ){
			clicked.addClass('current');			
		}else{			
			clicked.addClass('nav-tab-active');
		}
		

		$( tab_id ).show();

		jQuery('#calderawp_license_manager-active-tab').val(tab_id).trigger('change');

	});

	// row remover global neeto
	$(document).on('click', '[data-remove-parent]', function(e){
		var clicked = $(this),
			parent = clicked.closest(clicked.data('removeParent'));
		if( clicked.data('confirm') ){
			if( !confirm(clicked.data('confirm')) ){
				return;
			}
		}
		parent.remove();
		blkbr_record_change();
	});
	
	// row remover global neeto
	$(document).on('click', '[data-remove-element]', function(e){
		var clicked = $(this),
			elements = $(clicked.data('removeElement'));
		if( clicked.data('confirm') ){
			if( !confirm(clicked.data('confirm')) ){
				return;
			}
		}
		elements.remove();
		blkbr_record_change();
	});

	// init tags
	$('body').on('click', '.magic-tag-init', function(e){
		var clicked = $(this),
			input = clicked.prev();

		input.focus().trigger('init.magic');

	});
	
	// initialize live sync rebuild
	$(document).on('change', '[data-live-sync]', function(e){
		blkbr_record_change();
	});

	// initialise baldrick triggers
	$('.wp-baldrick').baldrick({
		request     : ajaxurl,
		method      : 'POST',
		before		: function(el){
			
			var tr = $(el);

			if( tr.data('addNode') && !tr.data('request') ){
				tr.data('request', 'blkbr_get_default_setting');
			}
		}
	});



});



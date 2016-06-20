jQuery( document ).ready( function ( $ ) {
    'use strict';

    var router;
    (router = function () {

        $( '.cwp-lm-tab' ).on( 'click', function( e ) {
            var $el = $( this );
            var tab = $el.data( 'tab' );
            if( tab ){
                e.preventDefault();
                var contents = document.getElementById( 'calderawp-license-manager-tabs' );
                var xhr = $.get( CWP_LM.api, {
                    view: tab,
                    nonce: CWP_LM.nonce,
                    _wpnonce: CWP_LM.rest_nonce
                });
                xhr.done = function( r ){
                    contents.innerHTML = r;
                };

                xhr.error( function(){
                    window.location = $el.attr( 'href' );
                });
            }


        });
    })();





});

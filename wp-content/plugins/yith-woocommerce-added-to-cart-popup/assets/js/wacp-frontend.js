/**
 * wacp-frontend.js
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    "use strict";

    var popup       = $('#yith-wacp-popup'),
        overlay     = popup.find( '.yith-wacp-overlay'),
        close       = popup.find( '.yith-wacp-close'),
        close_popup = function(){
            // remove class open
            popup.removeClass( 'open' );
            // after 2 sec remove content
            setTimeout(function () {
                popup.find('.yith-wacp-content').html('');
            }, 2000);
        };

    $('body').on( 'added_to_cart', function( ev, fragmentsJSON, cart_hash, button ){

        if( typeof fragmentsJSON == 'undefined' )
            fragmentsJSON = $.parseJSON( sessionStorage.getItem( wc_cart_fragments_params.fragment_name ) );

        $.each( fragmentsJSON, function( key, value ) {

            if ( key == 'yith_wacp_message' ) {

                popup.find('.yith-wacp-content').html( value );

                popup.addClass('open');

                popup.find( 'a.continue-shopping' ).on( 'click', function (e) {
                    e.preventDefault();
                    close_popup();
                });

                return false;
            }
        });
    });

    // Close box by click close button
    close.on( 'click', function(ev){
        ev.preventDefault();

        close_popup();
    });
});
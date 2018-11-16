var tribe_events_community_tickets = {};

( function( $, obj ) {
	'use strict';

	obj.init = function() {
		$( '.wp-list-table' ).wrap( '<div class="tribe-scrollable-table"/>' );
	};

	obj.order_panel_height = function() {
		var $panel = $( '.welcome-panel-last' );

		if ( 1024 < $( window ).width() ) {
			$panel.css( 'height', $panel.parent().height() );
		} else {
			$panel.css( 'height', 'auto' );
		}
	};

	$( function() {
		obj.init();
		obj.order_panel_height();
	} );

	$( window ).on( 'resize', obj.order_panel_height() );

} )( jQuery, tribe_events_community_tickets );

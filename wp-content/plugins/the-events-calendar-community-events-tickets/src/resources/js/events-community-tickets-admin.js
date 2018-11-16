var tribe_events_community_tickets_admin = {
	event: {}
};

( function( $, my ) {
	'use strict';

	/**
	 * initializes the JS logic
	 */
	my.init = function() {
		this.$site_fee_type = $( 'select[name="site_fee_type"]' );
		this.$enable_split_payments = $( 'input[name="enable_split_payments"]' );
		this.$form = this.$site_fee_type.closest( 'form' );

		$( document ).on( 'change', 'select[name="site_fee_type"]', this.event.change_site_fee );
		$( document ).on( 'change', 'input[name="enable_split_payments"]', this.event.change_split_payments );

		this.$site_fee_type.trigger( 'change' );
		this.$enable_split_payments.trigger( 'change' );
	};

	/**
	 * Changes the classes on the form based on the provided site fee value
	 *
	 * @param string value Site fee setting
	 */
	my.change_site_fee = function( value ) {
		if ( 'none' === value ) {
			this.$form.removeClass( 'site-fee-flat site-fee-percentage' );
			this.$form.addClass( 'site-fee-none' );
		}

		if ( 'flat' === value ) {
			this.$form.removeClass( 'site-fee-none site-fee-percentage' );
			this.$form.addClass( 'site-fee-flat' );
		}

		if ( 'percentage' === value ) {
			this.$form.removeClass( 'site-fee-none site-fee-flat' );
			this.$form.addClass( 'site-fee-percentage' );
		}

		if ( 'flat-and-percentage' === value ) {
			this.$form.removeClass( 'site-fee-none' );
			this.$form.addClass( 'site-fee-flat site-fee-percentage' );
		}
	};

	/**
	 * Toggles the visibility of the PayPal settings based on the Split Payments checkbox
	 *
	 * @param boolean value Whether or not the Split Payments checkbox is checked
	 */
	my.change_split_payments = function( value ) {
		if ( value ) {
			this.$form.addClass( 'split-payments-enabled' );
		} else {
			this.$form.removeClass( 'split-payments-enabled' );
		}
	};

	/**
	 * Handles the site fee change event
	 */
	my.event.change_site_fee = function() {
		my.change_site_fee( $( this ).val() );
	};

	/**
	 * Handles the paypal sandbox change event
	 */
	my.event.change_split_payments = function() {
		my.change_split_payments( $( this ).is( ':checked' ) );
	};

	/**
	 * initialize this JS file's logic when the document is ready
	 */
	$( function() {
		my.init();
	} );
} )( jQuery, tribe_events_community_tickets_admin );

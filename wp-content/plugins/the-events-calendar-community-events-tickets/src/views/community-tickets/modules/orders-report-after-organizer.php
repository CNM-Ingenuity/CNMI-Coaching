<?php
/**
 * Inserts the organizer's PayPal address after the organizer name.
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/community-tickets/orders-report-after-organizer.php
 * @version 4.3.2
 */
$meta = Tribe__Events__Community__Tickets__Payment_Options_Form::get_meta( $organizer->ID );

if ( empty( $meta['paypal_account_email'] ) ) {
	return;
}
?>
<div class="tribe-event-meta tribe-event-meta-organizer-paypal">
	<strong><?php echo esc_html__( 'Organizer PayPal:', 'the-events-calendar' ); ?></strong>
	<a href="mailto:<?php echo esc_attr( $meta['paypal_account_email'] ); ?>"><?php echo esc_html( $meta['paypal_account_email'] ); ?></a>
</div>

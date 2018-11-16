<?php
/**
 * Event Submission Form Ticket Block
 * Renders the ticket settings in the submission form.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community-tickets/modules/tickets.php
 *
 * @version 4.5.2
 * @since  3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$community_tickets = Tribe__Events__Community__Tickets__Main::instance();

if ( ! $community_tickets->is_enabled() ) {
	return;
}

$options = get_option( Tribe__Events__Community__Tickets__Main::OPTIONNAME );

$image_uploads_class = 'tribe-image-uploads-enabled';

if ( empty( $options['enable_image_uploads'] ) ) {
	$image_uploads_class = 'tribe-image-uploads-disabled';
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();

$community_events = Tribe__Events__Community__Main::instance();
$event_id = $community_events->event_form()->get_event_id();
$event = get_post( $event_id );

if ( ! current_user_can( 'edit_event_tickets' ) ) {
	return;
}
?>

<div id="tribetickets" class="tribe-section tribe-section-tickets <?php echo sanitize_html_class( $image_uploads_class ); ?>">
	<div class="tribe-section-header">
		<h3><?php echo esc_html__( 'Tickets', 'tribe-events-community-tickets' ); ?></h3>
	</div>

	<?php
	/**
	 * Allow developers to hook and add content to the beginning of this section
	 */
	do_action( 'tribe_events_community_section_before_tickets' );
	?>

	<div class="tribe-section-content">
	<?php
	if ( $community_tickets->is_enabled_for_event( $event_id ) && current_user_can( 'sell_event_tickets' ) ) {
		tribe( 'tickets.metabox' )->render( $event );
	} else {
		?>
		<p>
			<?php
			printf(
				esc_html__(
					'Before you can create tickets, please add your PayPal email address on the %1$sPayment options%2$s form.',
					'tribe-events-community-tickets'
				),
				'<a href="' . esc_url( $community_tickets->routes['payment-options']->url() ) . '">',
				'</a>'
			);
			?>
		</p>
		<?php
	}
	?>
	</div>

	<?php
	/**
	 * Allow developers to hook and add content to the end of this section
	 */
	do_action( 'tribe_events_community_section_after_tickets' );
	?>
</div>

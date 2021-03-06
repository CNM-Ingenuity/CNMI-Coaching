<?php
/**
 * Event Submission Form
 * The wrapper template for the event submission form.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/edit-event.php
 *
 * @since    3.1
 * @version  4.5.13
 *
 * @var int|string $tribe_event_id
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// add in top matter
function add_in_top_matter_to_event_add() {
	// for the top matter title
	$eventType = "Schedule a Training";
	$breadcrumbs = [
		"My Organization's Trainings" => "/my-trainings",
		$eventType => '#'
	];
	include(locate_template('partials/elements/breadcrumbs.php'));
	include(locate_template('partials/elements/top-matter.php'));
}
add_action('genesis_entry_content', 'add_in_top_matter_to_event_add', 1);

if(wc_memberships_is_user_active_member( get_current_user_id(), 'licensed-org' )){

	if ( ! isset( $tribe_event_id ) ) {
		$tribe_event_id = null;
	}

	?>

	<?php //tribe_get_template_part( 'community/modules/header-links' ); ?>

	<?php do_action( 'tribe_events_community_form_before_template', $tribe_event_id ); ?>

	<form method="post" enctype="multipart/form-data" data-datepicker_format="<?php echo esc_attr( tribe_get_option( 'datepickerFormat', 0 ) ); ?>">
		<input type="hidden" name="post_ID" id="post_ID" value="<?php echo absint( $tribe_event_id ); ?>"/>
		<?php wp_nonce_field( 'ecp_event_submission' ); ?>

		<?php tribe_get_template_part( 'community/modules/title' ); ?>

		<?php tribe_get_template_part( 'community/modules/description' ); ?>

		<?php tribe_get_template_part( 'community/modules/datepickers' ); ?>

		<?php tribe_get_template_part( 'community/modules/image' ); ?>

		<?php tribe_get_template_part( 'community/modules/taxonomy', null, array( 'taxonomy' => Tribe__Events__Main::TAXONOMY ) ); ?>

		<?php tribe_get_template_part( 'community/modules/taxonomy', null, array( 'taxonomy' => 'post_tag' ) ); ?>

		<?php tribe_get_template_part( 'community/modules/choose-coaches', null, array( 'taxonomy' => Tribe__Events__Main::TAXONOMY ) ); ?>

		<?php
		/**
		 * Action hook before loading linked post types template parts.
		 *
		 * Useful if you want to insert your own additional custom linked post types.
		 *
		 * @since 4.5.13
		 *
		 * @param int|string $tribe_event_id The Event ID.
		 */
		do_action( 'tribe_events_community_form_before_linked_posts', $tribe_event_id );
		?>

		<?php tribe_get_template_part( 'community/modules/venue' ); ?>

		<?php tribe_get_template_part( 'community/modules/organizer' ); ?>

		<?php
		/**
		 * Action hook after loading linked post types template parts.
		 *
		 * Useful if you want to insert your own additional custom linked post types.
		 *
		 * @since 4.5.13
		 *
		 * @param int|string $tribe_event_id The Event ID.
		 */
		do_action( 'tribe_events_community_form_after_linked_posts', $tribe_event_id );
		?>	

		<?php tribe_get_template_part( 'community/modules/custom' ); ?>

		<?php tribe_get_template_part( 'community/modules/cost' ); ?>

		<?php tribe_get_template_part( 'community/modules/spam-control' ); ?>

		<?php tribe_get_template_part( 'community/modules/submit' ); ?>
	</form>

	<?php do_action( 'tribe_events_community_form_after_template', $tribe_event_id );
} else {
	?>
		<p>You don't have permission to view this page.</p>
	<?php
}

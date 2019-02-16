<?php
// Don't load directly
defined( 'WPINC' ) or die;

/**
 * Event Submission Form Taxonomy Block
 * Renders the taxonomy field in the submission form.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/modules/taxonomy.php
 *
 * @since  3.1
 * @version 4.5.7
 *
 */

$users = get_users(  );

$user_options = array();

$user_options = array();
if ( $users ) {
    foreach ( $users as $user ) {
      $user_id = $user->ID;
      if(wc_memberships_is_user_active_member( $user_id, 'certified-coach-trainer' )){
        $user_options[ $user->ID ] = $user->first_name . ' ' . $user->last_name;
      }
    }
}

// $selected_users = array();

// // Setup selected users
// $value = ! empty( $_POST['_cnmi_event_metabox_user_multicheckbox' ] ) ? explode( ',', esc_attr( trim( $_POST['_cnmi_event_metabox_user_multicheckbox' ] ) ) ) : array();

// // if no tags from $_POST then look for saved tags
// if ( empty( $value )  && isset($tribe_event_id)) {
// 	$users = get_post_meta(
//         $tribe_event_id,
//         '_cnmi_event_metabox_user_multicheckbox',
//         true
//     );
//     if($users) {
//     	foreach ($users as $userId) {
//     		$user = get_user_by('id', $userId);	
//     		$selected_users[$user->ID] = $user->first_name . ' ' . $user->last_name;
//     	}	
// 	}
// }

// if ( is_array( $value ) ) {
// 	$value = implode( ',', $value );
// }

?>
<div class="tribe-section tribe-section-taxonomy">
	<div class="tribe-section-header">
		<h3>Coaches for this Event</h3>
		<?php echo tribe_community_required_field_marker( "tax_input.tribe_events_cat" ); ?>
	</div>

	<div class="tribe-section-content">
		<div class="tribe-section-content-field">
			<select name="coaches[]" multiple>
				<?php
					foreach($user_options as $id => $name) {
						?>
							<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
						<?php
					}
				?>
			</select>
		</div>
	</div>
</div>

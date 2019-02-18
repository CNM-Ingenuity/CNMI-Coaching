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

?>
<div class="tribe-section tribe-section-taxonomy">
	<div class="tribe-section-header">
		<h3>Coaches for this Event</h3>
		<?php echo tribe_community_required_field_marker( "tax_input.tribe_events_cat" ); ?>
	</div>

	<div class="tribe-section-content">
		<div class="tribe-section-content-field">
			<select id="coaches" name="coaches[]" class="tribe-dropdown tribe-dropdown-created" multiple="multiple">
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
<script>
	jQuery(document).ready(function() {
	    jQuery('#coaches').select2({
	    	width: '100%',
	    	placeholder: 'Select Coaches'
	    });
	});
</script>

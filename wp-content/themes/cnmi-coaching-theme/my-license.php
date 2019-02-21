<?php
/*
 * Template Name: My License
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {
	
	$classes[] = 'progress-form-page';
	return $classes;
	
}

function show_my_resources() {
	$breadcrumbs = [
		"My Organization's License" => "#"
	];
	include(locate_template('partials/elements/breadcrumbs.php'));
	get_template_part('partials/elements/top-matter');
	$coaches = CNMI_Licensing_Org::get_coaches();
	$current_user_id = get_current_user_id();
	$renewal_date = get_user_meta( $current_user_id, 'licensing_renewal_date', true );
	$licensing_document = get_user_meta( $current_user_id, 'licensing_document', true );
	?>
	<div class="item">
		<p class="date">Renewal Date: <?php echo $renewal_date; ?></p>
	</div>
	<table>
		<tr>
			<th>Trainer Name</th>
			<th>Email</th>
		</tr>
	<?php
	foreach ($coaches as $coach) {
		?>
			<tr>
				<td><?php echo $coach->first_name . ' ' . $coach->last_name; ?></td>
				<td><?php echo $coach->user_email; ?></td>
			</tr>
		<?php
	}
	?>
	</table>
	<a class="button item-button" target="_blank" href="<?php echo $licensing_document;?>"><p>License Document</p><span class="dashicons dashicons-media-text"></span></a>
	<?php
}
add_action('genesis_entry_content', 'show_my_resources');

genesis();

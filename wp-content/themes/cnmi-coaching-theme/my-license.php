<?php
/*
 * Template Name: My License
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_resources() {
	$breadcrumbs = [
		"My Organization's License" => "#"
	];
	include(locate_template('partials/elements/breadcrumbs.php'));
	get_template_part('partials/elements/top-matter');
	$coaches = CNMI_Licensing_Org::get_coaches();
	?>
		<table>
		<tr>
			<th>Trainer Name</th>
			<th>Email</th>
		</tr>
	<?php
	foreach ($coaches as $coach) {
		?>
			<tr>
				<td><?php echo $coach->user_nicename; ?></td>
				<td><?php echo $coach->user_email; ?></td>
			</tr>
		<?php
	}
	?>
	</table>
	<?php
}
add_action('genesis_entry_content', 'show_my_resources');

genesis();

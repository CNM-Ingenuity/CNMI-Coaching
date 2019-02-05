<?php
$certifications = CNMI_Certifications::get_unique_certifications_by_coach_id($user_id);
foreach ($certifications as $certificationId => $name) {
	?>
		<a href='<?php echo $linkAddress . $certificationId; ?>'>
			<div class="item">
				<h3 class="title"><?php echo $name; ?></h3>
			</div>
		</a>
	<?php
}
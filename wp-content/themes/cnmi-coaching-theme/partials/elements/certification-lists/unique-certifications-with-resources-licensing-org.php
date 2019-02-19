<?php
//* Add custom body class to the head
$certifications = CNMI_Certifications::get_unique_certifications_by_licensing_org_id($user_id);
foreach ($certifications as $certificationId => $name) {
  $resourcesArray = CNMI_Certifications::get_certification_resources($certificationId);
	$numberResources = is_array($resourcesArray) ? count($resourcesArray) : 0;
	?>
		<a href='<?php echo $linkAddress . $certificationId; ?>'>
			<div class="item item-padding">
				<h3 class="title"><?php echo $name; ?></h3>
        <p class="students">Resources: <?php echo $numberResources; ?></p>
			</div>
		</a>
	<?php
}

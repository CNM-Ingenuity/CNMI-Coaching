<?php
$resourcesArray = CNMI_Certifications::get_certification_resources($progressID);
if(!is_array($resourcesArray)) {
	$resourcesArray = [];
}
$numberResources = count($resourcesArray);
$certificationName = get_the_title($progressID);
$eventType = $certificationName . " Resources";
$breadcrumbs = [
	"My Resources" => "/my-resources",
	$eventType => "#"
];
include(locate_template('partials/elements/breadcrumbs.php'));
include(locate_template('partials/elements/top-matter.php'));

?>
<div class="item">
	<h3 class="title"><?php echo $certificationName; ?></h3>
	<p class="students">Resources: <?php echo $numberResources; ?></p>
</div>
	<div class='resource-buttons'>
		<?php
		foreach ($resourcesArray as $resource) {
			$resourceName= $resource['name'];
			$resourceFile = $resource['file'];
			?>
				<a class="button item-button" href="<?php echo $resourceFile; ?>" target="_blank">
					<p><?php echo $resourceName;?></p>
					<img src="/wp-content/uploads/2019/01/download-arrow.png">
				</a>
			<?php
		}
	?>
	</div>
	<?php
	include(locate_template('partials/elements/view-shop-button.php'));

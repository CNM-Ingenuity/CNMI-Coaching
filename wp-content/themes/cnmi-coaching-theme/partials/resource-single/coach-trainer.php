<?php
$resourcesArray = CNMI_Certifications::get_certification_resources($progressID);
$numberResources = count($resourcesArray);
$certificationName = get_the_title($progressID);
$breadcrumbs = [
	"Resources" => "/my-resources"
];
include(locate_template('partials/elements/breadcrumbs.php'));
get_template_part('partials/elements/top-matter');

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

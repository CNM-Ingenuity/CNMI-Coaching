<?php
?>
<div class='breadcrumbs'>
	<span class='back-link' onClick="javascript:history.go(-1)"><span class="dashicons dashicons-arrow-left"></span> Back</span>
	<a class='breadcrumb' href="/dashboard">Dashboard</a>
	<?php
		foreach ($breadcrumbs as $name => $url) {
			?>
				<a class='breadcrumb' href="<?php echo $url; ?>"><?php echo $name; ?></a>
			<?php
		}
	?>
</div>
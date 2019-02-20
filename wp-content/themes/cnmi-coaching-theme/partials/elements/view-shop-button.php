<?php
?>
<div class="view-shop">
	<h2>Products for Sale</h2>
	<?php
  		$productCategory = sanitize_title($eventType);
  		echo do_shortcode("[products category='$productCategory']");
  	?>
	<a class="button" href="/shop">
		<p> VIEW PUBLIC STORE </p>
	</a>
</div>	

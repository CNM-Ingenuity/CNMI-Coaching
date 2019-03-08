<?php
/*
 * Template Name: Form
 */
//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {

	$classes[] = 'form-page fast-form';
	return $classes;

}

add_action('genesis_entry_content', 'add_contact_form_to_page');
function add_contact_form_to_page(){
	?>
	<script type="text/javascript" id="jsFastForms" src="https://sfapi-sandbox.formstack.io/FormEngine/Scripts/Main.js?d=KVHvMtU34D97ATrNrJAyTv4htltq8KpsxMIR6HmIg4eK3K0Yb0ewhMi0s1M2VQBB"></script>
	<?php
}

genesis();

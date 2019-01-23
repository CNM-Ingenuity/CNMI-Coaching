<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Theme Name' );
define( 'CHILD_THEME_URL', 'http://www.11online.us/' );
define( 'CHILD_THEME_VERSION', '2.2.2' );

//* Enqueue Google Fonts
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts', 100 );
function genesis_sample_google_fonts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto:300|Open+Sans:700', array(), CHILD_THEME_VERSION );
	wp_enqueue_script( 'genesis-responsive-menu', get_bloginfo('stylesheet_directory').'/js/responsive-menu.js', array('jquery'), '1.0.0');
    wp_enqueue_script( 'genesis-smooth-scrolling', get_bloginfo('stylesheet_directory').'/js/smooth-scrolling.js', array('jquery'), '1.0.0');
    wp_enqueue_script( 'genesis-sticky-header', get_bloginfo('stylesheet_directory').'/js/sticky-header.js', array('jquery'), '1.0.0');
    wp_enqueue_script( 'genesis-modernizr', get_bloginfo('stylesheet_directory').'/js/modernizr.js', array(), '1.0.0');
	wp_enqueue_style( 'dashicons' );

}

// change stylesheet load order
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );
add_action( 'wp_enqueue_scripts', 'genesis_enqueue_main_stylesheet', 100 ); 

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links', 'rems' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* unregister superfish since it interferes with our mobile responsive menu
function unregister_superfish() {
	wp_deregister_script( 'superfish' );
	wp_deregister_script( 'superfish-args' );
}
add_action( 'wp_enqueue_scripts', 'unregister_superfish' );

//* register the before footer widget
genesis_register_sidebar(array(
    'id' => 'before-footer-widget',
    'name' => __('Before Footer', 'genesis'),
    'description' => __('Area right before the footer', 'CNM Coaching'),
));

//* use the before footer widget
add_action('genesis_before_footer', 'add_before_footer_widget_area', 9);
function add_before_footer_widget_area()
{
    genesis_widget_area('before-footer-widget', array(
        'before' => '<div class="before-footer-widget widget-area"><div class="wrap">',
        'after' => '</div></div>',
  ));
}

//* allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

//* Modify the Genesis content limit read more link
add_filter('get_the_content_more_link', 'sp_read_more_link');
function sp_read_more_link()
{
    return '... <a class="more-link" href="'.get_permalink().'">Read More</a>';
}

//* Modify the excerpt more [...] content
function eleven_online_excerpt_more( $more ) {
    return '<a class="read-more" href="' . get_permalink( get_the_ID() ) . '"> ... Read More</a>';
}
add_filter( 'excerpt_more', 'eleven_online_excerpt_more' );

//* Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter($creds)
{
    $creds = 'Copyright '.date('Y').' - CNM Ingenuity';

    return $creds;
}

//* Add Our Customizer Options
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Include Section Image CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Include Custom Post Types
include_once( get_stylesheet_directory() . '/custom-post-types/index.php' );

//* Include Custom Taxonomies
include_once( get_stylesheet_directory() . '/custom-taxonomies/index.php' );

//* Add support for custom header
add_theme_support('custom-header', array(
    'width' => 160,
    'height' => 58,
    'header-selector' => '.site-title a',
    'header-text' => false,
    )
);

//*add a custom stylesheet to the TinyMCE editor
function my_theme_add_editor_styles()
{
    add_editor_style();
}
add_action('admin_init', 'my_theme_add_editor_styles');

//*remove worthless dashboard panels
function remove_dashboard_meta() {
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );   // Right Now
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );  // Incoming Links
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );  // Quick Press
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );   // WordPress blog
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );   // Other WordPress News
}

add_action( 'admin_init', 'remove_dashboard_meta' );


//*remove welcome dashboard panel
function remove_welcome_panel() {
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
	$user_id = get_current_user_id();
	if ( 0 !== get_user_meta( $user_id, 'show_welcome_panel', true ) ) {
		update_user_meta( $user_id, 'show_welcome_panel', 0 );
	}
}

add_action( 'load-index.php', 'remove_welcome_panel' );

//*only load woocommerce scripts on woocommerce pages
function conditionally_load_woc_js_css(){
if( function_exists( 'is_woocommerce' ) ){
        # Only load CSS and JS on Woocommerce pages
	if(! is_woocommerce() && ! is_cart() && ! is_checkout() ) {

		## Dequeue scripts.
		wp_dequeue_script('woocommerce');
		wp_dequeue_script('wc-add-to-cart');
		wp_dequeue_script('wc-cart-fragments');

		## Dequeue styles.
		wp_dequeue_style('woocommerce-general');
		wp_dequeue_style('woocommerce-layout');
		wp_dequeue_style('woocommerce-smallscreen');

		}
	}
}

//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action( 'wp_enqueue_scripts', 'conditionally_load_woc_js_css' );

//* Use Featured Image as Hero Image with Title
add_action('genesis_after_header', 'eleven_online_add_hero_area');
function eleven_online_add_hero_area()
{
    if (!is_front_page()) {
        if (is_single() || is_page()) {
            if (has_post_thumbnail()) {
                // add the title with in a single hero area
                echo '<div class="single-hero" style="background: url(' . get_the_post_thumbnail_url(get_the_ID(), 'full') . ')"><div class="wrap"><div class="hero-content"><h1>' . get_the_title() . '</h1></div></div></div>';
                // remove the default title
                remove_action('genesis_entry_header', 'genesis_do_post_title');
            }
        }
    }
}
//Register users for site on ticket purchase.
add_action('woocommerce_payment_complete', 'custom_add_to_cart');
function custom_add_to_cart($order_id) {
		//Need to add conditional to only run on ticket purchases
		$order = wc_get_order($order_id);
		// var_dump($order);
		$order_array = json_decode($order);
		print_r($order_array);

		$order_meta = $order_array->meta_data[0];

		$meta_array = $order_meta->value;

		foreach ($meta_array as $var => $value) {
			print_r($var);
			$product = wc_get_product($var);
			print_r($product);
		 	foreach ($value as $key => $ticket_meta_array) {
				$first_name = $ticket_meta_array->{'first-name'};
				$last_name = $ticket_meta_array->{'last-name'};
				$user_email = $ticket_meta_array->email;
				$user_title = $ticket_meta_array->title;
				print_r($user_title);
				$user_data = array(
					'user_pass' => '',
					'user_login' => $user_email,
					'user_email' => $user_email,
					'first_name' => $first_name,
					'last_name' => $last_name,
					'role' => 'subscriber'
				);
				if(! email_exists($user_email)){
					echo 'I would have registered'. $user_email .'but we are testing';
					$user_id = wp_insert_user($user_data);
					// print_r($user_id);
				} else {
					echo 'This user already exists!';
				}
			}
		}
		//need to go through meta array and

		// for($i= 0; $i < count($meta_array); $i++) {
		// $first_name = $meta_array[$i]->{'first-name'};
		// $last_name = $meta_array[$i]->{'last-name'};
		// //need to check if user exists and if user does exist, pass the user id into the insert user function
		// $user_email = $meta_array[$i]->email;
		// $user_data = array(
		// 	'user_pass' => '',
		// 	'user_login' => $user_email,
		// 	'user_email' => $user_email,
		// 	'first_name' => $first_name,
		// 	'last_name' => $last_name,
		// 	'role' => 'subscriber'
		// );
		// $user_id = wp_insert_user($user_data);
		// // Send notification email. We will probably want to customize the message
		// $notification = wp_new_user_notification($user_id, '' , 'user');
		// Need to add custom meta to user saying which event they registered for.
		// Do we want to try to send this data to salesforce using the API or Zapier?


		// 	// code...
		}
// }

//Change dashboard based on the member's account type
add_action('genesis_after_header','set_user_dashboard');
function set_user_dashboard(){
	$user_id = get_current_user_id();
	$memberships = wc_memberships_get_user_active_memberships( $user_id );
	if($memberships){

		$plan_id = $memberships[0]->{"plan_id"};
		if ($plan_id == 406) {
			// echo '<h2>You are a Certified Coach!</h2>';
			get_template_part('certified-coach-dashboard');
		} elseif ($plan_id == 407) {
			// echo '<h2>You are a Coach in Training!</h2>';
			get_template_part('coach-in-training-dashboard');
		} elseif ($plan_id == 408) {
			// echo '<h2>You are a Contracting Organization!</h2>';
			get_template_part('contracting-organization-dashboard');
		} elseif ($plan_id == 410) {
			// echo '<h2>You are a Licensed Organization!</h2>';
			get_template_part('licensed-organization-dashboard');
		} elseif ($plan_id == 411) {
			// echo '<h2>You are a Certified Coach Trainer!</h2>';
			get_template_part('certified-coach-trainer-dashboard');

		}
	}

}

add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

function wti_loginout_menu_link( $items, $args ) {
   if ($args->theme_location == 'primary') {
      if (is_user_logged_in()) {
         $items .= '<li class="right"><a href="'. wp_logout_url() .'">'. __("Log Out") .'</a></li>';
      } else {
         $items .= '<li class="right"><a href="'. wp_login_url(get_permalink()) .'">'. __("Log In") .'</a></li>';
      }
   }
   return $items;
}

function training_calendar_grid(){
	// upcoming events section
	$tz = new DateTimeZone('America/Denver');
	$start_date = new DateTime();
	
	// this is the latest events section
	$events = tribe_get_events( array(
		'start_date'     => $start_date->format('Y-m-d 00:00:00'),
		'eventDisplay'   => 'custom',
		'posts_per_page' => 4
	));
	
	$output = "<div class='upcoming-events-section content'><div class='wrap'>";
	$output .= "<h1>Training Calendar</h1>";
	$count = 0;
	foreach($events as $event) {
		$eventStartTime = new DateTime($event->EventStartDate, $tz);
		$eventEndTime = new DateTime($event->EventEndDate, $tz);
		if($count % 2 === 0) {
			$output .= "<div class='one-half first event-block'>";
		} else {
			$output .= "<div class='one-half event-block'>";
		}
		$output .= "<div class='event-date'>";
		$output .= $eventStartTime->format('M d');
		$output .= "</div><div class='event-details'><h5>";
		$output .= $event->post_title;
		$output .= "</h5><p>";
		$output .= $eventStartTime->format('g:i a');
		$output .= " - ";
		$output .= $eventEndTime->format('g:i a');
		$output .= "</p><a class='button secondary' href='" . get_permalink($event->ID) . "'>Sign Up</a></div></div>";
		$count++;
	}
	$output .= "<p class='view-more-events-container'><a class='view-more-events' href='/events'>VIEW MORE</a></p>";
	$output .= "</div></div>";
	return $output;
}
add_shortcode( 'training_calendar_grid', 'training_calendar_grid' );

function certification_list(){
	// WP_Query arguments
	$args = array (
		'post_type'              => array( 'certifications' ),
		'post_status'            => array( 'publish' ),
		'nopaging'               => true,
		'order'                  => 'ASC',
		'orderby'                => 'menu_order',
	);

	// The Query
	$certifications = new WP_Query( $args );

	$output = "<div class='certifications-list'>";

	// The Loop
	if ( $certifications->have_posts() ) {
		while ( $certifications->have_posts() ) {
			$certifications->the_post();
			// set up container
			$output .= "<div class='certification'>";

			// title
			$output .= "<h3>";
			$output .= get_the_title();
			$output .= "</h3>";

			// content
			$output .= "<p>";
			$output .= get_the_content();
			$output .= "</p>";

			// hours
			$output .= "<div class='one-third first'><p><span class='dashicons dashicons-clock'></span>";
			$output .= get_post_meta( get_the_ID(), '_cnmi_certification_metabox_hours', true );
			$output .= "&nbsp;Hours</p></div>";	

			// type
			$output .= "<div class='one-third'><p><span class='dashicons dashicons-admin-site'></span>";
			$output .= get_post_meta( get_the_ID(), '_cnmi_certification_metabox_training_type', true );
			$output .= "</p></div>";

			// type
			$output .= "<div class='one-third'><p><span class='dashicons dashicons-awards'></span>";
			$output .= "Master Your Craft";
			$output .= "</p></div>";	

			// link
			$output .= "<a href='" . get_the_permalink() . "' class='button secondary'>Get Started</a>";		

			// close the container
			$output .= "</div>";
		}
	} else {
		// no posts found
		$output .= "No certifications found.";
	}

	// Restore original Post Data
	wp_reset_postdata();

	$output .= "</div>";
	
	return $output;
}
add_shortcode( 'certification_list', 'certification_list' );

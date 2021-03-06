<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'CNM Coaching' );
define( 'CHILD_THEME_URL', 'http://www.11online.us/' );
define( 'CHILD_THEME_VERSION', '2.2.3' );

//* Enqueue Google Fonts
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts', 100 );
function genesis_sample_google_fonts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto:300|Open+Sans:700', array(), CHILD_THEME_VERSION );
	wp_enqueue_script( 'genesis-responsive-menu', get_bloginfo('stylesheet_directory').'/js/responsive-menu.js', array('jquery'), '1.0.0');
    wp_enqueue_script( 'genesis-smooth-scrolling', get_bloginfo('stylesheet_directory').'/js/smooth-scrolling.js', array('jquery'), '1.0.0');
    wp_enqueue_script( 'genesis-sticky-header', get_bloginfo('stylesheet_directory').'/js/sticky-header.js', array('jquery'), '1.0.0');
    wp_enqueue_script( 'genesis-modernizr', get_bloginfo('stylesheet_directory').'/js/modernizr.js', array(), '1.0.0');
    wp_enqueue_script( 'jquery-validation', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js', array('jquery'), '1.0.0');
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
	if(!is_product()){
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
}
//Register users for site on ticket purchase.
add_action('woocommerce_payment_complete', 'custom_add_to_cart');
function custom_add_to_cart($order_id) {
		//Need to add conditional to only run on ticket purchases
		$order = wc_get_order($order_id);
		$event_id = false;
		// Iterating through each "line" items in the order
		foreach ($order->get_items() as $item_id => $item_data) {

			$product = $item_data->get_product();
			$product_id = $product->get_id();
			$event = get_post_meta(
				$product_id,
				'_tribe_wooticket_for_event',
				true
			);
			if($event) {
				$event_id = $event;
			}
		}
		if($event_id)	{
			foreach($order->meta_data as $meta) {
				if($meta->key == '_tribe_tickets_meta') {
					foreach($meta->value as $ticketArray) {
						foreach($ticketArray as $ticket) {
							$first_name = $ticket['first-name'];
							$last_name = $ticket['last-name'];
							$user_email = $ticket['email'];
							$user_title = $ticket['title'];
							$user_data = array(
								'user_pass' => '',
								'user_login' => $user_email,
								'user_email' => $user_email,
								'first_name' => $first_name,
								'last_name' => $last_name,
								'role' => 'subscriber'
							);

							if(! email_exists($user_email)){
								$user_id = wp_insert_user($user_data);
								// Send notification email. We will probably want to customize the message
								$notification = wp_new_user_notification($user_id, null , 'user');
								// attach the coach in trainer membership
								$membershipArgs = array(
								// Enter the ID (post ID) of the plan to grant at registration
									'plan_id' => 407,
									'user_id' => $user_id,
								);
								wc_memberships_create_user_membership( $membershipArgs );
								global $wpdb;
								$table_name  = $wpdb->prefix.'progress';
								$wpdb->insert(
									$table_name,
									array(
										'user_id' => $user_id,
										'event_id' => $event_id
									),
									array('%d','%d')
								);
							} else {
								$user = get_user_by( 'email', $user_email);
								$user_id = $user->ID;
								global $wpdb;
								$table_name  = $wpdb->prefix.'progress';
								$wpdb->insert(
									$table_name,
									array(
										'user_id' => $user_id,
										'event_id' => $event_id
									),
									array('%d','%d')
								);
							}
						}
					}
				}
			}
		}
 }


// GUTENBERG Compatibility
add_action( 'enqueue_block_editor_assets', function() {
    wp_enqueue_style('eleven_online_theme_styles', get_theme_file_uri('/style.css') );
} );
 add_action( 'after_setup_theme', function() {
    add_theme_support( 'editor-color-palette', [
        [
            'name'  => esc_html__( 'Dark Blue', 'CNM Coaching' ),
            'slug' => 'dark-blue',
            'color' => '#0b3254',
        ],
        [
            'name'  => esc_html__( 'Orange', 'CNM Coaching' ),
            'slug' => 'orange',
            'color' => '#c19022',
        ],
        [
            'name'  => esc_html__( 'Black', 'CNM Coaching' ),
            'slug' => 'black',
            'color' => '#2e2e2e',
        ],
        [
            'name'  => esc_html__( 'White', 'CNM Coaching' ),
            'slug' => 'white',
            'color' => '#fff',
        ],
        [
            'name'  => esc_html__( 'Grey', 'CNM Coaching' ),
            'slug' => 'grey',
            'color' => '#f7f7f7',
        ]
    ] );
    add_theme_support( 'disable-custom-colors' );
    add_theme_support( 'align-wide' );
});

add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

function wti_loginout_menu_link( $items, $args ) {
   if ($args->menu->slug == 'main-menu') {
   	  $isAdmin = false;
   	  if( current_user_can('administrator') ) {
   	  	$isAdmin = true;
      	$items .= '<li class="menu-item"><a href="/search-for-users/">Coach Search</a></li>';
      }
      if (is_user_logged_in()) {
      	 if(!$isAdmin) {
      	 	$items .= '<li class="menu-item"><a href="/dashboard">'. __("Dashboard") .'</a></li>';
      	 }
         $items .= '<li class="menu-item"><a href="'. wp_logout_url('/') .'">'. __("Log Out") .'</a></li>';
      } else {
      	 $items .= '<li class="menu-item"><a href="/contact">'. __("Contact") .'</a></li>';
         $items .= '<li class="menu-item"><a href="/login">'. __("Log In") .'</a></li>';
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

	$output = "<div class='upcoming-events-section'><div class='wrap'>";
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
			$output .= "<div class='first'><a href='/events/category/" . sanitize_title(get_the_title()) . "/' class='button secondary'>Get Started</a></div>";

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


//Woocommerce Functions
//Change columns to 4 on shop page
function woocommerce_column_override(){
	return 4;
}
add_filter('loop_shop_columns', 'woocommerce_column_override');

add_action( 'get_header', 'remove_titles_single_posts' );
function remove_titles_single_posts() {
    if ( is_singular('product') ) {
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    }
}

/**
 * Change number of related products output
 */
function woo_related_products_limit() {
  global $product;

	$args['posts_per_page'] = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
  function jk_related_products_args( $args ) {
	$args['posts_per_page'] = 4; // 4 related products
	$args['columns'] = 4; // arranged in 2 columns
	return $args;
}

add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//integrate Woocommerce and Genesis
/**********************************
*
* Integrate WooCommerce with Genesis.
*
* Unhook WooCommerce wrappers and
* Replace with Genesis wrappers.
*
* Reference Genesis file:
* genesis/lib/framework.php
*
* @author AlphaBlossom / Tony Eppright
* @link http://www.alphablossom.com
*
**********************************/

// Add WooCommerce support for Genesis layouts (sidebar, full-width, etc) - Thank you Kelly Murray/David Wang
add_post_type_support( 'product', array( 'genesis-layouts', 'genesis-seo' ) );

// Unhook WooCommerce Sidebar - use Genesis Sidebars instead
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Unhook WooCommerce wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Hook new functions with Genesis wrappers
add_action( 'woocommerce_before_main_content', 'youruniqueprefix_my_theme_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'youruniqueprefix_my_theme_wrapper_end', 10 );

// Add opening wrapper before WooCommerce loop
function youruniqueprefix_my_theme_wrapper_start() {

    do_action( 'genesis_before_content_sidebar_wrap' );
    genesis_markup( array(
        'html5' => '<div %s>',
        'xhtml' => '<div id="content-sidebar-wrap">',
        'context' => 'content-sidebar-wrap',
    ) );

    do_action( 'genesis_before_content' );
    genesis_markup( array(
        'html5' => '<main %s>',
        'xhtml' => '<div id="content" class="hfeed">',
        'context' => 'content',
    ) );
    do_action( 'genesis_before_loop' );

}

/* Add closing wrapper after WooCommerce loop */
function youruniqueprefix_my_theme_wrapper_end() {

    do_action( 'genesis_after_loop' );
    genesis_markup( array(
        'html5' => '</main>', //* end .content
        'xhtml' => '</div>', //* end #content
    ) );
    do_action( 'genesis_after_content' );

    echo '</div>'; //* end .content-sidebar-wrap or #content-sidebar-wrap
    do_action( 'genesis_after_content_sidebar_wrap' );

}

// Remove WooCommerce breadcrumbs, using Genesis crumbs instead.
add_action( 'get_header', 'youruniqueprefix_remove_wc_breadcrumbs' );
function youruniqueprefix_remove_wc_breadcrumbs() {

    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

}



// Remove WooCommerce Theme Support admin message
add_theme_support( 'woocommerce' );

/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */

function redirect_to_dashboard( $redirect_to, $request, $user ) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for subscribers
        if (in_array('subscriber', $user->roles) || in_array('customer', $user->roles)) {
            // redirect them to another URL, in this case, the homepage
            $redirect_to =  home_url('/dashboard');
						//Redirect to the most recently visited training, if set.
						if(isset($_SESSION['last_visited_training'])) {
						 		$redirect_to =  $_SESSION['last_visited_training'];
						}
        }
    }
    return $redirect_to;
}

add_filter( 'login_redirect', 'redirect_to_dashboard', 10, 3 );

add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

function my_front_end_login_fail( $username ) {
	$referrer = $_SERVER['HTTP_REFERER'];
	// where did the post submission come from?
	// if there's a valid referrer, and it's not the default log-in screen
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
		if(!strstr($referrer, '?login=failed')) {
			wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
		} else {
			wp_redirect( $referrer );
		}
		exit;
	}
}

/* Communicty Event Customizations */
add_filter( 'tribe_events_community_required_fields', 'elevenonline_add_required_fields_to_community_events', 10, 1 );

function elevenonline_add_required_fields_to_community_events( $fields ) {

    if ( ! is_array( $fields ) ) {
        return $fields;
    }

    $fields[] = 'tax_input.tribe_events_cat';

    return $fields;
}

add_filter( 'tribe_community_events_form_errors', 'elevenonline_custom_community_events_error_messages' );

function elevenonline_custom_community_events_error_messages( $errors ) {

	if( is_array($errors) ) {
		$message = $errors[0]['message'];
		// replace our bad error message
		$message = str_replace("Event Category is required", "The Training Type is required.", $message);
		$errors[0]['message'] = $message;
	}

    return $errors;
}

function elevenonline_save_extra_community_event_data( $post_id, $post, $update ) {

    $post_type = get_post_type($post_id);

    // If this isn't a 'tribe_events' post, don't update it.
    if ( "tribe_events" != $post_type ) return;


    // Set the coaches
    if ( isset( $_POST['coaches'] ) ) {
    	$coaches = [];
    	foreach ($_POST['coaches'] as $coach) {
    		$coaches[] = $coach;
    	}
        update_post_meta( $post_id, '_cnmi_event_metabox_user_multicheckbox', $coaches);
    }

    // if licensing org, set that data
    $user_id = get_current_user_id();
	$memberships = wc_memberships_get_user_active_memberships( $user_id );
	$plan_id = $memberships[0]->{"plan_id"};
	if ($plan_id == 410) {
        update_post_meta( $post_id, '_cnmi_event_metabox_licensing_org_multicheckbox', $user_id);
	}
}
add_action( 'save_post', 'elevenonline_save_extra_community_event_data', 10, 3 );

/* adding extra profile fields */
add_action( 'show_user_profile', 'elevenonline_extra_coaching_fields' );
add_action( 'edit_user_profile', 'elevenonline_extra_coaching_fields' );

function elevenonline_extra_coaching_fields( $currentUser ) {
	$users = get_users(  );

	$user_options = array();

	$selected_org = get_user_meta($currentUser->ID, 'licensing_org', true);

	$user_options = array();
	if ( $users ) {
		foreach ( $users as $user ) {
			$user_id = $user->ID;
			if(wc_memberships_is_user_active_member( $user_id, 'licensed-org' )){
				$user_options[ $user->ID ] = $user->first_name . ' ' . $user->last_name;
			}
		}
	}
	$renewal_date = get_user_meta( $currentUser->ID, 'licensing_renewal_date', true );
	$licensing_document = get_user_meta( $currentUser->ID, 'licensing_document', true );
	?>
    <h3>Licensing Organization Extra Data</h3>

    <table class="form-table">
	    <tr>
	    	<th>
	    		<label for="licensing_org">Licencing Org (for coaches attached to a licensing organization)</label>
	    	</th>
	        <td>
	            <select id="licensing_org" name="licensing_org">
	            	<option value="">Select One</option>
					<?php
						foreach($user_options as $id => $name) {
							?>
								<option value="<?php echo $id; ?>" <?php echo $selected_org == $id ? "selected" : null?>><?php echo $name; ?></option>
							<?php
						}
					?>
				</select>
	        </td>
	    </tr>
	    <tr>
	    	<th>
	    		<label for="licensing_renewal_date">Renewal Date (for Licensing Orgs)</label>
	    	</th>
	        <td>
	            <input type="date" id="licensing_renewal_date" name="licensing_renewal_date" value="<?php echo $renewal_date; ?>"/>
	        </td>
	    </tr>
	    <tr>
            <th>
            	<label for="licensing_document">Licensing Document</label>
            </th>
            <td><input type="file" name="licensing_document" id="licensing_document" value="">
            <?php
                if($licensing_document) {
                	?>
						<p><a href="<?php echo $licensing_document; ?>" target="_blank">View Current Document</a></p>
                	<?php
                }
            ?>
            </td>
        </tr>
    </table>
    <script type="text/javascript">
		(function( $ ) {
			$( '#your-profile' ).attr("enctype", "multipart/form-data");
		})(jQuery);
	</script>
	<?php
}

add_action( 'personal_options_update', 'elevenonline_save_licensing_extra_info' );
add_action( 'edit_user_profile_update', 'elevenonline_save_licensing_extra_info' );

function elevenonline_save_licensing_extra_info( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    if( isset($_FILES['licensing_document']['name']) && $_FILES['licensing_document']['name'] != '' ) {
    	$file = $_FILES['licensing_document'];
    	$bits = file_get_contents($file["tmp_name"]);
        $filetype = wp_check_filetype($file["name"]);
        $filename = $file["name"] . '_' . time() . '.' . $filetype['ext'];
        $upload = wp_upload_bits($filename, null, $bits);
        update_user_meta( $user_id, 'licensing_document', $upload['url'] );
    }
    if($_POST['licensing_org'] && $_POST['licensing_org'] != '') {
    	update_user_meta( $user_id, 'licensing_org', $_POST['licensing_org'] );
    }
    if($_POST['licensing_renewal_date'] && $_POST['licensing_renewal_date'] != '') {
    	update_user_meta( $user_id, 'licensing_renewal_date', $_POST['licensing_renewal_date'] );
    }
}


/**
 * Change "Buy Now!" button text
 */
function elevenonline_buy_now_button_text( $html ) {
	$html = str_replace( 'Buy Now!' , 'Register', $html );
	return $html;
}
add_filter( 'tribe_tickets_buy_button', 'elevenonline_buy_now_button_text', 11, 2 );


// Fix for the attendee registration page glitch with Genesis
add_action( 'genesis_before_loop', 'tribe_tickets_fix_etp_attendee_registration_page_for_genesis' );
function tribe_tickets_fix_etp_attendee_registration_page_for_genesis() {
	if ( ! function_exists( 'tribe' ) ) {
		return;
	}
	try {
		if ( tribe( 'tickets.attendee_registration' )->is_on_page() ) {
			remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
			add_action( 'genesis_entry_content', 'do_post_content' );
		}
	} catch ( Exception $e ) {}
}
function do_post_content() {
	the_content();
}
//Set last visited training  variable for redirection after login or registration.
add_action('genesis_footer', 'eo_set_last_visited_training');
function eo_set_last_visited_training() {
	if(!is_user_logged_in() && tribe_is_event() && is_single()){
		$obj_id = get_queried_object_id();
		$current_url = get_permalink( $obj_id );
	 $_SESSION['last_visited_training'] = $current_url;
	}
}

//clear last visited training on logout.
add_action('wp_logout', 'eo_clear_last_visited_training');
function eo_clear_last_visited_training() {
	if($_SESSION['last_visited_training']){
		unset($_SESSION['last_visited_training']);
	}
}

add_action( 'pre_get_posts', 'restrict_license_org_events' );
//Restrict license org events to events created by that org.
function restrict_license_org_events( $query ) {
	 $post_type = $query->get('post_type');
	if(! is_admin() && $post_type == 'tribe_events'){
		$current_user = wp_get_current_user();
	  $user_id = $current_user->ID;
		$memberships = wc_memberships_get_user_active_memberships( $user_id );
		$plan_id = $memberships[0]->{"plan_id"};
		if($plan_id == 410){
	 		$query->set('meta_query', array(
				array('key' => '_cnmi_event_metabox_licensing_org_multicheckbox',
							'value' => $user_id,
							'compare' => 'like'
						)
					)
			);
		}
	 }
}

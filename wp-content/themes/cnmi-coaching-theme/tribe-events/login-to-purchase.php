<?php
/**
 * Renders a link displayed to customers when they must first login
 * before being able to purchase tickets.
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/login-to-purchase.php
 *
 * @version 4.7
 */
//Commented out $login_url because we are using a different login url.
// $login_url = Tribe__Tickets__Tickets::get_login_url();
// ?>
 <a href="/login"><?php esc_html_e( 'Log in', 'event-tickets' ); ?></a>
 <p>or</p>
 <a href="/register-as-a-coach-in-training/">Register</a>
 <p>to purchse</p>

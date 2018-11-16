<?php
/**
 * My Payment Options Template
 * The template for payment options.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/payment-options.php
 *
 * @package Tribe__Events__Community__Community_Events_Tickets
 * @since  3.1
 * @author Modern Tribe Inc.
 * @version 4.5.4
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div id="tribe-community-events">
	<div class="tribe-menu-wrapper">
		<a href="<?php echo esc_url( tribe_community_events_list_events_link() ); ?>" class="button">
			<?php echo sprintf( __( 'My %s', 'tribe-events-community' ), tribe_get_event_label_plural() ); ?>
		</a>
		<?php do_action( 'tribe_ct_payment_options_nav' ); ?>
	</div>

	<?php
	do_action( 'tribe_ct_before_the_payment_options' );
	$options = get_option( Tribe__Events__Community__Tickets__Main::OPTIONNAME, Tribe__Events__Community__Tickets__Main::instance()->option_defaults );
	?>

	<form method="post">
		<?php wp_nonce_field( 'tribe_ct_save_payment_options', 'payment_options_nonce' ); ?>
		<h3><?php echo esc_html__( 'PayPal Options', 'tribe-events-community-tickets' ); ?></h3>
		<div class="tribe-section-container">
			<p>
				<?php
				esc_html_e( 'Please enter your PayPal email address; this is needed in order to take payment.', 'tribe-events-community-tickets' );
				?>
			</p>
			<table class="tribe-community-tickets-payment-options" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td>
							<?php tribe_community_events_field_label( 'paypal_account_email', __( 'Email:', 'tribe-events-community-tickets' ) ); ?>
						</td>
						<td>
							<input type="email" id="paypal_account_email" name="paypal_account_email" value="<?php echo esc_attr( $data['paypal_account_email'] ); ?>"/>
							<?php
							if ( Tribe__Events__Community__Tickets__Main::instance()->is_split_payments_enabled() ) {
								?>
								<div class="note">
									<?php
									esc_html_e( 'Tickets cannot be created without an email address that is associated with PayPal', 'tribe-events-community-tickets' );
									?>
								</div>
								<?php
							}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php
		$gateway = Tribe__Events__Community__Tickets__Main::instance()->gateway( 'PayPal' );

		$flat = $gateway->fee_flat;
		$percentage = $gateway->fee_percentage;

		if ( 'flat' === $options['site_fee_type'] || 'flat-and-percentage' === $options['site_fee_type'] ) {
			$flat += (float) $options['site_fee_flat'];
		}

		if ( 'percentage' === $options['site_fee_type'] || 'flat-and-percentage' === $options['site_fee_type'] ) {
			$percentage += (float) $options['site_fee_percentage'];
		}

		if ( $flat || $percentage ) {
			?>
			<h3><?php echo esc_html__( 'Ticket Fees', 'tribe-events-community-tickets' ); ?></h3>
			<div class="tribe-section-container">
				<table class="tribe-community-tickets-payment-options" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td>
								<?php echo esc_html__( 'Fee Structure:', 'tribe-events-community-tickets' ); ?>
							</td>
							<td>
								<p>
									<?php
									if ( $flat && $percentage ) {
										echo sprintf(
											esc_html__(
												'Fees are %s%% per transaction plus a $%s flat fee per ticket.',
												'tribe-events-community-tickets'
											),
											number_format( $percentage, 1 ),
											number_format( $flat, 2 )
										);
									} elseif ( $flat ) {
										echo sprintf(
											esc_html__(
												'Fees are a flat fee of $%1$s per ticket.',
												'tribe-events-community-tickets'
											),
											number_format( $flat, 2 )
										);
									} else {
										echo sprintf(
											esc_html__(
												'Fees are %s%% per transaction.',
												'tribe-events-community-tickets'
											),
											number_format( $percentage, 1 )
										);
									}
									?>
								</p>
							</td>
						</tr>
						<?php
						if ( Tribe__Events__Community__Tickets__Main::instance()->is_split_payments_enabled() ) {
							?>
							<tr>
								<td>
									<?php echo esc_html__( 'Options:', 'tribe-events-community-tickets' ); ?>
								</td>
								<td>
									<input type="radio" id="payment_fee_setting_absorb" name="payment_fee_setting" value="absorb" <?php checked( 'absorb', $data['payment_fee_setting'] ); ?>/>
									<label for="payment_fee_setting_absorb"><?php echo esc_html__( 'Include fees in ticket price', 'tribe-events-community-tickets' ); ?></label>
									<p class="note">
										<?php echo esc_html__( 'Fees will be subtracted from the cost of the ticket (paid tickets only)', 'tribe-events-community-tickets' ); ?>
									</p>
									<input type="radio" id="payment_fee_setting_pass" name="payment_fee_setting" value="pass" <?php checked( 'pass', $data['payment_fee_setting'] ); ?>/>
									<label for="payment_fee_setting_pass"><?php echo esc_html__( 'Display fees in addition to ticket price', 'tribe-events-community-tickets' ); ?></label>
									<p class="note">
										<?php echo esc_html__( 'Additional fees will be added to the total ticket price (applies to paid and free tickets)', 'tribe-events-community-tickets' ); ?>
									</p>
								</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<?php
		}//end if
		?>
		<div class="tribe-events-community-footer">
			<input type="submit" class="button submit events-community-submit" value="<?php echo esc_attr__( 'Save', 'tribe-events-community-tickets' ); ?>">
		</div>
	</form>
</div>
<?php
do_action( 'tribe_ct_before_the_payment_options' );

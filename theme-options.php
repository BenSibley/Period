<?php

function ct_period_register_theme_page() {
	add_theme_page( sprintf( __( '%s Dashboard', 'period' ), wp_get_theme( get_template() ) ), sprintf( __( '%s Dashboard', 'period' ), wp_get_theme( get_template() ) ), 'edit_theme_options', 'period-options', 'ct_period_options_content', 'ct_period_options_content' );
}
add_action( 'admin_menu', 'ct_period_register_theme_page' );

function ct_period_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => home_url(),
			'return' => add_query_arg( 'page', 'period-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	?>
	<div id="period-dashboard-wrap" class="wrap">
		<h2><?php printf( __( '%s Dashboard', 'period' ), wp_get_theme( get_template() ) ); ?></h2>
		<?php do_action( 'theme_options_before' ); ?>
		<div class="content-boxes">
			<div class="content content-support">
				<h3><?php _e( 'Get Started', 'period' ); ?></h3>
				<p><?php printf( __( 'Not sure where to start? The %1$s Support Center is filled with tutorials that will take you step-by-step through every feature in %2$s.', 'period' ), wp_get_theme( get_template() ) ); ?></p>
				<p>
					<a target="_blank" class="button-primary"
					   href="https://www.competethemes.com/documentation/period-support-center/"><?php _e( 'Visit Support Center', 'period' ); ?></a>
				</p>
			</div>
			<?php if ( !function_exists( 'ct_period_pro_init' ) ) : ?>
				<div class="content content-premium-upgrade">
					<h3><?php _e( 'Period Pro', 'period' ); ?></h3>
					<p><?php printf( __( 'Download the %s Pro plugin and unlock custom colors, new layouts, sliders, and more', 'period' ), wp_get_theme( get_template() ) ); ?>...</p>
					<p>
						<a target="_blank" class="button-primary"
						   href="https://www.competethemes.com/period-pro/"><?php _e( 'See Full Feature List', 'period' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
			<div class="content content-review">
				<h3><?php _e( 'Leave a Review', 'period' ); ?></h3>
				<p><?php printf( __( 'Help others find %s by leaving a review on wordpress.org.', 'period' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://wordpress.org/support/theme/period/reviews/"><?php _e( 'Leave a Review', 'period' ); ?></a>
			</div>
			<div class="content content-delete-settings">
				<h3><?php _e( 'Reset Customizer Settings', 'period' ); ?></h3>
				<p>
					<?php printf( __( "<strong>Warning:</strong> Clicking this button will erase the %2$s theme's current settings in the <a href='%1$s'>Customizer</a>.", 'period' ), esc_url( $customizer_url ), wp_get_theme( get_template() ) ); ?>
				</p>
				<form method="post">
					<input type="hidden" name="period_reset_customizer" value="period_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field( 'period_reset_customizer_nonce', 'period_reset_customizer_nonce' ); ?>
						<?php submit_button( __( 'Reset Customizer Settings', 'period' ), 'delete', 'delete', false ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }
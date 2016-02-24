<?php

function ct_period_register_theme_page() {
	add_theme_page( __( 'Period Dashboard', 'period' ), __( 'Period Dashboard', 'period' ), 'edit_theme_options', 'period-options', 'ct_period_options_content', 'ct_period_options_content' );
}
add_action( 'admin_menu', 'ct_period_register_theme_page' );

function ct_period_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => site_url(),
			'return' => add_query_arg( 'page', 'period-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	?>
	<div id="period-dashboard-wrap" class="wrap">
		<h2><?php _e( 'Period Dashboard', 'period' ); ?></h2>
		<?php do_action( 'theme_options_before' ); ?>
		<div class="content content-customization">
			<h3><?php _e( 'Customization', 'period' ); ?></h3>
			<p><?php _e( 'Click the "Customize" link in your menu, or use the button below to get started customizing Period', 'period' ); ?>.</p>
			<p>
				<a class="button-primary"
				   href="<?php echo esc_url( $customizer_url ); ?>"><?php _e( 'Use Customizer', 'period' ) ?></a>
			</p>
		</div>
		<div class="content content-support">
			<h3><?php _e( 'Support', 'period' ); ?></h3>
			<p><?php _e( "You can find the knowledgebase, changelog, support forum, and more in the Period Support Center", "period" ); ?>.</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/documentation/period-support-center/"><?php _e( 'Visit Support Center', 'period' ); ?></a>
			</p>
		</div>
		<div class="content content-premium-upgrade">
			<h3><?php _e( 'Period Pro', 'period' ); ?></h3>
			<p><?php _e( 'Download the Period Pro plugin and unlock custom colors, new layouts, sliders, and more', 'period' ); ?>...</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/period-pro/"><?php _e( 'See Full Feature List', 'period' ); ?></a>
			</p>
		</div>
		<div class="content content-resources">
			<h3><?php _e( 'WordPress Resources', 'period' ); ?></h3>
			<p><?php _e( 'Save time and money searching for WordPress products by following our recommendations', 'period' ); ?>.</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/wordpress-resources/"><?php _e( 'View Resources', 'period' ); ?></a>
			</p>
		</div>
		<div class="content content-review">
			<h3><?php _e( 'Leave a Review', 'period' ); ?></h3>
			<p><?php _e( 'Help others find Period by leaving a review on wordpress.org.', 'period' ); ?></p>
			<a target="_blank" class="button-primary" href="https://wordpress.org/support/view/theme-reviews/period"><?php _e( 'Leave a Review', 'period' ); ?></a>
		</div>
		<div class="content content-delete-settings">
			<h3><?php _e( 'Reset Customizer Settings', 'period' ); ?></h3>
			<p>
				<?php printf( __( "<strong>Warning:</strong> Clicking this button will erase the Period theme's current settings in the <a href='%s'>Customizer</a>.", 'period' ), esc_url( $customizer_url ) ); ?>
			</p>
			<form method="post">
				<input type="hidden" name="period_reset_customizer" value="period_reset_customizer_settings"/>
				<p>
					<?php wp_nonce_field( 'period_reset_customizer_nonce', 'period_reset_customizer_nonce' ); ?>
					<?php submit_button( __( 'Reset Customizer Settings', 'period' ), 'delete', 'delete', false ); ?>
				</p>
			</form>
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }
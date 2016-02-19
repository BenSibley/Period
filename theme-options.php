<?php

function ct_ct_theme_name_register_theme_page() {
	add_theme_page( __( 'Ct_theme_name Dashboard', 'ct_theme_name' ), __( 'Ct_theme_name Dashboard', 'ct_theme_name' ), 'edit_theme_options', 'ct_theme_name-options', 'ct_ct_theme_name_options_content', 'ct_ct_theme_name_options_content' );
}
add_action( 'admin_menu', 'ct_ct_theme_name_register_theme_page' );

function ct_ct_theme_name_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => site_url(),
			'return' => add_query_arg( 'page', 'ct_theme_name-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	?>
	<div id="ct_theme_name-dashboard-wrap" class="wrap">
		<h2><?php _e( 'Ct_theme_name Dashboard', 'ct_theme_name' ); ?></h2>
		<?php do_action( 'theme_options_before' ); ?>
		<div class="content content-customization">
			<h3><?php _e( 'Customization', 'ct_theme_name' ); ?></h3>
			<p><?php _e( 'Click the "Customize" link in your menu, or use the button below to get started customizing Ct_theme_name', 'ct_theme_name' ); ?>.</p>
			<p>
				<a class="button-primary"
				   href="<?php echo esc_url( $customizer_url ); ?>"><?php _e( 'Use Customizer', 'ct_theme_name' ) ?></a>
			</p>
		</div>
		<div class="content content-support">
			<h3><?php _e( 'Support', 'ct_theme_name' ); ?></h3>
			<p><?php _e( "You can find the knowledgebase, changelog, support forum, and more in the ct_theme_name Support Center", "ct_theme_name" ); ?>.</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/documentation/ct_theme_name-support-center/"><?php _e( 'Visit Support Center', 'ct_theme_name' ); ?></a>
			</p>
		</div>
		<div class="content content-premium-upgrade">
			<h3><?php _e( 'Get More Features & Flexibility', 'ct_theme_name' ); ?></h3>
			<p><?php _e( 'Download the Ct_theme_name Pro plugin and unlock custom colors, new layouts, sliders, and more', 'ct_theme_name' ); ?>...</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/ct_theme_name-pro/"><?php _e( 'See Full Feature List', 'ct_theme_name' ); ?></a>
			</p>
		</div>
		<div class="content content-resources">
			<h3><?php _e( 'WordPress Resources', 'ct_theme_name' ); ?></h3>
			<p><?php _e( 'Save time and money searching for WordPress products by following our recommendations', 'ct_theme_name' ); ?>.</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/wordpress-resources/"><?php _e( 'View Resources', 'ct_theme_name' ); ?></a>
			</p>
		</div>
		<div class="content content-review">
			<h3><?php _e( 'Leave a Review', 'ct_theme_name' ); ?></h3>
			<p><?php _e( 'Help others find Ct_theme_name by leaving a review on wordpress.org.', 'ct_theme_name' ); ?></p>
			<a target="_blank" class="button-primary" href="https://wordpress.org/support/view/theme-reviews/ct_theme_name"><?php _e( 'Leave a Review', 'ct_theme_name' ); ?></a>
		</div>
		<div class="content content-delete-settings">
			<h3><?php _e( 'Reset Customizer Settings', 'ct_theme_name' ); ?></h3>
			<p>
				<?php printf( __( "<strong>Warning:</strong> Clicking this button will erase the Ct_theme_name theme's current settings in the <a href='%s'>Customizer</a>.", 'ct_theme_name' ), esc_url( $customizer_url ) ); ?>
			</p>
			<form method="post">
				<input type="hidden" name="ct_theme_name_reset_customizer" value="ct_theme_name_reset_customizer_settings"/>
				<p>
					<?php wp_nonce_field( 'ct_theme_name_reset_customizer_nonce', 'ct_theme_name_reset_customizer_nonce' ); ?>
					<?php submit_button( __( 'Reset Customizer Settings', 'ct_theme_name' ), 'delete', 'delete', false ); ?>
				</p>
			</form>
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }
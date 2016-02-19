<?php

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'ct_ct_theme_name_add_customizer_content' );

function ct_ct_theme_name_add_customizer_content( $wp_customize ) {

	/***** Reorder default sections *****/

	$wp_customize->get_section( 'title_tagline' )->priority = 1;

	// check if exists in case user has no pages
	if ( is_object( $wp_customize->get_section( 'static_front_page' ) ) ) {
		$wp_customize->get_section( 'static_front_page' )->priority = 5;
		$wp_customize->get_section( 'static_front_page' )->title    = __( 'Front Page', 'ct_theme_name' );
	}

	/***** Add PostMessage Support *****/

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	/***** Logo Upload *****/

	// section
	$wp_customize->add_section( 'ct_ct_theme_name_logo_upload', array(
		'title'    => __( 'Logo', 'ct_theme_name' ),
		'priority' => 20
	) );
	// setting
	$wp_customize->add_setting( 'logo_upload', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'logo_image', array(
			'label'    => __( 'Upload custom logo.', 'ct_theme_name' ),
			'section'  => 'ct_ct_theme_name_logo_upload',
			'settings' => 'logo_upload'
		)
	) );

	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = ct_ct_theme_name_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_ct_theme_name_social_media_icons', array(
		'title'       => __( 'Social Media Icons', 'ct_theme_name' ),
		'priority'    => 25,
		'description' => __( 'Add the URL for each of your social profiles.', 'ct_theme_name' )
	) );

	// create a setting and control for each social site
	foreach ( $social_sites as $social_site => $value ) {
		// if email icon
		if ( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_ct_theme_name_sanitize_email'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Email Address', 'ct_theme_name' ),
				'section'  => 'ct_ct_theme_name_social_media_icons',
				'priority' => $priority
			) );
		} else {

			$label = ucfirst( $social_site );

			if ( $social_site == 'google-plus' ) {
				$label = 'Google Plus';
			} elseif ( $social_site == 'rss' ) {
				$label = 'RSS';
			} elseif ( $social_site == 'soundcloud' ) {
				$label = 'SoundCloud';
			} elseif ( $social_site == 'slideshare' ) {
				$label = 'SlideShare';
			} elseif ( $social_site == 'codepen' ) {
				$label = 'CodePen';
			} elseif ( $social_site == 'stumbleupon' ) {
				$label = 'StumbleUpon';
			} elseif ( $social_site == 'deviantart' ) {
				$label = 'DeviantArt';
			} elseif ( $social_site == 'hacker-news' ) {
				$label = 'Hacker News';
			} elseif ( $social_site == 'whatsapp' ) {
				$label = 'WhatsApp';
			} elseif ( $social_site == 'qq' ) {
				$label = 'QQ';
			} elseif ( $social_site == 'vk' ) {
				$label = 'VK';
			} elseif ( $social_site == 'wechat' ) {
				$label = 'WeChat';
			} elseif ( $social_site == 'tencent-weibo' ) {
				$label = 'Tencent Weibo';
			} elseif ( $social_site == 'paypal' ) {
				$label = 'PayPal';
			} elseif ( $social_site == 'email-form' ) {
				$label = 'Contact Form';
			}

			if ( $social_site == 'skype' ) {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'ct_ct_theme_name_sanitize_skype'
				) );
			} else {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'esc_url_raw'
				) );
			}
			// control
			$wp_customize->add_control( $social_site, array(
				'type'     => 'url',
				'label'    => $label,
				'section'  => 'ct_ct_theme_name_social_media_icons',
				'priority' => $priority
			) );
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	/***** Search Bar *****/

	// section
	$wp_customize->add_section( 'ct_theme_name_search_bar', array(
		'title'    => __( 'Search Bar', 'ct_theme_name' ),
		'priority' => 37
	) );
	// setting
	$wp_customize->add_setting( 'search_bar', array(
		'default'           => 'hide',
		'sanitize_callback' => 'ct_ct_theme_name_sanitize_all_show_hide_settings'
	) );
	// control
	$wp_customize->add_control( 'search_bar', array(
		'type'    => 'radio',
		'label'   => __( 'Show search bar at top of site?', 'ct_theme_name' ),
		'section' => 'ct_theme_name_search_bar',
		'setting' => 'search_bar',
		'choices' => array(
			'show' => __( 'Show', 'ct_theme_name' ),
			'hide' => __( 'Hide', 'ct_theme_name' )
		),
	) );

	/***** Blog *****/

	// section
	$wp_customize->add_section( 'ct_theme_name_blog', array(
		'title'    => __( 'Blog', 'ct_theme_name' ),
		'priority' => 45
	) );
	// setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_ct_theme_name_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'full_post', array(
		'label'    => __( 'Show full posts on blog?', 'ct_theme_name' ),
		'section'  => 'ct_theme_name_blog',
		'settings' => 'full_post',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'ct_theme_name' ),
			'no'  => __( 'No', 'ct_theme_name' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => '25',
		'sanitize_callback' => 'absint'
	) );
	// control
	$wp_customize->add_control( 'excerpt_length', array(
		'label'    => __( 'Excerpt word count', 'ct_theme_name' ),
		'section'  => 'ct_theme_name_blog',
		'settings' => 'excerpt_length',
		'type'     => 'number'
	) );
	// Read More text - setting
	$wp_customize->add_setting( 'read_more_text', array(
		'default'           => __( 'Continue Reading', 'ct_theme_name' ),
		'sanitize_callback' => 'ct_ct_theme_name_sanitize_text'
	) );
	// Read More text - control
	$wp_customize->add_control( 'read_more_text', array(
		'label'    => __( 'Read More button text', 'ct_theme_name' ),
		'section'  => 'ct_theme_name_blog',
		'settings' => 'read_more_text',
		'type'     => 'text'
	) );

	/***** Additional Options *****/

	// section
	$wp_customize->add_section( 'ct_theme_name_additional', array(
		'title'    => __( 'Additional Options', 'ct_theme_name' ),
		'priority' => 70
	) );
	// extra-wide post - setting
	$wp_customize->add_setting( 'full_width_post', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_ct_theme_name_sanitize_yes_no_settings'
	) );
	// extra-wide post - control
	$wp_customize->add_control( 'full_width_post', array(
		'label'    => __( 'Make first post on blog extra wide?', 'ct_theme_name' ),
		'section'  => 'ct_theme_name_additional',
		'settings' => 'full_width_post',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'ct_theme_name' ),
			'no'  => __( 'No', 'ct_theme_name' )
		)
	) );
	// author byline - setting
	$wp_customize->add_setting( 'author_byline', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_ct_theme_name_sanitize_yes_no_settings'
	) );
	// author byline - control
	$wp_customize->add_control( 'author_byline', array(
		'label'    => __( 'Display post author name in byline?', 'ct_theme_name' ),
		'section'  => 'ct_theme_name_additional',
		'settings' => 'author_byline',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'ct_theme_name' ),
			'no'  => __( 'No', 'ct_theme_name' )
		)
	) );

	/***** Custom CSS *****/

	// section
	$wp_customize->add_section( 'ct_theme_name_custom_css', array(
		'title'    => __( 'Custom CSS', 'ct_theme_name' ),
		'priority' => 75
	) );
	// setting
	$wp_customize->add_setting( 'custom_css', array(
		'sanitize_callback' => 'ct_ct_theme_name_sanitize_css',
		'transport'         => 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'custom_css', array(
		'type'     => 'textarea',
		'label'    => __( 'Add Custom CSS Here:', 'ct_theme_name' ),
		'section'  => 'ct_theme_name_custom_css',
		'settings' => 'custom_css'
	) );
}

/***** Custom Sanitization Functions *****/

/*
 * Sanitize settings with show/hide as options
 * Used in: search bar
 */
function ct_ct_theme_name_sanitize_all_show_hide_settings( $input ) {

	$valid = array(
		'show' => __( 'Show', 'ct_theme_name' ),
		'hide' => __( 'Hide', 'ct_theme_name' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

/*
 * sanitize email address
 * Used in: Social Media Icons
 */
function ct_ct_theme_name_sanitize_email( $input ) {
	return sanitize_email( $input );
}

// sanitize yes/no settings
function ct_ct_theme_name_sanitize_yes_no_settings( $input ) {

	$valid = array(
		'yes' => __( 'Yes', 'ct_theme_name' ),
		'no'  => __( 'No', 'ct_theme_name' ),
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_ct_theme_name_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function ct_ct_theme_name_sanitize_skype( $input ) {
	return esc_url_raw( $input, array( 'http', 'https', 'skype' ) );
}

function ct_ct_theme_name_sanitize_css( $css ) {
	$css = wp_kses( $css, array( '\'', '\"' ) );
	$css = str_replace( '&gt;', '>', $css );

	return $css;
}

/***** Helper Functions *****/

function ct_ct_theme_name_customize_preview_js() {

	$content = "<script>jQuery('#customize-info').prepend('<div class=\"upgrades-ad\"><a href=\"https://www.competethemes.com/ct_theme_name-pro/\" target=\"_blank\">" . __( 'View the Ct_theme_name Pro Plugin', 'ct_theme_name' ) . " <span>&rarr;</span></a></div>')</script>";
	echo apply_filters( 'ct_ct_theme_name_customizer_ad', $content );
}

add_action( 'customize_controls_print_footer_scripts', 'ct_ct_theme_name_customize_preview_js' );
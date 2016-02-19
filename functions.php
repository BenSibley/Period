<?php

if ( ! isset( $content_width ) ) {
	$content_width = 891;
}

if ( ! function_exists( ( 'ct_ct_theme_name_theme_setup' ) ) ) {
	function ct_ct_theme_name_theme_setup() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container',
			'render'    => 'ct_ct_theme_name_infinite_scroll_render'
		) );

		require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
		foreach ( glob( trailingslashit( get_template_directory() ) . 'inc/*' ) as $filename ) {
			include $filename;
		}

		register_nav_menus( array(
			'primary' => __( 'Primary', 'ct_theme_name' )
		) );

		load_theme_textdomain( 'ct_theme_name', get_template_directory() . '/languages' );
	}
}
add_action( 'after_setup_theme', 'ct_ct_theme_name_theme_setup', 10 );

function ct_ct_theme_name_register_widget_areas() {

	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'ct_theme_name' ),
		'id'            => 'primary',
		'description'   => __( 'Widgets in this area will be shown in the sidebar next to the main post content', 'ct_theme_name' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );
}
add_action( 'widgets_init', 'ct_ct_theme_name_register_widget_areas' );

if ( ! function_exists( ( 'ct_ct_theme_name_customize_comments' ) ) ) {
	function ct_ct_theme_name_customize_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php
				echo get_avatar( get_comment_author_email(), 36, '', get_comment_author() );
				?>
				<span class="author-name"><?php comment_author_link(); ?></span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'ct_theme_name' ) ?></em>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-footer">
				<span class="comment-date"><?php comment_date(); ?></span>
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => __( 'Reply', 'ct_theme_name' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				) ) ); ?>
				<?php edit_comment_link( __( 'Edit', 'ct_theme_name' ) ); ?>
			</div>
		</article>
		<?php
	}
}

if ( ! function_exists( 'ct_ct_theme_name_update_fields' ) ) {
	function ct_ct_theme_name_update_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$label     = $req ? '*' : ' ' . __( '(optional)', 'ct_theme_name' );
		$aria_req  = $req ? "aria-required='true'" : '';

		$fields['author'] =
			'<p class="comment-form-author">
	            <label for="author">' . __( "Name", "ct_theme_name" ) . $label . '</label>
	            <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['email'] =
			'<p class="comment-form-email">
	            <label for="email">' . __( "Email", "ct_theme_name" ) . $label . '</label>
	            <input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['url'] =
			'<p class="comment-form-url">
	            <label for="url">' . __( "Website", "ct_theme_name" ) . '</label>
	            <input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" />
	            </p>';

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'ct_ct_theme_name_update_fields' );

if ( ! function_exists( 'ct_ct_theme_name_update_comment_field' ) ) {
	function ct_ct_theme_name_update_comment_field( $comment_field ) {

		$comment_field =
			'<p class="comment-form-comment">
	            <label for="comment">' . __( "Comment", "ct_theme_name" ) . '</label>
	            <textarea required id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	        </p>';

		return $comment_field;
	}
}
add_filter( 'comment_form_field_comment', 'ct_ct_theme_name_update_comment_field' );

if ( ! function_exists( 'ct_ct_theme_name_remove_comments_notes_after' ) ) {
	function ct_ct_theme_name_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'ct_ct_theme_name_remove_comments_notes_after' );

if ( ! function_exists( 'ct_ct_theme_name_excerpt' ) ) {
	function ct_ct_theme_name_excerpt() {

		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$read_more_text = get_theme_mod( 'read_more_text' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( ( $show_full_post == 'yes' ) && ! is_search() ) {
			if ( $ismore ) {
				// Has to be written this way because i18n text CANNOT be stored in a variable
				if ( ! empty( $read_more_text ) ) {
					the_content( $read_more_text . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
				} else {
					the_content( __( 'Continue reading', 'ct_theme_name' ) . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
				}
			} else {
				the_content();
			}
		} elseif ( $ismore ) {
			if ( ! empty( $read_more_text ) ) {
				the_content( $read_more_text . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
			} else {
				the_content( __( 'Continue reading', 'ct_theme_name' ) . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
			}
		} else {
			the_excerpt();
		}
	}
}

if ( ! function_exists( 'ct_ct_theme_name_excerpt_read_more_link' ) ) {
	function ct_ct_theme_name_excerpt_read_more_link( $output ) {

		$read_more_text = get_theme_mod( 'read_more_text' );

		if ( ! empty( $read_more_text ) ) {
			return $output . "<p><a class='more-link' href='" . esc_url( get_permalink() ) . "'>" . $read_more_text . " <span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
		} else {
			return $output . "<p><a class='more-link' href='" . esc_url( get_permalink() ) . "'>" . __( 'Continue reading', 'ct_theme_name' ) . " <span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
		}
	}
}
add_filter( 'the_excerpt', 'ct_ct_theme_name_excerpt_read_more_link' );

if ( ! function_exists( 'ct_ct_theme_name_custom_excerpt_length' ) ) {
	function ct_ct_theme_name_custom_excerpt_length( $length ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 25 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 25;
		}
	}
}
add_filter( 'excerpt_length', 'ct_ct_theme_name_custom_excerpt_length', 99 );

if ( ! function_exists( 'ct_ct_theme_name_new_excerpt_more' ) ) {
	function ct_ct_theme_name_new_excerpt_more( $more ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';

		return $excerpt_more;
	}
}
add_filter( 'excerpt_more', 'ct_ct_theme_name_new_excerpt_more' );

if ( ! function_exists( 'ct_ct_theme_name_remove_more_link_scroll' ) ) {
	function ct_ct_theme_name_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_ct_theme_name_remove_more_link_scroll' );

if ( ! function_exists( 'ct_ct_theme_name_featured_image' ) ) {
	function ct_ct_theme_name_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
		}

		$featured_image = apply_filters( 'ct_ct_theme_name_featured_image', $featured_image );

		if ( $featured_image ) {
			echo $featured_image;
		}
	}
}

if ( ! function_exists( 'ct_ct_theme_name_social_array' ) ) {
	function ct_ct_theme_name_social_array() {

		$social_sites = array(
			'twitter'       => 'ct_theme_name_twitter_profile',
			'facebook'      => 'ct_theme_name_facebook_profile',
			'google-plus'   => 'ct_theme_name_googleplus_profile',
			'pinterest'     => 'ct_theme_name_pinterest_profile',
			'linkedin'      => 'ct_theme_name_linkedin_profile',
			'youtube'       => 'ct_theme_name_youtube_profile',
			'vimeo'         => 'ct_theme_name_vimeo_profile',
			'tumblr'        => 'ct_theme_name_tumblr_profile',
			'instagram'     => 'ct_theme_name_instagram_profile',
			'flickr'        => 'ct_theme_name_flickr_profile',
			'dribbble'      => 'ct_theme_name_dribbble_profile',
			'rss'           => 'ct_theme_name_rss_profile',
			'reddit'        => 'ct_theme_name_reddit_profile',
			'soundcloud'    => 'ct_theme_name_soundcloud_profile',
			'spotify'       => 'ct_theme_name_spotify_profile',
			'vine'          => 'ct_theme_name_vine_profile',
			'yahoo'         => 'ct_theme_name_yahoo_profile',
			'behance'       => 'ct_theme_name_behance_profile',
			'codepen'       => 'ct_theme_name_codepen_profile',
			'delicious'     => 'ct_theme_name_delicious_profile',
			'stumbleupon'   => 'ct_theme_name_stumbleupon_profile',
			'deviantart'    => 'ct_theme_name_deviantart_profile',
			'digg'          => 'ct_theme_name_digg_profile',
			'github'        => 'ct_theme_name_github_profile',
			'hacker-news'   => 'ct_theme_name_hacker-news_profile',
			'steam'         => 'ct_theme_name_steam_profile',
			'vk'            => 'ct_theme_name_vk_profile',
			'weibo'         => 'ct_theme_name_weibo_profile',
			'tencent-weibo' => 'ct_theme_name_tencent_weibo_profile',
			'500px'         => 'ct_theme_name_500px_profile',
			'foursquare'    => 'ct_theme_name_foursquare_profile',
			'slack'         => 'ct_theme_name_slack_profile',
			'slideshare'    => 'ct_theme_name_slideshare_profile',
			'qq'            => 'ct_theme_name_qq_profile',
			'whatsapp'      => 'ct_theme_name_whatsapp_profile',
			'skype'         => 'ct_theme_name_skype_profile',
			'wechat'        => 'ct_theme_name_wechat_profile',
			'xing'          => 'ct_theme_name_xing_profile',
			'paypal'        => 'ct_theme_name_paypal_profile',
			'email'         => 'ct_theme_name_email_profile',
			'email-form'    => 'ct_theme_name_email_form_profile'
		);

		return apply_filters( 'ct_ct_theme_name_social_array_filter', $social_sites );
	}
}

if ( ! function_exists( 'ct_ct_theme_name_social_icons_output' ) ) {
	function ct_ct_theme_name_social_icons_output() {

		$social_sites = ct_ct_theme_name_social_array();

		foreach ( $social_sites as $social_site => $profile ) {

			if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
				$active_sites[ $social_site ] = $social_site;
			}
		}

		if ( ! empty( $active_sites ) ) {

			echo "<ul class='social-media-icons'>";

			foreach ( $active_sites as $key => $active_site ) {

				echo '<li>';
				if ( $active_site == 'email' ) { ?>
					<a class="email" target="_blank"
					   href="mailto:<?php echo antispambot( is_email( get_theme_mod( $key ) ) ); ?>">
						<i class="fa fa-envelope" title="<?php esc_attr_e( 'email', 'ct_theme_name' ); ?>"></i>
					</a>
				<?php } elseif ( $active_site == 'skype' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ), array( 'http', 'https', 'skype' ) ); ?>">
						<i class="fa fa-<?php echo esc_attr( $active_site ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
					</a>
				<?php } elseif ( $active_site == 'email-form' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ) ); ?>">
						<i class="fa fa-envelope-o"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
					</a>
				<?php } else { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ) ); ?>">
						<i class="fa fa-<?php echo esc_attr( $active_site ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
					</a>
					<?php
				}
				echo '</li>';
			}
			echo "</ul>";
		}
	}
}

/*
 * WP will apply the ".menu-primary-items" class & id to the containing <div> instead of <ul>
 * making styling difficult and confusing. Using this wrapper to add a unique class to make styling easier.
 */
function ct_ct_theme_name_wp_page_menu() {
	wp_page_menu( array(
			"menu_class" => "menu-unset",
			"depth"      => - 1
		)
	);
}

if ( ! function_exists( '_wp_render_title_tag' ) ) :
	function ct_ct_theme_name_add_title_tag() {
		?>
		<title><?php wp_title( ' | ' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'ct_ct_theme_name_add_title_tag' );
endif;

function ct_ct_theme_name_nav_dropdown_buttons( $item_output, $item, $depth, $args ) {

	if ( $args->theme_location == 'primary' ) {

		if ( in_array( 'menu-item-has-children', $item->classes ) || in_array( 'page_item_has_children', $item->classes ) ) {
			$item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a><button class="toggle-dropdown" aria-expanded="false" name="toggle-dropdown"><span class="screen-reader-text">' . __( "open menu", "ct_theme_name" ) . '</span></button>', $item_output );
		}
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'ct_ct_theme_name_nav_dropdown_buttons', 10, 4 );

function ct_ct_theme_name_sticky_post_marker() {

	if ( is_sticky() && ! is_archive() ) {
		echo '<div class="sticky-status"><span>' . __( "Featured", "ct_theme_name" ) . '</span></div>';
	}
}
add_action( 'sticky_post_status', 'ct_ct_theme_name_sticky_post_marker' );

function ct_ct_theme_name_reset_customizer_options() {

	if ( empty( $_POST['ct_theme_name_reset_customizer'] ) || 'ct_theme_name_reset_customizer_settings' !== $_POST['ct_theme_name_reset_customizer'] ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['ct_theme_name_reset_customizer_nonce'], 'ct_theme_name_reset_customizer_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$mods_array = array(
		'logo_upload',
		'search_bar',
		'full_post',
		'excerpt_length',
		'read_more_text',
		'full_width_post',
		'author_byline',
		'custom_css'
	);

	$social_sites = ct_ct_theme_name_social_array();

	// add social site settings to mods array
	foreach ( $social_sites as $social_site => $value ) {
		$mods_array[] = $social_site;
	}

	$mods_array = apply_filters( 'ct_ct_theme_name_mods_to_remove', $mods_array );

	foreach ( $mods_array as $theme_mod ) {
		remove_theme_mod( $theme_mod );
	}

	$redirect = admin_url( 'themes.php?page=ct_theme_name-options' );
	$redirect = add_query_arg( 'ct_theme_name_status', 'deleted', $redirect );

	// safely redirect
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_init', 'ct_ct_theme_name_reset_customizer_options' );

function ct_ct_theme_name_delete_settings_notice() {

	if ( isset( $_GET['ct_theme_name_status'] ) ) {
		?>
		<div class="updated">
			<p><?php _e( 'Customizer settings deleted', 'ct_theme_name' ); ?>.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'ct_ct_theme_name_delete_settings_notice' );

function ct_ct_theme_name_body_class( $classes ) {

	global $post;
	$full_post       = get_theme_mod( 'full_post' );

	if ( $full_post == 'yes' ) {
		$classes[] = 'full-post';
	}

	return $classes;
}
add_filter( 'body_class', 'ct_ct_theme_name_body_class' );

function ct_ct_theme_name_post_class( $classes ) {
	$classes[] = 'entry';
	return $classes;
}
add_filter( 'post_class', 'ct_ct_theme_name_post_class' );

function ct_ct_theme_name_custom_css_output() {

	$custom_css = get_theme_mod( 'custom_css' );

	if ( $custom_css ) {
		$custom_css = ct_ct_theme_name_sanitize_css( $custom_css );

		wp_add_inline_style( 'ct-ct_theme_name-style', $custom_css );
		wp_add_inline_style( 'ct-ct_theme_name-style-rtl', $custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_ct_theme_name_custom_css_output', 20 );

function ct_ct_theme_name_svg_output( $type ) {

	$svg = '';

	if ( $type == 'toggle-navigation' ) {

		$svg = '<svg width="24px" height="18px" viewBox="0 0 24 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
				        <g transform="translate(-148.000000, -36.000000)" fill="#6B6B6B">
				            <g transform="translate(123.000000, 25.000000)">
				                <g transform="translate(25.000000, 11.000000)">
				                    <rect x="0" y="16" width="24" height="2"></rect>
				                    <rect x="0" y="8" width="24" height="2"></rect>
				                    <rect x="0" y="0" width="24" height="2"></rect>
				                </g>
				            </g>
				        </g>
				    </g>
				</svg>';
	}

	return $svg;
}

function ct_ct_theme_name_add_meta_elements() {

	$meta_elements = '';

	$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", get_bloginfo( 'charset' ) );
	$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

	$theme    = wp_get_theme( get_template() );
	$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
	$meta_elements .= $template;

	echo $meta_elements;
}
add_action( 'wp_head', 'ct_ct_theme_name_add_meta_elements', 1 );

// Move the WordPress generator to a better priority.
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'wp_generator', 1 );

function ct_ct_theme_name_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'content', 'archive' );
	}
}

if ( ! function_exists( 'ct_ct_theme_name_get_content_template' ) ) {
	function ct_ct_theme_name_get_content_template() {

		/* Blog */
		if ( is_home() ) {
			get_template_part( 'content', 'archive' );
		} /* Post */
		elseif ( is_singular( 'post' ) ) {
			get_template_part( 'content' );
		} /* Page */
		elseif ( is_page() ) {
			get_template_part( 'content', 'page' );
		} /* Attachment */
		elseif ( is_attachment() ) {
			get_template_part( 'content', 'attachment' );
		} /* Archive */
		elseif ( is_archive() ) {
			get_template_part( 'content', 'archive' );
		} /* Custom Post Type */
		else {
			get_template_part( 'content' );
		}
	}
}

// allow skype URIs to be used
function ct_ct_theme_name_allow_skype_protocol( $protocols ){
	$protocols[] = 'skype';
	return $protocols;
}
add_filter( 'kses_allowed_protocols' , 'ct_ct_theme_name_allow_skype_protocol' );
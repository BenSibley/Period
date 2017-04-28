<?php

require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
foreach ( glob( trailingslashit( get_template_directory() ) . 'inc/*' ) as $filename ) {
	include $filename;
}

if ( ! function_exists( ( 'ct_period_set_content_width' ) ) ) {
	function ct_period_set_content_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 891;
		}
	}
}
add_action( 'after_setup_theme', 'ct_period_set_content_width', 0 );

if ( ! function_exists( ( 'ct_period_theme_setup' ) ) ) {
	function ct_period_theme_setup() {

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
			'render'    => 'ct_period_infinite_scroll_render'
		) );

		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'period' )
		) );

		load_theme_textdomain( 'period', get_template_directory() . '/languages' );
	}
}
add_action( 'after_setup_theme', 'ct_period_theme_setup', 10 );

if ( ! function_exists( ( 'ct_period_register_widget_areas' ) ) ) {
	function ct_period_register_widget_areas() {

		register_sidebar( array(
			'name'          => esc_html__( 'Primary Sidebar', 'period' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'Widgets in this area will be shown in the sidebar next to the main post content', 'period' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
	}
}
add_action( 'widgets_init', 'ct_period_register_widget_areas' );

if ( ! function_exists( ( 'ct_period_customize_comments' ) ) ) {
	function ct_period_customize_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php
				echo get_avatar( get_comment_author_email(), 48, '', get_comment_author() );
				?>
				<div class="comment-meta">
					<span class="author-name"><?php comment_author_link(); ?></span>
					<span class="comment-date"><?php comment_date(); ?></span>
				</div>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'period' ) ?></em>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-footer">
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => _x( 'Reply', 'verb: reply to this comment', 'period' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'before'     => '<i class="fa fa-reply"></i>'
				) ) ); ?>
				<?php edit_comment_link( _x( 'Edit', 'verb: edit this comment', 'period' ), '<i class="fa fa-pencil"></i>' ); ?>
			</div>
		</article>
		<?php
	}
}

if ( ! function_exists( 'ct_period_update_fields' ) ) {
	function ct_period_update_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$label     = $req ? '*' : ' ' . __( '(optional)', 'period' );
		$aria_req  = $req ? "aria-required='true'" : '';

		$fields['author'] =
			'<p class="comment-form-author">
	            <label for="author">' . esc_html_x( "Name", "noun", "period" ) . $label . '</label>
	            <input id="author" name="author" type="text" placeholder="' . esc_attr__( "Jane Doe", "period" ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['email'] =
			'<p class="comment-form-email">
	            <label for="email">' . esc_html_x( "Email", "noun", "period" ) . $label . '</label>
	            <input id="email" name="email" type="email" placeholder="' . esc_attr__( "name@email.com", "period" ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['url'] =
			'<p class="comment-form-url">
	            <label for="url">' . esc_html__( "Website", "period" ) . '</label>
	            <input id="url" name="url" type="url" placeholder="http://google.com" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" />
	            </p>';

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'ct_period_update_fields' );

if ( ! function_exists( 'ct_period_update_comment_field' ) ) {
	function ct_period_update_comment_field( $comment_field ) {

		$comment_field =
			'<p class="comment-form-comment">
	            <label for="comment">' . esc_html_x( "Comment", "noun", "period" ) . '</label>
	            <textarea required id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	        </p>';

		return $comment_field;
	}
}
add_filter( 'comment_form_field_comment', 'ct_period_update_comment_field' );

if ( ! function_exists( 'ct_period_remove_comments_notes_after' ) ) {
	function ct_period_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'ct_period_remove_comments_notes_after' );

if ( ! function_exists( 'ct_period_filter_read_more_link' ) ) {
	function ct_period_filter_read_more_link( $custom = false ) {
		global $post;
		$ismore             = strpos( $post->post_content, '<!--more-->' );
		$read_more_text     = get_theme_mod( 'read_more_text' );
		$new_excerpt_length = get_theme_mod( 'excerpt_length' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';
		$output = '';

		// add ellipsis for automatic excerpts
		if ( empty( $ismore ) && $custom !== true ) {
			$output .= $excerpt_more;
		}
		// Because i18n text cannot be stored in a variable
		if ( empty( $read_more_text ) ) {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . __( 'Continue reading', 'period' ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		} else {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html( $read_more_text ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		}
		return $output;
	}
}
add_filter( 'the_content_more_link', 'ct_period_filter_read_more_link' ); // more tags
add_filter( 'excerpt_more', 'ct_period_filter_read_more_link', 10 ); // automatic excerpts

// handle manual excerpts
if ( ! function_exists( 'ct_period_filter_manual_excerpts' ) ) {
	function ct_period_filter_manual_excerpts( $excerpt ) {
		$excerpt_more = '';
		if ( has_excerpt() ) {
			$excerpt_more = ct_period_filter_read_more_link( true );
		}
		return $excerpt . $excerpt_more;
	}
}
add_filter( 'get_the_excerpt', 'ct_period_filter_manual_excerpts' );

if ( ! function_exists( 'ct_period_excerpt' ) ) {
	function ct_period_excerpt() {
		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( $show_full_post === 'yes' || $ismore ) {
			the_content();
		} else {
			the_excerpt();
		}
	}
}

if ( ! function_exists( 'ct_period_custom_excerpt_length' ) ) {
	function ct_period_custom_excerpt_length( $length ) {

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
add_filter( 'excerpt_length', 'ct_period_custom_excerpt_length', 99 );

if ( ! function_exists( 'ct_period_remove_more_link_scroll' ) ) {
	function ct_period_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_period_remove_more_link_scroll' );

if ( ! function_exists( 'ct_period_featured_image' ) ) {
	function ct_period_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
		}

		$featured_image = apply_filters( 'ct_period_featured_image', $featured_image );

		if ( $featured_image ) {
			echo $featured_image;
		}
	}
}

if ( ! function_exists( 'ct_period_social_array' ) ) {
	function ct_period_social_array() {

		$social_sites = array(
			'twitter'       => 'period_twitter_profile',
			'facebook'      => 'period_facebook_profile',
			'google-plus'   => 'period_googleplus_profile',
			'pinterest'     => 'period_pinterest_profile',
			'linkedin'      => 'period_linkedin_profile',
			'youtube'       => 'period_youtube_profile',
			'vimeo'         => 'period_vimeo_profile',
			'tumblr'        => 'period_tumblr_profile',
			'instagram'     => 'period_instagram_profile',
			'flickr'        => 'period_flickr_profile',
			'dribbble'      => 'period_dribbble_profile',
			'rss'           => 'period_rss_profile',
			'reddit'        => 'period_reddit_profile',
			'soundcloud'    => 'period_soundcloud_profile',
			'spotify'       => 'period_spotify_profile',
			'vine'          => 'period_vine_profile',
			'yahoo'         => 'period_yahoo_profile',
			'behance'       => 'period_behance_profile',
			'codepen'       => 'period_codepen_profile',
			'delicious'     => 'period_delicious_profile',
			'stumbleupon'   => 'period_stumbleupon_profile',
			'deviantart'    => 'period_deviantart_profile',
			'digg'          => 'period_digg_profile',
			'github'        => 'period_github_profile',
			'hacker-news'   => 'period_hacker-news_profile',
			'yelp'          => 'period_yelp_profile',
			'amazon'        => 'period_amazon_profile',
			'google-wallet' => 'period_google-wallet_profile',
			'twitch'        => 'period_twitch_profile',
			'snapchat'      => 'period_snapchat_profile',
			'bandcamp'      => 'period_bandcamp_profile',
			'etsy'          => 'period_etsy_profile',
			'quora'         => 'period_quora_profile',
			'ravelry'       => 'period_ravelry_profile',
			'meetup'        => 'period_meetup_profile',
			'telegram'      => 'period_telegram_profile',
			'podcast'       => 'period_podcast_profile',
			'steam'         => 'period_steam_profile',
			'vk'            => 'period_vk_profile',
			'weibo'         => 'period_weibo_profile',
			'tencent-weibo' => 'period_tencent_weibo_profile',
			'500px'         => 'period_500px_profile',
			'foursquare'    => 'period_foursquare_profile',
			'slack'         => 'period_slack_profile',
			'slideshare'    => 'period_slideshare_profile',
			'qq'            => 'period_qq_profile',
			'whatsapp'      => 'period_whatsapp_profile',
			'skype'         => 'period_skype_profile',
			'wechat'        => 'period_wechat_profile',
			'xing'          => 'period_xing_profile',
			'paypal'        => 'period_paypal_profile',
			'email'         => 'period_email_profile',
			'email-form'    => 'period_email_form_profile'
		);

		return apply_filters( 'ct_period_social_array_filter', $social_sites );
	}
}

if ( ! function_exists( 'ct_period_social_icons_output' ) ) {
	function ct_period_social_icons_output() {

		$social_sites = ct_period_social_array();

		foreach ( $social_sites as $social_site => $profile ) {

			if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
				$active_sites[ $social_site ] = $social_site;
			}
		}

		if ( ! empty( $active_sites ) ) {

			echo "<ul class='social-media-icons'>";

			foreach ( $active_sites as $key => $active_site ) {

				if ( $active_site == 'email-form' ) {
					$class = 'fa fa-envelope-o';
				} else {
					$class = 'fa fa-' . $active_site;
				}

				echo '<li>';
				if ( $active_site == 'email' ) { ?>
					<a class="email" target="_blank"
					   href="mailto:<?php echo antispambot( is_email( get_theme_mod( $key ) ) ); ?>">
						<i class="fa fa-envelope" title="<?php echo esc_attr_x( 'email', 'noun', 'period' ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html_x('email', 'noun', 'period'); ?></span>
					</a>
				<?php } elseif ( $active_site == 'skype' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ), array( 'http', 'https', 'skype' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
					</a>
				<?php } else { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
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
if ( ! function_exists( ( 'ct_period_wp_page_menu' ) ) ) {
	function ct_period_wp_page_menu() {
		wp_page_menu( array(
				"menu_class" => "menu-unset",
				"depth"      => - 1
			)
		);
	}
}

if ( ! function_exists( ( 'ct_period_nav_dropdown_buttons' ) ) ) {
	function ct_period_nav_dropdown_buttons( $item_output, $item, $depth, $args ) {

		if ( $args->theme_location == 'primary' ) {

			if ( in_array( 'menu-item-has-children', $item->classes ) || in_array( 'page_item_has_children', $item->classes ) ) {
				$item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a><button class="toggle-dropdown" aria-expanded="false" name="toggle-dropdown"><span class="screen-reader-text">' . _x( "open dropdown menu", "verb: open the dropdown menu", "period" ) . '</span><span class="arrow"></span></button>', $item_output );
			}
		}

		return $item_output;
	}
}
add_filter( 'walker_nav_menu_start_el', 'ct_period_nav_dropdown_buttons', 10, 4 );

if ( ! function_exists( ( 'ct_period_sticky_post_marker' ) ) ) {
	function ct_period_sticky_post_marker() {

		if ( is_sticky() && ! is_archive() ) {
			echo '<div class="sticky-status"><span>' . __( "Featured", "period" ) . '</span></div>';
		}
	}
}
add_action( 'sticky_post_status', 'ct_period_sticky_post_marker' );

if ( ! function_exists( ( 'ct_period_reset_customizer_options' ) ) ) {
	function ct_period_reset_customizer_options() {

		if ( empty( $_POST['period_reset_customizer'] ) || 'period_reset_customizer_settings' !== $_POST['period_reset_customizer'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['period_reset_customizer_nonce'], 'period_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'logo_upload',
			'search_bar',
			'layout',
			'full_post',
			'excerpt_length',
			'read_more_text',
			'display_post_author',
			'display_post_date',
			'custom_css'
		);

		$social_sites = ct_period_social_array();

		// add social site settings to mods array
		foreach ( $social_sites as $social_site => $value ) {
			$mods_array[] = $social_site;
		}

		$mods_array = apply_filters( 'ct_period_mods_to_remove', $mods_array );

		foreach ( $mods_array as $theme_mod ) {
			remove_theme_mod( $theme_mod );
		}

		$redirect = admin_url( 'themes.php?page=period-options' );
		$redirect = add_query_arg( 'period_status', 'deleted', $redirect );

		// safely redirect
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'ct_period_reset_customizer_options' );

if ( ! function_exists( ( 'ct_period_delete_settings_notice' ) ) ) {
	function ct_period_delete_settings_notice() {

		if ( isset( $_GET['period_status'] ) ) {
			
			if ( $_GET['period_status'] == 'deleted' ) {
				?>
				<div class="updated">
					<p><?php _e( 'Customizer settings deleted.', 'period' ); ?></p>
				</div>
				<?php
			} else if ( $_GET['period_status'] == 'activated' ) {
				?>
				<div class="updated">
					<p><?php printf( __( '%s successfully activated!', 'period' ), wp_get_theme( get_template() ) ); ?></p>
				</div>
				<?php
			}
		}
	}
}
add_action( 'admin_notices', 'ct_period_delete_settings_notice' );

if ( ! function_exists( ( 'ct_period_body_class' ) ) ) {
	function ct_period_body_class( $classes ) {

		global $post;
		$full_post = get_theme_mod( 'full_post' );
		$layout    = get_theme_mod( 'layout' );

		if ( $full_post == 'yes' ) {
			$classes[] = 'full-post';
		}
		if ( $layout == 'left' ) {
			$classes[] = 'left-sidebar';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ct_period_body_class' );

if ( ! function_exists( ( 'ct_period_post_class' ) ) ) {
	function ct_period_post_class( $classes ) {
		$classes[] = 'entry';

		return $classes;
	}
}
add_filter( 'post_class', 'ct_period_post_class' );

if ( ! function_exists( ( 'ct_period_custom_css_output' ) ) ) {
	function ct_period_custom_css_output() {

		if ( function_exists( 'wp_get_custom_css' ) ) {
			$custom_css = wp_get_custom_css();
		} else {
			$custom_css = get_theme_mod( 'custom_css' );
		}
		$logo_size  = get_theme_mod( 'logo_size' );

		if ( $logo_size != 168 && ! empty( $logo_size ) ) {
			$logo_size_css = '.logo {
							width: ' . $logo_size . 'px;
						  }';
			$custom_css .= $logo_size_css;
		}
		if ( get_theme_mod( 'display_post_author' ) == 'hide' ) {
			$custom_css .= '';
		}
		if ( get_theme_mod( 'display_post_date' ) == 'hide' ) {
			$custom_css .= '';
		}

		if ( ! empty( $custom_css ) ) {
			$custom_css = ct_period_sanitize_css( $custom_css );

			wp_add_inline_style( 'ct-period-style', $custom_css );
			wp_add_inline_style( 'ct-period-style-rtl', $custom_css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_period_custom_css_output', 20 );

if ( ! function_exists( ( 'ct_period_svg_output' ) ) ) {
	function ct_period_svg_output( $type ) {

		$svg = '';

		if ( $type == 'toggle-navigation' ) {

			$svg = '<svg width="36px" height="23px" viewBox="0 0 36 23" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				    <desc>mobile menu toggle button</desc>
				    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
				        <g transform="translate(-142.000000, -104.000000)" fill="#FFFFFF">
				            <g transform="translate(142.000000, 104.000000)">
				                <rect x="0" y="20" width="36" height="3"></rect>
				                <rect x="0" y="10" width="36" height="3"></rect>
				                <rect x="0" y="0" width="36" height="3"></rect>
				            </g>
				        </g>
				    </g>
				</svg>';
		}

		return $svg;
	}
}

if ( ! function_exists( ( 'ct_period_add_meta_elements' ) ) ) {
	function ct_period_add_meta_elements() {

		$meta_elements = '';

		$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", esc_attr( get_bloginfo( 'charset' ) ) );
		$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

		$theme    = wp_get_theme( get_template() );
		$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
		$meta_elements .= $template;

		echo $meta_elements;
	}
}
add_action( 'wp_head', 'ct_period_add_meta_elements', 1 );

// Move the WordPress generator to a better priority.
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'wp_generator', 1 );

if ( ! function_exists( ( 'ct_period_infinite_scroll_render' ) ) ) {
	function ct_period_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content', 'archive' );
		}
	}
}

if ( ! function_exists( 'ct_period_get_content_template' ) ) {
	function ct_period_get_content_template() {

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
if ( ! function_exists( ( 'ct_period_allow_skype_protocol' ) ) ) {
	function ct_period_allow_skype_protocol( $protocols ) {
		$protocols[] = 'skype';

		return $protocols;
	}
}
add_filter( 'kses_allowed_protocols' , 'ct_period_allow_skype_protocol' );

// trigger theme switch on link click and send to Appearance menu
function ct_period_welcome_redirect() {

	$welcome_url = add_query_arg(
		array(
			'page'          => 'period-options',
			'period_status' => 'activated'
		),
		admin_url( 'themes.php' )
	);
	wp_safe_redirect( esc_url_raw( $welcome_url ) );
}
add_action( 'after_switch_theme', 'ct_period_welcome_redirect' );
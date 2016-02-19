<span class="comments-link">
	<i class="fa fa-comment" title="<?php _e( 'comment icon', 'ct_theme_name' ); ?>"></i>
	<?php
	if ( ! comments_open() && get_comments_number() < 1 ) :
		comments_number( __( 'Comments closed', 'ct_theme_name' ), __( '1 Comment', 'ct_theme_name' ), __( '% Comments', 'ct_theme_name' ) );
	else :
		echo '<a href="' . esc_url( get_comments_link() ) . '">';
		comments_number( __( 'Leave a Comment', 'ct_theme_name' ), __( '1 Comment', 'ct_theme_name' ), __( '% Comments', 'ct_theme_name' ) );
		echo '</a>';
	endif;
	?>
</span>
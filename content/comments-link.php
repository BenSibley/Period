<span class="comments-link">
	<i class="fa fa-comment" aria-hidden="true" title="<?php esc_attr_e( 'comment icon', 'period' ); ?>"></i>
	<?php
	if ( ! comments_open() && get_comments_number() < 1 ) :
		comments_number( __( 'Comments closed', 'period' ), __( '1 Comment', 'period' ), __( '% Comments', 'period' ) );
	else :
		echo '<a href="' . esc_url( get_comments_link() ) . '">';
		comments_number( __( 'Leave a Comment', 'period' ), __( '1 Comment', 'period' ), __( '% Comments', 'period' ) );
		echo '</a>';
	endif;
	?>
</span>
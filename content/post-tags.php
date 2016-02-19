<?php
$tags   = get_the_tags( $post->ID );
$output = '';
if ( $tags ) {
	echo '<div class="post-tags">';
		echo '<span>' . __( "Tags:", "ct_theme_name" ) . '</span>';
		echo '<ul>';
			foreach ( $tags as $tag ) {
				echo '<li><a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts tagged %s", 'ct_theme_name' ), $tag->name ) ) . '">' . $tag->name . '</a></li>';
			}
		echo '</ul>';
	echo '</div>';
}
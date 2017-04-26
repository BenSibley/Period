<?php

$categories = get_the_category( $post->ID );
$separator  = ', ';
$output     = '';

if ( $categories ) {

	echo '<p class="post-categories">';
		echo '<span>' . __( "Published in", "period" ) . ' </span>';
		foreach ( $categories as $category ) {
			// if it's the last and not the first (only) category, pre-prend with "and"
			if ( $category === end( $categories ) && $category !== reset( $categories ) ) {
				$output = rtrim( $output, ", " ); // remove trailing comma
				$output .= ' ' . _x( 'and', 'category, category, AND category', 'period' ) . ' ';
			}
			$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'period' ), $category->name ) ) . '">' . esc_html( $category->cat_name ) . '</a>' . $separator;
		}
		echo trim( $output, $separator );
	echo "</p>";
}
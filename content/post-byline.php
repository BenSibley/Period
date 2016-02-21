<div class="post-byline">
	<?php
	$author = "<a class='author' href='" . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . "'>" . get_the_author() . "</a>";
	$date   = "<a class='date' href='" . esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'n' ) ) ) . "'>" . date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'r' ) ) ) . "</a>";
	printf( _x( 'Published %1$s by %2$s', 'This blog post was published on some date by some author', 'period' ), $date, $author );
	?>
</div>
<?php

global $post;

// gets the previous post if it exists
$previous_post = get_adjacent_post(false,'',true);
$previous_text = __('Previous Post', 'period');

if ( $previous_post == '' ) {
	$previous_text = __('No Older Posts', 'period');
	$previous_title = __('Return to Blog', 'period');
	$previous_url = home_url();
	$previous_link = '<a href="' . esc_url( $previous_url ) . '">' . esc_html( $previous_title ) . '</a>';
}

// gets the next post if it exists
$next_post = get_adjacent_post(false,'',false);
$next_text = __('Next Post', 'period');

if( $next_post == '' ) {
	$next_text = __('No Newer Posts', 'period');
	$next_title = __('Return to Blog', 'period');
	$next_url = home_url();
	$next_link = '<a href="' . esc_url( $next_url ) . '">' . esc_html( $next_title ) . '</a>';
}

?>
<nav class="further-reading">
	<div class="previous">
		<span><?php echo esc_html( $previous_text ); ?></span>
		<?php
		if ( $previous_post == '' ) {
			echo $previous_link;
		} else {
			previous_post_link( '%link' );
		}
		?>
	</div>
	<div class="next">
		<span><?php echo esc_html( $next_text ); ?></span>
		<?php
		if ( $next_post == '' ) {
			echo $next_link;
		} else {
			next_post_link( '%link' );
		}
		?>
	</div>
</nav>
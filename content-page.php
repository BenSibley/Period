<div <?php post_class(); ?>>
	<?php do_action( 'page_before' ); ?>
	<article>
		<?php ct_period_featured_image(); ?>
		<div class="post-container">
			<div class='post-header'>
				<h1 class='post-title'><?php the_title(); ?></h1>
			</div>
			<div class="post-content">
				<?php the_content(); ?>
				<?php wp_link_pages( array(
					'before' => '<p class="singular-pagination">' . __( 'Pages:', 'period' ),
					'after'  => '</p>',
				) ); ?>
				<?php do_action( 'page_after' ); ?>
			</div>
		</div>
	</article>
	<?php comments_template(); ?>
</div>
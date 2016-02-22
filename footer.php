<?php do_action( 'main_bottom' ); ?>
</section> <!-- .main -->
<?php get_sidebar( 'primary' ); ?>
<?php do_action( 'after_main' ); ?>
</div><!-- .primary-container -->

<footer id="site-footer" class="site-footer" role="contentinfo">
    <div id="footer-title-container" class="footer-title-container">
        <h2 id='site-title' class='site-title'>
            <a href='<?php echo esc_url( home_url() ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>'>
                <?php bloginfo( 'name' ); ?>
            </a>
        </h2>
        <?php if ( get_bloginfo( 'description' ) ) {
            echo '<p class="tagline">' . get_bloginfo( 'description' ) . '</p>';
        } ?>
    </div>
    <?php do_action( 'footer_top' ); ?>
    <div class="design-credit">
        <span>
            <?php
            $footer_text = sprintf( __( '<a href="%s">Period WordPress Theme</a> by Compete Themes.', 'period' ), 'https://www.competethemes.com/period/' );
            $footer_text = apply_filters( 'ct_period_footer_text', $footer_text );
            echo wp_kses_post( $footer_text );
            ?>
        </span>
    </div>
</footer>
</div><!-- .max-width -->
</div><!-- .overflow-container -->

<?php do_action( 'body_bottom' ); ?>

<?php wp_footer(); ?>

</body>
</html>
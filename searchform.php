<div class='search-form-container'>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label class="screen-reader-text" for="search-field"><?php _e( 'Search', 'period' ); ?></label>
        <input id="search-field" type="search" class="search-field" value="" name="s"
               title="<?php _e( 'Search for:', 'period' ); ?>" placeholder="<?php _e( 'Search for...', 'period' ); ?>"/>
        <input type="submit" class="search-submit" value='<?php _e( 'Search', 'period' ); ?>'/>
    </form>
</div>
<div class='search-form-container'>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label class="screen-reader-text" for="search-field"><?php _e( 'Search', 'ct_theme_name' ); ?></label>
        <input id="search-field" type="search" class="search-field" value="" name="s"
               title="<?php _e( 'Search for:', 'ct_theme_name' ); ?>"/>
        <input type="submit" class="search-submit" value='<?php _e( 'Go', 'ct_theme_name' ); ?>'/>
    </form>
</div>
( function( $ ) {

    wp.customize( 'logo_size', function( value ) {
        value.bind( function( to ) {
            $('.logo').css('width', to);
        } );
    } );

} )( jQuery );
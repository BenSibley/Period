( function( $ ) {

    var body = $('body');

    wp.customize( 'logo_size', function( value ) {
        value.bind( function( to ) {
            $('.logo').css('width', to);
        } );
    } );

    wp.customize( 'layout', function( value ) {
        value.bind( function( to ) {
            if ( to == 'left' ) {
                body.addClass('left-sidebar');
            } else {
                body.removeClass('left-sidebar');
            }
        } );
    } );

} )( jQuery );
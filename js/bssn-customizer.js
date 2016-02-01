/**
 * Better Simple Section Nav Customizer JS.
 */

( function( $ ) {

	var $body = $( 'body' );

	// Handle live previewing for the mobile nav sub menu toggle style.
	wp.customize( 'toggle_icon', function( value ) {
		value.bind( function( to ) {
			$body.removeClass( 'sub-menu-toggle-style-chevron sub-menu-toggle-style-plus-minus' );
			$body.addClass( 'sub-menu-toggle-style-' + to );
		});
	});

})( jQuery );
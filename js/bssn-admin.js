/**
 * Better Simple Section Nav Admin JS
 *
 * @since  1.0.0
 */

( function( $ ) {

	$( document ).ready( function() {

		// Set up field dependencies for Better Simple Section Nav Content.
		$( '.widget[id*="better_simple_section_nav"]' ).bssnWidgetFields();
	});

	// Reset or initialize certain fields when widgets are added or updated.
	$( document ).on( 'widget-added widget-updated', function( e, data ) {

		if ( $( data[0] ).is( '.widget[id*="better_simple_section_nav"]' ) ) {
			$( data[0] ).bssnWidgetFields();
		}
	});

	/**
	 * Dependency for dropdown widget fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.bssnWidgetFields = function() {

		return this.each( function() {

			var $widget           = $( this );
			var $toggleIconWrap   = $widget.find( '.bssn-select-field-wrap' ).has( '.bssn-toggle-icon' );
			var $hideToggle       = $widget.find( '.bssn-hide-toggle' );
			var $showSubPageWrap  = $widget.find( '.bssn-checkbox-field-wrap' ).has( '.bssn-show-subpages' );

			$showSubPageWrap.addClass( 'bssn-hidden' );
			$toggleIconWrap.removeClass( 'bssn-hidden' );

			$hideToggle.on( 'click', function() {
				$toggleIconWrap.toggleClass( 'bssn-hidden' );
				$showSubPageWrap.toggleClass( 'bssn-hidden' );
			});

			if ( $hideToggle.is( ':checked' ) ) {
				$toggleIconWrap.addClass( 'bssn-hidden' );
				$showSubPageWrap.removeClass( 'bssn-hidden' );
			}
		});
	}

}( jQuery ));
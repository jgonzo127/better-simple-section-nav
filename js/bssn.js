( function( $ ) {

	$( document ).ready( function() {

		var $this        = $( this);
		var $list        = $( "ul.bssn-top-level" );
		var $items       = $list.find( 'li' );
		var $item        = $list
		var $subList     = $items.has( 'ul' );

		$list.addClass( 'top-level' );

		if ( $list.hasClass( 'plus-minus' ) || $list.hasClass( 'chevron' ) ) {
			var toggle = '<a class="bssn-sub-level-toggle">' +
				     	 '<span class="bssn-sub-level-toggle-span span-1">Toggle</span>' +
				 	 	 '<span class="bssn-sub-level-toggle-span span-2">Toggle</span>' +
				 	 	 '<span class="bssn-sub-level-toggle-span span-3">Toggle</span>' +
				 	 	 '</a>';
		} else {
			toggle = '';
		}

		if( $list.hasClass( 'no-toggle' ) ) {
			$subLevel.toggleClass( 'bssn-toggled' );
		}

		$list.children().find( 'ul' ).addClass( 'bssn-sub-level' );
		$( '.bssn-sub-level .current_page_item' ).parents().show();

		$subList.each( function() {
		  $( this ).find( 'a' ).first().after( toggle );
		});

		var $subLevel = $( '.bssn-sub-level-toggle' );

		$subLevel.on( 'click', function() {
		  $( this ).toggleClass( 'bssn-toggled' );
		  $( this ).siblings( 'ul.children' ).slideToggle();
		});

	});

})( jQuery );
( function( $ ) {

	$( document ).ready( function() {

		var $this        = $( this );
		var $list        = $( "ul.bssn-top-level" );
		var $items       = $list.find( 'li' );
		var $item        = $list
		var $subList     = $items.has( 'ul' );
		var toggle       = '<a class="bssn-sub-level-toggle">' +
					    	'<span class="bssn-sub-level-toggle-span span-1">Toggle</span>' +
					 		'<span class="bssn-sub-level-toggle-span span-2">Toggle</span>' +
					 		'<span class="bssn-sub-level-toggle-span span-3">Toggle</span>' +
					 		'</a>';

		$list.addClass( 'top-level' );
		$list.children().find( 'ul' ).addClass( 'bssn-sub-level' );
		$( '.current_page_item' ).parents().show();

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
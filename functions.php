<?php

/**
 * Build and return the Sub Page Navigation HTML.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args for wp_list_pages().
 *
 * @return  string        The sub page nav HTML.
 */
function bssn_output_the_sub_page_nav( $args = array() ) {

	// Set our defaults and use them as needed.
	$defaults = array(
		'title'       => '',
		'toggle_icon' => '',
		'link_title'  => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	$title       = $args['title'];
	$toggle_icon = $args['toggle_icon'];
	$link_title  = $args['link_title'];

	global $post;

	// Only proceed if we have a post object and we're displaying a page.
	if ( ! $post || ! is_page() ) {
		return false;
	}

	$output   = '';

	// Find the top level page id.
	if ( ! $post->post_parent ) {
		$top_page_id = $post->ID;
	} else {
		$ancestors   = get_post_ancestors( $post );
		$top_page_id = $ancestors ? end( $ancestors ) : $post->ID;
	}

	$default_args = array(
		'depth'       => 5,
		'echo'        => 0,
		'title_li'    => '',
	);
	$args = wp_parse_args( $args, $default_args );

	// Use the top level page id.
	$args['child_of'] = $top_page_id;

	// Generate the page list.
	$page_list = wp_list_pages( $args );

	if ( $page_list ) {

		// Get our top page title.
		if ( $link_title ) {

			$page_title = sprintf(
				'<h2 class="%s"><a href="%s">%s</a></h2>',
				'bssn-title',
				get_permalink( $top_page_id ),
				get_the_title( $top_page_id )
			);

		} else {

			$page_title = sprintf(
				'<h2 class="%s">%s</h2>',
				'bssn-title',
				get_the_title( $top_page_id )
			);
		}

		$output = sprintf( '<nav class="%s">%s<ul class="%s %s">%s</ul></nav>',
			'bssn-sub-page-nav',
			$page_title,
			'bssn-top-level',
			$toggle_icon,
			$page_list
		);
	}

	return apply_filters( 'better_sub_page_nav', $output, $args );
}

/**
 * Display the Sub Page Navigation output.
 *
 * @since  1.0.0
 *
 * @param  array  $args  The args for wp_list_pages().
 */
function bssn_the_sub_page_nav( $args = array() ) {

	return bssn_output_the_sub_page_nav( $args );
}

/**
 * Return true or false based on the passed in value.
 *
 * @since   1.0.0
 *
 * @param   mixed  $value  The value to be tested.
 * @return  bool
 */
function bssn_true_or_false( $value ) {

	if ( ! isset( $value ) ) {
		return false;
	}

	if ( true === $value || 'true' === $value || 1 === $value || '1' === $value || 'yes' === $value || 'on' === $value ) {
		return true;
	} else {
		return false;
	}
}



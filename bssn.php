<?php

/*
Plugin Name: Better Simple Section Navigation
Plugin URI:http://jgonzo127.com
Description: Simple section navigation with sub page dropdown functionality.
Version: 1.0.0
Author:  Jordan Gonzales
Author URI: http://jgonzo127.com
License:
*/

define( 'BSSN_VERSION', '1.0.0' );
define( 'BSSN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BSSN_URL', plugin_dir_url( __FILE__ ) );

// Include general functionality.
require_once BSSN_PATH . 'functions.php';

// Include widget base class.
require_once BSSN_PATH . 'classes/class-bssn-widget.php';

add_action( 'wp_enqueue_scripts', 'bssn_scripts_and_styles' );
/**
 * Enqueue front-end scripts and styles.
 *
 * @since  1.0.0
 */
function bssn_scripts_and_styles() {

	// General styles.
	wp_enqueue_style(
		'better-simple-section-nav',
		BSSN_URL . 'css/bssn-public.css',
		array(),
		BSSN_VERSION
	);

	// General scripts.
	wp_enqueue_script(
		'better-simple-section-nav',
		BSSN_URL . 'js/bssn.js',
		array( 'jquery' ),
		BSSN_URL,
		true
	);
}

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
		$ancestors    = get_post_ancestors( $post );
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
		$page_title = sprintf(
			'<h2 class="%s"><a href="%s">%s</a></h2>',
			'bssn-title',
			get_permalink( $top_page_id ),
			get_the_title( $top_page_id )
		);

		$output = sprintf( '<nav class="%s">%s<ul class="%s">%s</ul></nav>',
			'bssn-sub-page-nav',
			$page_title,
			'bssn-top-level',
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

add_action('widgets_init', 'bssn_register_widget' );
/**
 * Register the bssn widget.
 *
 * @since 1.0.0
 */
function bssn_register_widget() {

	register_widget( 'better_simple_section_nav' );
}

/**
 * Better Simple Section Nav widget.
 *
 * @since 1.0.0
 */
class Better_Simple_Section_Nav extends Better_Simple_Section_Nav_Widget {

	/**
	 * Global options for this widget.
	 *
	 * @since 1.0.0
	 */
	protected $options;

	/**
	 * Initalize an instance of the widget.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set up the options to pass to the WP_Widget constructor.
		$this->options = array(
			'classname'   => 'better-simple-section-nav',
			'description' => __( 'Better Simple Section Nav', 'better-simple-section-nav' ),
		);

		parent::__construct(
			'better_simple_section_nav',
			__( 'Better Simple Section Nav', 'better-simple-section-nav' ),
			$this->options
		);
	}

	/**
	 * Output the widget.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $args      The global options for the widget.
	 * @param  array  $instance  The options for the widget instance.
	 */
	public function widget( $args, $instance ) {

		// At this point, all instance options have been sanitized.
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		if( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo bssn_the_sub_page_nav( $instance );

		echo $args['after_widget'];
	}

	/**
	 * Output the Widgets settings form.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $instance  The options for the widget instance.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$defaults = array(
			'title'         => '',
			'toggle_icon'   => 'chevron',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title       = $instance['title'];
		$toggle_icon = $instance['toggle_icon'];

		// Title.
		$this->field_text(
			__( 'Title', 'better-simple-section-nav' ),
			'',
			'bssn-title widefat',
			'title',
			$title
		);

		// Toggle Icon.
		$this->field_select(
			__( 'Toggle Icon', 'better-simple-section-nav' ),
			'',
			'bssn-toggle-icon widefat',
			'toggle-icon',
			$toggle_icon,
			array(
				'chevron'    => __( 'Chevron', 'better-simple-section-nav' ),
				'plus-minus' => __( 'Plus/Minus', 'better-simple-section-nav' ),
			)
		);
	}

	/**
	 * Update the widget settings.
	 *
	 * @since 1.0.0
	 * @param  array  $new_instnace  The new settings for the widget instance.
	 * @param  array  $old_instance  The old settings for the widget instance.
	 *
	 * @return  array  The sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                  = $old_instance;
		$instance['title']         = wp_kses_post( $new_instance['title'] );
		$instance['toggle_icon']   = sanitize_text_field( $new_instance['toggle_icon'] );

		return $instance;
	}
}
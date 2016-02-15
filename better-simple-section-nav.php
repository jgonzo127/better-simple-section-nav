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

// Maybe include admin class.
if ( is_admin() ) {

	require_once BSSN_PATH . 'classes/class-bssn-admin.php';

	new Bssn_Admin();
}

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
		BSSN_VERSION,
		true
	);
}

add_shortcode( 'better_simple_section_nav', 'bssn_shortcode' );
/**
 * Better Simple Section Nav shortcode.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function bssn_shortcode( $atts = array(), $content = null ) {

	if ( $content ) {
		$atts['content'] = $content;
	}

	return bssn_shortcode( $atts );
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
class Better_Simple_Section_Nav extends WP_Widget {

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

		if ( ! empty( $title ) ) {
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
			'title'       	 => '',
			'toggle_icon' 	 => 'chevron',
			'exclude'     	 => '',
			'link_title'  	 => true,
			'hide_toggle' 	 => '',
			'show_sub_pages' => '',
 		);

		$title       	= $instance['title'];
		$toggle_icon 	= $instance['toggle_icon'];
		$exclude     	= $instance['exclude'];
		$link_title  	= $instance['link_title'];
		$hide_toggle 	= $instance['hide_toggle'];
		$show_sub_pages = $instance['show_sub_pages'];

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
			'toggle_icon',
			$toggle_icon,
			array(
				'chevron'    => __( 'Chevron', 'better-simple-section-nav' ),
				'plus-minus' => __( 'Plus/Minus', 'better-simple-section-nav' ),
			)
		);

		// Exclude List.
		$this->field_text(
			__( 'Exclude:', 'better-simple-section-nav' ),
			__( 'Comma separated page IDs', 'better-simple-section-nav' ),
			'bssn-exclude widefat',
			'exclude',
			$exclude
		);

		// Link Title.
		$this->field_checkbox(
			__( 'Link Top Level Page?', 'better-simple-section-nav' ),
			'',
			'bssn-link-title widefat',
			'link_title',
			$link_title
		);

		// Hide Toggle.
		$this->field_checkbox(
			__( 'Hide Toggle Icons?', 'better-simple-section-nav' ),
			'',
			'bssn-hide-toggle widefat',
			'hide_toggle',
			$hide_toggle
		);

		// Hide Toggle.
		$this->field_checkbox(
			__( 'Show Sub Pages?', 'better-simple-section-nav' ),
			'',
			'bssn-show-subpages widefat',
			'show_sub_pages',
			$show_sub_pages
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

		$instance                	= $old_instance;
		$instance['title']       	= wp_kses_post( $new_instance['title'] );
		$instance['toggle_icon'] 	= sanitize_text_field( $new_instance['toggle_icon'] );
		$instance['exclude']     	= sanitize_text_field( $new_instance['exclude'] );
		$instance['link_title']  	= sanitize_text_field( $new_instance['link_title'] );
		$instance['hide_toggle'] 	= sanitize_text_field( $new_instance['hide_toggle'] );
		$instance['show_sub_pages'] = sanitize_text_field( $new_instance['show_sub_pages'] );

		return $instance;
	}

	/**
	 * Output a text input.
	 *
	 * @since  1.0.0
	 */
	public function field_text( $label = '', $description = '', $classes = '', $key = '', $value = '' ) {

		echo '<p class="bssn-text-field-wrap">';

			echo '<label>' . esc_html( $label ) . '</label><br />';

			printf(
				'<input type="text" class="%s" name="%s" value="%s" />',
				esc_attr( $classes ),
				$this->get_field_name( $key ),
				esc_attr( $value )
			);

			if ( '' !== $description) {
				printf(
					'<small class="bssn-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}

	/**
	 * Output a textarea input.
	 *
	 * @since  1.0.0
	 */
	public function field_textarea( $label = '', $description = '', $classes = '', $key = '', $value = '', $rows = '4', $cols = '4' ) {

		echo '<p class="bssn-textarea-field-wrap">';

			echo '<label>' . esc_html( $label ) . '</label><br />';

			printf(
				'<textarea class="%s" name="%s" rows="%s" cols="%s">%s</textarea>',
				esc_attr( $classes ),
				$this->get_field_name( $key ),
				esc_attr( $rows ),
				esc_attr( $cols ),
				$value
			);

			if ( '' !== $description) {
				printf(
					'<small class="bssn-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}

	/**
	 * Output a select dropdown.
	 *
	 * @since  1.0.0
	 */
	public function field_select( $label = '', $description = '', $classes = '', $key = '', $value = '', $options = array() ) {

		echo '<p class="bssn-select-field-wrap">';

			echo '<label>' . esc_html( $label ) . '</label><br />';

			printf(
				'<select class="%s" name="%s">',
				esc_attr( $classes ),
				$this->get_field_name( $key )
			);

			// Test whether we have an associative or indexed array.
			if ( array_values( $options ) === $options ) {

				// We have an indexed array.
				foreach ( $options as $option ) {

					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $option ),
						selected( $value, $option, false ),
						esc_html( $option )
					);
				}

			} else {

				// We have an associative array.
				foreach ( $options as $option_value => $option_display_name ) {

					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $option_value ),
						selected( $value, $option_value, false ),
						esc_html( $option_display_name )
					);
				}
			}

			echo '</select>';

			if ( '' !== $description) {
				printf(
					'<small class="bssn-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}

	/**
	 * Output a checkbox.
	 *
	 * @since  1.0.0
	 */
	public function field_checkbox( $label = '', $description = '', $classes = '', $key = '', $value = '' ) {

		$val = (int)bssn_true_or_false( $value );

		echo '<p class="bssn-checkbox-field-wrap">';

			printf(
				'<input type="checkbox" class="%s" name="%s" value="1" %s /> <label class="%s">%s</label><br />',
				esc_attr( $classes ),
				$this->get_field_name( $key ),
				checked( $val, 1, false ),
				'radio-label',
				esc_html( $label )
			);

			if ( '' !== $description) {
				printf(
					'<small class="bssn-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}

}
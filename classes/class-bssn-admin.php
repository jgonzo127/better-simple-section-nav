<?php
/**
 * Better Simple Section Nav Admin Class
 *
 * @since  1.0.0
 */

class Bssn_Admin {

	/**
	 * The constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		// Set up our admin hooks.
		$this->initialize();
	}

	/**
	 * Set up our admin hooks.
	 *
	 * @since  1.0.0
	 */
	public function initialize() {

		// Include our JS and CSS.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since  1.0.0
	 */
	function admin_enqueue( $hook ) {

		// BSSN Admin JS.
		wp_register_script(
			'better-simple-section-nav-admin',
			BSSN_URL . 'js/bssn-admin.js',
			array(),
			BSSN_VERSION,
			true
		);

		// BSSN Admin JS.
		wp_register_style(
			'better-simple-section-nav-admin',
			BSSN_URL . 'css/bssn-admin.css',
			array(),
			BSSN_VERSION
		);

		// Only enqueue on specific admin pages.
		if ( 'widgets.php' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_script( 'better-simple-section-nav-admin' );
			wp_enqueue_style( 'better-simple-section-nav-admin' );
		}
	}
}
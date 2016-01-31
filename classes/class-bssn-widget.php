<?php
/**
 * Better Simple Section Nav Widget Class.
 *
 * This class is designed for sub-classing. It extends the main WP_Widget
 * class with functions to output various field types.
 *
 * @since  1.0.0
 */
class Better_Simple_Section_Nav_Widget extends WP_Widget {

	/**
	 * Initialize an instance of the parent class.
	 *
	 * @since  1.0.0
	 */
	public function __construct( $id_base, $name, $widget_options = array(), $control_options = array() ) {

		parent::__construct(
			$id_base,
			$name,
			$widget_options,
			$control_options
		);
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

	/**
	 * Output a group of checkboxes.
	 *
	 * @since  1.0.0
	 */
	public function field_multi_checkbox( $label = '', $description = '', $classes = '', $key = '', $value = '', $options = array() ) {

		if ( ! is_array( $value ) ) {
			$values = ( strpos( $value, ',' ) ) ? explode( ',', $value ) : (array)$value;
		} else {
			$values = $value;
		}

		echo '<p class="bssn-multi-checkbox-field-wrap">';

			echo '<label class="multi-checkbox-group-label">' . esc_html( $label ) . '</label><br />';

			echo '<span class="bssn-multi-checkbox-wrap">';

			// Test whether we have an associative or indexed array.
			if ( array_values( $options ) === $options ) {

				// We have an indexed array.
				foreach ( $options as $option_value ) {

					$val = ( in_array( $option_value, $values ) ) ? 1 : 0;
					$option_display_name = ucwords( str_replace( '_', ' ', $option_value ) );

					printf(
						'<input type="checkbox" class="%s" value="%s" %s /> <label class="%s">%s</label><br />',
						esc_attr( $classes ),
						esc_attr( $option_value ),
						checked( $val, 1, false ),
						'multi-checkbox-label',
						esc_html( $option_display_name )
					);
				}

			} else {

				// We have an associative array.
				foreach ( $options as $option_value => $option_display_name ) {

					$val = ( in_array( $option_value, $values ) ) ? 1 : 0;

					printf(
						'<input type="checkbox" class="%s" value="%s" %s /> <label class="%s">%s</label><br />',
						esc_attr( $classes ),
						esc_attr( $option_value ),
						checked( $val, 1, false ),
						'multi-checkbox-label',
						esc_html( $option_display_name )
					);
				}
			}

			printf(
				'<input type="hidden" class="%s" name="%s" value="%s" />',
				'multi-checkbox-hidden-input',
				$this->get_field_name( $key ),
				esc_attr( (string)$value )
			);

			echo '</span>';

			if ( '' !== $description) {
				printf(
					'<small class="bssn-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}

	/**
	 * Output a group of radio buttons.
	 *
	 * @since  1.0.0
	 */
	public function field_radio( $label = '', $description = '', $classes = '', $key = '', $value = '', $options = array() ) {

		echo '<p class="bssn-radio-field-wrap">';

			echo '<label class="radio-group-label">' . esc_html( $label ) . '</label><br />';

			// Test whether we have an associative or indexed array.
			if ( array_values( $options ) === $options ) {

				// We have an indexed array.
				foreach ( $options as $option ) {

					printf(
						'<input type="radio" class="%s" name="%s" value="%s" %s /> <label class="%s">%s</label><br />',
						esc_attr( $classes ),
						$this->get_field_name( $key ),
						esc_attr( $option ),
						checked( $value, $option, false ),
						'radio-option-label',
						esc_html( $option )
					);
				}

			} else {

				// We have an associative array.
				foreach ( $options as $option_value => $option_display_name ) {

					printf(
						'<input type="radio" class="%s" name="%s" value="%s" %s /> <label class="%s">%s</label><br />',
						esc_attr( $classes ),
						$this->get_field_name( $key ),
						esc_attr( $option_value ),
						checked( $value, $option_value, false ),
						'radio-option-label',
						esc_html( $option_display_name )
					);
				}
			}

			if ( '' !== $description) {
				printf(
					'<small class="bssn-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}

}

<?php

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


<?php
/**
 * Trait which contains multiple value assignment method for fields which
 * allow selection of a value from amongst multiple values like a select
 * dropdown or radio button group.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

namespace iG\Metabox\Traits;

trait Set_Values {

	/**
	 * Set values for the field.
	 *
	 * @param array $values Associative array of values
	 * @return iG\Metabox\Field
	 */
	public function set_values( array $values ) {
		if ( empty( $values ) ) {
			throw new ErrorException( 'Metabox field values need to be in an array' );
		}

		$this->_values = $values;

		return $this;
	}

}	//end trait


//EOF
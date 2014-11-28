<?php
/**
 * The email field class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

namespace iG\Metabox;

class Email_Field extends Text_Field {

	/**
	 * Field initialization stuff
	 *
	 * @return void
	 */
	protected function _sub_init() {
		parent::_sub_init();

		$this->_field['type'] = 'email';
	}

}	//end of class



//EOF
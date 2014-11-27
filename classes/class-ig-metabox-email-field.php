<?php
/**
 * The email field class.
 *
 * @author Amit Gupta <http://amitgupta.in/>
 */

class iG_Metabox_Email_Field extends iG_Metabox_Text_Field {

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
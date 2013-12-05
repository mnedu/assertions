<?php

namespace mstate\assertions;

interface ValidatableInterface {

	/**
	 * An external hook to confirm that the data in the object is conforming to a contract to be checked by this method.
	 * 
	 * @return boolean
	 */
	public function valid();

	/**
	 * Retrieve an array of errors or "reasons" for why the validation failed
	 * 
	 * @return array
	 */
	public function validationErrors();
}
?>
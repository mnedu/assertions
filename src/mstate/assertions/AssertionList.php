<?php

namespace mstate\assertions;

use SplObjectStorage;

class AssertionList implements ValidatableInterface {

	public function __construct(array $chains = NULL) {

		$this->chains = new SplObjectStorage();

		if ($chains) {
			foreach ($chains as $chain) {
				if (($chain instanceof AssertionChain) == FALSE) {
					throw new \InvalidArgumentException("Chain list must be contain only AssertionChain objects");
				}
				
				/** @var $chain AssertionChain */

				$this->addChain($chain);

				if ($chain->valid()) {
					continue;
				}

				$this->violations = array_merge(
					$chain->validationErrors(),
					$this->violations
				);
				
			}
		}
	}

	protected $violations = [];

	/**
	 * @var \SplObjectStorage|AssertionChain[]
	 */
	protected $chains;

	protected $subject;

	/**
	 * @inheritdoc
	 */
	public function valid() {
		return !$this->violations;
	}

	/**
	 * @inheritdoc
	 */
	public function validationErrors() {
		return $this->violations;
	}

	public function addChain(AssertionChain $c) {
		$this->chains->attach($c);
	}


	public function removeChain(AssertionChain $c) {
		$this->chains->detach($c);
	}

	/**
	 * @return \SplObjectStorage|AssertionChain[]
	 */
	public function getChains() {
		return $this->chains;
	}
}
?>
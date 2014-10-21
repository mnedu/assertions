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

    /**
     * Add a chain to the list of assertions - this will also call valid() on the chain.
     *
     * @param AssertionChain
     *
     * @return NULL
     */
	public function addChain(AssertionChain $c) {
		$this->chains->attach($c);

        if ($c->valid()) {
            return;
        }

        $this->violations = array_merge(
            $c->validationErrors(),
            $this->violations
        );

        return;
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
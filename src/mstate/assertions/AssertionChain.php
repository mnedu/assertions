<?php

namespace mstate\assertions;

use Assert\Assertion;
use Assert\InvalidArgumentException as AssertionException;

/**
 * @package mstate\assertions
 *
 * @method \mstate\assertions\AssertionChain integer($message = NULL);
 * @method \mstate\assertions\AssertionChain digit($message = NULL);
 * @method \mstate\assertions\AssertionChain integerish($message = NULL);
 * @method \mstate\assertions\AssertionChain range($minValue, $maxValue, $message = NULL);
 * @method \mstate\assertions\AssertionChain boolean($message = NULL);
 * @method \mstate\assertions\AssertionChain notEmpty($message = NULL);
 * @method \mstate\assertions\AssertionChain noContent($message = NULL);
 * @method \mstate\assertions\AssertionChain notNull($message = NULL);
 * @method \mstate\assertions\AssertionChain string($message = NULL);
 * @method \mstate\assertions\AssertionChain regex($regex, $message = NULL);
 * @method \mstate\assertions\AssertionChain length($length, $message = NULL);
 * @method \mstate\assertions\AssertionChain minLength($length, $message = NULL);
 * @method \mstate\assertions\AssertionChain maxLength($length, $message = NULL);
 * @method \mstate\assertions\AssertionChain betweenLength($minLength, $maxLength, $message = NULL);
 * @method \mstate\assertions\AssertionChain startsWith($needle, $message = NULL);
 * @method \mstate\assertions\AssertionChain endsWith($needle, $message = NULL);
 * @method \mstate\assertions\AssertionChain isArray($message = NULL);
 * @method \mstate\assertions\AssertionChain contains($needle, $message = NULL);
 * @method \mstate\assertions\AssertionChain choice($choices, $message = NULL);
 * @method \mstate\assertions\AssertionChain inArray($choices, $message = NULL);
 * @method \mstate\assertions\AssertionChain numeric($message = NULL);
 * @method \mstate\assertions\AssertionChain keyExists($key, $message = NULL);
 * @method \mstate\assertions\AssertionChain notEmptyKey($key, $message = NULL);
 * @method \mstate\assertions\AssertionChain notBlank($message = NULL);
 * @method \mstate\assertions\AssertionChain isInstanceOf($className, $message = NULL);
 * @method \mstate\assertions\AssertionChain notIsInstanceOf($className, $message = NULL);
 * @method \mstate\assertions\AssertionChain classExists($message = NULL);
 * @method \mstate\assertions\AssertionChain subclassOf($className, $message = NULL);
 * @method \mstate\assertions\AssertionChain directory($message = NULL);
 * @method \mstate\assertions\AssertionChain file($message = NULL);
 * @method \mstate\assertions\AssertionChain readable($message = NULL);
 * @method \mstate\assertions\AssertionChain writeable($message = NULL);
 * @method \mstate\assertions\AssertionChain email($message = NULL);
 * @method \mstate\assertions\AssertionChain url($message = NULL);
 * @method \mstate\assertions\AssertionChain alnum($message = NULL);
 * @method \mstate\assertions\AssertionChain true($message = NULL);
 * @method \mstate\assertions\AssertionChain false($message = NULL);
 * @method \mstate\assertions\AssertionChain min($min, $message = NULL);
 * @method \mstate\assertions\AssertionChain max($max, $message = NULL);
 * @method \mstate\assertions\AssertionChain eq($actual, $expected, $message = NULL);
 * @method \mstate\assertions\AssertionChain same($actual, $expected, $message = NULL);
 * @method \mstate\assertions\AssertionChain implementsInterface($interfaceName, $message = NULL);
 * @method \mstate\assertions\AssertionChain isJsonString($message = NULL);
 * @method \mstate\assertions\AssertionChain uuid($message = NULL);
 * 
 */
class AssertionChain implements ValidatableInterface {

	protected $value;
	protected $violations = [];

	/**
	 * @param mixed $value A scalar value to evaluate
	 *
	 * @throws \Exception If the value is non-scalar
	 * 
	 * @return \mstate\assertions\AssertionChain
	 */
	public function __construct($value) {

		if (!is_scalar($value)) {
			throw new \Exception('Value should be scalar');
		}

		$this->value = $value;
		
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function valid() {
		return !$this->violations; /* Inversion intentional - if values in array, it's not valid. */
	}

	/**
	 * @inheritdoc
	 */
	public function validationErrors() {
		return $this->violations;
	}

	public function __call($name, $args) {
		
		if (!is_callable([Assertion::class, $name])) {
			throw new \BadMethodCallException(Assertion::class.'::'.$name.' does not exist, or is not callable.');
		}

		array_unshift($args, $this->value);

		try {
			call_user_func_array(
				[
					Assertion::class,
					$name
				],
				$args
			);

		} catch (AssertionException $e) {
			$this->violations[] = (string)$e->getMessage();
		}

		return $this;
	}
}

?> 
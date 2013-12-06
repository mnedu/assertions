<?php

use mstate\assertions\AssertionChain;

class AssertionChainTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @expectedException \Exception
	 */
	public function testNonScalar() {
		new AssertionChain(new DateTime());
	}

	/**
	 * @expectedException \BadMethodCallException
	 */
	public function testBadMethodMagic() {

		$c = new AssertionChain('1');

		$c->IDoNotExistAsAMethod();
	}

	public function testGoodMethodMagic() {

		$c = new AssertionChain('z');

		$this->assertTrue(
			is_callable([
				$c,
				'integer'
			])
		);
	}
	
	public function testValid() {
		$c = new AssertionChain(1000);
		
		$this->assertTrue($c->valid());
		
		$c->integer();

		$this->assertTrue($c->valid());
		
		$c->string();
				
		$this->assertFalse($c->valid());
	}
	
	public function testViolationListMessages() {
		$expected = [
			'Message A',
			'Message B'
		];
		
		$c = (new AssertionChain('zed'))->integer('Message A')->boolean('Message B');
				
		$this->assertEquals(
			$expected,
			$c->validationErrors()
		);
	}
	
	public function testViolationList() {
		
		$c = new AssertionChain('Not an integer');

		$this->assertCount(
			0,
			$c->validationErrors()
		);
		
		$c->integer();
		
		$this->assertCount(
			1,
			$c->validationErrors()
		);
		
		$c->false();

		$this->assertCount(
			2,
			$c->validationErrors()
		);
		
		$c->string();

		$this->assertCount(
			2,
			$c->validationErrors()
		);
	}
}
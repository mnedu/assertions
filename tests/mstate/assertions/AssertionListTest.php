<?php

use mstate\assertions\AssertionChain;
use mstate\assertions\AssertionList;

class AssertionListTest extends PHPUnit_Framework_TestCase {


	public function testConstructor() {

		$chains = [
			new AssertionChain('z'),
			new AssertionChain(1)
		];

		$list = new AssertionList($chains);

		$this->assertCount(
			2,
			$list->getChains()
		);
	}

	public function testRemoveChain() {

		$c = new AssertionChain('zarb, the impaler.');

		$list = new AssertionList();

		$list->addChain($c);

		$this->assertCount(
			1,
			$list->getChains()
		);

		$list->removeChain($c);

		$this->assertCount(
			0,
			$list->getChains()
		);
	}

	public function testValid() {

		$chains = [
			(new AssertionChain('z'))->integer(),
			new AssertionChain(1)
		];

		$list = new AssertionList($chains);

		$this->assertFalse($list->valid());

		/***************/

		$chains = [
			new AssertionChain('z'),
			(new AssertionChain(1))->integer()
		];

		$list = new AssertionList($chains);

		$this->assertTrue($list->valid());
	}

	public function testChainViolationMessageMerging() {

		$expected = [
			'Message A',
			'Message B'
		];

		$chains = [
			(new AssertionChain('Not an integer 1'))->integer('Message A'),
			(new AssertionChain('Not an integer 2'))->integer('Message B'),
		];

		$list = new AssertionList($chains);
		
		$messages = $list->validationErrors();
		
		sort($messages);
		sort($expected);
		

		$this->assertEquals(
			$expected,
			$messages
		);
	}
}
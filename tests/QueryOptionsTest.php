<?php
declare(strict_types=1);

use Wikitext\Query;

class QueryOptionsTest extends QueryTest
{
	/**
	 * @var Query
	 */
	private $wikitext;

	/**
	 * @var array
	 */
	private $items = ['+first+', '-second-', '=third=', '-fourth-', '+fifth+'];

	/**
	 * Set up a test
	 */
	public function setUp()
	{
		Query::setMatchers(
			[
				$this->getMatcherMock('+', '/\+(.*)\+/', 1),
				$this->getMatcherMock('-', '/\-(.*)\-/', 1),
				$this->getMatcherMock('=', '/\=(.*)\=/', 1),
			]
		);

		$this->wikitext = new Query($this->items);
	}

	/**
	 * @test
	 */
	public function Should_SelectItemMatchingContent_When_WithContentOptionGiven_On_Query()
	{
		// When
		$result = $this->wikitext->find('-', ['withContent' => 'fourth']);

		// Then
		$this->assertEquals('fourth', $result);
	}

	/**
	 * @test
	 */
	public function Should_SelectItemsMatchingStartOfContent_When_StartsWithContentOptionGiven_On_Query()
	{
		// When
		$result = $this->wikitext->find('+', ['startsWithContent' => 'fi']);

		// Then
		$this->assertEquals(2, $result->count());
	}

	/**
	 * @test
	 */
	public function Should_SelectItemsUntilMatchingSelector_When_UntilOptionGiven_On_Query()
	{
		// When
		$result = $this->wikitext->find('+', ['until' => '=']);

		// Then
		$this->assertEquals(1, $result->count());
	}

	/**
	 * @test
	 */
	public function Should_SelectTheGivenAmountOfMatchingItems_When_LimitOptionGiven_On_Query()
	{
		// When
		$result = $this->wikitext->find('-', ['limit' => 1]);

		// Then
		$this->assertEquals(1, $result->count());
	}
}
<?php
declare(strict_types=1);

use Wikitext\Query;
use Wikitext\QueryCollection;

class QuerySelectorTest extends QueryTest
{
	/**
	 * @var Query
	 */
	private $wikitext;

	/**
	 * @var array
	 */
	private $items = ['+', '-', '-', '!', '===', '!', '!'];

	/**
	 * Set up a test
	 */
	public function setUp()
	{
		Query::setMatchers(
			[
				$this->getMatcherMock('*', '/.*/'),
				$this->getMatcherMock('+', '/\+/'),
				$this->getMatcherMock('-', '/\-/'),
				$this->getMatcherMock('!', '/\!/', 0, true),
				$this->getMatcherMock('=', '/=(=)=/', 1),
			]
		);

		$this->wikitext = new Query($this->items);
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_Query()
	{
		// When
		$result = $this->wikitext->find('*');

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_ReturnNoItems_When_AnNonMatchingSelectorIsGiven_On_Query()
	{
		// When
		$result = $this->wikitext->find('?');

		// Then
		$this->assertEquals(0, $result->count());
	}

	/**
	 * @test
	 */
	public function Should_ReturnMatchingItem_When_ASelectorIsGiven_On_Query()
	{
		// When
		$result = $this->wikitext->find('+');

		// Then
		$this->assertEquals(1, $result->count());
	}

	/**
	 * @test
	 */
	public function Should_ReturnMultipleMatchingItems_When_ASelectorIsGiven_On_Query()
	{
		// When
		$result = $this->wikitext->find('-');

		// Then
		$this->assertEquals(2, $result->count());
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikiTextCollection_When_GroupConsecutiveInMatcher_On_Query()
	{
		// When
		$result = $this->wikitext->find('!');

		// Then
		$this->assertTrue($result instanceof QueryCollection);
	}

	/**
	 * @test
	 */
	public function Should_ReturnGroupedItems_When_GroupConsecutiveInMatcher_On_Query()
	{
		// When
		$result = $this->wikitext->find('!');

		// Then
		$this->assertEquals(2, $result->count());
		$this->assertEquals(2, $result->last()->count());
	}

	/**
	 * @test
	 */
	public function Should_ExtractMatchedGroup_When_MatchIndexInMatcher_On_Query()
	{
		// When
		$result = $this->wikitext->find('=');

		// Then
		$this->assertEquals('=', $result->getText());
	}
}
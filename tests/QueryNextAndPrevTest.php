<?php
declare(strict_types=1);

use Wikitext\Query;

class QueryNextAndPrevTest extends QueryTest
{
	/**
	 * @var Query
	 */
	private $wikitext;

	/**
	 * @var array
	 */
	private $items = ['+', '-', '+', '=', '-', '+', '-'];

	/**
	 * Set up a test
	 */
	public function setUp()
	{
		Query::addMatchers(
			[
				$this->getMatcherMock('*', '/.*/'),
				$this->getMatcherMock('+', '/\+/'),
				$this->getMatcherMock('=', '/\=/'),
				$this->getMatcherMock('-', '/\-/'),
			]
		);

		$this->wikitext = (new Query($this->items))->find('=');
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_Next()
	{
		// When
		$result = $this->wikitext->next();

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_SelectNextSibling_When_NoSelectorIsGiven_On_Next()
	{
		// When
		$result = $this->wikitext->next();

		// Then
		$this->assertEquals(1, $result->count());
		$this->assertEquals('-', $result->getText());
	}

	/**
	 * @test
	 */
	public function Should_SelectNextMatchingSibling_When_ASelectorIsGiven_On_Next()
	{
		// When
		$result = $this->wikitext->next('+');

		// Then
		$this->assertEquals(1, $result->count());
		$this->assertEquals('+', $result->getText());
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_Prev()
	{
		// When
		$result = $this->wikitext->prev();

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_SelectPreviousSibling_When_NoSelectorIsGiven_On_Prev()
	{
		// When
		$result = $this->wikitext->prev();

		// Then
		$this->assertEquals(1, $result->count());
		$this->assertEquals('+', $result->getText());
	}

	/**
	 * @test
	 */
	public function Should_SelectPreviousMatchingSibling_When_ASelectorIsGiven_On_Prev()
	{
		// When
		$result = $this->wikitext->prev('-');

		// Then
		$this->assertEquals(1, $result->count());
		$this->assertEquals('-', $result->getText());
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_NextAll()
	{
		// When
		$result = $this->wikitext->nextAll();

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_SelectAllNextSiblings_When_NoSelectorIsGiven_On_NextAll()
	{
		// When
		$result = $this->wikitext->nextAll();

		// Then
		$this->assertEquals(3, $result->count());
	}

	/**
	 * @test
	 */
	public function Should_SelectAllNextMatchingSiblings_When_ASelectorIsGiven_On_NextAll()
	{
		// When
		$result = $this->wikitext->nextAll('-');

		// Then
		$this->assertEquals(2, $result->count());
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_PrevAll()
	{
		// When
		$result = $this->wikitext->prevAll();

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_SelectAllPreviousSiblings_When_NoSelectorIsGiven_On_PrevAll()
	{
		// When
		$result = $this->wikitext->prevAll();

		// Then
		$this->assertEquals(3, $result->count());
	}

	/**
	 * @test
	 */
	public function Should_SelectAllPreviousMatchingSiblings_When_ASelectorIsGiven_On_PrevAll()
	{
		// When
		$result = $this->wikitext->prevAll('+');

		// Then
		$this->assertEquals(2, $result->count());
	}
}
<?php
declare(strict_types=1);

use Wikitext\Query;

class QueryAccessTest extends QueryTest
{
	/**
	 * @var Query
	 */
	private $wikitext;

	/**
	 * @var array
	 */
	private $items = ['+', '-', '='];

	/**
	 * Set up a test
	 */
	public function setUp()
	{
		$this->wikitext = new Query($this->items);
	}

	/**
	 * @test
	 */
	public function Should_CreateFromString_On_Construct()
	{
		// When
		$wikitext = new Query("+\n-\n=");

		// Then
		$this->assertEquals(3, $wikitext->count());
	}

	/**
	 * @test
	 */
	public function Should_ReturnArrayOfItems_On_GetItems()
	{
		// When
		$result = $this->wikitext->getItems();

		// Then
		$this->assertEquals(['+', '-', '='], $result);
	}

	/**
	 * @test
	 */
	public function Should_ReturnFalse_When_WikitextHasItems_On_IsEmpty()
	{
		// Then
		$this->assertFalse($this->wikitext->isEmpty());
	}

	/**
	 * @test
	 */
	public function Should_ReturnTrue_When_WikitextHasZeroItems_On_IsEmpty()
	{
		// When
		$wikitext = new Query([]);

		// Then
		$this->assertTrue($wikitext->isEmpty());
	}

	/**
	 * @test
	 */
	public function Should_BeCountable()
	{
		// Then
		$this->assertEquals(3, count($this->wikitext));
	}

	/**
	 * @test
	 */
	public function Should_ReturnNumberOfItems_On_Count()
	{
		// When
		$result = $this->wikitext->count();

		// Then
		$this->assertEquals(3, $result);
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_First()
	{
		// When
		$result = $this->wikitext->first();

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_ReturnFirstItem_On_First()
	{
		// When
		$result = $this->wikitext->first();

		// Then
		$this->assertEquals('+', $result->getText());
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_Last()
	{
		// When
		$result = $this->wikitext->last();

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_ReturnLastItem_On_Last()
	{
		// When
		$result = $this->wikitext->last();

		// Then
		$this->assertEquals('=', $result->getText());
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_Eq()
	{
		// When
		$result = $this->wikitext->eq(1);

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_ReturnNthItem_On_Eq()
	{
		// When
		$result = $this->wikitext->eq(1);

		// Then
		$this->assertEquals('-', $result->getText());
	}

	/**
	 * @test
	 */
	public function Should_ReturnWikitext_On_GetOffset()
	{
		// When
		$result = $this->wikitext[1];

		// Then
		$this->assertTrue($result instanceof Query);
	}

	/**
	 * @test
	 */
	public function Should_ReturnNthItem_On_GetOffset()
	{
		// When
		$result = $this->wikitext[1];

		// Then
		$this->assertEquals('-', $result->getText());
	}

	/**
	 * @test
	 */
	public function Should_PresentWikitextItems_When_LoopingOverItems()
	{
		// Given
		$i = 0;

		// When
		foreach ($this->wikitext as $item) {
			$i++;

			// Then
			$this->assertTrue($item instanceof Query);
		}

		$this->assertEquals($this->wikitext->count(), $i);
	}

	/**
	 * @test
	 */
	public function Should_ReturnContentOfAllItemsSeparatedWithLineBreaks_On_GetText()
	{
		// When
		$result = $this->wikitext->getText();

		// Then
		$this->assertEquals("+\n-\n=", $result);
	}

	/**
	 * @test
	 */
	public function Should_ReturnGetTextValue_When_CastingToAString()
	{
		// When
		$result = (string) $this->wikitext;

		// Then
		$this->assertEquals($this->wikitext->getText(), $result);
	}

	/**
	 * @test
	 */
	public function Should_ReturnMatch_When_FoundWithRegex_On_Match()
	{
		// Given
		$wikitext = new Query('fobazo bar baz');

		// When
		$result = $wikitext->match('/bar (baz)/');

		// Then
		$this->assertEquals('bar baz', $result);
	}

	/**
	 * @test
	 */
	public function Should_ReturnRequestedMatch_When_FoundWithRegex_On_Match()
	{
		// Given
		$wikitext = new Query('fobazo bar baz');

		// When
		$result = $wikitext->match('/bar (baz)/', 1);

		// Then
		$this->assertEquals('baz', $result);
	}

	/**
	 * @test
	 */
	public function Should_ReturnNullValue_When_NotFoundWithRegex_On_Match()
	{
		// Given
		$wikitext = new Query('fobazo bar baz');

		// When
		$result = $wikitext->match('/hello/');

		// Then
		$this->assertEquals(null, $result);
	}

	/**
	 * @test
	 */
	public function Should_ReturnDefinedDefaultValue_When_NotFoundWithRegex_On_Match()
	{
		// Given
		$wikitext = new Query('fobazo bar baz');

		// When
		$result = $wikitext->match('/hello/', 0, 123);

		// Then
		$this->assertEquals(123, $result);
	}
}
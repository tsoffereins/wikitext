<?php
declare(strict_types=1);

namespace Wikitext;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class Query implements QueryInterface, ArrayAccess, Countable, IteratorAggregate
{
	/**
	 * @var array
	 */
	protected static $matchers = [];

	/**
	 * @var array
	 */
	protected $items = [];

	/**
	 * @var Query
	 */
	protected $context = null;

	/**
	 * @var int
	 */
	private $position = 0;

	/**
	 * Set the matchers for supporting selectors.
	 *
	 * @param array $matchers
	 * @return void
	 */
	public static function setMatchers(array $matchers): void
	{
		foreach ($matchers as $matcher) {
			if ($matcher instanceof MatcherInterface) {
				self::$matchers[$matcher->getSelector()] = $matcher;
			}
		}
	}

	/**
	 * Query constructor.
	 *
	 * @param mixed $items
	 */
	public function __construct($items = [])
	{
		if (is_string($items)) {
			$items = explode("\n", $items);
		}

		$this->items = $items;

		$this->position = 0;
	}

	/**
	 * Find items within the wikitext.
	 *
	 * @param string $selector
	 * @param array  $options
	 * @return QueryInterface
	 */
	public function find(string $selector, array $options = []): QueryInterface
	{
		$matcher = $this->getMatcher($selector);

		$result = $this->search($matcher, $options);

		$result->setContext($this);

		return $result;
	}

	/**
	 * Get the items in the wikitext.
	 *
	 * @return array
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * Count the number of items in the wikitext.
	 *
	 * @return int
	 */
	public function count(): int
	{
		return count($this->items);
	}

	/**
	 * Get the first item in the wikitext.
	 *
	 * @return QueryInterface
	 */
	public function first(): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		return $this[0];
	}

	/**
	 * Get the last item in the wikitext.
	 *
	 * @return QueryInterface
	 */
	public function last(): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		return $this[$this->count() - 1];
	}

	/**
	 * Get the nth item in the wikitext.
	 *
	 * @param int $offset
	 * @return QueryInterface
	 */
	public function eq(int $offset): QueryInterface
	{
		if ( ! isset($this[$offset])) {
			return new Query();
		}

		return $this[$offset];
	}

	/**
	 * Determine if the wikitext has items.
	 *
	 * @return bool
	 */
	public function isEmpty(): bool
	{
		return $this->count() === 0;
	}

	/**
	 * Get all items after this wikitext matching a selector.
	 *
	 * @param string $selector
	 * @param array  $options
	 * @return QueryInterface
	 */
	public function nextAll(string $selector = '*', array $options = []): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		$index = array_keys($this->items)[0];

		$options = array_merge(
			$options,
			[
				'fromIndex' => $index + 1,
			]
		);

		return $this->getContext()->find($selector, $options);
	}

	/**
	 * Get the item after this wikitext matching a selector.
	 *
	 * @param string $selector
	 * @param array  $options
	 * @return QueryInterface
	 */
	public function next(string $selector = '*', array $options = []): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		$indices = array_keys($this->items);

		$options = array_merge(
			$options,
			[
				'fromIndex' => $indices[0] + 1,
				'limit' => 1,
			]
		);

		return $this->getContext()->find($selector, $options);
	}

	/**
	 * Get all items before this wikitext matching a selector.
	 *
	 * @param string $selector
	 * @param array  $options
	 * @return QueryInterface
	 */
	public function prevAll(string $selector = '*', array $options = []): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		$indices = array_keys($this->items);

		$options = array_merge(
			$options,
			[
				'fromIndex' => $indices[0],
				'reverse' => true,
			]
		);

		return $this->getContext()->find($selector, $options);
	}

	/**
	 * Get the item before this wikitext matching a selector.
	 *
	 * @param string $selector
	 * @param array  $options
	 * @return QueryInterface
	 */
	public function prev(string $selector = '*', array $options = []): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		$indices = array_keys($this->items);

		$options = array_merge(
			$options,
			[
				'fromIndex' => $indices[0],
				'limit' => 1,
				'reverse' => true,
			]
		);

		return $this->getContext()->find($selector, $options);
	}

	/**
	 * Get the content of the wikitexts first item.
	 *
	 * @return string
	 */
	public function getText(): string
	{
		return implode("\n", $this->items);
	}

	/**
	 * Set the context that the wikitext was presented in.
	 *
	 * @param QueryInterface $context
	 * @return void
	 */
	public function setContext(QueryInterface $context): void
	{
		$this->context = $context;
	}

	/**
	 * Get the context that the wikitext was presented in.
	 *
	 * @return QueryInterface
	 */
	public function getContext(): QueryInterface
	{
		return $this->context ?? $this;
	}

	/**
	 * Get the matcher for a selector.
	 *
	 * @param string $selector
	 * @return MatcherInterface
	 */
	private function getMatcher(string $selector): MatcherInterface
	{
		if ($selector === '*') {
			return new Matcher('*', '/(.*)/');
		}

		if ( ! isset(self::$matchers[$selector])) {
			return new Matcher('', '/^$/');
		}

		return self::$matchers[$selector];
	}

	/**
	 * Find items within the wikitext.
	 *
	 * @param MatcherInterface $matcher
	 * @param array            $options
	 * @return Query
	 */
	private function search(MatcherInterface $matcher, array $options = []): Query
	{
		$result = [];
		$options = $this->fillOptions($options);
		$items = $this->getSearchableItems($options);

		foreach ($items as $index => $item) {

			if (isset($options['until'])) {
				$untilMatcher = $this->getMatcher($options['until']);

				if (preg_match($untilMatcher->getPattern(), $item))
					break;
			}

			if (preg_match_all($matcher->getPattern(), $item, $matches)) {
				if ($this->shouldIncludeMatch($matches, $options)) {
					$result[$index] = $matches[$matcher->getMatchIndex()][0];
				}
			}
		}

		if ($matcher->shouldGroupConsecutive()) {
			$result = $this->groupConsecutive($result);
		}

		$result = $options['limit'] < 0 ? $result : $this->slice($result, 0, $options['limit']);

		if ($matcher->shouldGroupConsecutive()) {
			return new QueryCollection($result);
		}

		return new Query($result);
	}

	/**
	 * Get the items to search in.
	 *
	 * @param array $options
	 * @return array
	 */
	private function getSearchableItems(array $options)
	{
		$fromIndex = isset($options['fromIndex']) ? $options['fromIndex'] : 0;

		if (isset($options['reverse']) && $options['reverse']) {
			return array_reverse($this->slice($this->items, 0, $fromIndex), true);
		}

		return $this->slice($this->items, $fromIndex);
	}

	/**
	 * Determine if a match should be included.
	 *
	 * @param array $matches
	 * @param array $options
	 * @return bool
	 */
	private function shouldIncludeMatch(array $matches, array $options): bool
	{
		$matchText = isset($options['withContent']) && isset($matches[1]);
		$matchStartText = isset($options['startsWithContent']) && isset($matches[1]);

		return (( ! $matchText) || $matches[1][0] === $options['withContent']) &&
			(( ! $matchStartText) || strpos($matches[1][0], $options['startsWithContent']) === 0);
	}

	/**
	 * Group consecutive items in a list of items.
	 *
	 * @param array $items
	 * @return array
	 */
	private function groupConsecutive(array $items): array
	{
		$groupIndex = array_keys($items)[0];
		$iterator = $groupIndex;
		$grouped = [$groupIndex => []];

		foreach ($items as $index => $item) {
			if ($iterator + 1 < $index) {
				$groupIndex = $index;
			}

			$grouped[$groupIndex][$index] = $item;
			$iterator = $index;
		}

		return $grouped;
	}

	/**
	 * Slice items without losing keys.
	 *
	 * @param array    $items
	 * @param null     $start
	 * @param int|null $length
	 * @return array
	 */
	protected function slice(array $items, $start = null, int $length = null): array
	{
		$result = [];
		$i = 0;

		foreach ($items as $index => $item) {
			if ($i === $length) {
				break;
			}

			if ($index >= $start) {
				$result[$index] = $item;
				$i++;
			}
		}

		return $result;
	}

	/**
	 * Fill an options array with defaults.
	 *
	 * @param array $options
	 * @return array
	 */
	private function fillOptions(array $options): array
	{
		$defaults = [
			'fromIndex' => 0,
			'limit' => -1,
			'reverse' => false,
		];

		return array_merge($defaults, $options);
	}

	/**
	 * Determine if an item exists at a given offset.
	 *
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset): bool
	{
		return $offset < count($this->items);
	}

	/**
	 * Get the item at a given offset.
	 *
	 * @param mixed $offset
	 * @return QueryInterface
	 */
	public function offsetGet($offset): QueryInterface
	{
		$index = array_keys($this->items)[$offset];
		$item = $this->items[$index];

		$return = new Query(is_array($item) ? $item : [$item]);

		$return->setContext($this);

		return $return;
	}

	/**
	 * Set an item at a given offset (not supported).
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 * @deprecated
	 */
	public function offsetSet($offset, $value)
	{
		// TODO: Implement offsetSet() method.
	}

	/**
	 * Remove an item from a given offset (not supported).
	 *
	 * @param mixed $offset
	 * @deprecated
	 */
	public function offsetUnset($offset)
	{
		// TODO: Implement offsetUnset() method.
	}

	/**
	 * Get the iterator to allow the wikitext to run in a loop.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		$items = array_map(
			function($item)
			{
				return new Query((array) $item);
			},
			$this->items
		);

		return new ArrayIterator($items);
	}

	/**
	 * Get the info to show in var_dump.
	 *
	 * @return array
	 */
	public function __debugInfo(): array
	{
		return [
			'count' => $this->count(),
			'items' => $this->items,
			'context' => is_null($this->context) ? null : '[context]',
		];
	}

	/**
	 * Convert the wikitext to a string.
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->getText();
	}
}
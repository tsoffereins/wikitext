<?php
declare(strict_types=1);

namespace Wikitext;

class QueryCollection extends Query
{
	/**
	 * QueryCollection constructor.
	 *
	 * @param mixed $items
	 */
	public function __construct($items = [])
	{
		$items = array_map(
			function($item)
			{
				return $item instanceof Query ? $item : new Query((array) $item);
			},
			$items
		);

		parent::__construct($items);
	}

	/**
	 * Query items within the wikitext.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function find(string $selector, array $options = []): QueryInterface
	{
		return $this->callEach('query', [$selector, $options]);
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
	 * Get all items after this wikitext matching a selector.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function nextAll(string $selector = '*', array $options = []): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		return $this->first()->nextAll($selector, $options);
	}

	/**
	 * Get the item after this wikitext matching a selector.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function next(string $selector = '*', array $options = []): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		return $this->first()->next($selector, $options);
	}

	/**
	 * Get all items before this wikitext matching a selector.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function prevAll(string $selector = '*', array $options = []): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		return $this->first()->prevAll($selector, $options);
	}

	/**
	 * Get the item before this wikitext matching a selector.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function prev(string $selector = '*', array $options = []): QueryInterface
	{
		if ($this->isEmpty()) {
			return new Query();
		}

		return $this->first()->prev($selector, $options);
	}

	/**
	 * Get the content of the wikitexts first item.
	 *
	 * @return string
	 */
	public function getText(): string
	{
		return $this->isEmpty() ? '' : $this->first()->getText();
	}

	/**
	 * Set the context that the wikitext was presented in.
	 *
	 * @param  QueryInterface $context
	 * @return void
	 */
	public function setContext(QueryInterface $context): void
	{
		parent::setContext($context);

		foreach ($this->items as $item) {
			$item->setContext($context);
		}
	}

	/**
	 * Call a method for each item.
	 *
	 * @param  string $method
	 * @param  array  $arguments
	 * @return QueryInterface
	 */
	private function callEach(string $method, array $arguments): QueryInterface
	{
		$items = array_reduce(
			$this->items,
			function($collection, $item) use ($method, $arguments)
			{
				if (is_null($collection)) {
					$collection = [];
				}

				$matches = call_user_func_array([$item, $method], $arguments);

				return array_merge($collection, $matches->getItems());
			}
		);

		return new Query($items);
	}

	/**
	 * Get the item at a given offset.
	 *
	 * @param  mixed $offset
	 * @return QueryInterface
	 */
	public function offsetGet($offset): QueryInterface
	{
		$index = array_keys($this->items)[$offset];

		return $this->items[$index];
	}
}
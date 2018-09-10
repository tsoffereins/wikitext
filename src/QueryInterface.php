<?php
declare(strict_types=1);

namespace Wikitext;

interface QueryInterface
{
	/**
	 * Add matchers for supporting selectors.
	 *
	 * @param  array $matchers
	 * @return void
	 */
	public static function addMatchers(array $matchers): void;

	/**
	 * Query items within the wikitext.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function find(string $selector, array $options = []): QueryInterface;

	/**
	 * Get the items in the wikitext.
	 *
	 * @return array
	 */
	public function getItems(): array;

	/**
	 * Count the number of items in the wikitext.
	 *
	 * @return int
	 */
	public function count(): int;

	/**
	 * Get the first item in the wikitext.
	 *
	 * @return QueryInterface
	 */
	public function first(): QueryInterface;

	/**
	 * Get the last item in the wikitext.
	 *
	 * @return QueryInterface
	 */
	public function last(): QueryInterface;

	/**
	 * Get the nth item in the wikitext.
	 *
	 * @param  int $offset
	 * @return QueryInterface
	 */
	public function eq(int $offset): QueryInterface;

	/**
	 * Determine if the wikitext has items.
	 *
	 * @return bool
	 */
	public function isEmpty(): bool;

	/**
	 * Get all items after this wikitext matching a selector.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function nextAll(string $selector = '*', array $options = []): QueryInterface;

	/**
	 * Get the item after this wikitext matching a selector.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function next(string $selector = '*', array $options = []): QueryInterface;

	/**
	 * Get all items before this wikitext matching a selector.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function prevAll(string $selector = '*', array $options = []): QueryInterface;

	/**
	 * Get the item before this wikitext matching a selector.
	 *
	 * @param  string $selector
	 * @param  array  $options
	 * @return QueryInterface
	 */
	public function prev(string $selector = '*', array $options = []): QueryInterface;

	/**
	 * Get the content of the wikitexts first item.
	 *
	 * @return string
	 */
	public function getText(): string;

	/**
	 * Set the context that the wikitext was presented in.
	 *
	 * @param  QueryInterface $context
	 * @return void
	 */
	public function setContext(QueryInterface $context): void;

	/**
	 * Get the context that the wikitext was presented in.
	 *
	 * @return QueryInterface
	 */
	public function getContext(): QueryInterface;

	/**
	 * Convert the wikitext to a string.
	 *
	 * @return string
	 */
	public function __toString(): string;
}
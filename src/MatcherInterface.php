<?php
declare(strict_types=1);

namespace Wikitext;

interface MatcherInterface
{
	/**
	 * Get the selector that is listened for.
	 *
	 * @return string
	 */
	public function getSelector(): string;

	/**
	 * Get the regex pattern to run.
	 *
	 * @return string
	 */
	public function getPattern(): string;

	/**
	 * Determine if consecutive matches should be grouped in a single result.
	 *
	 * @return bool
	 */
	public function shouldGroupConsecutive(): bool;

	/**
	 * Get the index that should be taken from the matches (match group).
	 *
	 * @return int
	 */
	public function getMatchIndex(): int;
}
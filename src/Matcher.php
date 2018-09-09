<?php
declare(strict_types=1);

namespace Wikitext;

class Matcher implements MatcherInterface
{
	/**
	 * @var string
	 */
	private $selector;

	/**
	 * @var string
	 */
	private $pattern;

	/**
	 * @var array
	 */
	private $options = [
		'matchIndex' => 1,
		'groupConsecutive' => false,
	];

	/**
	 * Matcher constructor.
	 *
	 * @param string $selector
	 * @param string $pattern
	 * @param array  $options
	 */
	public function __construct(string $selector, string $pattern, array $options = [])
	{
		$this->selector = $selector;

		$this->pattern = $pattern;

		$this->options = array_merge($this->options, $options);
	}

	/**
	 * Get the selector that is listened for.
	 *
	 * @return string
	 */
	public function getSelector(): string
	{
		return $this->selector;
	}

	/**
	 * Get the regex pattern to run.
	 *
	 * @return string
	 */
	public function getPattern(): string
	{
		return $this->pattern;
	}

	/**
	 * Determine if consecutive matches should be grouped in a single result.
	 *
	 * @return bool
	 */
	public function shouldGroupConsecutive(): bool
	{
		return (bool) $this->getOption('groupConsecutive');
	}

	/**
	 * Get the index that should be taken from the matches (match group).
	 *
	 * @return int
	 */
	public function getMatchIndex(): int
	{
		return (int) $this->getOption('matchIndex');
	}

	/**
	 * Get an setting for a key from the options.
	 *
	 * @param string $key
	 * @param null   $default
	 * @return mixed|null
	 */
	public function getOption(string $key, $default = null)
	{
		return isset($this->options[$key]) ? $this->options[$key] : $default;
	}
}
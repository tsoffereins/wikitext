<?php
declare(strict_types=1);

namespace Wikitext\Matchers;

use Wikitext\Matcher;
use Wikitext\UnknownMatcherException;

class HeaderMatcher extends Matcher
{
	/**
	 * @var array
	 */
	static private $levels = [
		'h1' => '/^= ?(.*) ?=$/',
		'h2' => '/^== ?(.*) ?==$/',
		'h3' => '/^=== ?(.*) ?===$/',
		'h4' => '/^==== ?(.*) ?====$/',
		'h5' => '/^===== ?(.*) ?=====$/',
		'h6' => '/^====== ?(.*) ?======$/',
	];

	/**
	 * HeaderMatcher constructor.
	 *
	 * @param string $level
	 * @throws UnknownMatcherException
	 */
	public function __construct(string $level = 'h1')
	{
		if ( ! isset(self::$levels[$level])) {
			throw new UnknownMatcherException("$level could not be resolved to a known matcher");
		}

		parent::__construct($level, self::$levels[$level]);
	}
}
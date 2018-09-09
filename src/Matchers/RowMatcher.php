<?php
declare(strict_types=1);

namespace Wikitext\Matchers;

use Wikitext\Matcher;

class RowMatcher extends Matcher
{
	/**
	 * RowMatcher constructor.
	 */
	public function __construct()
	{
		parent::__construct('hr', '/^----$/', ['matchIndex' => 0]);
	}
}
<?php
declare(strict_types=1);

namespace Wikitext\Matchers;

use Wikitext\Matcher;

class UnorderedListMatcher extends Matcher
{
	/**
	 * UnorderedListMatcher constructor.
	 */
	public function __construct()
	{
		parent::__construct('ul', '/^\*+ ?(.*)$/', ['groupConsecutive' => true, 'matchIndex' => 0]);
	}
}
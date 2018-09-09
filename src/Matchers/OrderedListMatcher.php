<?php
declare(strict_types=1);

namespace Wikitext\Matchers;

use Wikitext\Matcher;

class OrderedListMatcher extends Matcher
{
	/**
	 * OrderedListMatcher constructor.
	 */
	public function __construct()
	{
		parent::__construct('ol', '/^#+ ?(.*)$/', ['groupConsecutive' => true, 'matchIndex' => 0]);
	}
}
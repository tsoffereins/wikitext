<?php
declare(strict_types=1);

namespace Wikitext\Matchers;

use Wikitext\Matcher;

class AnchorMatcher extends Matcher
{
	/**
	 * AnchorMatcher constructor.
	 */
	public function __construct()
	{
		parent::__construct('a', '/\[\[(.*?)\]\]/');
	}
}
<?php
declare(strict_types=1);

namespace Wikitext\Matchers;

use Wikitext\Matcher;

class ListItemMatcher extends Matcher
{
	/**
	 * ListItemMatcher constructor.
	 */
	public function __construct()
	{
		parent::__construct('li', '/^\*+ ?(.*)|#+ .*$/');
	}
}
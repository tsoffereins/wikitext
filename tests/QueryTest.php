<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Wikitext\Matcher;

abstract class QueryTest extends TestCase
{
    /**
     * Get a mock for a Matcher.
     *
     * @param  string  $selector
     * @param  string  $pattern
     * @param  int  $matchIndex
     * @param  bool  $groupConsecutive
     * @return object
     */
    protected function getMatcherMock(
        string $selector,
        string $pattern,
        int $matchIndex = 0,
        bool $groupConsecutive = false)
    {
        $matcher = $this->prophesize(Matcher::class);

        $matcher->getSelector()->willReturn($selector);
        $matcher->getPattern()->willReturn($pattern);
        $matcher->getMatchIndex()->willReturn($matchIndex);
        $matcher->shouldGroupConsecutive()->willReturn($groupConsecutive);

        return $matcher->reveal();
    }
}
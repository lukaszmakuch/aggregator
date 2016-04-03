<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\GroupingAggregator;

use lukaszmakuch\Aggregator\Impl\GroupingAggregator\Exception\UnableToReadProperty;

/**
 * Reads a property of the given subject.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface PropertyReader
{
    /**
     * @param mixed $subject
     * 
     * @return ComparableProperty
     * @throws UnableToReadProperty
     */
    public function readPropertyOf($subject);
}

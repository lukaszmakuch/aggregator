<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\GroupingAggregator;

/**
 * A property that may be compared for equality with another property.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ComparableProperty
{
    /**
     * @param ComparableProperty $anotherComparableProperty
     * @return boolean true if values are equal, false otherwise
     */
    public function isEqualTo(ComparableProperty $anotherComparableProperty);
}

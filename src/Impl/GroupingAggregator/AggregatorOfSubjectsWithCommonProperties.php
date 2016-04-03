<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\GroupingAggregator;

use lukaszmakuch\Aggregator\Aggregator;

class AggregatorOfSubjectsWithCommonProperties implements Aggregator
{
    private $commonProperty;
    private $actualAggregator;
    
    public function __construct(
        ComparableProperty $commonProperty,
        Aggregator $actualAggregator
    ) {
        $this->commonProperty = $commonProperty;
        $this->actualAggregator = $actualAggregator;
    }

    public function aggregate($subject)
    {
        $this->actualAggregator->aggregate($subject);
    }

    /**
     * @return ComparableProperty
     */
    public function getCommonProperty()
    {
        return $this->commonProperty;
    }

    /**
     * @return Aggregator
     */
    public function getActualAggregator()
    {
        return $this->actualAggregator;
    }
}

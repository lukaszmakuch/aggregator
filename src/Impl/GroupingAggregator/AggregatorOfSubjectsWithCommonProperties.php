<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\GroupingAggregator;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\SubjectProperty\ComparableProperty;

class AggregatorOfSubjectsWithCommonProperties implements Aggregator
{
    private $commonProperty;
    private $actualAggregator;
    
    /**
     * @param ComparableProperty $commonProperty represents what's common
     * for every of aggregated subjects 
     * @param Aggregator $actualAggregatorPrototype used to actually aggregate
     * subjects
     */
    public function __construct(
        ComparableProperty $commonProperty,
        Aggregator $actualAggregatorPrototype
    ) {
        $this->commonProperty = $commonProperty;
        $this->actualAggregator = clone $actualAggregatorPrototype;
    }
    
    public function __clone()
    {
        $this->actualAggregator = clone $this->actualAggregator;
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

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Counter;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;

/**
 * Counts all given subjects.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Counter implements Aggregator
{
    private $numberOfAggregatedSubjects = 0;
    
    /**
     * @return int
     */
    public function getNumberOfAggregatedSubjects()
    {
        return $this->numberOfAggregatedSubjects;
    }

    public function aggregate($subject)
    {
        $this->numberOfAggregatedSubjects++;
    }

    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }

    public function __clone()
    {
        $this->numberOfAggregatedSubjects = $this->numberOfAggregatedSubjects;
    }
}

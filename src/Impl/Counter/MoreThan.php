<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Counter;

use lukaszmakuch\Aggregator\AggregatorVisitor;
use lukaszmakuch\Aggregator\PredicateAggregator;

/**
 * Predicate that gives true only if some number of subjects have been aggregated.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MoreThan implements PredicateAggregator
{
    private $numberOfSubjects;
    private $counter;
    
    public function __construct($numerOfSubjects)
    {
        $this->counter = new Counter();
        $this->numberOfSubjects = $numerOfSubjects;
    }
    
    public function isTrue()
    {
        return $this->counter->getNumberOfAggregatedSubjects() > $this->numberOfSubjects;
    }

    public function aggregate($subject)
    {
        $this->counter->aggregate($subject);
    }
    
    /**
     * @return int this predicate evaluates to true 
     * when more subjects than this value are taken into account
     */
    public function getThreshold()
    {
        return $this->numberOfSubjects;
    }

    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }

    public function __clone()
    {
        $this->counter = clone $this->counter;
    }
}

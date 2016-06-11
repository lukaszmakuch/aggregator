<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Counter;

/**
 * Counts all given subjects.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Counter implements \lukaszmakuch\Aggregator\Aggregator
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
}

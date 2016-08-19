<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Limit;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;

/**
 * Aggregates no more than the given number of subjects starting from the given index.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Limit implements Aggregator
{
    private $actualAggregator;
    private $startingIndexFrom0;
    private $maxNumberOfSubjects;
    private $currentSubjectIndex = 0;
    
    /**
     * @var int
     */
    private $lastIndex;
    
    /**
     * @param Aggregator $actualAggregator
     * @param int $startingIndexFrom0
     * @param int $maxNumberOfSubjects
     */
    public function __construct(
        Aggregator $actualAggregator,
        $startingIndexFrom0,
        $maxNumberOfSubjects
    ) {
        $this->actualAggregator = $actualAggregator;
        $this->startingIndexFrom0 = $startingIndexFrom0;
        $this->maxNumberOfSubjects = $maxNumberOfSubjects;
        $this->lastIndex = $startingIndexFrom0 + $maxNumberOfSubjects - 1;
    }
    
    public function __clone()
    {
        $this->actualAggregator = clone $this->actualAggregator;
    }
    
    public function aggregate($subject)
    {
        if ($this->currentSubjectIndex <= $this->lastIndex) {
            if ($this->currentSubjectIndex >= $this->startingIndexFrom0) {
                $this->actualAggregator->aggregate($subject);
            }
            
            $this->currentSubjectIndex++;
        }
    }
    
    /**
     * @return Aggregator
     */
    public function getActualAggregator()
    {
        return $this->actualAggregator;
    }
    
    /**
     * @return int
     */
    public function getStartingIndexFrom0()
    {
        return $this->startingIndexFrom0;
    }
    
    /**
     * @return int
     */
    public function getMaxNumberOfSubjects()
    {
        return $this->maxNumberOfSubjects;
    }

    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }
}

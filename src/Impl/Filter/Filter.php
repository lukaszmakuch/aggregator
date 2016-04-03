<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Filter;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;
use lukaszmakuch\Aggregator\SubjectRequirement\Exception\UnableToCheckRequirement;
use lukaszmakuch\Aggregator\SubjectRequirement\SubjectRequirement;

/**
 * Takes into account only those subjects that meet the given requirement. 
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Filter implements Aggregator
{
    private $requirement;
    private $aggregator;
    
    public function __construct(
        SubjectRequirement $requirement,
        Aggregator $aggregatorOfSubjectsThatMeetTheRequirement
    ) {
        $this->requirement = $requirement;
        $this->aggregator = $aggregatorOfSubjectsThatMeetTheRequirement;
    }
    
    public function __clone()
    {
        $this->requirement = clone $this->requirement;
        $this->aggregator = clone $this->aggregator;
    }
    
    public function aggregate($subject)
    {
        try {
            if ($this->requirement->isMetFor($subject)) { 
                $this->aggregator->aggregate($subject);
            }
        } catch (UnableToCheckRequirement $e) {
            throw new UnableToAggregate(
                "it was impossible to check requirement for " . get_class($subject)
            );
        }
    }
    
    /**
     * @return SubjectRequirement
     */
    public function getRequirement()
    {
        return $this->requirement;
    }
    
    /**
     * @return Aggregator
     */
    public function getActualAggregator()
    {
        return $this->aggregator;
    }
}

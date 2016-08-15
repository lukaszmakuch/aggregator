<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Projection;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;
use lukaszmakuch\Aggregator\SubjectProjector\Exception\UnableToProject;
use lukaszmakuch\Aggregator\SubjectProjector\SubjectProjector;

class ProjectionAggregator implements Aggregator
{
    private $projector;
    private $aggregator;
    
    public function __construct(
        SubjectProjector $usedProjector,
        Aggregator $actualAggregator
    ) {
        $this->projector = $usedProjector;
        $this->aggregator = $actualAggregator;
    }
    
    public function __clone()
    {
        $this->aggregator = clone $this->aggregator;
    }
    
    public function aggregate($subject)
    {
        try {
            $this->aggregator->aggregate($this->projector->getDifferentRepresentationOf($subject));
        } catch (UnableToProject $e) {
            throw new UnableToAggregate("it was impossible to project " . get_class($subject));
        }
    }
    
    /**
     * @return SubjectProjector
     */
    public function getUsedProjector()
    {
        return $this->projector;
    }
    
    /**
     * @return Aggregator
     */
    public function getActualAggregator()
    {
        return $this->aggregator;
    }
    
    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\GroupingAggregator;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;
use lukaszmakuch\Aggregator\SubjectProperty\Exception\UnableToReadProperty;
use lukaszmakuch\Aggregator\SubjectProperty\PropertyReader;
use lukaszmakuch\Aggregator\SubjectProperty\ComparableProperty;

/**
 * Groups subjects by some property.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class GroupingAggregator implements Aggregator
{
    /**
     * @var AggregatorOfSubjectsWithCommonProperties[]
     */
    private $aggregatorsOfSubjectsWithCommonProperties = [];
    
    /**
     * @var Aggregator 
     */
    private $prototypeOfAggregatorOfEachGroup;
    
    /**
     * @var PropertyReader
     */
    private $propertyReader;
    
    /**
     * @param PropertyReader $propertyReader reads properties that may be
     * common among some subjects
     * @param Aggregator $prototypeOfAggregatorOfEachGroup used to aggregate
     * subjects of each group
     */
    public function __construct(
        PropertyReader $propertyReader,
        Aggregator $prototypeOfAggregatorOfEachGroup
    ) {
        $this->prototypeOfAggregatorOfEachGroup = clone $prototypeOfAggregatorOfEachGroup;
        $this->propertyReader = $propertyReader;
    }
    
    public function __clone()
    {
        $this->aggregatorsOfSubjectsWithCommonProperties = array_map(
            function ($agg) { return clone $agg; },
            $this->aggregatorsOfSubjectsWithCommonProperties
        );
    }

    /**
     * @return AggregatorOfSubjectsWithCommonProperties[]
     */
    public function getAggregatorsOfSubjectsWithCommonProperties()
    {
        return $this->aggregatorsOfSubjectsWithCommonProperties;
    }

    /**
     * @return PropertyReader
     */
    public function getPropertyReader()
    {
        return $this->propertyReader;
    }
    
    public function aggregate($subject)
    {
        $property = $this->readPropertyOf($subject);
        $itsAggregator = $this->findAggregatorBy($property);
        $itsAggregator->aggregate($subject);
    }
    
    /**
     * @param ComparableProperty $property
     * @return Aggregator
     * @throws UnableToAggregate if it's impossible to get an aggregator
     */
    private function findAggregatorBy($property)
    {
        $existingAggregator = $this->findExistingAggregatorBy($property);
        if (!is_null($existingAggregator)) {
            return $existingAggregator;
        }
        
        $newAggregator = $this->buildAggregatorFor($property);
        $this->aggregatorsOfSubjectsWithCommonProperties[] = $newAggregator;
        return $newAggregator;
    }
    
    /**
     * @param mixed $subject
     * @return mixed
     * @throws UnableToAggregate
     */
    private function readPropertyOf($subject)
    {
        try {
            return $this->propertyReader->readPropertyOf($subject);
        } catch (UnableToReadProperty $e) {
            throw new UnableToAggregate("unable to read property of " . get_class($subject), 0, $e);
        }
    }

    /**
     * @param ComparableProperty $property
     * @return Aggregator|null 
     */
    private function findExistingAggregatorBy(ComparableProperty $property)
    {
        foreach ($this->aggregatorsOfSubjectsWithCommonProperties as $aggregator) {
            if ($aggregator->getCommonProperty()->isEqualTo($property)) {
                return $aggregator;
            }
        }

        return null;
    }
    
    /**
     * @param ComparableProperty $property
     * 
     * @return AggregatorOfSubjectsWithCommonProperties
     */
    private function buildAggregatorFor(ComparableProperty $property)
    {
        return new AggregatorOfSubjectsWithCommonProperties(
            $property, 
            clone $this->prototypeOfAggregatorOfEachGroup
        );
    }
}

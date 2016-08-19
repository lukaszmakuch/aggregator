<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\PropertyList;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;
use lukaszmakuch\Aggregator\SubjectProperty\ComparableProperty;
use lukaszmakuch\Aggregator\SubjectProperty\Exception\UnableToReadProperty;
use lukaszmakuch\Aggregator\SubjectProperty\PropertyReader;

/**
 * List of properties of aggregated subjects. Uses a PropertyReader to read them.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PropertyList implements Aggregator
{
    /**
     * @var ComparableProperty[]
     */
    private $readProperties = [];
    
    private $propertyReader;
    
    public function __construct(PropertyReader $r)
    {
        $this->propertyReader = $r;
    }
    
    public function __clone()
    {
    }

    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }

    public function aggregate($subject)
    {
        try {
            $this->readProperties[] = $this->propertyReader->readPropertyOf($subject);
        } catch (UnableToReadProperty $e) {
            throw new UnableToAggregate("unable to read property of " . get_class($subject));
        }
    }
    
    /**
     * @return ComparableProperty[]
     */
    public function getProperties()
    {
        return $this->readProperties;
    }
    
    /**
     * @return PropertyReader
     */
    public function getPropertyReader()
    {
        return $this->propertyReader;
    }
}

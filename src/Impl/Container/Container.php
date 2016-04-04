<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Container;

use lukaszmakuch\Aggregator\Aggregator;

/**
 * Holds many actual aggregators and passes to them all what's passed to it.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Container implements Aggregator
{
    /**
     * @var Aggregator[]
     */
    private $actualAggregators = [];
    
    /**
     * Adds a new aggregator to the container.
     * 
     * @param Aggregator $aggregator
     * @return \lukaszmakuch\Aggregator\Impl\Filter\Container self
     */
    public function add(Aggregator $aggregator)
    {
        $this->actualAggregators[] = $aggregator;
        return $this;
    }
    
    public function aggregate($subject)
    {
        foreach ($this->actualAggregators as $aggregator) {
            $aggregator->aggregate($subject);
        }
    }
    
    /**
     * @return Aggregator[]
     */
    public function getAllActualAggregators()
    {
        return $this->actualAggregators;
    }
}

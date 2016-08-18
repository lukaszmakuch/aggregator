<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\HierarchicalAggregator;

use lukaszmakuch\Aggregator\Aggregator;

/**
 * Clones the given prototype of aggregator for every node.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CloningFactory implements NodeAggregatorFactory
{
    private $prototype;
    
    public function __construct(Aggregator $aggregatorPrototype)
    {
        $this->prototype = $aggregatorPrototype;
    }
    
    public function buildAggregatorFor(Node $n)
    {
        return clone $this->prototype;
    }
}

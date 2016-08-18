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
 * Builds aggregators for each level of a hierarchy.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface NodeAggregatorFactory
{
    /**
     * @param Node $n
     * @return Aggregator used to aggregate subjects for this node
     */
    public function buildAggregatorFor(Node $n);
}

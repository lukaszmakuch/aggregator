<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\HierarchicalAggregator;

/**
 * Reads the name of a node the subjects belongs to.
 *
 * @author Łukasz Makuch <lukasz.makuch@librus.pl>
 */
interface NodeReader
{
    /**
     * @param mixed $subject anything
     * @return String node name
     */
    public function readNodeOf($subject);
}

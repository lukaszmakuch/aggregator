<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

/**
 * Reads a cat's color as it's node value.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ColorReader implements \lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\NodeReader
{
    public function readNodeOf($subject)
    {
        if (!($subject instanceof Cat)) {
            return "";
        }
        
        return $subject->getColor();
    }
}

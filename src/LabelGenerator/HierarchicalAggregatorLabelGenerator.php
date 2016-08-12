<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Generates labels for hierarchical aggregators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class HierarchicalAggregatorLabelGenerator extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return HierarchicalAggregator::class;
    }

    protected function getTextBasedOnObject($object)
    {
        return "hierarchy";
    }
}

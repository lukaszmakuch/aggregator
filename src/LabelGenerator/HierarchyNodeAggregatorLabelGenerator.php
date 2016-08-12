<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\NodeAggregator;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Generates labels for nodes of some hierarchy.
 *
 * @author Łukasz Makuch <lukasz.makuch@librus.pl>
 */
class HierarchyNodeAggregatorLabelGenerator extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return NodeAggregator::class;
    }
    
    protected function getTextBasedOnObject($object)
    {
        /* @var $object NodeAggregator */
        return "node:" . $object->getNodeName();
    }
}

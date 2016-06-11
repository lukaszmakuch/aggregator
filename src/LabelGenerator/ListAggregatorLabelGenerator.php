<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Generates labels for list aggregators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ListAggregatorLabelGenerator extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return ListAggregator::class;
    }

    protected function getTextBasedOnObject($object)
    {
        return "list";
    }
}

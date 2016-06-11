<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Generates labels for counters.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CounterLabelGenerator extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return Counter::class;
    }

    protected function getTextBasedOnObject($object)
    {
        return "count";
    }
}

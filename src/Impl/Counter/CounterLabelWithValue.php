<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Counter;

use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Produces label like "(3)".
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CounterLabelWithValue extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return Counter::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object Counter */
        return "(" . $object->getNumberOfAggregatedSubjects() . ")";
    }
}

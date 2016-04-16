<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

use lukaszmakuch\Aggregator\Cat\Age;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Renders age as a string.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AgeToTextConverter extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return Age::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object Age */
        return "age " . $object->years;
    }
}

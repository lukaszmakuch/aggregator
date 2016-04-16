<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Test subject.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class OlderThanRenderer extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return OlderThan::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object OlderThan */
        return "older than " . $object->mustBeOlderThan;
    }
}

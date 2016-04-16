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
 * Reads a cat's name.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class NameReader extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return Cat::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object Cat */
        return $object->getName();
    }
}

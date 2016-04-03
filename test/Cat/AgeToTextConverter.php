<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

/**
 * Renders age as a string.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AgeToTextConverter extends \lukaszmakuch\Aggregator\TextGenerator\ObjectToTextConverter
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
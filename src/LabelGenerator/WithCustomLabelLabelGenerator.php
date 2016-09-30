<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Generates labels aggregators with custom label generators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class WithCustomLabelLabelGenerator extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return WithCustomLabel::class;
    }

    protected function getTextBasedOnObject($object)
    {
        return $object->getLabel();
    }
}

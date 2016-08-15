<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\Counter\MoreThan;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Generates labels for "more than" predicate aggregators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MoreThanLabelGenerator extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return MoreThan::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object MoreThan */
        return "more subjects than " . $object->getThreshold();
    }
}

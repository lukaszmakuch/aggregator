<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\Limit\Limit;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Generates simple labels for limits.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LimitLabelGenerator extends ObjectToTextConverter
{
    protected function getClassOfSupportedObjects()
    {
        return Limit::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object Limit */
        return sprintf(
            "%s:%s",
            $object->getStartingIndexFrom0(),
            $object->getMaxNumberOfSubjects()
        );
    }
}

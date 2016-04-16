<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\Filter\Filter;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Generates labels for filters.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FilterLabelGenerator extends ObjectToTextConverter
{
    private $requirementToTextConverter;

    public function __construct(TextGenerator $requirementToTextConverter)
    {
        $this->requirementToTextConverter = $requirementToTextConverter;
    }

    protected function getClassOfSupportedObjects()
    {
        return Filter::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object Filter */
        return $this->requirementToTextConverter->getTextBasedOn($object->getRequirement());
    }
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\Aggregator\TextGenerator\ObjectToTextConverter;
use lukaszmakuch\Aggregator\TextGenerator\TextGenerator;

/**
 * Provides a textual representation a comparable property.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AggregatorOfSubjectsWithCommonPropertiesLabelGenerator extends ObjectToTextConverter
{
    private $propertyToTextConverter;
    
    public function __construct(TextGenerator $propertyToTextConverter)
    {
        $this->propertyToTextConverter = $propertyToTextConverter;
    }
    
    protected function getClassOfSupportedObjects()
    {
        return AggregatorOfSubjectsWithCommonProperties::class;
    }

    protected function getTextBasedOnObject($object)
    {
        return $this->propertyToTextConverter->getTextBasedOn($object->getCommonProperty());
    }
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Provides a textual representation a comparable property.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AggregatorOfSubjectsWithCommonPropertiesLabelGenerator extends ObjectToTextConverter implements
    PropertyToTextConverterUser
{
    private $propertyToTextConverter;
    
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        $this->propertyToTextConverter = NULLTextGenerator::getInstance();
    }

    public function setPropertyToTextConverter(TextGenerator $converter)
    {
        $this->propertyToTextConverter = $converter;
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

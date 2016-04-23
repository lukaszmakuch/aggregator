<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Generates labels for grouping aggregators.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class GroupingAggregatorLabelGenerator 
    extends ObjectToTextConverter 
    implements PropertyReaderToTextConverterUser
{
    
    private $propertyReaderToTextConverter;

    public function __construct()
    {
        $this->propertyReaderToTextConverter = NULLTextGenerator::getInstance();
    }

    public function setPropertyReaderToTextConverter(TextGenerator $converter) 
    {
        $this->propertyReaderToTextConverter = $converter;
    }
    
    protected function getClassOfSupportedObjects()
    {
        return GroupingAggregator::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object GroupingAggregator */
        return "grouped by " . $this->propertyReaderToTextConverter->getTextBasedOn($object->getPropertyReader());
    }



}

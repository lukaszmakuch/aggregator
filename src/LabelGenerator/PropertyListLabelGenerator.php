<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\PropertyList\PropertyList;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Generates labels for grouping aggregators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PropertyListLabelGenerator extends ObjectToTextConverter implements PropertyReaderToTextConverterUser
{
    /**
     * @var TextGenerator
     */
    private $propertyReaderToTextConverter;
    
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        $this->propertyReaderToTextConverter = NULLTextGenerator::getInstance();
    }
    
    protected function getClassOfSupportedObjects()
    {
        return PropertyList::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object PropertyList */
        return $this->propertyReaderToTextConverter->getTextBasedOn($object->getPropertyReader());
    }

    public function setPropertyReaderToTextConverter(TextGenerator $converter)
    {
        $this->propertyReaderToTextConverter = $converter;
    }
}

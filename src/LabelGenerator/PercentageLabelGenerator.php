<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\Percentage\Percentage;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Generates labels for percentage values.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PercentageLabelGenerator extends ObjectToTextConverter implements RequirementToTextConverterUser
{
    /**
     * @var TextGenerator
     */
    private $requirementAsText;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        $this->requirementAsText = NULLTextGenerator::getInstance();
    }
    
    public function setRequirementToTextConverter(TextGenerator $converter)
    {
        $this->requirementAsText = $converter;
    }
    
    protected function getClassOfSupportedObjects()
    {
        return Percentage::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object Percentage */
        return sprintf(
            "(%s)/(%s)",
            $this->requirementAsText->getTextBasedOn($object->getNumeratorRequirement()),
            $this->requirementAsText->getTextBasedOn($object->getDenominatorRequirement())
        );
    }
}

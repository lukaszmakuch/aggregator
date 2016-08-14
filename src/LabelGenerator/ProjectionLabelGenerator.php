<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Impl\Projection\ProjectionAggregator;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Generates labels for projections.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ProjectionLabelGenerator extends ObjectToTextConverter implements SubjectProjectorToTextConverterUser
{
    /**
     * @var TextGenerator
     */
    private $projectorToText;
    
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        $this->projectorToText = NULLTextGenerator::getInstance();
    }
    
    protected function getClassOfSupportedObjects()
    {
        return ProjectionAggregator::class;
    }

    protected function getTextBasedOnObject($object)
    {
        /* @var $object ProjectionAggregator */
        return $this->projectorToText->getTextBasedOn($object->getUsedProjector());
    }

    public function setSubjectProjectorToTextConverter(TextGenerator $converter)
    {
        $this->projectorToText = $converter;
    }
}

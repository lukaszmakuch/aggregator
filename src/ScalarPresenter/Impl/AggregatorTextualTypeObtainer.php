<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\Aggregator\TextGenerator\ObjectToTextConverter;
use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;

/**
 * Holds a map of textual representations of aggregators. 
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AggregatorTextualTypeObtainer extends ObjectToTextConverter
{
    private $textualTypesOfAggregators;
    
    public function __construct()
    {
        $this->textualTypesOfAggregators = new ClassBasedRegistry();
    }
    
    public function addNameFor($aggregatorClass, $itsName)
    {
        $this->textualTypesOfAggregators->associateValueWithClasses($itsName, [$aggregatorClass]);
    }

    protected function getClassOfSupportedObjects()
    {
        return Aggregator::class;
    }

    protected function getTextBasedOnObject($object)
    {
        try {
            return $this->textualTypesOfAggregators->fetchValueByObjects([$object]);
        } catch (ValueNotFound $e) {
            throw new UnableToGetText(get_class($object) . " not found within map", 0, $e);
        }
    }
}

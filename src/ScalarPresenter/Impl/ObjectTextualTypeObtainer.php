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
 * Holds a map of textual representations of classes. 
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ObjectTextualTypeObtainer extends ObjectToTextConverter
{
    private $textualTypesOfObjects;
    private $classOfSupportedObjects;
    
    /**
     * @param String $classOfSupportedObjects
     */
    public function __construct($classOfSupportedObjects)
    {
        $this->classOfSupportedObjects = $classOfSupportedObjects;
        $this->textualTypesOfObjects = new ClassBasedRegistry();
    }
    
    public function addNameFor($class, $itsName)
    {
        $this->textualTypesOfObjects->associateValueWithClasses($itsName, [$class]);
    }

    protected function getClassOfSupportedObjects()
    {
        return $this->classOfSupportedObjects;
    }

    protected function getTextBasedOnObject($object)
    {
        try {
            return $this->textualTypesOfObjects->fetchValueByObjects([$object]);
        } catch (ValueNotFound $e) {
            throw new UnableToGetText(get_class($object) . " not found within map", 0, $e);
        }
    }
}

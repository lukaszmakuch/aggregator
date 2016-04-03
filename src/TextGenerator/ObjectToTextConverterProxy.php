<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\TextGenerator;

use lukaszmakuch\Aggregator\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\Aggregator\TextGenerator\TextGenerator;
use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;

/**
 * Hides any number of actual implementations under a common interface.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ObjectToTextConverterProxy implements TextGenerator
{
    private $actualGenerators;
    
    public function __construct()
    {
        $this->actualGenerators = new ClassBasedRegistry();
    }
    
    public function registerActualGenerator(
        $classOfSupportedObjects, 
        TextGenerator $actualGenerator
    ) {
        $this->actualGenerators->associateValueWithClasses(
            $actualGenerator, 
            [$classOfSupportedObjects]
        );
    } 
    
    public function getTextBasedOn($something)
    {
        try {
            /* @var $actualGenerator TextGenerator */
            $actualGenerator = $this->actualGenerators->fetchValueByObjects([$something]);
            return $actualGenerator->getTextBasedOn($something);
        } catch (ValueNotFound $e) {
            throw new UnableToGetText("no suitable generator found");
        }
    }
}

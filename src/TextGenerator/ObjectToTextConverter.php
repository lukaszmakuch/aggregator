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

/**
 * Ensures that the given input is an object of the supported type.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class ObjectToTextConverter implements TextGenerator
{
    public function getTextBasedOn($something)
    {
        $this->throwExceptionIfUnsupported($something);
        return $this->getTextBasedOnObject($something);
    }
    
    /**
     * @return String class of supported objects
     */
    protected abstract function getClassOfSupportedObjects();
    
    /**
     * @param mixed $object object to translate
     * @return String
     * @throws UnableToGetText
     */
    protected abstract function getTextBasedOnObject($object);

    /**
     * @param mixed $input probably a supported object
     * @throws UnableToGetText
     */
    private function throwExceptionIfUnsupported($input)
    {
        if (!is_object($input)) { 
            throw new UnableToGenerateLabel(sprintf(
                "%s expected an object, but %s was given",
                __CLASS__,
                gettype($input)
            ));
        }

        $supportedClass = $this->getClassOfSupportedObjects();
        if (!($input instanceof $supportedClass)) {
            throw new UnableToGetText(sprintf(
                "%s expected %s, but %s was given",
                __CLASS__,
                $supportedClass,
                get_class($input)
            ));
        }
    }
}

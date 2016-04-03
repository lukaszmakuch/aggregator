<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\TextGenerator;

use lukaszmakuch\Aggregator\TextGenerator\TextGenerator;

/**
 * Hides some implementation under the hood.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class TextGeneratorProxy implements TextGenerator
{
    /**
     * @var TextGenerator|null 
     */
    private $actualGenerator = null;
    
    public function setActualGenerator(TextGenerator $generator)
    {
        $this->actualGenerator = $generator;
    }
    
    public function getTextBasedOn($something)
    {
        if (is_null($this->actualGenerator)) {
            throw new Exception\UnableToGetText("trying to use an empty proxy");
        }
        
        return $this->actualGenerator->getTextBasedOn($something);
    }
}

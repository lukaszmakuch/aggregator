<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\TextGenerator;

use lukaszmakuch\Aggregator\TextGenerator\Exception\UnableToGetText;

/**
 * Converts some given input into a single string.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface TextGenerator
{
    /**
     * @param mixed $something
     * @return String
     * @throws UnableToGetText
     */
    public function getTextBasedOn($something);
}
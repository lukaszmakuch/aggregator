<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

use lukaszmakuch\Aggregator\SubjectProperty\Exception\UnableToReadProperty;
use lukaszmakuch\Aggregator\SubjectProperty\PropertyReader;

/**
 * Reads age of a cat.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AgeReader implements PropertyReader
{
    public function readPropertyOf($subject)
    {
        if (!($subject instanceof Cat)) {
            throw new UnableToReadProperty();
        }
        
        return new Age($subject->getAge());
    }
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

class NameLetterByLetter implements \lukaszmakuch\Aggregator\SubjectProjector\SubjectProjector
{
    public function getDifferentRepresentationOf($subject)
    {
        /* @var $subject Cat */
        return new Cat([
            'age' => $subject->getAge(),
            'color' => $subject->getColor(),
            'name' => join('-', str_split($subject->getName())),
        ]);
    }
}

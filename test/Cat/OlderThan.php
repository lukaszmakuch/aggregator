<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

/**
 * Test subject.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class OlderThan implements \lukaszmakuch\Aggregator\SubjectRequirement\SubjectRequirement
{
    /**
     * @var Age
     */
    public $mustBeOlderThan;
    
    public function __construct(Age $mustBeOlderThan)
    {
        $this->mustBeOlderThan = $mustBeOlderThan;
    }
    
    public function isMetFor($subject)
    {
        /* @var $subject Cat */
        return $subject->getAge()->years > $this->mustBeOlderThan->years;
    }
}

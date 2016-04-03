<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\SubjectRequirement;

/**
 * Checks whether a subjects meets some requirement. 
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface SubjectRequirement
{
    /**
     * @param mixed $subject
     * 
     * @return boolean true if the given subjects meets this requirement
     * 
     */
    public function isMetFor($subject);
}

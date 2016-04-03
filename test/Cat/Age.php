<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

/**
 * Age of a cat.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Age
{
    /**
     * @var int
     */
    public $years;
    
    public function __construct($years)
    {
        $this->years = $years;
    }
}

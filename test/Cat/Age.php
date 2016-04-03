<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

use lukaszmakuch\Aggregator\Impl\GroupingAggregator\ComparableProperty;

/**
 * Represents comparable age of a cat.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Age implements ComparableProperty
{
    /**
     * @var int
     */
    public $years;
    
    public function __construct($years)
    {
        $this->years = $years;
    }
    
    public function isEqualTo(ComparableProperty $anotherComparableProperty)
    {
        if (!($anotherComparableProperty instanceof Age)) {
            return false;
        }
        
        return $this->years == $anotherComparableProperty->years;
    }
}

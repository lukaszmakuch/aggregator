<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Percentage;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;
use lukaszmakuch\Aggregator\SubjectRequirement\SubjectRequirement;

/**
 * Calculates percentage.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Percentage implements Aggregator
{
    private $numeratorRequirement;
    private $denominatorRequirement;
    
    /**
     * @var int
     */
    private $numerator = 0;
    
    /**
     * @var int
     */
    private $denominator = 0;
    
    public function __construct(
        SubjectRequirement $numeratorRequirement,
        SubjectRequirement $denominatorRequirement
    ) {
        $this->numeratorRequirement = $numeratorRequirement;
        $this->denominatorRequirement = $denominatorRequirement;
    }
    
    public function aggregate($subject)
    {
        $this->numerator += (int)$this->numeratorRequirement->isMetFor($subject);
        $this->denominator += (int)$this->denominatorRequirement->isMetFor($subject);
    }
    
    /**
     * @return float like 35.23 for 35.23%
     */
    public function getAsFloat()
    {
        if ($this->denominator == 0) {
            return 0;
        }
        
        return ($this->numerator / $this->denominator) * 100;
    }
    
    /**
     * @return SubjectRequirement
     */
    public function getNumeratorRequirement()
    {
        return $this->numeratorRequirement;
    }
    
    /**
     * @return SubjectRequirement
     */
    public function getDenominatorRequirement()
    {
        return $this->denominatorRequirement;
    }
    
    public function __clone()
    {
        
    }

    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }
}

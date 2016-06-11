<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;

/**
 * Always returns the value set in the constructor.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PresenterReturningStaticValue implements ScalarPresenter
{
    private $returnedValue;
    
    /**
     * @param mixed $returnedValue the value that is going to be returned
     * every time the user asks for a scalar representation
     */
    public function __construct($returnedValue)
    {
        $this->returnedValue = $returnedValue;
    }
    
    public function convertToScalar(Aggregator $aggregator)
    {
        return $this->returnedValue;
    }
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToPresent;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToPresentByVisitor;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;

/**
 * Gets a scalar representation of an aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PresentingVisitor implements AggregatorVisitor
{
    private $scalarPresenter;
    
    public function __construct(ScalarPresenter $scalarPresenter)
    {
        $this->scalarPresenter = $scalarPresenter;
    }
    
    /**
     * @param Aggregator $a
     * @return mixed a scalar value or an array of scalar values
     * @throws UnableToPresentByVisitor
     */
    public function visit(Aggregator $a)
    {
        try {
            return $this->scalarPresenter->convertToScalar($a);
        } catch (UnableToPresent $e) {
            throw new UnableToPresentByVisitor();
        }
    }
}

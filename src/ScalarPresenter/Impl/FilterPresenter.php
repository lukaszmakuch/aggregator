<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;

/**
 * Reads values of filters.
 * 
 * Example output:
 * <pre>
 *     representation of actual aggregator hidden behind a filter
 * </pre>
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FilterPresenter extends ScalarPresenterTpl
{
    private $presenterOfActualAggregators;
    
    public function __construct(ScalarPresenter $presenterOfActualAggregators)
    {
        $this->presenterOfActualAggregators = $presenterOfActualAggregators;
    }
    
    protected function getSupportedAggregatorClass()
    {
        return Filter::class;
    }

    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator Filter */
        return $this->presenterOfActualAggregators->convertToScalar($aggregator->getActualAggregator());
    }
}

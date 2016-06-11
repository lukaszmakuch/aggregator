<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;

/**
 * Reads values of counters.
 *
 * Example output:
 * <pre>
 *     4
 * </pre>
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CounterPresenter extends ScalarPresenterTpl
{
    protected function getSupportedAggregatorClass()
    {
        return Counter::class;
    }
    
    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator Counter */
        return $aggregator->getNumberOfAggregatedSubjects();
    }
}

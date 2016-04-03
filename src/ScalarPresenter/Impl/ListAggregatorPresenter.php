<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;

/**
 * Reads values of list aggregators.
 * 
 * Example output:
 * <pre>
 *     Bob, Tom
 * </pre>
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ListAggregatorPresenter extends ScalarPresenterTpl
{
    protected function getSupportedAggregatorClass()
    {
        return ListAggregator::class;
    }
    
    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator ListAggregator */
        return $aggregator->getListAsString();
    }
}

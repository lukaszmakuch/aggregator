<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;

/**
 * Presents grouping aggregators.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class GroupingAggregatorPresenter extends ScalarPresenterTpl implements \lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser
{
    private $presenterOfActualAggregators;
    
    public function __construct()
    {
        $this->presenterOfActualAggregators = new PresenterReturningStaticValue("");
    }

    public function setScalarPresenter(ScalarPresenter $presenter)
    {
        $this->presenterOfActualAggregators = $presenter;
    }

    protected function getSupportedAggregatorClass()
    {
        return GroupingAggregator::class;
    }

    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator GroupingAggregator */
        return array_map(function (AggregatorOfSubjectsWithCommonProperties $subAggregator) {
            return $this->presenterOfActualAggregators->convertToScalar($subAggregator);
        }, $aggregator->getAggregatorsOfSubjectsWithCommonProperties());
    }
}

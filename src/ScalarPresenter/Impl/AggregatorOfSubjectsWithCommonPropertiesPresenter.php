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
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;

/**
 * Presents aggregators of subjects with common properties.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AggregatorOfSubjectsWithCommonPropertiesPresenter 
    extends ScalarPresenterTpl 
    implements \lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser
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
        return AggregatorOfSubjectsWithCommonProperties::class;
    }

    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator AggregatorOfSubjectsWithCommonProperties */
        return $this->presenterOfActualAggregators->convertToScalar($aggregator->getActualAggregator());
    }
}

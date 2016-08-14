<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Projection\ProjectionAggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;

/**
 * Presents projections.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ProjectionPresenter extends ScalarPresenterTpl implements ScalarPresenterUser
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
        return ProjectionAggregator::class;
    }
    
    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator ProjectionAggregator */
        return $this->presenterOfActualAggregators->convertToScalar($aggregator->getActualAggregator());
    }
}

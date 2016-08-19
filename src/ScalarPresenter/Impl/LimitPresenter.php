<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Limit\Limit;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;

/**
 * Reads limited aggregator.
 *
 * Example output:
 * <pre>
 *     representation of actual aggregator which input is limited
 * </pre>
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LimitPresenter extends ScalarPresenterTpl implements ScalarPresenterUser
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
        return Limit::class;
    }

    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator Limit */
        return $this->presenterOfActualAggregators->convertToScalar($aggregator->getActualAggregator());
    }
}

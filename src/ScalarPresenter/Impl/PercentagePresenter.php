<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Percentage\Percentage;

/**
 * Reads percentage values.
 *
 * Example output for 33.(3)%:
 * <pre>
 *     33
 * </pre>
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PercentagePresenter extends ScalarPresenterTpl
{
    protected function getSupportedAggregatorClass()
    {
        return Percentage::class;
    }
    
    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator Percentage */
        return number_format($aggregator->getAsFloat(), 0);
    }
}

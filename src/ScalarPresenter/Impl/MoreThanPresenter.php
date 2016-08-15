<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Counter\MoreThan;

/**
 * Presents "more than" predicate.
 * 
 * Example output:
 * <pre>
 *     true
 * </pre>
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MoreThanPresenter extends ScalarPresenterTpl
{
    protected function getSupportedAggregatorClass()
    {
        return MoreThan::class;
    }

    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator MoreThan */
        return $aggregator->isTrue();
    }
}

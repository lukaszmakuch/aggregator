<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;

/**
 * Generates labels for counters.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CounterLabelGenerator extends LabelGeneratorTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return Counter::class;
    }

    protected function getLabelForImpl(Aggregator $aggregator)
    {
        return "count";
    }
}

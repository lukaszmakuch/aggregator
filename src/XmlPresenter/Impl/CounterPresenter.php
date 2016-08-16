<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use DOMElement;
use DOMText;
use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;

/**
 * Renders counters.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CounterPresenter extends PresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return Counter::class;
    }

    protected function getRootTagName()
    {
        return "counter";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a Counter */
        $destination->appendChild(new DOMText($a->getNumberOfAggregatedSubjects()));
    }
}

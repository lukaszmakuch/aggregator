<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use DOMElement;
use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;

/**
 * Renders grouping aggregators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class GroupPresenter extends PresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return GroupingAggregator::class;
    }

    protected function getRootTagName()
    {
        return "group";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        $this->setLabelAttribute($destination, $a);
        /* @var $a GroupingAggregator */
        foreach ($a->getAggregatorsOfSubjectsWithCommonProperties() as $aggregatorToAdd) {
            $destination->appendChild($this->getDOMNodeOf($aggregatorToAdd, $destination));
        }
    }
}

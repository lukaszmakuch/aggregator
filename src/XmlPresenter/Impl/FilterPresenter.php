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
use lukaszmakuch\Aggregator\Impl\Filter\Filter;

/**
 * Renders filters.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FilterPresenter extends PresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return Filter::class;
    }

    protected function getRootTagName()
    {
        return "filter";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a Filter */
        $this->setLabelAttribute($destination, $a);
        $destination->appendChild($this->getDOMNodeOf($a->getActualAggregator(), $destination));
    }
}

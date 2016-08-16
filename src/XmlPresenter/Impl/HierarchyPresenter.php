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
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator;

/**
 * Renders hierarchies.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class HierarchyPresenter extends PresenterUsingPresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return HierarchicalAggregator::class;
    }

    protected function getRootTagName()
    {
        return "hierarchy";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        $destination->appendChild($this->getDOMNodeOf($a->getAggregatorOfNodes(), $destination));
    }
}

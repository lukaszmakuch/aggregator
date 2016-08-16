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
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;

/**
 * Renders lists.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ListPresenter extends PresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return ListAggregator::class;
    }

    protected function getRootTagName()
    {
        return "list";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a ListAggregator */
        $destination->appendChild(new DOMText($a->getListAsString()));
    }
}

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
use lukaszmakuch\Aggregator\Impl\Limit\Limit;

/**
 * Renders limits.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LimitPresenter extends PresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return Limit::class;
    }

    protected function getRootTagName()
    {
        return "limit";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a Limit */
        $this->setLabelAttribute($destination, $a);
        $destination->appendChild($this->getDOMNodeOf($a->getActualAggregator(), $destination));
    }
}

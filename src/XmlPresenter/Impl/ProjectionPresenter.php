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
use lukaszmakuch\Aggregator\Impl\Projection\ProjectionAggregator;

/**
 * Renders projections.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ProjectionPresenter extends PresenterUsingPresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return ProjectionAggregator::class;
    }

    protected function getRootTagName()
    {
        return "projection";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a ProjectionAggregator */
        $destination->appendChild($this->getDOMNodeOf($a->getActualAggregator(), $destination));
    }
}

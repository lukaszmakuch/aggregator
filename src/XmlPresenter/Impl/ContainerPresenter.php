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
use lukaszmakuch\Aggregator\Impl\Container\Container;

/**
 * Renders containers.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ContainerPresenter extends PresenterUsingPresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return Container::class;
    }

    protected function getRootTagName()
    {
        return "container";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a Container */
        foreach ($a->getAllActualAggregators() as $aggregatorToAdd) {
            $destination->appendChild($this->getDOMNodeOf($aggregatorToAdd, $destination));
        }
    }
}

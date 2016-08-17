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
use \lukaszmakuch\Aggregator\Impl\Percentage\Percentage;

/**
 * Renders percentage.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PercentagePresenter extends PresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return Percentage::class;
    }

    protected function getRootTagName()
    {
        return "percentage";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a Percentage */
        $this->setLabelAttribute($destination, $a);
        $destination->appendChild(new DOMText(number_format($a->getAsFloat(), 0)));
    }
}

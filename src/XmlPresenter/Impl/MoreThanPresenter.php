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
use lukaszmakuch\Aggregator\Impl\Counter\MoreThan;

/**
 * Renders "more than" predicates.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MoreThanPresenter extends PresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return MoreThan::class;
    }

    protected function getRootTagName()
    {
        return "more_than_predicate";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a MoreThan */
        $destination->appendChild(new DOMText((int)$a->isTrue()));
    }
}

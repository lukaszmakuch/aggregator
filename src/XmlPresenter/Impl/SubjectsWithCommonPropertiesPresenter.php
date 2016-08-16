<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use DOMElement;

/**
 * Renders subjects with common properties.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class SubjectsWithCommonPropertiesPresenter extends PresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return AggregatorOfSubjectsWithCommonProperties::class;
    }

    protected function getRootTagName()
    {
        return "subjects_with_common_properties";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a AggregatorOfSubjectsWithCommonProperties */
        $this->setLabelAttribute($destination, $a);
        $destination->appendChild($this->getDOMNodeOf($a->getActualAggregator(), $destination));
    }
}

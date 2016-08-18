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

/**
 * Presents predicates with labels.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LabelingPredicatePresenter extends PredicatePresenter
{
    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        parent::putDataHeldByAggregator($a, $destination);
        $this->setLabelAttribute($destination, $a);
    }
}

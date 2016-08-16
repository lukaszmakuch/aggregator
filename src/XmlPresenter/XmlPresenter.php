<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;
use lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml;

/**
 * Returns an xml representation of an aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface XmlPresenter extends AggregatorVisitor
{
    /**
     * @param Aggregator $a
     * @return String xml representation
     * @throws UnableToCreateXml
     */
    public function visit(Aggregator $a);
}

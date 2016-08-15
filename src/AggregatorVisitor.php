<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Exception\UnableToVisit;

/**
 * Visits some aggregator and returns result of this visitation.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface AggregatorVisitor
{
    /**
     * @param Aggregator $a
     * @return mixed
     * @throws Exception\VisitorException
     */
    public function visit(Aggregator $a);
}

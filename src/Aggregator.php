<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Exception\UnableToAggregate;
use lukaszmakuch\Aggregator\Exception\VisitorException;

/**
 * Takes a subject into account what causes modification of the aggregator's state.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface Aggregator
{
    /**
     * @param mixed $subject a subject to aggregate
     *
     * @return null
     * @throws UnableToAggregate
     */
    public function aggregate($subject);
    
    /**
     * @param AggregatorVisitor $v
     * @return mixed what was returned by the visit method of the given aggregator
     * @throws VisitorException
     */
    public function accept(AggregatorVisitor $v);
    
    public function __clone();
}

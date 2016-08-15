<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\ConditionalAggregator;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;
use lukaszmakuch\Aggregator\PredicateAggregator;

/**
 * Equals one of two given aggregators, depending on the given PredicateAggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ConditionalAggregator implements Aggregator
{
    /**
     * @var PredicateAggregator
     */
    private $if;
    
    /**
     * @var Aggregator
     */
    private $then;
    
    /**
     * @var Aggregator
     */
    private $else;
    
    /**
     * @param PredicateAggregator $if determines which aggregator should be used ("if" or "else")
     * @param Aggregator $then when the given PredicateAggregator evaluates to true
     * @param Aggregator $else when the given PredicateAggregator evaluates to false
     */
    public function __construct(
        PredicateAggregator $if,
        Aggregator $then,
        Aggregator $else
    ) {
        $this->if = $if;
        $this->then = $then;
        $this->else = $else;
    }
    
    public function __clone()
    {
        $this->if = clone $this->if;
        $this->then = clone $this->then;
        $this->else = clone $this->else;
    }
    
    public function aggregate($subject)
    {
        $this->if->aggregate($subject);
        $this->then->aggregate($subject);
        $this->else->aggregate($subject);
    }

    public function accept(AggregatorVisitor $v)
    {
        return $this->if->isTrue()
            ? $this->then->accept($v)
            : $this->else->accept($v);
    }
}

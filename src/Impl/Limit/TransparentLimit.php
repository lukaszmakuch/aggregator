<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\Limit;

use lukaszmakuch\Aggregator\AggregatorVisitor;

/**
 * Hides the fact it exists from its visitors.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class TransparentLimit extends Limit
{
    public function accept(AggregatorVisitor $v)
    {
        return $this->getActualAggregator()->accept($v);
    }
}

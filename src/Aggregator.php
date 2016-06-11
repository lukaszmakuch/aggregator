<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Exception\UnableToAggregate;

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
}

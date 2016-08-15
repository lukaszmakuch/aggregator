<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

/**
 * It evaluates to true or false, depending on subjects passed to it.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface PredicateAggregator extends Aggregator
{
    /**
     * @return boolean
     */
    public function isTrue();
}

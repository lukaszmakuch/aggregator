<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

/**
 * Tests the counter.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CounterTest extends AggregatorTest
{
    public function testZeroIfNothingPassed()
    {
        $this->assertAggregationResult("count", 0);
    }

    public function testCountingSubjects()
    {
        $this->aggregator->aggregate(new \stdClass());
        $this->aggregator->aggregate(new \stdClass());
        $this->aggregator->aggregate(new \stdClass());

        $this->assertAggregationResult("count", 3);
    }
}

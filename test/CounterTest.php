<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;

/**
 * Tests the counter.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CounterTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = new Counter();
    }
    
    public function testZeroIfNothingPassed()
    {
        $this->assertAggregationResult([
            'type' => 'counter',
            'label' => 'count',
            'data' => 0
        ]);
    }

    public function testCountingSubjects()
    {
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());

        $this->assertAggregationResult([
            'type' => 'counter',
            'label' => 'count',
            'data' => 3
        ]);
    }
    
    public function testPresentingAsXml()
    {
        $this->aggregator->aggregate(new Cat());
        
        $this->assertAggregationResultXml("
            <counter>1</counter>
        ");
    }
    
    public function testCloning()
    {
        $this->aggregator->aggregate(new Cat());
        $this->cloneAggregator();
        $this->aggregatorClone->aggregate(new Cat());
        
        $this->assertAggregationResult([
            'type' => 'counter',
            'label' => 'count',
            'data' => 1
        ]);
        
        $this->assertAggregationResultForClone([
            'type' => 'counter',
            'label' => 'count',
            'data' => 2
        ]);
    }
}

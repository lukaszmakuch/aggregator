<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\AggregatorTest;
use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Impl\Counter\MoreThan;

class MoreThanPredicateTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = new MoreThan(2);
    }
    
    public function testSwitchingFromFalseToTrue()
    {
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());
        
        $this->assertAggregationResult([
            'type' => 'more_than_predicate',
            'label' => 'more subjects than 2',
            'data' => false,
        ]);
        
        $this->aggregator->aggregate(new Cat());
        
        $this->assertAggregationResult([
            'type' => 'more_than_predicate',
            'label' => 'more subjects than 2',
            'data' => true,
        ]);
    }
    
    public function testCloning()
    {
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());
        
        $this->cloneAggregator();
        
        $this->aggregatorClone->aggregate(new Cat());
        
        $this->assertAggregationResult([
            'type' => 'more_than_predicate',
            'label' => 'more subjects than 2',
            'data' => false,
        ]);
        
        $this->assertAggregationResultForClone([
            'type' => 'more_than_predicate',
            'label' => 'more subjects than 2',
            'data' => true,
        ]);
    }
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\NameReader;
use lukaszmakuch\Aggregator\Impl\ConditionalAggregator\ConditionalAggregator;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\Impl\Counter\MoreThan;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\Cat\Cat;

class ConditionalAggregatorTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator =
            (new ConditionalAggregator(
                new MoreThan(2),
                new Counter(),
                new ListAggregator(new NameReader(), ", ")
            ))
            ;
    }
    
    public function testConditionalAggregation()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Tom']));
        $this->aggregator->aggregate(new Cat(['name' => 'Emma']));
        
        $this->assertAggregationResult([
            'type' => 'list',
            'label' => "list",
            'data' => "Tom, Emma",
        ]);
        
        $this->aggregator->aggregate(new Cat(['name' => 'Jim']));
        
        $this->assertAggregationResult([
            'type' => 'counter',
            'label' => "count",
            'data' => 3,
        ]);
    }
    
    public function testCloning()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Tom']));
        
        $this->cloneAggregator();
        
        $this->aggregator->aggregate(new Cat(['name' => 'Jim']));
        $this->aggregatorClone->aggregate(new Cat(['name' => 'Emma']));
        
        $this->assertAggregationResult([
            'type' => 'list',
            'label' => "list",
            'data' => "Tom, Jim",
        ]);
        
        $this->assertAggregationResultForClone([
            'type' => 'list',
            'label' => "list",
            'data' => "Tom, Emma",
        ]);
        
    }
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Cat\NameReader;
use lukaszmakuch\Aggregator\Impl\Limit\Limit;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;

/**
 * Tests the limit.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LimitTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator =
            new Limit(
                new ListAggregator(new NameReader(), ", "),
                2,
                3
            )
        ;
    }
    
    public function testLimiting()
    {
        //omitted because index 0 < 2
        $this->aggregator->aggregate(new Cat(['name' => "first"]));
        
        //omitted because index 1 < 2
        $this->aggregator->aggregate(new Cat(['name' => "second"]));
        
        //those 3 are taken into account
        $this->aggregator->aggregate(new Cat(['name' => "third"]));
        $this->aggregator->aggregate(new Cat(['name' => "fourth"]));
        $this->aggregator->aggregate(new Cat(['name' => "fifth"]));
        
        // ommited because it'd be the 4th onetaken into account and we want just 3
        $this->aggregator->aggregate(new Cat(['name' => "sixth"]));
        
        $this->assertAggregationResult([
            'type' => 'limit',
            'label' => '2:3',
            'data' => [
                'type' => 'list',
                'label' => 'list',
                'data' => 'third, fourth, fifth',
            ]
        ]);
    }
    
    public function testCloning()
    {
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat(['name' => "A"]));
        
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['name' => "B"]));
        $this->aggregatorClone->aggregate(new Cat(['name' => "C"]));
        
        $this->assertAggregationResult([
            'type' => 'limit',
            'label' => '2:3',
            'data' => [
                'type' => 'list',
                'label' => 'list',
                'data' => 'A, B',
            ]
        ]);
        $this->assertAggregationResultForClone([
            'type' => 'limit',
            'label' => '2:3',
            'data' => [
                'type' => 'list',
                'label' => 'list',
                'data' => 'A, C',
            ]
        ]);
    }
    
    public function testPresentingAsXml()
    {
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat(['name' => "Tim"]));
        
        $this->assertAggregationResultXml('
            <limit label="2:3">
                <list>Tim</list>
            </limit>
        ');
    }
}

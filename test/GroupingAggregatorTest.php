<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\AgeReader;
use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\Cat\NameReader;

/**
 * Tests grouping by some property.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class GroupingAggregatorTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = new GroupingAggregator(
            new AgeReader(),
            new ListAggregator(new NameReader(), ", ")
        );
    }
    
    public function testGrouping()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Henry', 'age' => 5]));
        $this->aggregator->aggregate(new Cat(['name' => 'Mruczek', 'age' => 2]));
        $this->aggregator->aggregate(new Cat(['name' => 'Meow', 'age' => 2]));
        $this->aggregator->aggregate(new Cat(['name' => 'Bob', 'age' => 2]));
        $this->aggregator->aggregate(new Cat(['name' => 'Tim', 'age' => 5]));
        
        $this->assertAggregationResult([
            'type' => 'group',
            'label' => "grouped by age",
            'data' => [
                [
                    'type' => 'subjects_with_common_properties',
                    'label' => 'age 5',
                    'data' => [
                        'type' => 'list',
                        'label' => 'list',
                        'data' => 'Henry, Tim'
                        
                    ]
                ],
                [
                    'type' => 'subjects_with_common_properties',
                    'label' => 'age 2',
                    'data' => [
                        'type' => 'list',
                        'label' => 'list',
                        'data' => 'Mruczek, Meow, Bob'
                        
                    ]
                ],
            ],
        ]);
    }
    
    public function testCloning()
    {
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['name' => 'Henry', 'age' => 5]));
        $this->aggregatorClone->aggregate(new Cat(['name' => 'Jim', 'age' => 1]));
        
        $this->assertAggregationResult([
            'type' => 'group',
            'label' => "grouped by age",
            'data' => [
                [
                    'type' => 'subjects_with_common_properties',
                    'label' => 'age 5',
                    'data' => [
                        'type' => 'list',
                        'label' => 'list',
                        'data' => 'Henry'
                        
                    ]
                ],
            ],
        ]);
        $this->assertAggregationResultForClone([
            'type' => 'group',
            'label' => "grouped by age",
            'data' => [
                [
                    'type' => 'subjects_with_common_properties',
                    'label' => 'age 1',
                    'data' => [
                        'type' => 'list',
                        'label' => 'list',
                        'data' => 'Jim'
                        
                    ]
                ],
            ],
        ]);
    }
}

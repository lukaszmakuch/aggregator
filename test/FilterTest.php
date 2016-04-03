<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\Age;
use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Cat\NameReader;
use lukaszmakuch\Aggregator\Cat\OlderThan;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;

/**
 * Tests the filter.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FilterTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = 
            new Filter(
                new OlderThan(new Age(3)),
                new ListAggregator(new NameReader(), ", ")
            )
        ;
    }

    public function testFilteringSubjects()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Tom', 'age' => new Age(5)]));
        $this->aggregator->aggregate(new Cat(['name' => 'Emma', 'age' => new Age(2)]));
        $this->aggregator->aggregate(new Cat(['name' => 'Michael', 'age' => new Age(4)]));
        
        $this->assertAggregationResult([
            'type' => 'filter',
            'label' => "older than 3",
            'data' => [
                "type" => "list", 
                "label" => "list", 
                "data" => "Tom, Michael",
            ],
        ]);
    }
}

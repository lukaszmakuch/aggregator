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
                new OlderThan(3),
                new ListAggregator(new NameReader(), ", ")
            )
        ;
    }

    public function testFilteringSubjects()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Tom', 'age' => 5]));
        $this->aggregator->aggregate(new Cat(['name' => 'Emma', 'age' => 2]));
        $this->aggregator->aggregate(new Cat(['name' => 'Michael', 'age' => 4]));

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
    
    public function testCloning()
    {
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['name' => 'Tom', 'age' => 5]));
        $this->aggregatorClone->aggregate(new Cat(['name' => 'Jim', 'age' => 5]));
        
        $this->assertAggregationResult([
            'type' => 'filter',
            'label' => "older than 3",
            'data' => [
                "type" => "list",
                "label" => "list",
                "data" => "Tom",
            ],
        ]);
        
        $this->assertAggregationResultForClone([
            'type' => 'filter',
            'label' => "older than 3",
            'data' => [
                "type" => "list",
                "label" => "list",
                "data" => "Jim",
            ],
        ]);
        
    }
    
    public function testPresentingAsXml()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Tom', 'age' => 5]));
        $this->assertAggregationResultXml("
            <filter label=\"older than 3\">
                <list>Tom</list>
            </filter>
        ");
    }
}

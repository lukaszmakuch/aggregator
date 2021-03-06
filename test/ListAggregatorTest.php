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
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;

/**
 * Tests the list aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ListAggregatorTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = new ListAggregator(
            new NameReader(),
            ", "
        );
    }
    
    public function testEmptyStringIfNothingPassed()
    {
        $this->assertAggregationResult([
            'type' => 'list',
            'label' => 'list',
            'data' => ''
        ]);
    }
    
    public function testListOfValues()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Bob']));
        $this->aggregator->aggregate(new Cat(['name' => 'Tom']));
        
        $this->assertAggregationResult([
            'type' => 'list',
            'label' => 'list',
            'data' => 'Bob, Tom'
        ]);
    }
    
    public function testCloning()
    {
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['name' => 'Bob']));
        $this->aggregatorClone->aggregate(new Cat(['name' => 'Tom']));
        
        $this->assertAggregationResult([
            'type' => 'list',
            'label' => 'list',
            'data' => 'Bob'
        ]);
        
        $this->assertAggregationResultForClone([
            'type' => 'list',
            'label' => 'list',
            'data' => 'Tom'
        ]);
    }
    
    public function testPresentingAsXml()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Tom']));
        $this->assertAggregationResultXml("
            <list>Tom</list>
        ");
    }
}

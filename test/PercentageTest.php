<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Cat\OlderThan;
use lukaszmakuch\Aggregator\Impl\Percentage\Percentage;

/**
 * Tests the percentage aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PercentageTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator =
            new Percentage(
                new OlderThan(3),
                new OlderThan(1)
            )
        ;
    }
    
    public function testNothingAggregated()
    {
        $this->assertAggregationResult([
            'type' => 'percentage',
            'label' => "(older than 3)/(older than 1)",
            'data' => "0",
        ]);
    }
    
    public function testNoDenominator()
    {
        $this->aggregator = new Percentage(
            new OlderThan(3),
            new OlderThan(999)
        );
        
        $this->aggregator->aggregate(new Cat(['age' => 5]));
        
        $this->assertAggregationResult([
            'type' => 'percentage',
            'label' => "(older than 3)/(older than 999)",
            'data' => "0",
        ]);
    }

    public function testCalculation()
    {
        $this->aggregator->aggregate(new Cat(['age' => 5]));
        $this->aggregator->aggregate(new Cat(['age' => 2]));
        $this->aggregator->aggregate(new Cat(['age' => 4]));

        $this->assertAggregationResult([
            'type' => 'percentage',
            'label' => "(older than 3)/(older than 1)",
            'data' => "67",
        ]);
    }
    
    public function testCloning()
    {
        $this->aggregator->aggregate(new Cat(['age' => 5]));
        $this->aggregator->aggregate(new Cat(['age' => 2]));
        $this->cloneAggregator();
        $this->aggregatorClone->aggregate(new Cat(['age' => 4]));
        
        $this->assertAggregationResult([
            'type' => 'percentage',
            'label' => "(older than 3)/(older than 1)",
            'data' => "50",
        ]);
        
        $this->assertAggregationResultForClone([
            'type' => 'percentage',
            'label' => "(older than 3)/(older than 1)",
            'data' => "67",
        ]);
        
    }
    
    public function testPresentingAsXml()
    {
        $this->aggregator->aggregate(new Cat(['age' => 5]));
        $this->aggregator->aggregate(new Cat(['age' => 2]));
        $this->aggregator->aggregate(new Cat(['age' => 4]));
        $this->assertAggregationResultXml('
            <percentage label="(older than 3)/(older than 1)">67</percentage>
        ');
    }
}

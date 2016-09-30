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
use lukaszmakuch\Aggregator\Impl\Counter\CounterLabelWithValue;
use lukaszmakuch\Aggregator\LabelGenerator\WithCustomLabel;

class CustomLabelTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = new WithCustomLabel(
            new Counter(),
            new CounterLabelWithValue()
        );
    }
    
    public function testCustomLabel()
    {
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());
        
        $this->assertAggregationResult([
            'type' => 'with_custom_label',
            'label' => '(3)',
            'data' => [
                'type' => 'counter',
                'label' => 'count',
                'data' => 3
            ]
        ]);
        $this->assertAggregationResultXml('
            <counter label="(3)">3</counter>
        ');
    }
    
    public function testCloning()
    {
        $this->aggregator->aggregate(new Cat());
        $this->cloneAggregator();
        $this->aggregatorClone->aggregate(new Cat());
        
        $this->assertAggregationResult([
            'type' => 'with_custom_label',
            'label' => '(1)',
            'data' => [
                'type' => 'counter',
                'label' => 'count',
                'data' => 1
            ]
        ]);
        
        $this->assertAggregationResultForClone([
            'type' => 'with_custom_label',
            'label' => '(2)',
            'data' => [
                'type' => 'counter',
                'label' => 'count',
                'data' => 2
            ]
        ]);
    }
}

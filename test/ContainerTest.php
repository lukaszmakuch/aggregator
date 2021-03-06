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
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\Impl\Container\Container;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;

/**
 * Tests the container.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ContainerTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator =
            (new Container())
                ->add(new ListAggregator(new NameReader(), ", "))
                ->add(new Counter())
        ;
    }

    public function testComposition()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Tom']));
        $this->aggregator->aggregate(new Cat(['name' => 'Emma']));
        $this->aggregator->aggregate(new Cat(['name' => 'Michael']));
        
        $this->assertAggregationResult([
            'type' => 'container',
            'label' => '',
            'data' => [
                [
                    "type" => "list",
                    "label" => "list",
                    "data" => "Tom, Emma, Michael",
                ],
                [
                    "type" => "counter",
                    "label" => "count",
                    "data" => 3,
                ],
            ],
        ]);
    }
    
    public function testClonning()
    {
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['name' => 'Tom']));
        $this->aggregator->aggregate(new Cat(['name' => 'Emma']));
        $this->aggregatorClone->aggregate(new Cat(['name' => 'The One Who Should Not Appear']));
        $this->assertAggregationResult([
            'type' => 'container',
            'label' => '',
            'data' => [
                [
                    "type" => "list",
                    "label" => "list",
                    "data" => "Tom, Emma",
                ],
                [
                    "type" => "counter",
                    "label" => "count",
                    "data" => 2,
                ],
            ],
        ]);
    }
    public function testPresentingAsXml()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Tom']));
        $this->assertAggregationResultXml("
            <container>
                <list>Tom</list>
                <counter>1</counter>
            </container>
        ");
    }
}

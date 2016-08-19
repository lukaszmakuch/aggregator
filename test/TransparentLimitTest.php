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
use lukaszmakuch\Aggregator\Impl\Limit\TransparentLimit;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;

/**
 * Tests the transparent limit.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class TransparentLimitTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator =
            new TransparentLimit(
                new ListAggregator(new NameReader(), ", "),
                2,
                3
            )
        ;
    }

    public function testTransparency()
    {
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->aggregate(new Cat(['name' => "Tim"]));
        
        $this->assertAggregationResult([
            'type' => 'list',
            'label' => 'list',
            'data' => 'Tim'
        ]);
    }
}

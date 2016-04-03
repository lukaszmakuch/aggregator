<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\LabelGenerator\Impl\CounterLabelGenerator;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\CounterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\LabelingPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use PHPUnit_Framework_TestCase;

/**
 * Contains common part of aggregators tests.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class AggregatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Aggregator
     */
    protected $aggregator;

    /**
     * @var ScalarPresenter
     */
    private $scalarPresenter;

    protected function setUp()
    {
        $labelGenerator = new CounterLabelGenerator();
        $this->aggregator = new Counter();
        $this->scalarPresenter = new LabelingPresenter(new CounterPresenter(), $labelGenerator);
    }

    /**
     * Asserts that the aggregation result as JSON matches the given array.
     * 
     * @param String $expectedLabel
     * @param mixed $expectedResultOfAggregation
     */
    protected function assertAggregationResult($expectedLabel, $expectedResultOfAggregation)
    {
        $this->assertSame(
            ['label' => $expectedLabel, 'data' => $expectedResultOfAggregation],
            $this->scalarPresenter->convertToScalar($this->aggregator)
        );
    }
}

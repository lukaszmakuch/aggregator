<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\OlderThanRenderer;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\LabelGenerator\CounterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\ListAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\CounterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\LabelingPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ListAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ScalarPresenterProxy;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\FilterPresenter;
use lukaszmakuch\Aggregator\TextGenerator\ObjectToTextConverterProxy;
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
        //build label generators
        $labelGenerator = new ObjectToTextConverterProxy();
        $labelGenerator->registerActualGenerator(
            Counter::class, 
            new CounterLabelGenerator()
        );
        $labelGenerator->registerActualGenerator(
            ListAggregator::class, 
            new ListAggregatorLabelGenerator()
        );
        $labelGenerator->registerActualGenerator(
            Filter::class, 
            new LabelGenerator\FilterLabelGenerator(new OlderThanRenderer())
        );
        
        //build scalar presenters
        $presenter = new ScalarPresenterProxy();
        $this->scalarPresenter = new LabelingPresenter($presenter, $labelGenerator);
        $presenter->registerActualPresenter(
            Counter::class,
            new CounterPresenter()
        );
        $presenter->registerActualPresenter(
            ListAggregator::class, 
            new ListAggregatorPresenter()
        );
        $presenter->registerActualPresenter(
            Filter::class, 
            new FilterPresenter($this->scalarPresenter)
        );
    }

    /**
     * Asserts that the aggregation result as JSON matches the given array.
     * 
     * @param mixed $expectedResultOfAggregation
     */
    protected function assertAggregationResult($expectedResultOfAggregation)
    {
        $this->assertSame(
            $expectedResultOfAggregation,
            $this->scalarPresenter->convertToScalar($this->aggregator)
        );
    }
}

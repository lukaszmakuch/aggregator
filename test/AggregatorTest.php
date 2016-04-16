<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\Age;
use lukaszmakuch\Aggregator\Cat\AgeReader;
use lukaszmakuch\Aggregator\Cat\AgeToTextConverter;
use lukaszmakuch\Aggregator\Cat\OlderThanRenderer;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\Impl\Container\Container;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\LabelGenerator\AggregatorOfSubjectsWithCommonPropertiesLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\CounterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\FilterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\GroupingAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\ListAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\AggregatorOfSubjectsWithCommonPropertiesPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\CounterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\FilterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\GroupingAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\LabelingPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ListAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ScalarPresenterProxy;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\ClassBasedTextGeneratorProxy;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ContainerPresenter;
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
        //property reader to text converter
        $propertyReaderToTextConverter = new ClassBasedTextGenerator();
        $propertyReaderToTextConverter->addTextualRepresentationOf(AgeReader::class, "age");

        //property to text converter
        $propertyToTextConverter = new ClassBasedTextGeneratorProxy();
        $propertyToTextConverter->registerActualGenerator(
            Age::class, 
            new AgeToTextConverter()
        );
        
        //build label generators
        $labelGenerator = new ClassBasedTextGeneratorProxy();
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
            new FilterLabelGenerator(new OlderThanRenderer())
        );
        $labelGenerator->registerActualGenerator(
            Container::class, 
            NULLTextGenerator::getInstance()
        );
        $labelGenerator->registerActualGenerator(
            GroupingAggregator::class, 
            new GroupingAggregatorLabelGenerator($propertyReaderToTextConverter)
        );
        $labelGenerator->registerActualGenerator(
            AggregatorOfSubjectsWithCommonProperties::class, 
            new AggregatorOfSubjectsWithCommonPropertiesLabelGenerator($propertyToTextConverter)
        );
        
        //build scalar presenters
        $aggregatorTextualTypeObtainer = new ClassBasedTextGenerator();
        $aggregatorTextualTypeObtainer->addTextualRepresentationOf(
            Counter::class, 
            "counter"
        );
        $aggregatorTextualTypeObtainer->addTextualRepresentationOf(
            ListAggregator::class, 
            "list"
        );
        $aggregatorTextualTypeObtainer->addTextualRepresentationOf(
            Filter::class, 
            "filter"
        );
        $aggregatorTextualTypeObtainer->addTextualRepresentationOf(
            Container::class, 
            "container"
        );
        $aggregatorTextualTypeObtainer->addTextualRepresentationOf(
            GroupingAggregator::class, 
            "group"
        );
        $aggregatorTextualTypeObtainer->addTextualRepresentationOf(
            AggregatorOfSubjectsWithCommonProperties::class, 
            "subjects_with_common_properties"
        );
        $presenter = new ScalarPresenterProxy();
        $this->scalarPresenter = new LabelingPresenter($presenter, $labelGenerator, $aggregatorTextualTypeObtainer);
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
        $presenter->registerActualPresenter(
            GroupingAggregator::class, 
            new GroupingAggregatorPresenter($this->scalarPresenter)
        );
        $presenter->registerActualPresenter(
            Container::class, 
            new ContainerPresenter($this->scalarPresenter)
        );
        $presenter->registerActualPresenter(
            AggregatorOfSubjectsWithCommonProperties::class, 
            new AggregatorOfSubjectsWithCommonPropertiesPresenter($this->scalarPresenter)
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

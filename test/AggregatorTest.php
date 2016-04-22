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
use lukaszmakuch\Aggregator\Impl\Container\Container;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\LabelGenerator\AggregatorOfSubjectsWithCommonPropertiesLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\Builder\LabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\CounterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\FilterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\GroupingAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\ListAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\PropertyReaderToTextConverterUser;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\ScalarPresenterBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\AggregatorOfSubjectsWithCommonPropertiesPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ContainerPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\CounterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\FilterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\GroupingAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ListAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;
use lukaszmakuch\TextGenerator\ClassBasedTextGeneratorProxy;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
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

        //build the scalar presenter
        $this->scalarPresenter = (new ScalarPresenterBuilder())
            ->registerPresenter(
                Counter::class,
                new CounterPresenter(),
                new CounterLabelGenerator(),
                "counter"
            )
            ->registerPresenter(
                ListAggregator::class, 
                new ListAggregatorPresenter(),
                new ListAggregatorLabelGenerator(),
                "list"
            )
            ->registerPresenter(
                Filter::class, 
                new FilterPresenter(),
                new FilterLabelGenerator(),
                "filter"
            )
            ->registerPresenter(
                GroupingAggregator::class, 
                new GroupingAggregatorPresenter(),
                new GroupingAggregatorLabelGenerator(),
                "group"
            )
            ->registerPresenter(
                Container::class, 
                new ContainerPresenter(),
                NULLTextGenerator::getInstance(),
                "container"
            )
            ->registerPresenter(
                AggregatorOfSubjectsWithCommonProperties::class, 
                new AggregatorOfSubjectsWithCommonPropertiesPresenter(),
                new AggregatorOfSubjectsWithCommonPropertiesLabelGenerator(),
                "subjects_with_common_properties"
            )
            ->registerDependency(
                PropertyReaderToTextConverterUser::class,
                $propertyReaderToTextConverter
            )
            ->registerDependency(
                LabelGenerator\RequirementToTextConverterUser::class,
                new OlderThanRenderer()
            )
            ->registerDependency(
                LabelGenerator\PropertyToTextConverterUser::class,
                $propertyToTextConverter
            )
            ->build();
        ;
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

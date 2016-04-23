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
use lukaszmakuch\Aggregator\Cat\OlderThan;
use lukaszmakuch\Aggregator\Cat\OlderThanRenderer;
use lukaszmakuch\Aggregator\Impl\Container\Container;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
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
use lukaszmakuch\Aggregator\LabelGenerator\PropertyToTextConverterUser;
use lukaszmakuch\Aggregator\LabelGenerator\RequirementToTextConverterUser;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\DefaultScalarPresenterBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;
use lukaszmakuch\TextGenerator\ClassBasedTextGeneratorProxy;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\TextGenerator;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;
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
        $this->scalarPresenter = (new DefaultScalarPresenterBuilder())
            ->setLabelGenerator($this->buildLabelGenerator())
            ->build();
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

    /**
     * @return TextGenerator
     */
    private function buildLabelGenerator()
    {
        $propertyReaderToTextConverter = new ClassBasedTextGenerator();
        $propertyReaderToTextConverter->addTextualRepresentationOf(
            AgeReader::class,
            "age"
        );

        $propertyToTextConverter = new ClassBasedTextGeneratorProxy();
        $propertyToTextConverter->registerActualGenerator(
            Age::class, 
            new AgeToTextConverter()
        );

        $requirementToTextConverter = new ClassBasedTextGeneratorProxy();
        $requirementToTextConverter->registerActualGenerator(
            OlderThan::class,
            new OlderThanRenderer()
        );

        return (new LabelGeneratorBuilder())
            ->registerDependency(
                PropertyReaderToTextConverterUser::class,
                $propertyReaderToTextConverter
            )
            ->registerDependency(
                RequirementToTextConverterUser::class,
                $requirementToTextConverter
            )
            ->registerDependency(
                PropertyToTextConverterUser::class,
                $propertyToTextConverter
            )
            ->registerLabelGeneratorPrototype(
                Counter::class,
                new CounterLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                ListAggregator::class,
                new ListAggregatorLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                Filter::class,
                new FilterLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                GroupingAggregator::class,
                new GroupingAggregatorLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                Container::class,
                NULLTextGenerator::getInstance()
            )
            ->registerLabelGeneratorPrototype(
                AggregatorOfSubjectsWithCommonProperties::class,
                new AggregatorOfSubjectsWithCommonPropertiesLabelGenerator()
            )
            ->build()
        ;
    }
}

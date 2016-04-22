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
use lukaszmakuch\Aggregator\LabelGenerator\PropertyReaderToTextConverterUser;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;
use lukaszmakuch\TextGenerator\ClassBasedTextGeneratorProxy;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\DefaultScalarPresenterBuilder;
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
        $this->scalarPresenter = (new DefaultScalarPresenterBuilder())
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

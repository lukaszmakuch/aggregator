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
use lukaszmakuch\Aggregator\Cat\NameLetterByLetter;
use lukaszmakuch\Aggregator\Cat\OlderThan;
use lukaszmakuch\Aggregator\Cat\OlderThanRenderer;
use lukaszmakuch\Aggregator\LabelGenerator\Builder\DefaultLabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\PropertyReaderToTextConverterUser;
use lukaszmakuch\Aggregator\LabelGenerator\PropertyToTextConverterUser;
use lukaszmakuch\Aggregator\LabelGenerator\RequirementToTextConverterUser;
use lukaszmakuch\Aggregator\LabelGenerator\SubjectProjectorToTextConverterUser;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\DefaultScalarPresenterBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;
use lukaszmakuch\TextGenerator\ClassBasedTextGeneratorProxy;
use lukaszmakuch\TextGenerator\TextGenerator;
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
        
        $subjectProjectorToTextConverter = new ClassBasedTextGenerator();
        $subjectProjectorToTextConverter->addTextualRepresentationOf(
            NameLetterByLetter::class,
            "name-letter-by-letter"
        );

        return (new DefaultLabelGeneratorBuilder())
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
            ->registerDependency(
                SubjectProjectorToTextConverterUser::class,
                $subjectProjectorToTextConverter
            )
            ->build()
        ;
    }
}

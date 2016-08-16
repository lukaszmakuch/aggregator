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
use lukaszmakuch\Aggregator\Exception\VisitorException;
use lukaszmakuch\Aggregator\LabelGenerator\Builder\DefaultLabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitorUser;
use lukaszmakuch\Aggregator\LabelGenerator\PropertyReaderToTextConverterUser;
use lukaszmakuch\Aggregator\LabelGenerator\PropertyToTextConverterUser;
use lukaszmakuch\Aggregator\LabelGenerator\RequirementToTextConverterUser;
use lukaszmakuch\Aggregator\LabelGenerator\SubjectProjectorToTextConverterUser;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\DefaultScalarPresenterBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Builder\DefaultXmlPresenterBuilder;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;
use lukaszmakuch\TextGenerator\ClassBasedTextGeneratorProxy;
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
     * @var Aggregator
     */
    protected $aggregatorClone;

    /**
     * @var ScalarPresenter\PresentingVisitor
     */
    private $scalarPresentingVisitor;
    
    /**
     * @var XmlPresenter
     */
    private $xmlPresenter;

    protected function setUp()
    {
        $this->scalarPresentingVisitor = (new DefaultScalarPresenterBuilder())
            ->setLabelingVisitor($this->buildLabelGenerator())
            ->build();
        
        $this->xmlPresenter =
            (new DefaultXmlPresenterBuilder())
            ->registerDependency(
                LabelingVisitorUser::class,
                $this->buildLabelGenerator()
            )
            ->build()
        ;
    }
    
    /**
     * Clones aggregator and keeps the clone as aggregatorClone.
     */
    protected function cloneAggregator()
    {
        $this->aggregatorClone = clone $this->aggregator;
    }
    
    /**
     * Asserts that the aggregation result for the main aggregator matches the given result.
     *
     * @param mixed $expectedResultOfAggregation
     * @throws VisitorException
     */
    protected function assertAggregationResult($expectedResultOfAggregation)
    {
        $this->assertAggregationResultImpl($expectedResultOfAggregation, $this->aggregator);
    }
    
    /**
     * Asserts that the aggregation result for the cloned aggregator matches the given result.
     *
     * @param mixed $expectedResultOfAggregation
     * @throws VisitorException
     */
    protected function assertAggregationResultForClone($expectedResultOfAggregation)
    {
        $this->assertAggregationResultImpl($expectedResultOfAggregation, $this->aggregatorClone);
    }
    
    /**
     * Asserts that the aggregation result for the main aggregator matches the expected XML.
     *
     * @param String $expectedResultOfAggregationAsXml
     * @throws VisitorException
     */
    protected function assertAggregationResultXml($expectedResultOfAggregationAsXml)
    {
        $this->assertXmlStringEqualsXmlString(
            $expectedResultOfAggregationAsXml,
            $this->aggregator->accept($this->xmlPresenter)
        );
    }
    
    /**
     * Asserts that the aggregation result for the given aggregator matches the given result.
     *
     * @param mixed $expectedResultOfAggregation
     * @throws VisitorException
     */
    private function assertAggregationResultImpl($expectedResultOfAggregation, Aggregator $a)
    {
        $this->assertSame(
            $expectedResultOfAggregation,
            $a->accept($this->scalarPresentingVisitor)
        );
    }

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

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
    protected $scalarPresentingVisitor;
    
    /**
     * @var XmlPresenter
     */
    protected $xmlPresenter;

    protected function setUp()
    {
        $this->scalarPresentingVisitor = (new DefaultScalarPresenterBuilder())
            ->registerDependency(
                LabelingVisitorUser::class,
                $this->buildLabelGenerator()
            )
            ->registerDependency(
                PropertyReaderToTextConverterUser::class,
                $this->buildPropertyReaderToTextConverter()
            )
            ->registerDependency(
                PropertyToTextConverterUser::class,
                $this->buildPropertyToTextConverter()
            )
            ->build();
        
        $this->xmlPresenter =
            (new DefaultXmlPresenterBuilder())
            ->registerDependency(
                LabelingVisitorUser::class,
                $this->buildLabelGenerator()
            )
            ->registerDependency(
                PropertyReaderToTextConverterUser::class,
                $this->buildPropertyReaderToTextConverter()
            )
            ->registerDependency(
                PropertyToTextConverterUser::class,
                $this->buildPropertyToTextConverter()
            )
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
    
    protected function buildLabelGenerator()
    {
        return (new DefaultLabelGeneratorBuilder())
            ->registerDependency(
                PropertyReaderToTextConverterUser::class,
                $this->buildPropertyReaderToTextConverter()
            )
            ->registerDependency(
                PropertyToTextConverterUser::class,
                $this->buildPropertyToTextConverter()
            )
            ->registerDependency(
                RequirementToTextConverterUser::class,
                $this->buildRequirementToTextConverter()
            )
            ->registerDependency(
                SubjectProjectorToTextConverterUser::class,
                $this->buildSubjectProjectorToTextConverter()
            )
            ->build()
        ;
    }
    
    /**
     * @return \lukaszmakuch\TextGenerator\TextGenerator
     */
    protected function buildPropertyReaderToTextConverter()
    {
        $c = new ClassBasedTextGenerator();
        $c->addTextualRepresentationOf(
            AgeReader::class,
            "age"
        );
        return $c;
    }
    
    /**
     * @return \lukaszmakuch\TextGenerator\TextGenerator
     */
    protected function buildPropertyToTextConverter()
    {
        $c = new ClassBasedTextGeneratorProxy();
        $c->registerActualGenerator(
            Age::class,
            new AgeToTextConverter()
        );
        return $c;
    }
    
    /**
     * @return \lukaszmakuch\TextGenerator\TextGenerator
     */
    protected function buildRequirementToTextConverter()
    {
        $c = new ClassBasedTextGeneratorProxy();
        $c->registerActualGenerator(
            OlderThan::class,
            new OlderThanRenderer()
        );
        return $c;
    }
    
    /**
     * @return \lukaszmakuch\TextGenerator\TextGenerator
     */
    protected function buildSubjectProjectorToTextConverter()
    {
        $c = new ClassBasedTextGenerator();
        $c->addTextualRepresentationOf(
            NameLetterByLetter::class,
            "name-letter-by-letter"
        );
        return $c;
    }
}

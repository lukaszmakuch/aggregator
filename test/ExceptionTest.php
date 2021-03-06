<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\LabelGenerator\Builder\BareLabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\Builder\DefaultLabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\Exception\UnableToGenerateLabel;
use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitorUser;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\DefaultScalarPresenterBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToPresentByVisitor;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\LabelingPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Builder\DefaultXmlPresenterBuilder;
use lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;
use PHPUnit_Framework_TestCase;

class UnsupportedAggregator implements Aggregator
{
    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }

    public function aggregate($subject)
    {
    }
    
    public function __clone()
    {
    }
}

/**
 * Checks exceptions.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AggregatorVisitor
     */
    private $scalarPresenter;
    
    /**
     * @var XmlPresenter
     */
    private $xmlPresenter;
    
    protected function setUp()
    {
        $this->scalarPresenter = (new DefaultScalarPresenterBuilder())
            ->registerDependency(
                LabelingVisitorUser::class,
                (new DefaultLabelGeneratorBuilder())->build()
            )
            ->build();
        $this->xmlPresenter = (new DefaultXmlPresenterBuilder())->build();
    }
    
    public function testUnableToVisitForScalar()
    {
        $this->setExpectedExceptionRegExp(UnableToPresentByVisitor::class);
        $this->scalarPresenter->visit(new UnsupportedAggregator());
    }
    
    public function testUnableToAcceptForScalar()
    {
        $this->setExpectedExceptionRegExp(UnableToPresentByVisitor::class);
        (new UnsupportedAggregator())->accept($this->scalarPresenter);
    }
    
    public function testUnableToVisitForXml()
    {
        $this->setExpectedExceptionRegExp(UnableToCreateXml::class);
        $this->xmlPresenter->visit(new UnsupportedAggregator());
    }
    
    public function testUnableToAcceptForXml()
    {
        $this->setExpectedExceptionRegExp(UnableToCreateXml::class);
        (new UnsupportedAggregator())->accept($this->xmlPresenter);
    }

    public function testUnableToVisitForLabel()
    {
        $this->setExpectedExceptionRegExp(UnableToGenerateLabel::class);
        $this->buildLabelGenerator()->visit(new UnsupportedAggregator());
    }

    public function testUnableToAcceptForLabel()
    {
        $this->setExpectedExceptionRegExp(UnableToGenerateLabel::class);
        (new UnsupportedAggregator())->accept($this->buildLabelGenerator());
    }
    
    /**
     * @return LabelingPresenter
     */
    private function buildLabelGenerator()
    {
        return (new BareLabelGeneratorBuilder())->build();
    }
}

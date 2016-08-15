<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\LabelGenerator\Builder\DefaultLabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\Exception\UnableToGenerateLabel;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\DefaultScalarPresenterBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToPresentByVisitor;
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
abstract class ExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AggregatorVisitor
     */
    private $presentingVisitor;
    
    protected function setUp()
    {
        $this->presentingVisitor = (new DefaultScalarPresenterBuilder())
            ->setLabelingVisitor((new DefaultLabelGeneratorBuilder())->build())
            ->build();
    }
    
    public function testUnableToVisitAggregatorToPresentIt()
    {
        $this->setExpectedExceptionRegExp(UnableToPresentByVisitor::class);
        $this->presentingVisitor->visit(new UnsupportedAggregator());
    }
    
    public function testUnableToAcceptVisitorToPresentAggregator()
    {
        $this->setExpectedExceptionRegExp(UnableToPresentByVisitor::class);
        $this->presentingVisitor->visit(new UnsupportedAggregator());
    }

    public function testUnableToVisitAggregatorToGenerateLabel()
    {
        $this->setExpectedExceptionRegExp(UnableToGenerateLabel::class);
        $this->buildLabelGenerator()->visit(new UnsupportedAggregator());
    }

    public function testUnableToAcceptVisitorToGenerateLabel()
    {
        $this->setExpectedExceptionRegExp(UnableToGenerateLabel::class);
        (new UnsupportedAggregator())->accept($this->buildLabelGenerator());
    }
}

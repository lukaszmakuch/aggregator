<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Cat\NameLetterByLetter;
use lukaszmakuch\Aggregator\Cat\NameReader;
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\Impl\Projection\ProjectionAggregator;
use lukaszmakuch\Aggregator\SubjectProjector\Exception\UnableToProject;
use lukaszmakuch\Aggregator\SubjectProjector\SubjectProjector;

class ProjectionAggregatorTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = new ProjectionAggregator(
            new NameLetterByLetter(),
            new ListAggregator(
                new NameReader(),
                ", "
            )
        );
    }

    public function testProjection()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Henry']));
        $this->aggregator->aggregate(new Cat(['name' => 'Jim']));
        $this->aggregator->aggregate(new Cat(['name' => 'Tim']));
        
        $this->assertAggregationResult([
            'type' => 'projection',
            'label' => 'name-letter-by-letter',
            'data' => [
                'type' => 'list',
                'label' => 'list',
                'data' => "H-e-n-r-y, J-i-m, T-i-m",
            ]
        ]);
    }

    public function testExceptionIfUnableToProject()
    {
        $projectorThrowingException = $this->getMockBuilder(SubjectProjector::class)
            ->getMock();
        $projectorThrowingException
            ->method('getDifferentRepresentationOf')
            ->will($this->throwException(new UnableToProject()));
        $this->aggregator = new ProjectionAggregator(
            $projectorThrowingException,
            $this->getMockBuilder(Aggregator::class)->getMock()
        );
        $this->setExpectedExceptionRegExp(UnableToAggregate::class);
        $this->aggregator->aggregate(new Cat());
    }
    
    public function testCloning()
    {
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['name' => 'Henry']));
        $this->aggregatorClone->aggregate(new Cat(['name' => 'Jim']));
        
        $this->assertAggregationResult([
            'type' => 'projection',
            'label' => 'name-letter-by-letter',
            'data' => [
                'type' => 'list',
                'label' => 'list',
                'data' => "H-e-n-r-y",
            ]
        ]);
        
        $this->assertAggregationResultForClone([
            'type' => 'projection',
            'label' => 'name-letter-by-letter',
            'data' => [
                'type' => 'list',
                'label' => 'list',
                'data' => "J-i-m",
            ]
        ]);
        
    }

    public function testPresentingAsXml()
    {
        $this->aggregator->aggregate(new Cat(['name' => 'Henry']));
        $this->assertAggregationResultXml("
            <projection label=\"name-letter-by-letter\">
                <list label=\"list\">H-e-n-r-y</list>
            </projection>
        ");
    }
}

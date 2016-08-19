<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\Cat\AgeReader;
use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Impl\PropertyList\PropertyList;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToPresent;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\PropertyListPresenter as ScalarPropertyListPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\PropertyListPresenter as XmlPropertyListPresenter;
use lukaszmakuch\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Tests the list of properties.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PropertyListTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = new PropertyList(new AgeReader());
    }
    
    public function testAggregation()
    {
        $this->aggregator->aggregate(new Cat(['age' => 4]));
        $this->aggregator->aggregate(new Cat(['age' => 2]));
        $this->aggregator->aggregate(new Cat(['age' => 6]));
        
        $this->assertAggregationResult([
            'type' => 'properties',
            'label' => 'age',
            'data' => ['age 4', 'age 2', 'age 6']
        ]);
    }
    
    public function testCloning()
    {
        $this->aggregator->aggregate(new Cat(['age' => 4]));
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['age' => 2]));
        $this->aggregatorClone->aggregate(new Cat(['age' => 6]));
        
        $this->assertAggregationResult([
            'type' => 'properties',
            'label' => 'age',
            'data' => ['age 4', 'age 2']
        ]);
        $this->assertAggregationResultForClone([
            'type' => 'properties',
            'label' => 'age',
            'data' => ['age 4', 'age 6']
        ]);
    }
    
    public function testPresentingAsXml()
    {
        $this->aggregator->aggregate(new Cat(['age' => 4]));
        $this->aggregator->aggregate(new Cat(['age' => 2]));
        
        $this->assertAggregationResultXml('
            <properties label="age">
                <value>age 4</value>
                <value>age 2</value>
            </properties>
        ');
    }

    public function testExceptionWhenUnableToConvertPropertyToTextForScalar()
    {
        $this->setExpectedExceptionRegExp(UnableToPresent::class, '@.*property to text.*@');
        $presenter = new ScalarPropertyListPresenter();
        $presenter->setPropertyToTextConverter($this->buildFailingTextGenerator());
        $this->aggregator->aggregate(new Cat());
        $presenter->convertToScalar($this->aggregator);
    }
    
    public function testExceptionWhenUnableToConvertPropertyToTextForXml()
    {
        $this->setExpectedExceptionRegExp(UnableToCreateXml::class, '@.*property to text.*@');
        $presenter = new XmlPropertyListPresenter();
        $presenter->setPropertyToTextConverter($this->buildFailingTextGenerator());
        $this->aggregator->aggregate(new Cat());
        $this->aggregator->accept($presenter);
    }

    /**
     * @return TextGenerator that always throws an exception
     */
    private function buildFailingTextGenerator()
    {
        $g = $this->getMockBuilder(TextGenerator::class)->getMock();
        $g->method('getTextBasedOn')->will($this->throwException(new UnableToGetText()));
        return $g;
    }
}

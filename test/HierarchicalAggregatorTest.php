<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator;

use lukaszmakuch\Aggregator\AggregatorTest;
use lukaszmakuch\Aggregator\Cat\Cat;
use lukaszmakuch\Aggregator\Cat\ColorReader;
use lukaszmakuch\Aggregator\Cat\NameReader;
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\CloningFactory;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\LeafAwareFactory;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\Node;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitorUser;
use lukaszmakuch\Aggregator\XmlPresenter\Builder\DefaultXmlPresenterBuilder;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\FlatHierarchyPresenter;

class HierarchicalAggregatorTest extends AggregatorTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->aggregator = new HierarchicalAggregator(
            //determines what node a subjects belongs to
            new ColorReader(),
            //hierarchy description
            new Node("all", [
                new Node("dark", [
                    new Node("black"),
                    new Node("brown"),
                ]),
                new Node("favorite", [
                    new Node("black", [
                        //doubled on purpose in order to make sure that
                        //one subject is not taken into account few times
                        new Node("black")
                    ])
                ])
            ]),
            //factory of aggregators used on each level
            new CloningFactory(new ListAggregator(
                new NameReader(),
                ", "
            ))
        );
    }
    
    public function testGrouping()
    {
        foreach ([
            new Cat(['name' => 'Jim', 'color' => 'black']),
            new Cat(['name' => 'Meow', 'color' => 'brown']),
            new Cat(['name' => 'Tim', 'color' => 'dark']),
        ] as $cat) {
            $this->aggregator->aggregate($cat);
        }
        
        $this->assertAggregationResult([
            'type' => 'hierarchy',
            'label' => "hierarchy",
            'data' => [
                'type' => 'hierarchy_node',
                'label' => 'all',
                'data' => [
                    'own' => [
                        'type' => 'list',
                        'label' => 'list',
                        'data' => 'Jim, Meow, Tim',
                    ],
                    'children' => [
                        [
                            'type' => 'hierarchy_node',
                            'label' => 'dark',
                            'data' => [
                                'own' => [
                                    'type' => 'list',
                                    'label' => 'list',
                                    'data' => 'Jim, Meow, Tim',
                                ],
                                'children' => [
                                    [
                                        'type' => 'hierarchy_node',
                                        'label' => 'black',
                                        'data' => [
                                            'own' => [
                                                'type' => 'list',
                                                'label' => 'list',
                                                'data'=> 'Jim',
                                            ],
                                            'children' => [],
                                        ],
                                    ],
                                    [
                                        'type' => 'hierarchy_node',
                                        'label' => 'brown',
                                        'data' => [
                                            'own' => [
                                                'type' => 'list',
                                                'label' => 'list',
                                                'data'=> 'Meow',
                                            ],
                                            'children' => [],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'type' => 'hierarchy_node',
                            'label' => 'favorite',
                            'data' => [
                                'own' => [
                                    'type' => 'list',
                                    'label' => 'list',
                                    'data' => 'Jim',
                                ],
                                'children' => [
                                    [
                                        'type' => 'hierarchy_node',
                                        'label' => 'black',
                                        'data' => [
                                            'own' => [
                                                'type' => 'list',
                                                'label'=> 'list',
                                                'data' => 'Jim',
                                            ],
                                            'children' => [
                                                [
                                                    'type' => 'hierarchy_node',
                                                    'label' => 'black',
                                                    'data' => [
                                                        'own' => [
                                                            'type' => 'list',
                                                            'label'=> 'list',
                                                            'data' => 'Jim',
                                                        ],
                                                        'children' => [],
                                                    ],
                                                ],
                                                
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testCloning()
    {
        $this->aggregator = new HierarchicalAggregator(
            new ColorReader(),
            new Node("black"),
            new CloningFactory(new ListAggregator(
                new NameReader(),
                ", "
            ))
        );
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['color' => 'black', 'name' => 'Jim']));
        $this->aggregatorClone->aggregate(new Cat(['color' => 'black', 'name' => 'Tim']));

        $this->assertAggregationResult([
            'type' => 'hierarchy',
            'label' => "hierarchy",
            'data' => [
                'type' => 'hierarchy_node',
                'label' => 'black',
                'data' => [
                    'own' => [
                        'type' => 'list',
                        'label' => 'list',
                        'data' => 'Jim',
                    ],
                    'children' => [],
                ],
            ],
        ]);

        $this->assertAggregationResultForClone([
            'type' => 'hierarchy',
            'label' => "hierarchy",
            'data' => [
                'type' => 'hierarchy_node',
                'label' => 'black',
                'data' => [
                    'own' => [
                        'type' => 'list',
                        'label' => 'list',
                        'data' => 'Tim',
                    ],
                    'children' => [],
                ],
            ],
        ]);
    }
    
    public function testPresentingAsXml()
    {
        $this->aggregator = new HierarchicalAggregator(
            new ColorReader(),
            new Node("blue", [
                new Node("dark blue")
            ]),
            new CloningFactory(new ListAggregator(
                new NameReader(),
                ", "
            ))
        );
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['color' => 'blue', 'name' => 'Jim']));
        $this->aggregator->aggregate(new Cat(['color' => 'dark blue', 'name' => 'Tim']));
        
        $this->assertAggregationResultXml('
            <hierarchy>
                <hierarchy_node label="blue">
                    <value>
                        <list>Jim, Tim</list>
                    </value>
                    <children>
                        <hierarchy_node label="dark blue">
                            <value>
                                <list>Tim</list>
                            </value>
                            <children/>
                        </hierarchy_node>
                    </children>
                </hierarchy_node>
            </hierarchy>
        ');
        
        $this->assertFlatHierarchy('
            <hierarchy>
                <node label="blue" parent_label="" depth="0" has_children="1">
                    <list>Jim, Tim</list>
                </node>
                <node label="dark blue" parent_label="blue" depth="1" has_children="0">
                    <list>Tim</list>
                </node>
            </hierarchy>
        ');
    }
    
    public function testLeafAwareNodeAggregatorFactory()
    {
        $this->aggregator = new HierarchicalAggregator(
            new ColorReader(),
            new Node("blue", [
                new Node("dark blue")
            ]),
            new LeafAwareFactory(
                new Counter(),
                new ListAggregator(
                    new NameReader(),
                    ", "
                )
            )
        );
        
        $this->aggregator->aggregate(new Cat(['color' => 'blue', 'name' => 'Jim']));
        $this->aggregator->aggregate(new Cat(['color' => 'dark blue', 'name' => 'Tim']));
        
        $this->assertFlatHierarchy('
            <hierarchy>
                <node label="blue" parent_label="" depth="0" has_children="1">
                    <counter>2</counter>
                </node>
                <node label="dark blue" parent_label="blue" depth="1" has_children="0">
                    <list>Tim</list>
                </node>
            </hierarchy>
        ');
    }
    
    public function testExceptionIfSubjectsBelongsToUnknownNode()
    {
        $this->setExpectedExceptionRegExp(UnableToAggregate::class);
        $this->aggregator->aggregate(new Cat(['color' => 'not included in the hierarchy description']));
    }
    
    public function testNodeEquality()
    {
        $this->assertTrue((new Node("n1", [
            new Node("n1.1"),
            new Node("n1.2")
        ]))->equals(new Node("n1", [
            new Node("n1.1"),
            new Node("n1.2")
        ])));
    }
    
    public function testNodeInequality()
    {
        $this->assertFalse((new Node("n1", [
            new Node("n1.1"),
            new Node("n1.2")
        ]))->equals(new Node("n1", [
            new Node("n1.1"),
        ])));
    }
    
    private function assertFlatHierarchy($xml)
    {
        $this->changeHierarchyRendererToFlat();
        $this->assertAggregationResultXml($xml);
    }
    
    private function changeHierarchyRendererToFlat()
    {
        $this->xmlPresenter =
            (new DefaultXmlPresenterBuilder())
            ->registerActualPresenter(
                HierarchicalAggregator::class,
                new FlatHierarchyPresenter()
            )
            ->registerDependency(
                LabelingVisitorUser::class,
                $this->buildLabelGenerator()
            )
            ->build()
        ;
    }
}

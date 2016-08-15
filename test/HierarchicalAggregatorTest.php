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
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\Node;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;

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
            //aggregator used on each level
            new ListAggregator(
                new NameReader(),
                ", "
            )
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
                'label' => 'node:all',
                'data' => [
                    'own' => [
                        'type' => 'list',
                        'label' => 'list',
                        'data' => 'Jim, Meow, Tim',
                    ],
                    'children' => [
                        [
                            'type' => 'hierarchy_node',
                            'label' => 'node:dark',
                            'data' => [
                                'own' => [
                                    'type' => 'list',
                                    'label' => 'list',
                                    'data' => 'Jim, Meow, Tim',
                                ],
                                'children' => [
                                    [
                                        'type' => 'hierarchy_node',
                                        'label' => 'node:black',
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
                                        'label' => 'node:brown',
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
                            'label' => 'node:favorite',
                            'data' => [
                                'own' => [
                                    'type' => 'list',
                                    'label' => 'list',
                                    'data' => 'Jim',
                                ],
                                'children' => [
                                    [
                                        'type' => 'hierarchy_node',
                                        'label' => 'node:black',
                                        'data' => [
                                            'own' => [
                                                'type' => 'list',
                                                'label'=> 'list',
                                                'data' => 'Jim',
                                            ],
                                            'children' => [
                                                [
                                                    'type' => 'hierarchy_node',
                                                    'label' => 'node:black',
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
            new ListAggregator(
                new NameReader(),
                ", "
            )
        );
        $this->cloneAggregator();
        $this->aggregator->aggregate(new Cat(['color' => 'black', 'name' => 'Jim']));
        $this->aggregatorClone->aggregate(new Cat(['color' => 'black', 'name' => 'Tim']));

        $this->assertAggregationResult([
            'type' => 'hierarchy',
            'label' => "hierarchy",
            'data' => [
                'type' => 'hierarchy_node',
                'label' => 'node:black',
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
                'label' => 'node:black',
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
    
    public function testExceptionIfSubjectsBelongsToUnknownNode()
    {
        $this->setExpectedExceptionRegExp(UnableToAggregate::class);
        $this->aggregator->aggregate(new Cat(['color' => 'not included in the hierarchy description']));
    }
}

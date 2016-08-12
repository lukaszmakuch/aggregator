<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\HierarchicalAggregator;

/**
 * Value object used to build a hierarchy description.
 *
 * @author Łukasz Makuch <lukasz.makuch@librus.pl>
 */
class Node
{
    private $name;
    private $children;
    
    /**
     * @param String name
     * @param Node[] $children
     */
    public function __construct(
        $name,
        array $children = []
    ) {
        $this->name = $name;
        $this->children = $children;
    }
    
    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return Node[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}

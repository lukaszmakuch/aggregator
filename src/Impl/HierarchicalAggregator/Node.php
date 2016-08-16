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
    
    /**
     * @param \lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\Node $anotherNode
     * @return boolean
     */
    public function equals(Node $anotherNode)
    {
        if ($this->name != $anotherNode->name) {
            return false;
        }
        
        /* @var $expectedNodes Node */
        $expectedNodes = array_values($this->children);
        $actualNodes = array_values($anotherNode->children);
        
        if (count($expectedNodes) != count($actualNodes)) {
            return false;
        }
        
        for ($nodeIndex = 0; $nodeIndex < count($expectedNodes); $nodeIndex++) {
            if (!$expectedNodes[$nodeIndex]->equals($actualNodes[$nodeIndex])) {
                return false;
            }
        }
        
        return true;
    }
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\HierarchicalAggregator;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;

/**
 * Represents one node in the hierarchy.
 *
 * @author Łukasz Makuch <lukasz.makuch@librus.pl>
 */
class NodeAggregator implements Aggregator
{
    private $nodeName;
    private $actualAggregator;
    private $children;
    
    /**
     * @param String $nodeName
     * @param NodeAggregator[] $children
     * @param Aggregator $actualAggregator
     */
    public function __construct(
        $nodeName,
        array $children,
        Aggregator $actualAggregator
    ) {
        $this->nodeName = $nodeName;
        $this->children = $children;
        $this->actualAggregator = $actualAggregator;
    }
    
    public function __clone()
    {
        $this->children = array_map(function (Aggregator $a) {
            return clone $a;
        }, $this->children);
        $this->actualAggregator = clone $this->actualAggregator;
    }

    
    public function aggregate($subject)
    {
        $this->actualAggregator->aggregate($subject);
    }
    
    /**
     * @return NodeAggregator[]
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * @return boolean true if this aggregator has any children
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }
    
    /**
     * @return Aggregator used to aggregate all subjects of that node
     */
    public function getActualAggregator()
    {
        return $this->actualAggregator;
    }
    
    /**
     * @return String
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }
    
    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }
}

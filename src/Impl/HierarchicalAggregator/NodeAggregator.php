<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\HierarchicalAggregator;

/**
 * Represents one node in the hierarchy.
 *
 * @author Łukasz Makuch <lukasz.makuch@librus.pl>
 */
class NodeAggregator implements \lukaszmakuch\Aggregator\Aggregator
{
    private $nodeName;
    private $actualAggregator;
    private $children;
    
    /**
     * @param String $nodeName
     * @param NodeAggregator[] $children
     * @param \lukaszmakuch\Aggregator\Aggregator $actualAggregator
     */
    public function __construct(
        $nodeName,
        array $children,
        \lukaszmakuch\Aggregator\Aggregator $actualAggregator
    ) {
        $this->nodeName = $nodeName;
        $this->children = $children;
        $this->actualAggregator = $actualAggregator;
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
     * @return \lukaszmakuch\Aggregator\Aggregator used to aggregate all subjects of that node
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
}

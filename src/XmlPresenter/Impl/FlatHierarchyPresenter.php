<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use DOMElement;
use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\NodeAggregator;

/**
 * Renders hierarchies as a flat structure.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FlatHierarchyPresenter extends PresenterTpl
{
    const ROOT_NODE_LEVEL = 0;
    const ROOT_NODE_PARENT_LABEL = "";
    
    protected function getRootTagName()
    {
        return "hierarchy";
    }
    
    protected function getClassOfSupportedAggregators()
    {
        return HierarchicalAggregator::class;
    }
    
    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a HierarchicalAggregator */
        $this->addHierarchyNodeTags(
            $a->getAggregatorOfNodes(), 
            $destination, 
            self::ROOT_NODE_LEVEL, 
            self::ROOT_NODE_PARENT_LABEL
        );
    }
    
    /**
     * @param NodeAggregator $nodeAggregator
     * @param DOMElement $destination
     * @param int $depth
     * @param String $parentHierarchyNodeLabel
     * @return null
     */
    private function addHierarchyNodeTags(
        NodeAggregator $nodeAggregator,
        DOMElement $destination, 
        $depth, 
        $parentHierarchyNodeLabel
    ) {
        $node = $destination->ownerDocument->createElement("node");
        $this->setLabelAttribute($node, $nodeAggregator);
        $node->setAttribute("parent_label", $parentHierarchyNodeLabel);
        $node->setAttribute("depth", $depth);
        $node->appendChild($this->getDOMNodeOf($nodeAggregator->getActualAggregator(), $destination));
        $destination->appendChild($node);
        foreach ($nodeAggregator->getChildren() as $childNode) {
            $this->addHierarchyNodeTags(
                $childNode, 
                $destination, 
                $depth + 1, 
                $this->getLabelFor($nodeAggregator, $destination)
            );
        }
    }
}

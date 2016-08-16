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
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\NodeAggregator;

/**
 * Renders hierarchy nodes.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class HierarchyNodePresenter extends PresenterUsingPresenterTpl
{
    protected function getClassOfSupportedAggregators()
    {
        return NodeAggregator::class;
    }

    protected function getRootTagName()
    {
        return "hierarchy_node";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a NodeAggregator */
        $ownValueTag = $destination->ownerDocument->createElement("value");
        ;
        $ownValueTag->appendChild($this->getDOMNodeOf($a->getActualAggregator(), $destination));
        $destination->appendChild($ownValueTag);
        
        $childrenTag = $destination->ownerDocument->createElement("children");
        foreach ($a->getChildren() as $childAggregator) {
            $childrenTag->appendChild($this->getDOMNodeOf($childAggregator, $destination));
        }
        
        $destination->appendChild($childrenTag);
    }
}

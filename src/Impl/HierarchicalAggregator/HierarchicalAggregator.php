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
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;

/**
 * Supports parent-children relationships.
 *
 * If some subjects is taken into account by a child,
 * it's taken into account by its parent too.
 *
 * @author Łukasz Makuch <lukasz.makuch@librus.pl>
 */
class HierarchicalAggregator implements Aggregator
{
    private $levelAggregatorPrototype;
    private $nodeAggregator;
    private $nodeReader;
    
    /**
     * @var array like
     * [
     *     String node name => [Aggregator, ...],
     *     ...
     * ]
     */
    private $aggregatorsByNode = [];
    
    /**
     * @param NodeReader $nodeReader reads the name of a node each subjet belongs to
     * @param Node $hierarchyDescription parent-children relationships of every possible node
     * @param Aggregator $levelAggregatorPrototype its clones are used to aggregate subjects on every level
     */
    public function __construct(
        NodeReader $nodeReader,
        Node $hierarchyDescription,
        Aggregator $levelAggregatorPrototype
    ) {
        $this->nodeReader = $nodeReader;
        $this->levelAggregatorPrototype = $levelAggregatorPrototype;
        $this->nodeAggregator = $this->buildAggregatorOfNodes($hierarchyDescription);
        $this->groupAggregatorsByPathEndpoint(
            $this->nodeAggregator->getNodeName(),
            [],
            $this->nodeAggregator
        );
        $this->leaveOnlyUniqueAggregatorsInEachGroup();
    }
    
    public function aggregate($subject)
    {
        $this->throwExceptionIfDoesNotBelongToHierarchy($subject);
        foreach ($this->getAggregatorsFor($subject) as $a) {
            $a->aggregate($subject);
        }
    }
    
    /**
     * @return NodeAggregator
     */
    public function getAggregatorOfNodes()
    {
        return $this->nodeAggregator;
    }
    
    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }
    
    public function __clone()
    {
        $this->nodeAggregator = clone $this->nodeAggregator;
        $this->aggregatorsByNode = [];
        $this->groupAggregatorsByPathEndpoint(
            $this->nodeAggregator->getNodeName(),
            [],
            $this->nodeAggregator
        );
        $this->leaveOnlyUniqueAggregatorsInEachGroup();
    }
    
    /**
     * @param Node $hierarchyDescription
     * @return NodeAggregator
     */
    private function buildAggregatorOfNodes(
        Node $hierarchyDescription
    ) {
        return new NodeAggregator(
            $hierarchyDescription->getName(),
            array_map(function (Node $childNode) {
                return $this->buildAggregatorOfNodes($childNode);
            }, $hierarchyDescription->getChildren()),
            clone $this->levelAggregatorPrototype
        );
    }
    
    /**
     * From:
     * |a
     * |
     * |__b
     * |  |
     * |  |__c
     * |
     * |__d
     * creates:
     * c -> b -> a
     * b -> a
     * a
     * d -> a
     *
     * @param String $nodeName
     * @param NodeAggregator[] $previousAggregators
     * @param NodeAggregator $newAggregator
     */
    private function groupAggregatorsByPathEndpoint(
        $nodeName,
        array $previousAggregators,
        NodeAggregator $newAggregator
    ) {
        if (!isset($this->aggregatorsByNode[$nodeName])) {
            $this->aggregatorsByNode[$nodeName] = [];
        }
        
        $this->aggregatorsByNode[$nodeName] = array_merge(
            $this->aggregatorsByNode[$nodeName],
            $previousAggregators,
            [$newAggregator]
        );
        
        foreach ($newAggregator->getChildren() as $child) {
            $this->groupAggregatorsByPathEndpoint(
                $child->getNodeName(),
                $this->aggregatorsByNode[$nodeName],
                $child
            );
        }
    }
    
    private function leaveOnlyUniqueAggregatorsInEachGroup()
    {
        foreach ($this->aggregatorsByNode as $nodeName => $listOfAggregators) {
            $this->aggregatorsByNode[$nodeName] = $this->removeDuplicatesFrom($listOfAggregators);
        }
    }
    
    /**
     * @param Aggregator[] $listOfAggregators
     * @return Aggregator[]
     */
    private function removeDuplicatesFrom(array $listOfAggregators)
    {
        $uniqueAggregators = [];
        foreach ($listOfAggregators as $maybeUniqueAggregator) {
            if (!in_array($maybeUniqueAggregator, $uniqueAggregators, true)) {
                $uniqueAggregators[] = $maybeUniqueAggregator;
            }
        }
        
        return $uniqueAggregators;
    }
    
    /**
     * @param mixed $subject
     * @throws UnableToAggregate
     */
    private function throwExceptionIfDoesNotBelongToHierarchy($subject)
    {
        if (!isset($this->aggregatorsByNode[$this->nodeReader->readNodeOf($subject)])) {
            throw new UnableToAggregate("The given subject doesn't belong to the hierarchy.");
        }
    }
    
    /**
     * @param Aggregator[] $subject
     * @return Aggregator[]
     */
    private function getAggregatorsFor($subject)
    {
        return $this->aggregatorsByNode[$this->nodeReader->readNodeOf($subject)];
    }
}

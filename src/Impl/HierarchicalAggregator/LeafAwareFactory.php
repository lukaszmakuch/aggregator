<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\HierarchicalAggregator;

use lukaszmakuch\Aggregator\Aggregator;

/**
 * Uses different prototypes of aggreagtors for leaves and internal nodes.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LeafAwareFactory implements NodeAggregatorFactory
{
    private $protoForInternalNodes;
    private $protoForLeaves;
    
    public function __construct(
        Aggregator $prototypeForInternalNodes,
        Aggregator $prototypeForLeaves
    ) {
        $this->protoForInternalNodes = $prototypeForInternalNodes;
        $this->protoForLeaves = $prototypeForLeaves;
    }
    
    public function buildAggregatorFor(Node $n)
    {
        return clone ($n->isLeaf() ? $this->protoForLeaves : $this->protoForInternalNodes);
    }
}

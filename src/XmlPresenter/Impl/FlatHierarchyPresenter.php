<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use DOMDocument;
use DOMElement;
use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\LabelGenerator\Exception\UnableToGenerateLabel;
use lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenterUser;

/**
 * Renders hierarchies as a flat structure.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FlatHierarchyPresenter implements XmlPresenter, XmlPresenterUser, \lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitorUser
{
    /**
     * @var XmlPresenter
     */
    private $presenterOfEveryAggregator;
    
    /**
     * @var \lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitor
     */
    private $labelingVisitor;
    
    public function __construct()
    {
        $this->presenterOfEveryAggregator = new NullXmlPresenter();
        $this->labelingVisitor = new \lukaszmakuch\Aggregator\LabelGenerator\NullLabelingVisitor();
    }

    public function setXmlPresenter(XmlPresenter $p)
    {
        $this->presenterOfEveryAggregator = $p;
    }
    
    public function setLabelingPresenter(\lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitor $v)
    {
        $this->labelingVisitor = $v;
    }

    
    public function visit(Aggregator $a)
    {
        $xmlDoc = new DOMDocument();
        $rootTag = $xmlDoc->createElement($this->getRootTagName());
        $this->putDataHeldByAggregator($a, $rootTag);
        $xmlDoc->appendChild($rootTag);
        return $xmlDoc->saveXML($rootTag);
    }
    
    /**
     * @param Aggregator $a (implements the class returned by the getClassOfSupportedAggregators method)
     * @param DOMElement $destination target destination where data held by $a should be put
     * @return null
     * @throws UnableToCreateXml
     */
    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a \lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator */
        $rootNode = $a->getAggregatorOfNodes();
        $this->addNodeTag($rootNode, $destination, 0, "");
    }
    
    protected function addNodeTag(\lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\NodeAggregator $n, DOMElement $destination, $depth, $parentLabel)
    {
        $node = $destination->ownerDocument->createElement("node");
        $label = $n->accept($this->labelingVisitor);
        $node->setAttribute("label", $label);
        $node->setAttribute("parent_label", $parentLabel);
        $node->setAttribute("depth", $depth);
        $node->appendChild($this->getDOMNodeOf($n->getActualAggregator(), $destination));
        $destination->appendChild($node);
        foreach ($n->getChildren() as $childNode) {
            $this->addNodeTag($childNode, $destination, $depth + 1, $label);
        }
    }
    
    /**
     * @return String root XML tag of this aggregator representation, like "counter"
     */
    protected function getRootTagName()
    {
        return "hierarchy";
    }
    
    /**
     * @param Aggregator $a
     * @throws UnableToCreateXml
     * @return DOMNode
     */
    protected function getDOMNodeOf(Aggregator $a, DOMElement $destination)
    {
        $childXml = $destination->ownerDocument->createDocumentFragment();
        $childXml->appendXML($a->accept($this->presenterOfEveryAggregator));
        return $childXml;
    }
}

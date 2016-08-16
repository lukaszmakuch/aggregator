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
use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitor;
use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitorUser;
use lukaszmakuch\Aggregator\LabelGenerator\NullLabelingVisitor;
use lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenterUser;

/**
 * Template of an xml presenter.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class PresenterTpl implements XmlPresenter, XmlPresenterUser, LabelingVisitorUser
{
    /**
     * @var LabelingVisitor
     */
    private $labelingVisitor;
    
    /**
     * @var XmlPresenter
     */
    private $presenterOfEveryAggregator;
    
    public function __construct()
    {
        $this->labelingVisitor = new NullLabelingVisitor();
        $this->presenterOfEveryAggregator = new NullXmlPresenter();
    }
    
    public function setLabelingPresenter(LabelingVisitor $v)
    {
        $this->labelingVisitor = $v;
    }

    public function setXmlPresenter(XmlPresenter $p)
    {
        $this->presenterOfEveryAggregator = $p;
    }
    
    public function visit(Aggregator $a)
    {
        $this->throwExceptionIfUnsupported($a);
        $xmlDoc = new DOMDocument();
        $rootTag = $xmlDoc->createElement($this->getRootTagName());
        $this->putDataHeldByAggregator($a, $rootTag);
        $xmlDoc->appendChild($rootTag);
        return $xmlDoc->saveXML($rootTag);
    }
    
    /**
     * @param DOMElement $target
     * @param Aggregator $labelSource
     * @throws UnableToCreateXml
     */
    protected function setLabelAttribute(\DOMElement $target, Aggregator $labelSource)
    {
        $target->setAttribute("label", $this->getLabelFor($labelSource, $target));
    }
    
    /**
     * @param Aggregator $a
     * @param DOMElement $where
     * @throws UnableToCreateXml
     */
    protected function getLabelFor(Aggregator $a, \DOMElement $where)
    {
        try {
            return $a->accept($this->labelingVisitor);
        } catch (UnableToGenerateLabel $e) {
            throw new UnableToCreateXml("it was impossible to generate a label for " . get_class($a));
        }
    }
    
    /**
     * @return String class that is supported by this presenter
     */
    abstract protected function getClassOfSupportedAggregators();
    
    /**
     * @param Aggregator $a (implements the class returned by the getClassOfSupportedAggregators method)
     * @param DOMElement $destination target destination where data held by $a should be put
     * @return null
     * @throws UnableToCreateXml
     */
    abstract protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination);
    
    /**
     * @return String root XML tag of this aggregator representation, like "counter"
     */
    abstract protected function getRootTagName();
    
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
    
    /**
     * @param Aggregator $a
     * @throws UnableToCreateXml
     */
    private function throwExceptionIfUnsupported(Aggregator $a)
    {
        $supportedClass = $this->getClassOfSupportedAggregators();
        if (!($a instanceof $supportedClass)) {
            throw new UnableToCreateXml(get_class($this) . " doesn't support " . get_class($a));
        }
    }
}

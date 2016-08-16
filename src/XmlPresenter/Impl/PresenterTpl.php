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

/**
 * Template of an xml presenter.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class PresenterTpl implements XmlPresenter, LabelingVisitorUser
{
    /**
     * @var LabelingVisitor
     */
    private $labelingVisitor;
    
    public function __construct()
    {
        $this->labelingVisitor = new NullLabelingVisitor();
    }
    
    public function setLabelingPresenter(LabelingVisitor $v)
    {
        $this->labelingVisitor = $v;
    }

    public function visit(Aggregator $a)
    {
        $this->throwExceptionIfUnsupported($a);
        $xmlDoc = new DOMDocument();
        $rootTag = $xmlDoc->createElement($this->getRootTagName());
        $rootTag->setAttribute("label", $this->getLabelFor($a));
        $this->putDataHeldByAggregator($a, $rootTag);
        $xmlDoc->appendChild($rootTag);
        return $xmlDoc->saveXML($rootTag);
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
     */
    private function throwExceptionIfUnsupported(Aggregator $a)
    {
        $supportedClass = $this->getClassOfSupportedAggregators();
        if (!($a instanceof $supportedClass)) {
            throw new UnableToCreateXml(get_class($this) . " doesn't support " . get_class($a));
        }
    }
    
    /**
     * @param Aggregator $a
     * @return String
     * @throws UnableToCreateXml
     */
    private function getLabelFor(Aggregator $a)
    {
        try {
            return $a->accept($this->labelingVisitor);
        } catch (UnableToGenerateLabel $e) {
            throw new UnableToCreateXml("it was impossible to generate a label for " . get_class($a));
        }
    }
}

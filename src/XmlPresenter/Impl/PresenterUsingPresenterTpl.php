<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use DOMElement;
use DOMNode;
use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenterUser;

/**
 * Template of a presenter that uses a presenter of every other aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class PresenterUsingPresenterTpl extends PresenterTpl implements XmlPresenterUser
{
    /**
     * @var XmlPresenter
     */
    private $presenterOfEveryAggregator;
    
    public function __construct()
    {
        parent::__construct();
        $this->presenterOfEveryAggregator = new NullXmlPresenter();
    }

    public function setXmlPresenter(XmlPresenter $p)
    {
        $this->presenterOfEveryAggregator = $p;
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

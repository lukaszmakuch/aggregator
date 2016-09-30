<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;

/**
 * Renders some aggregator with a label provided by some custom label generator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class WithCustomLabelPresenter implements \lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter, \lukaszmakuch\Aggregator\XmlPresenter\XmlPresenterUser
{
    /**
     * @var XmlPresenter
     */
    private $presenterOfEveryAggregator;
    
    public function __construct()
    {
    }
    
    public function visit(Aggregator $a)
    {
        if (!($a instanceof \lukaszmakuch\Aggregator\LabelGenerator\WithCustomLabel)) {
            throw new \lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml();
        }
        
        $xmlDoc = new \DOMDocument();
        $xmlDoc->loadXML($a->getActualAggregator()->accept($this->presenterOfEveryAggregator));
        $xmlDoc->documentElement->setAttribute("label", $a->getLabel());
        return $xmlDoc->saveXML();;
    }

    public function setXmlPresenter(\lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter $p)
    {
        $this->presenterOfEveryAggregator = $p;
    }
}
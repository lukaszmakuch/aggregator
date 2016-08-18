<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use DOMElement;
use DOMText;
use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\PredicateAggregator;

/**
 * Presents predicates.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PredicatePresenter extends PresenterTpl
{
    private $tagName;
    
    /**
     *
     * @param String $tagName
     * @param boolean $addLabel if true, then a label attribute is added to the tag
     */
    public function __construct($tagName)
    {
        parent::__construct();
        $this->tagName = $tagName;
    }

    protected function getClassOfSupportedAggregators()
    {
        return PredicateAggregator::class;
    }
    
    protected function getRootTagName()
    {
        return $this->tagName;
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        $destination->appendChild(new DOMText((int)$a->isTrue()));
    }
}

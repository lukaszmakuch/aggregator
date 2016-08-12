<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;

/**
 * Presents a HierarchicalAggregator.
 *
 * @author Łukasz Makuch <lukasz.makuch@librus.pl>
 */
class HierarchicalAggregatorPresenter extends ScalarPresenterTpl implements ScalarPresenterUser
{
    /**
     * @var ScalarPresenter
     */
    private $nodePresenter;
    
    public function __construct()
    {
        $this->nodePresenter = new PresenterReturningStaticValue("");
    }

    public function setScalarPresenter(ScalarPresenter $presenter)
    {
        $this->nodePresenter = $presenter;
    }

    protected function getSupportedAggregatorClass()
    {
        return HierarchicalAggregator::class;
    }
    
    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator HierarchicalAggregator  */
        return $this->nodePresenter->convertToScalar($aggregator->getAggregatorOfNodes());
    }
}

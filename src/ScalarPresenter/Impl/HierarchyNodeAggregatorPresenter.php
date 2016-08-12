<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\NodeAggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;

/**
 * Presents a single node of a hierarchical aggregator.
 *
 * @author Łukasz Makuch <lukasz.makuch@librus.pl>
 */
class HierarchyNodeAggregatorPresenter extends ScalarPresenterTpl implements ScalarPresenterUser
{
    /**
     * @var ScalarPresenter
     */
    private $aggregatorPresenter;
    
    public function __construct()
    {
        $this->aggregatorPresenter = new PresenterReturningStaticValue("");
    }

    public function setScalarPresenter(ScalarPresenter $presenter)
    {
        $this->aggregatorPresenter = $presenter;
    }

    protected function getSupportedAggregatorClass()
    {
        return NodeAggregator::class;
    }
    
    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator NodeAggregator  */
        return [
            'own' => $this->aggregatorPresenter->convertToScalar($aggregator->getActualAggregator()),
            'children' => array_map(function (Aggregator $childAggregator) {
                return $this->aggregatorPresenter->convertToScalar($childAggregator);
            }, $aggregator->getChildren()),
        ];
    }
}

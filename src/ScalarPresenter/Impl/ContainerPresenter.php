<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\Container\Container;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;


/**
 * Presents value of each of container's elements.
 * 
 * Example output:
 * <pre>
 *     [output of thefirst of its elements, output of the second, ...]
 * </pre>
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ContainerPresenter extends ScalarPresenterTpl implements ScalarPresenterUser
{
    /**
     * @var ScalarPresenter 
     */
    private $presenterOfElements;

    public function __construct()
    {
        $this->presenterOfElements = new PresenterReturningStaticValue("");
    }

    public function setScalarPresenter(ScalarPresenter $presenter)
    {
        $this->presenterOfElements = $presenter;
    }

    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        /* @var $aggregator Container */
        return array_map(function (Aggregator $aggregator) {
            return $this->presenterOfElements->convertToScalar($aggregator);
        }, $aggregator->getAllActualAggregators());
    }

    protected function getSupportedAggregatorClass()
    {
        return Container::class;
    }
}

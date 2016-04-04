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
class ContainerPresenter extends ScalarPresenterTpl
{
    /**
     * @var \lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter 
     */
    private $presenterOfElements;
    
    public function __construct(\lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter $presenterOfElements)
    {
        $this->presenterOfElements = $presenterOfElements;
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

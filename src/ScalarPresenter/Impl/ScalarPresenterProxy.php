<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToConvert;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;

/**
 * Hides many actual implementation behind a common interface.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarPresenterProxy implements ScalarPresenter
{
    private $actualPresenters;
    
    public function __construct()
    {
        $this->actualPresenters = new ClassBasedRegistry();
    }
    
    public function registerActualPresenter(
        $classOfSupportedAggregators,
        ScalarPresenter $actualPresenter
    ) {
        $this->actualPresenters->associateValueWithClasses(
            $actualPresenter,
            [$classOfSupportedAggregators]
        );
    }
    
    public function convertToScalar(Aggregator $aggregator)
    {
        try {
            /* @var $actualPresenter ScalarPresenter */
            $actualPresenter = $this->actualPresenters->fetchValueByObjects([$aggregator]);
            return $actualPresenter->convertToScalar($aggregator);
        } catch (ValueNotFound $e) {
            throw new UnableToConvert("no suitable converter found for " . get_class($aggregator));
        }
    }
}

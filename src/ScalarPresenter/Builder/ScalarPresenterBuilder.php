<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\ScalarPresenter\Builder\Exception\UnableToBuild;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Allows to build a scalar presenter.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ScalarPresenterBuilder
{
    /**
     * @param String $classOfSupportedAggregators
     * @param ScalarPresenter $presenter
     * @param String $presenterTypeAsText
     * 
     * @return ScalarPresenterBuilder self
     */
    public function registerPresenter(
        $classOfSupportedAggregators,
        ScalarPresenter $presenter,
        TextGenerator $labelGeneratorPrototype,
        $presenterTypeAsText
    );
    
    /**
     * @param String $classOfDependentObjects
     * @param String $valueToInject
     * 
     * @return ScalarPresenterBuilder self
     */
    public function registerDependency(
        $classOfDependentObjects,
        $valueToInject
    );

    /**
     * @return ScalarPresenter
     * @throws UnableToBuild
     */
    public function build();
}

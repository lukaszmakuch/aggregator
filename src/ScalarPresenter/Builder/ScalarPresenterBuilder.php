<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\ScalarPresenter\Builder\Exception\UnableToBuild;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\ScalarPresenterExtension;
use lukaszmakuch\Aggregator\ScalarPresenter\PresentingVisitor;

/**
 * Allows to build a scalar presenter.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ScalarPresenterBuilder
{
    /**
     * @param ScalarPresenterExtension
     *
     * @return ScalarPresenterBuilder self
     */
    public function registerExtension(ScalarPresenterExtension $ext);
    
    /**
     * @param String $classOfDependentObjects
     * @param mixed $dependency
     * @return ScalarPresenterBuilder self
     */
    public function registerDependency($classOfDependentObjects, $dependency);

    /**
     * @return PresentingVisitor scalar presenter as a visitor
     * @throws UnableToBuild
     */
    public function build();
}

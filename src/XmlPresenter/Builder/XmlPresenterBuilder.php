<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Builder;

use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;

/**
 * Builds an XML presenter of aggregators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface XmlPresenterBuilder
{
    /**
     * @param String $classOfDependentObjects
     * @param mixed $dependency
     * @return XmlPresenterBuilder self
     */
    public function registerDependency($classOfDependentObjects, $dependency);
    
    /**
     * @param String $classOfSupportedAggregators
     * @param XmlPresenter $itsPresenter
     * @return XmlPresenterBuilder self
     */
    public function registerActualPresenter(
        $classOfSupportedAggregators,
        XmlPresenter $itsPresenter
    );
    
    /**
     * @return XmlPresenter
     */
    public function build();
}

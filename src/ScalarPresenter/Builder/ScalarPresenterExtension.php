<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;

/**
 * Holds together all what's needed in order to add support of a new aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ScalarPresenterExtension
{
    /**
     * @return String class of aggregators supported by this extension
     */
    public function getClassOfSupportedAggregators();
    
    /**
     * @return ScalarPresenter
     */
    public function getScalarPresenter();
    
    /**
     * @return String
     */
    public function getPresenterTypeAsText();
}

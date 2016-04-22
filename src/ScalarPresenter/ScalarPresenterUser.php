<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter;

/**
 * Represents an object that uses the scalar presenter.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ScalarPresenterUser
{
    /**
     * @param ScalarPresenter $presenter
     * 
     * @return null
     */
    public function setScalarPresenter(ScalarPresenter $presenter);
}

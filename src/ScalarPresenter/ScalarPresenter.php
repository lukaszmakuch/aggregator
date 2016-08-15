<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToPresent;

/**
 * Generates a representation of the given aggregator
 * as a scalar value or an array of scalar values.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ScalarPresenter
{
    /**
     * @param Aggregator $aggregator
     *
     * @return mixed a scalar value or an array of scalar values
     * @throws UnableToPresent
     */
    public function convertToScalar(Aggregator $aggregator);
}

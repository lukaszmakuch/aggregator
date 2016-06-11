<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Exception;

use RuntimeException;

/**
 * Thrown when it's impossible to convert an aggregator to a scalar value
 * or an array of scalar values.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class UnableToConvert extends RuntimeException
{
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Exception;

use lukaszmakuch\Aggregator\Exception\VisitorException;

/**
 * Thrown when it's impossible to get a scalar representation of an aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class UnableToPresent extends VisitorException
{
}

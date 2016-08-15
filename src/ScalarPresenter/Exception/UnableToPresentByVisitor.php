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
 * Thrown when the PresentingVisitor is unable to convert an aggregator to a scalar value
 * or an array of scalar values.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class UnableToPresentByVisitor extends VisitorException
{
}

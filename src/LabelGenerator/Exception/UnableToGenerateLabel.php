<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Exception;

use lukaszmakuch\Aggregator\Exception\VisitorException;

/**
 * Thrown when it's impossible to generate a label.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class UnableToGenerateLabel extends VisitorException
{
}

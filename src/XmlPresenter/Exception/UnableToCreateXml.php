<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Exception;

use lukaszmakuch\Aggregator\Exception\VisitorException;

/**
 * Thrown when it's impossible to get an xml representation of an aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class UnableToCreateXml extends VisitorException
{
}

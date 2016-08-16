<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;

/**
 * Returns an empty string.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class NullXmlPresenter implements XmlPresenter
{
    public function visit(Aggregator $a)
    {
        return "";
    }
}

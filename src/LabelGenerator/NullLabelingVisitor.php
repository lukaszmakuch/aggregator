<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Aggregator;

/**
 * Always returns an empty string.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class NullLabelingVisitor implements LabelingVisitor
{
    public function visit(Aggregator $a)
    {
        return '';
    }
}

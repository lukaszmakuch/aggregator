<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\AggregatorVisitor;
use lukaszmakuch\Aggregator\LabelGenerator\Exception\UnableToGenerateLabel;

/**
 * Generates labels for aggregators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface LabelingVisitor extends AggregatorVisitor
{
    /**
     * @param Aggregator $a
     * @return String
     * @throws UnableToGenerateLabel
     */
    public function visit(Aggregator $a);
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\LabelGenerator\Exception\UnableToGenerateLabel;

/**
 * Generates labels for aggregators.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface LabelGenerator
{
    /**
     * @param Aggregator $aggregator
     * @return String generated label
     * @throws UnableToGenerateLabel
     */
    public function getLabelFor(Aggregator $aggregator);
}

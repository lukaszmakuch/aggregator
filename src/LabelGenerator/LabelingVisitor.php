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
use lukaszmakuch\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Generates labels for aggregators.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LabelingVisitor implements AggregatorVisitor
{
    private $labelGenerator;
    
    public function __construct(TextGenerator $labelGenerator)
    {
        $this->labelGenerator = $labelGenerator;
    }
    
    /**
     * @param Aggregator $a
     * @return String
     * @throws UnableToGenerateLabel
     */
    public function visit(Aggregator $a)
    {
        try {
            return $this->labelGenerator->getTextBasedOn($a);
        } catch (UnableToGetText $e) {
            throw new UnableToGenerateLabel();
        }
    }
}

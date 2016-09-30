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
use lukaszmakuch\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\TextGenerator\ObjectToTextConverter;

/**
 * Provides a custom label for an underlying aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class WithCustomLabel implements Aggregator
{
    private $actualAggregator;
    private $alternativeLabelGenerator;
    
    public function __construct(
        Aggregator $actualAggregator,
        ObjectToTextConverter $alternativeLabelGenerator
    ) {
        $this->actualAggregator = $actualAggregator;
        $this->alternativeLabelGenerator = $alternativeLabelGenerator;
    }

    public function __clone()
    {
        $this->actualAggregator = clone $this->actualAggregator;
    }

    public function accept(AggregatorVisitor $v)
    {
        return $v->visit($this);
    }

    public function aggregate($subject)
    {
        $this->actualAggregator->aggregate($subject);
    }
    
    /**
     * @return Aggregator
     */
    public function getActualAggregator()
    {
        return $this->actualAggregator;
    }
    
    /**
     * @return String
     * @throws UnableToGetText
     */
    public function getLabel()
    {
        return $this->alternativeLabelGenerator->getTextBasedOn($this->actualAggregator);
    }
}
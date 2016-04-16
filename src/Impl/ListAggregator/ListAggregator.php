<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Impl\ListAggregator;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Exception\UnableToAggregate;
use lukaszmakuch\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Generates a list of text representations of subjects separated with
 * some given delimiter.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ListAggregator implements Aggregator
{
    private $aggregatedValues = [];
    private $delimiter;
    private $subjectToTextConverter;
    
    public function __construct(
        TextGenerator $subjectToTextConverter,
        $delimiter
    ) {
        $this->delimiter = $delimiter;
        $this->subjectToTextConverter = $subjectToTextConverter;
    }
    
    public function aggregate($subject)
    {
        try {
            $this->aggregatedValues[] = $this->subjectToTextConverter->getTextBasedOn($subject);
        } catch (UnableToGetText $e) {
            throw new UnableToAggregate(
                "impossible to get a textual representation of " . get_class($subject)
            );
        }
    }
    
    /**
     * @return String
     */
    public function getListAsString()
    {
        return join($this->delimiter, $this->aggregatedValues);
    }
}

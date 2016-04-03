<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\LabelGenerator\Exception\UnableToGenerateLabel;
use lukaszmakuch\Aggregator\LabelGenerator\LabelGenerator;

/**
 * Ensures that the given aggregator is of the supported type.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class LabelGeneratorTpl implements LabelGenerator
{
    public function getLabelFor(Aggregator $aggregator)
    {
        $this->throwExceptionIfUnsupported($aggregator);
        return $this->getLabelForImpl($aggregator);
    }

    /**
     * @return String class of supported aggregators
     */
    protected abstract function getClassOfSupportedAggregators();

    protected abstract function getLabelForImpl(Aggregator $aggregator);

    private function throwExceptionIfUnsupported(Aggregator $aggregator)
    {
        $supportedClass = $this->getClassOfSupportedAggregators();
        if (!($aggregator instanceof $supportedClass)) {
            throw new UnableToGenerateLabel(sprintf(
                "%s expected %s, but %s was given",
                __CLASS__,
                $supportedClass,
                get_class($aggregator)
            ));
        }
    }
}

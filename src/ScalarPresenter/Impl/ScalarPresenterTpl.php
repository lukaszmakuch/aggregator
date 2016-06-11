<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToConvert;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;

/**
 * Checks aggregator type.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class ScalarPresenterTpl implements ScalarPresenter
{
    /**
     * @return String class of supported aggregators
     */
    abstract protected function getSupportedAggregatorClass();
    
    public function convertToScalar(Aggregator $aggregator)
    {
        $this->throwExceptionIfUnsupported($aggregator);
        return $this->convertToScalarImpl($aggregator);
    }
    
    /**
     * Can assume that the given aggregator is of the supported type.
     *
     * @return mixed the given aggregator converted to a scalar or an array
     * of scalars
     */
    abstract protected function convertToScalarImpl(Aggregator $aggregator);
    
    /**
     * @param Aggregator $aggregator
     * @throws UnableToConvertToArray if the given aggregator is not supported
     */
    private function throwExceptionIfUnsupported(Aggregator $aggregator)
    {
        $supportedClass = $this->getSupportedAggregatorClass();
        if (!($aggregator instanceof $supportedClass)) {
            throw new UnableToConvert(sprintf(
                "%s expects %s, but %s was given",
                __CLASS__,
                $supportedClass,
                get_class($aggregator)
            ));
        }
    }
}

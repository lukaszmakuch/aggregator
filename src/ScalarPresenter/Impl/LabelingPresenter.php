<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToConvert;
use lukaszmakuch\Aggregator\LabelGenerator\Exception\UnableToGenerateLabel;
use lukaszmakuch\Aggregator\LabelGenerator\LabelGenerator;

/**
 * Adds a label describing the generated result.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LabelingPresenter implements ScalarPresenter
{
    private $actualPresenter;

    private $labelGenerator;

    /**
     * Provides a presenter used to actually convert aggregators into scalars and
     * a label generator which is reponsible for generating labels for aggregators.
     * 
     * @param ScalarPresenter $actualPresenter
     * @param LabelGenerator $labelGenerator
     */
    public function __construct(ScalarPresenter $actualPresenter, LabelGenerator $labelGenerator)
    {
        $this->actualPresenter = $actualPresenter;
        $this->labelGenerator = $labelGenerator;
    }

    /**
     * @param Aggregator $aggregator
     * @return array like
     * <pre>
     * [
     *     'data' => mixed result of actual mapping to array,
     *     'label' => String describing the aggregated result
     * ]
     * </pre>
     * @throws UnableToConvert
     */
    public function convertToScalar(Aggregator $aggregator) 
    {
        try {
            return [
                'label' => $this->labelGenerator->getLabelFor($aggregator),
                'data' => $this->actualPresenter->convertToScalar($aggregator),
            ];
        } catch (UnableToGenerateLabel $e) {
            throw new UnableToConvertToArray(
                sprintf("unable to render a labeled for %s", get_class($aggregator)),
                0,
                $e
            );
        }
    }
}

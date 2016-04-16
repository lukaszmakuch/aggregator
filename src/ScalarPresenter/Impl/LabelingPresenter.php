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
use lukaszmakuch\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Adds a label describing the generated result.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LabelingPresenter implements ScalarPresenter
{
    private $actualPresenter;
    private $labelGenerator;
    private $aggregatorTextualTypeObtainer;

    /**
     * Provides a presenter used to actually convert aggregators into scalars and
     * a label generator which is reponsible for generating labels for aggregators.
     * 
     * @param ScalarPresenter $actualPresenter
     * @param TextGenerator $labelGenerator
     * @param TextGenerator $aggregatorTextualTypeObtainer
     */
    public function __construct(
        ScalarPresenter $actualPresenter, 
        TextGenerator $labelGenerator,
        TextGenerator $aggregatorTextualTypeObtainer
    ) {
        $this->actualPresenter = $actualPresenter;
        $this->labelGenerator = $labelGenerator;
        $this->aggregatorTextualTypeObtainer = $aggregatorTextualTypeObtainer;
    }

    /**
     * @param Aggregator $aggregator
     * @return array like
     * <pre>
     * [
     *     'type' => String name of the aggregator family (like "filter")
     *     'data' => mixed result of actual mapping to array,
     *     'label' => String describing the aggregated result (like "more than 4")
     * ]
     * </pre>
     * @throws UnableToConvert
     */
    public function convertToScalar(Aggregator $aggregator) 
    {
        try {
            return [
                'type' => $this->aggregatorTextualTypeObtainer->getTextBasedOn($aggregator),
                'label' => $this->labelGenerator->getTextBasedOn($aggregator),
                'data' => $this->actualPresenter->convertToScalar($aggregator),
            ];
        } catch (UnableToGetText $e) {
            throw new UnableToConvert(
                sprintf("unable to render a label for %s", get_class($aggregator)),
                0,
                $e
            );
        }
    }
}

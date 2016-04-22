<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Builds an extension by composition.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ExtensionImpl implements ScalarPresenterExtension
{
    private $classOfSupportedAggregators;
    private $labelGeneratorPrototype;
    private $presenter;
    private $presenterTypeAsText;

    /**
     * @param String $classOfSupportedAggregators
     * @param TextGenerator $labelGeneratorPrototype
     * @param ScalarPresenter $presenter
     * @param String $presenterTypeAsText
     */
    public function __construct(
        $classOfSupportedAggregators,
        TextGenerator $labelGeneratorPrototype,
        ScalarPresenter $presenter,
        $presenterTypeAsText
    ) {
        $this->classOfSupportedAggregators = $classOfSupportedAggregators;
        $this->labelGeneratorPrototype = $labelGeneratorPrototype;
        $this->presenter = $presenter;
        $this->presenterTypeAsText = $presenterTypeAsText;
    }

    public function getClassOfSupportedAggregators()
    {
        return $this->classOfSupportedAggregators;
    }

    public function getPresenterTypeAsText()
    {
        return $this->presenterTypeAsText;
    }

    public function getPrototypeOfLabelGenerator()
    {
        return $this->labelGeneratorPrototype;
    }

    public function getScalarPresenter()
    {
        return $this->presenter;
    }
}

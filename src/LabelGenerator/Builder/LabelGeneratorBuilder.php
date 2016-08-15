<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Builder;

use lukaszmakuch\Aggregator\LabelGenerator\Builder\LabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitor;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\Exception\UnableToBuild;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Allows to build a complex label generator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface LabelGeneratorBuilder
{
    /**
     * @param String $classOfSupportedAggregators
     * @param TextGenerator $labelGeneratorPrototype
     *
     * @return LabelGeneratorBuilder self
     */
    public function registerLabelGeneratorPrototype(
        $classOfSupportedAggregators,
        TextGenerator $labelGeneratorPrototype
    );

    /**
     * @param String $classOfDependentLabelGenerator
     * @param String $dependency
     *
     * @return LabelGeneratorBuilder self
     */
    public function registerDependency(
        $classOfDependentLabelGenerator,
        $dependency
    );

    /**
     * @return LabelingVisitor label generator as a visitor
     * @throws UnableToBuild
     */
    public function build();
}

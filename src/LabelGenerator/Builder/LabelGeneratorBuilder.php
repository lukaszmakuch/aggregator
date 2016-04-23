<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Builder;

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
     * @return TextGenerator label generator
     * @throws Exception\UnableToBuild
     */
    public function build();
}

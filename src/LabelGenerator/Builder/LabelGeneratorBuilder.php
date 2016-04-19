<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Builder;

use lukaszmakuch\PropertySetter\SettingStrategy\CallOnlyMethodAsSetter;
use lukaszmakuch\PropertySetter\SilentChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimpleChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimplePropertySetter;
use lukaszmakuch\PropertySetter\TargetSpecifier\PickByClass;
use lukaszmakuch\PropertySetter\ValueSource\UseDirectly;
use lukaszmakuch\TextGenerator\ClassBasedTextGeneratorProxy;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Allows to build a complex label generator.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class LabelGeneratorBuilder
{
    /**
     * Prototypes of generators by classes of aggregators they support.
     * 
     * @var array like [String (class of supported aggregators) => TextGenerator (prototype)]
     */
    private $genProtoByClassOfSupportedAgg = [];
    
    /**
     * @var array like [
     *   String (class of dependent generator) => mixed (dependency), ...
     * ]
     */
    private $dependencies = [];
    
    /**
     * @param String $classOfSupportedAggregators
     * @param TextGenerator $labelGeneratorBuilderPrototype
     * 
     * @return LabelGeneratorBuilder self
     */
    public function registerLabelGeneratorPrototype(
        $classOfSupportedAggregators,
        TextGenerator $labelGeneratorBuilderPrototype
    ) {
        $this->genProtoByClassOfSupportedAgg[$classOfSupportedAggregators] = $labelGeneratorBuilderPrototype;
        return $this;
    }

    /**
     * @param String $classOfDependentLabelGenerator
     * @param String $dependency
     * 
     * @return LabelGeneratorBuilder self
     */
    public function registerDependency(
        $classOfDependentLabelGenerator,
        $dependency
    ) {
        $this->dependencies[$classOfDependentLabelGenerator] = $dependency;
        return $this;
    }

    /**
     * @return TextGenerator label generator
     */
    public function build()
    {
        $dependencySetterChain = new SilentChainOfPropertySetters(
            new SimpleChainOfPropertySetters()
        );
        foreach ($this->dependencies as $classOfDependentObject => $dependency) {
            $dependencySetterChain->add(new SimplePropertySetter(
                new PickByClass($classOfDependentObject), 
                new CallOnlyMethodAsSetter($classOfDependentObject), 
                new UseDirectly($dependency)
            ));
        }
        
        $labelGenerator = new ClassBasedTextGeneratorProxy();
        foreach ($this->genProtoByClassOfSupportedAgg as $supportedAggClass => $generatorProto) {
            $actualGenerator = clone $generatorProto;
            $dependencySetterChain->setPropertiesOf($actualGenerator);
            $labelGenerator->registerActualGenerator(
                $supportedAggClass, 
                $actualGenerator
            );
        }
        
        return $labelGenerator;
    }
}

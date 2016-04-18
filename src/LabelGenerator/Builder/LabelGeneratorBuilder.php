<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Builder;

use lukaszmakuch\PropertySetter\SettingStrategy\CallSetterMethod;
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
     *
     * @var array like [
     *   String (clsas of dependent generator) => [
     *     String (setter method), 
     *     mixed (dependency)
     *   ]
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
     * @param String $setterMethod
     * @param String $dependency
     * 
     * @return LabelGeneratorBuilder self
     */
    public function registerDependency(
        $classOfDependentLabelGenerator,
        $setterMethod,
        $dependency
    ) {
        $this->dependencies[$classOfDependentLabelGenerator] = [
            $setterMethod,
            $dependency
        ];
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
        foreach ($this->dependencies as $classOfDependentObject => $setterAndDependency) {
            $dependencySetterChain->add(new SimplePropertySetter(
                new PickByClass($classOfDependentObject), 
                new CallSetterMethod($setterAndDependency[0]), 
                new UseDirectly($setterAndDependency[1])
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

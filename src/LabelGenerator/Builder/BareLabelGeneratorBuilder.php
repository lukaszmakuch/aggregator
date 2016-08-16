<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Builder;

use lukaszmakuch\Aggregator\LabelGenerator\TextGeneratorBasedLabelingVisitor;
use lukaszmakuch\PropertySetter\PropertySetter;
use lukaszmakuch\PropertySetter\SettingStrategy\CallOnlyMethodAsSetter;
use lukaszmakuch\PropertySetter\SilentChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimpleChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimplePropertySetter;
use lukaszmakuch\PropertySetter\TargetSpecifier\PickByClass;
use lukaszmakuch\PropertySetter\ValueSource\UseDirectly;
use lukaszmakuch\TextGenerator\ClassBasedTextGeneratorProxy;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Without any additional method calls,
 * it build a label generator that supports nothing.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BareLabelGeneratorBuilder implements LabelGeneratorBuilder
{
    /**
     * Prototypes of generators by classes of aggregators they support.
     *
     * @var array like [String (class of supported aggregators) => TextGenerator (prototype)]
     */
    private $genProtoByClassOfSupportedAgg = [];
    
    /**
     * @var PropertySetter
     */
    private $dependencySetter;
    
    public function __construct()
    {
        $this->dependencySetter = new SilentChainOfPropertySetters(
            new SimpleChainOfPropertySetters()
        );
    }
    
    public function registerLabelGeneratorPrototype(
        $classOfSupportedAggregators,
        TextGenerator $labelGeneratorPrototype
    ) {
        $this->genProtoByClassOfSupportedAgg[$classOfSupportedAggregators] = $labelGeneratorPrototype;
        return $this;
    }

    public function registerDependency(
        $classOfDependentLabelGenerator,
        $dependency
    ) {
        $this->dependencySetter->add(new SimplePropertySetter(
            new PickByClass($classOfDependentLabelGenerator),
            new CallOnlyMethodAsSetter($classOfDependentLabelGenerator),
            new UseDirectly($dependency)
        ));
        return $this;
    }

    public function build()
    {
        $labelGenerator = new ClassBasedTextGeneratorProxy();
        foreach ($this->genProtoByClassOfSupportedAgg as $supportedAggClass => $genProto) {
            $actualGenerator = clone $genProto;
            $this->dependencySetter->setPropertiesOf($actualGenerator);
            $labelGenerator->registerActualGenerator(
                $supportedAggClass,
                $actualGenerator
            );
        }

        return new TextGeneratorBasedLabelingVisitor($labelGenerator);
    }
}

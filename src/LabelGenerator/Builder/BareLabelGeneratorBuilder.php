<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Builder;

use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitorUser;
use lukaszmakuch\Aggregator\LabelGenerator\TextGeneratorBasedLabelingVisitor;
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
     * @var array like [String (class of of dependent object) => mixed some dependency]
     */
    private $dependencyByDependentClass = [];
    
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
        $this->dependencyByDependentClass[$classOfDependentLabelGenerator] = $dependency;
        return $this;
    }

    public function build()
    {
        $dispatcher = new ClassBasedTextGeneratorProxy();
        $labelingVisitor = new TextGeneratorBasedLabelingVisitor($dispatcher);
        
        $dependencySetter = new SilentChainOfPropertySetters(new SimpleChainOfPropertySetters());
        $dependencySetter->add(new SimplePropertySetter(
            new PickByClass(LabelingVisitorUser::class),
            new CallOnlyMethodAsSetter(LabelingVisitorUser::class),
            new UseDirectly($labelingVisitor)
        ));
        foreach ($this->dependencyByDependentClass as $c => $d) {
            $dependencySetter->add(new SimplePropertySetter(
                new PickByClass($c),
                new CallOnlyMethodAsSetter($c),
                new UseDirectly($d)
            ));
        }
        
        foreach ($this->genProtoByClassOfSupportedAgg as $supportedAggClass => $genProto) {
            $actualGenerator = clone $genProto;
            $dependencySetter->setPropertiesOf($actualGenerator);
            $dispatcher->registerActualGenerator(
                $supportedAggClass,
                $actualGenerator
            );
        }

        return $labelingVisitor;
    }
}

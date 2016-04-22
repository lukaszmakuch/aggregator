<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\LabelGenerator\Builder\LabelGeneratorBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\LabelingPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ScalarPresenterProxy;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;
use lukaszmakuch\PropertySetter\SettingStrategy\CallOnlyMethodAsSetter;
use lukaszmakuch\PropertySetter\SilentChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimpleChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimplePropertySetter;
use lukaszmakuch\PropertySetter\TargetSpecifier\PickByClass;
use lukaszmakuch\PropertySetter\ValueSource\UseDirectly;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Allows to build a scalar presenter.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarPresenterBuilder
{
    /**
     * @var LabelGeneratorBuilder
     */
    private $labelGeneratorBuilder;

    /**
     * @var ClassBasedTextGenerator
     */
    private $aggregatorTextualTypeGenerator;
    
    /**
     * @var array like String (class of supported aggregators) => ScalarPresenter
     */
    private $presenterProtoByAggregatorClass = [];
    
    /**
     *
     * @var array like String (dependent object class) => mixed dependency
     */
    private $dependencies = [];

    public function __construct()
    {
        $this->labelGeneratorBuilder = new LabelGeneratorBuilder();
        $this->aggregatorTextualTypeGenerator = new ClassBasedTextGenerator();
    }

    /**
     * @param String $classOfSupportedAggregators
     * @param ScalarPresenter $presenter
     * @param String $presenterTypeAsText
     * 
     * @return ScalarPresenterBuilder self
     */
    public function registerPresenter(
        $classOfSupportedAggregators,
        ScalarPresenter $presenter,
        TextGenerator $labelGeneratorPrototype,
        $presenterTypeAsText
    ) {
        $this->labelGeneratorBuilder->registerLabelGeneratorPrototype(
            $classOfSupportedAggregators,
            $labelGeneratorPrototype
        );
        $this->aggregatorTextualTypeGenerator->addTextualRepresentationOf(
            $classOfSupportedAggregators,
            $presenterTypeAsText
        );
        $this->presenterProtoByAggregatorClass[$classOfSupportedAggregators] = $presenter;
        return $this;
    }
    
    /**
     * @param String $classOfDependentObjects
     * @param String $valueToInject
     * 
     * @return ScalarPresenterBuilder self
     */
    public function registerDependency(
        $classOfDependentObjects,
        $valueToInject
    ) {
        $this->labelGeneratorBuilder->registerDependency(
            $classOfDependentObjects,
            $valueToInject
        );
        return $this;
    }

    public function build()
    {
        $dependencySetter = new SilentChainOfPropertySetters(
            new SimpleChainOfPropertySetters()
        );
        foreach ($this->dependencies as $dependentClass => $dependency) {
            $dependencySetter->add(new SimplePropertySetter(
                new PickByClass($dependentClass), 
                new CallOnlyMethodAsSetter($dependentClass), 
                new UseDirectly($dependency)
            ));
        }

        $presenter = new ScalarPresenterProxy();
        $labeledPresenter = new LabelingPresenter(
            $presenter,
            $this->labelGeneratorBuilder->build(),
            $this->aggregatorTextualTypeGenerator
        );
        $dependencySetter->add(new SimplePropertySetter(
            new PickByClass(ScalarPresenterUser::class), 
            new CallOnlyMethodAsSetter(ScalarPresenterUser::class), 
            new UseDirectly($labeledPresenter)
        ));

        foreach ($this->presenterProtoByAggregatorClass as $supportedAggClass => $presenterProxy) {
            $actualPresenter = clone $presenterProxy;
            $dependencySetter->setPropertiesOf($actualPresenter);
            $presenter->registerActualPresenter($supportedAggClass, $actualPresenter);
        }

        return $labeledPresenter;
    }
}

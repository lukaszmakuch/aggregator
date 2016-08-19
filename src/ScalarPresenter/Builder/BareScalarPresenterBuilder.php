<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\ScalarPresenter\Impl\LabelingPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ScalarPresenterProxy;
use lukaszmakuch\Aggregator\ScalarPresenter\PresentingVisitor;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;
use lukaszmakuch\PropertySetter\ChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SettingStrategy\CallOnlyMethodAsSetter;
use lukaszmakuch\PropertySetter\SilentChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimpleChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimplePropertySetter;
use lukaszmakuch\PropertySetter\TargetSpecifier\PickByClass;
use lukaszmakuch\PropertySetter\ValueSource\UseDirectly;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;

/**
 * Without any additional method calls,
 * it build a scalar presenter that supports nothing.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BareScalarPresenterBuilder implements ScalarPresenterBuilder
{
    /**
     * @var ClassBasedTextGenerator
     */
    private $aggregatorTextualTypeGenerator;
    
    private $dependenciesByClassOfDependentObject = [];
    private $prototypesOfPresentsByClassOfSupportedAggregators = [];
    
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        $this->aggregatorTextualTypeGenerator = new ClassBasedTextGenerator();
    }

    public function registerExtension(ScalarPresenterExtension $ext)
    {
        $this->aggregatorTextualTypeGenerator->addTextualRepresentationOf(
            $ext->getClassOfSupportedAggregators(),
            $ext->getPresenterTypeAsText()
        );
        $this->prototypesOfPresentsByClassOfSupportedAggregators[$ext->getClassOfSupportedAggregators()] = $ext->getScalarPresenter();
        return $this;
    }
    
    public function registerDependency($classOfDependentObjects, $dependency)
    {
        $this->dependenciesByClassOfDependentObject[$classOfDependentObjects] = $dependency;
        return $this;
    }

    public function build()
    {
        $presenter = new ScalarPresenterProxy();
        $labeledPresenter = new LabelingPresenter(
            $presenter,
            $this->aggregatorTextualTypeGenerator
        );
        
        $dependencySetter = $this->buildDependencySetter();
        $this->saveInDependencySetter(
            $dependencySetter,
            ScalarPresenterUser::class,
            $labeledPresenter
        );
        $this->putRegisteredDependenciesInDependencySetter($dependencySetter);
        
        foreach ($this->prototypesOfPresentsByClassOfSupportedAggregators as $class => $presenterProto) {
            $presenter->registerActualPresenter(
                $class,
                $this->buildPresenter($presenterProto, $dependencySetter)
            );
        }

        $dependencySetter->setPropertiesOf($labeledPresenter);
        return new PresentingVisitor($labeledPresenter);
    }
    
    
    /**
     * @return ChainOfPropertySetters
     */
    private function buildDependencySetter()
    {
        return new SilentChainOfPropertySetters(
            new SimpleChainOfPropertySetters()
        );
    }
    
    /**
     * @param ChainOfPropertySetters $ds
     * @param String $classOfDependentObjects
     * @param mixed $dependency
     *
     * @return null
     */
    private function saveInDependencySetter(
        ChainOfPropertySetters $ds,
        $classOfDependentObjects,
        $dependency
    ) {
        $ds->add(new SimplePropertySetter(
            new PickByClass($classOfDependentObjects),
            new CallOnlyMethodAsSetter($classOfDependentObjects),
            new UseDirectly($dependency)
        ));
    }
    
    /**
     * Adds all what has been registered by calling the registerDependency method
     * to the given ChainOfPropertySetters.
     * @param ChainOfPropertySetters $ds
     * @return null
     */
    private function putRegisteredDependenciesInDependencySetter(ChainOfPropertySetters $ds)
    {
        foreach ($this->dependenciesByClassOfDependentObject as $dependentClass => $dependency) {
            $this->saveInDependencySetter($ds, $dependentClass, $dependency);
        }
    }
    
    /**
     * @param ScalarPresenter $proto
     * @param ChainOfPropertySetters $ds dependency setter
     * @return XmlPresenter ready to use, with set dependencies
     */
    private function buildPresenter(ScalarPresenter $proto, ChainOfPropertySetters $ds)
    {
        $presenter = clone $proto;
        $ds->setPropertiesOf($presenter);
        return $presenter;
    }
}

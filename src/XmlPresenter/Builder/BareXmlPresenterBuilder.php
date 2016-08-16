<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Builder;

use lukaszmakuch\Aggregator\XmlPresenter\Impl\ClassBasedProxy;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenterUser;
use lukaszmakuch\PropertySetter\ChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SettingStrategy\CallOnlyMethodAsSetter;
use lukaszmakuch\PropertySetter\SilentChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimpleChainOfPropertySetters;
use lukaszmakuch\PropertySetter\SimplePropertySetter;
use lukaszmakuch\PropertySetter\TargetSpecifier\PickByClass;
use lukaszmakuch\PropertySetter\ValueSource\UseDirectly;

/**
 * By default builds a presenter that supports nothing.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BareXmlPresenterBuilder implements XmlPresenterBuilder
{
    private $prototypesOfPresentsByClassOfSupportedAggregators = [];
    private $dependenciesByClassOfDependentObject = [];
    
    public function registerActualPresenter(
        $classOfSupportedAggregators,
        XmlPresenter $itsPresenter
    ) {
        $this->prototypesOfPresentsByClassOfSupportedAggregators[$classOfSupportedAggregators] = $itsPresenter;
        return $this;
    }


    public function build()
    {
        $presenter = new ClassBasedProxy();
        $dependencySetter = $this->buildDependencySetter();
        $this->saveInDependencySetter(
            $dependencySetter,
            XmlPresenterUser::class,
            $presenter
        );
        $this->putRegisteredDependenciesInDependencySetter($dependencySetter);
        foreach ($this->prototypesOfPresentsByClassOfSupportedAggregators as $class => $presenterProto) {
            $presenter->registerActualPresenter(
                $class,
                $this->buildPresenter($presenterProto, $dependencySetter)
            );
        }
        
        return $presenter;
    }

    public function registerDependency($classOfDependentObjects, $dependency)
    {
        $this->dependenciesByClassOfDependentObject[$classOfDependentObjects] = $dependency;
        return $this;
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
     * @param XmlPresenter $proto
     * @param ChainOfPropertySetters $ds dependency setter
     * @return XmlPresenter ready to use, with set dependencies
     */
    private function buildPresenter(XmlPresenter $proto, ChainOfPropertySetters $ds)
    {
        $presenter = clone $proto;
        $ds->setPropertiesOf($presenter);
        return $presenter;
    }
}

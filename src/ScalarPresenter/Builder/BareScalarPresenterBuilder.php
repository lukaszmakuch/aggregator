<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\ScalarPresenter\Builder\Exception\UnableToBuild;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\LabelingPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ScalarPresenterProxy;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;
use lukaszmakuch\PropertySetter\Exception\UnableToSetProperty;
use lukaszmakuch\PropertySetter\SettingStrategy\CallOnlyMethodAsSetter;
use lukaszmakuch\PropertySetter\SilentPropertySetter;
use lukaszmakuch\PropertySetter\SimplePropertySetter;
use lukaszmakuch\PropertySetter\TargetSpecifier\PickByClass;
use lukaszmakuch\PropertySetter\ValueSource\UseDirectly;
use lukaszmakuch\TextGenerator\ClassBasedTextGenerator;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Without any additional method calls,
 * it build a scalar presenter that supports nothing.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BareScalarPresenterBuilder implements ScalarPresenterBuilder
{
    /**
     * @var LabelGenerator
     */
    private $labelGenerator;

    /**
     * @var ClassBasedTextGenerator
     */
    private $aggregatorTextualTypeGenerator;
    
    /**
     * @var array like String (class of supported aggregators) => ScalarPresenter
     */
    private $presenterProtoByAggregatorClass = [];
    
    public function __construct()
    {
        $this->labelGenerator = NULLTextGenerator::getInstance();
        $this->aggregatorTextualTypeGenerator = new ClassBasedTextGenerator();
    }

    public function registerExtension(ScalarPresenterExtension $ext)
    {
        $this->aggregatorTextualTypeGenerator->addTextualRepresentationOf(
            $ext->getClassOfSupportedAggregators(),
            $ext->getPresenterTypeAsText()
        );
        $this->presenterProtoByAggregatorClass[$ext->getClassOfSupportedAggregators()] = $ext->getScalarPresenter();
        return $this;
    }
    
    public function setLabelGenerator(TextGenerator $labelGenerator)
    {
        $this->labelGenerator = $labelGenerator;
    }

    public function build()
    {
        $presenter = new ScalarPresenterProxy();
        $labeledPresenter = new LabelingPresenter(
            $presenter,
            $this->labelGenerator,
            $this->aggregatorTextualTypeGenerator
        );
        $dependencySetter = new SilentPropertySetter(new SimplePropertySetter(
                new PickByClass(ScalarPresenterUser::class), 
                new CallOnlyMethodAsSetter(ScalarPresenterUser::class), 
                new UseDirectly($labeledPresenter)
        ));
        try {
            foreach ($this->presenterProtoByAggregatorClass as $supportedAggClass => $presenterPrototype) {
                $actualPresenter = clone $presenterPrototype;
                $dependencySetter->setPropertiesOf($actualPresenter);
                $presenter->registerActualPresenter(
                    $supportedAggClass,
                    $actualPresenter
                );
            }
            
            return $labeledPresenter;
        } catch (UnableToSetProperty $e) {
            throw new UnableToBuild();
        }
    }
}

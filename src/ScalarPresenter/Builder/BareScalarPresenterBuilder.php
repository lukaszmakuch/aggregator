<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitor;
use lukaszmakuch\Aggregator\LabelGenerator\NullLabelingVisitor;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\Exception\UnableToBuild;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\LabelingPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ScalarPresenterProxy;
use lukaszmakuch\Aggregator\ScalarPresenter\PresentingVisitor;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenterUser;
use lukaszmakuch\PropertySetter\Exception\UnableToSetProperty;
use lukaszmakuch\PropertySetter\SettingStrategy\CallOnlyMethodAsSetter;
use lukaszmakuch\PropertySetter\SilentPropertySetter;
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
     * @var LabelingVisitor
     */
    private $labelingVisitor;

    /**
     * @var ClassBasedTextGenerator
     */
    private $aggregatorTextualTypeGenerator;
    
    /**
     * @var array like String (class of supported aggregators) => ScalarPresenter
     */
    private $presenterProtoByAggregatorClass = [];
    
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        $this->labelingVisitor = new NullLabelingVisitor();
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
    
    public function setLabelingVisitor(LabelingVisitor $labelingVisitor)
    {
        $this->labelingVisitor = $labelingVisitor;
    }

    public function build()
    {
        $presenter = new ScalarPresenterProxy();
        $labeledPresenter = new LabelingPresenter(
            $presenter,
            $this->labelingVisitor,
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
            
            return new PresentingVisitor($labeledPresenter);
        } catch (UnableToSetProperty $e) {
            throw new UnableToBuild();
        }
    }
}

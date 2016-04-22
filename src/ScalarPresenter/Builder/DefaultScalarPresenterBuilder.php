<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Builder;

use lukaszmakuch\Aggregator\Impl\Container\Container;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\LabelGenerator\AggregatorOfSubjectsWithCommonPropertiesLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\CounterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\FilterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\GroupingAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\ListAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\AggregatorOfSubjectsWithCommonPropertiesPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ContainerPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\CounterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\FilterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\GroupingAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ListAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\ScalarPresenter;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\TextGenerator;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;

/**
 * Adds support of built-in aggregators.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class DefaultScalarPresenterBuilder implements ScalarPresenterBuilder
{
    /**
     * @var ScalarPresenterBuilder 
     */
    private $bareBuilder;
    
    public function __construct()
    {
        $this->bareBuilder = (new BareScalarPresenterBuilder())
            ->registerPresenter(
                Counter::class,
                new CounterPresenter(),
                new CounterLabelGenerator(),
                "counter"
            )
            ->registerPresenter(
                ListAggregator::class, 
                new ListAggregatorPresenter(),
                new ListAggregatorLabelGenerator(),
                "list"
            )
            ->registerPresenter(
                Filter::class, 
                new FilterPresenter(),
                new FilterLabelGenerator(),
                "filter"
            )
            ->registerPresenter(
                GroupingAggregator::class, 
                new GroupingAggregatorPresenter(),
                new GroupingAggregatorLabelGenerator(),
                "group"
            )
            ->registerPresenter(
                Container::class, 
                new ContainerPresenter(),
                NULLTextGenerator::getInstance(),
                "container"
            )
            ->registerPresenter(
                AggregatorOfSubjectsWithCommonProperties::class, 
                new AggregatorOfSubjectsWithCommonPropertiesPresenter(),
                new AggregatorOfSubjectsWithCommonPropertiesLabelGenerator(),
                "subjects_with_common_properties"
            )
        ;
    }

    public function registerPresenter(
        $classOfSupportedAggregators,
        ScalarPresenter $presenter,
        TextGenerator $labelGeneratorPrototype,
        $presenterTypeAsText
    ) {
        $this->bareBuilder->registerPresenter(
            $classOfSupportedAggregators,
            $presenter,
            $labelGeneratorPrototype,
            $presenterTypeAsText
        );

        return $this;
    }
    
    public function registerDependency(
        $classOfDependentObjects,
        $valueToInject
    ) {
        $this->bareBuilder->registerDependency($classOfDependentObjects, $valueToInject);
        return $this;
    }

    public function build()
    {
        return $this->bareBuilder->build();
    }
}

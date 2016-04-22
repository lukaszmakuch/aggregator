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
use lukaszmakuch\TextGenerator\NULLTextGenerator;
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
            ->registerExtension(new ExtensionImpl(
                Counter::class,
                new CounterLabelGenerator(),
                new CounterPresenter(),
                "counter"
            ))
            ->registerExtension(new ExtensionImpl(
                ListAggregator::class, 
                new ListAggregatorLabelGenerator(),
                new ListAggregatorPresenter(),
                "list"
            ))
            ->registerExtension(new ExtensionImpl(
                Filter::class, 
                new FilterLabelGenerator(),
                new FilterPresenter(),
                "filter"
            ))
            ->registerExtension(new ExtensionImpl(
                GroupingAggregator::class, 
                new GroupingAggregatorLabelGenerator(),
                new GroupingAggregatorPresenter(),
                "group"
            ))
            ->registerExtension(new ExtensionImpl(
                Container::class, 
                NULLTextGenerator::getInstance(),
                new ContainerPresenter(),
                "container"
            ))
            ->registerExtension(new ExtensionImpl(
                AggregatorOfSubjectsWithCommonProperties::class, 
                new AggregatorOfSubjectsWithCommonPropertiesLabelGenerator(),
                new AggregatorOfSubjectsWithCommonPropertiesPresenter(),
                "subjects_with_common_properties"
            ))
        ;
    }

    public function registerExtension(ScalarPresenterExtension $ext)
    {
        $this->bareBuilder->registerExtension($ext);
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

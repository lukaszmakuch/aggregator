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
use lukaszmakuch\Aggregator\Impl\Counter\MoreThan;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\NodeAggregator;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\Impl\Projection\ProjectionAggregator;
use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitor;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\BareScalarPresenterBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\ExtensionImpl;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\ScalarPresenterBuilder;
use lukaszmakuch\Aggregator\ScalarPresenter\Builder\ScalarPresenterExtension;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\AggregatorOfSubjectsWithCommonPropertiesPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ContainerPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\CounterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\FilterPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\GroupingAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\HierarchicalAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\HierarchyNodeAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ListAggregatorPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\MoreThanPresenter;
use lukaszmakuch\Aggregator\ScalarPresenter\Impl\ProjectionPresenter;

/**
 * Adds support of built-in aggregators.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
                new CounterPresenter(),
                "counter"
            ))
            ->registerExtension(new ExtensionImpl(
                ListAggregator::class,
                new ListAggregatorPresenter(),
                "list"
            ))
            ->registerExtension(new ExtensionImpl(
                Filter::class,
                new FilterPresenter(),
                "filter"
            ))
            ->registerExtension(new ExtensionImpl(
                GroupingAggregator::class,
                new GroupingAggregatorPresenter(),
                "group"
            ))
            ->registerExtension(new ExtensionImpl(
                Container::class,
                new ContainerPresenter(),
                "container"
            ))
            ->registerExtension(new ExtensionImpl(
                AggregatorOfSubjectsWithCommonProperties::class,
                new AggregatorOfSubjectsWithCommonPropertiesPresenter(),
                "subjects_with_common_properties"
            ))
            ->registerExtension(new ExtensionImpl(
                HierarchicalAggregator::class,
                new HierarchicalAggregatorPresenter(),
                "hierarchy"
            ))
            ->registerExtension(new ExtensionImpl(
                NodeAggregator::class,
                new HierarchyNodeAggregatorPresenter(),
                "hierarchy_node"
            ))
            ->registerExtension(new ExtensionImpl(
                ProjectionAggregator::class,
                new ProjectionPresenter(),
                "projection"
            ))
            ->registerExtension(new ExtensionImpl(
                MoreThan::class,
                new MoreThanPresenter(),
                "more_than_predicate"
            ))
        ;
    }

    public function registerExtension(ScalarPresenterExtension $ext)
    {
        $this->bareBuilder->registerExtension($ext);
        return $this;
    }

    public function setLabelingVisitor(LabelingVisitor $labelGenerator)
    {
        $this->bareBuilder->setLabelingVisitor($labelGenerator);
        return $this;
    }

    public function build()
    {
        return $this->bareBuilder->build();
    }
}

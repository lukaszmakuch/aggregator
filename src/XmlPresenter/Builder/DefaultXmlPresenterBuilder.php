<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Builder;

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
use lukaszmakuch\Aggregator\XmlPresenter\Impl\ContainerPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\CounterPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\FilterPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\GroupPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\HierarchyNodePresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\HierarchyPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\ListPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\MoreThanPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\ProjectionPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\Impl\SubjectsWithCommonPropertiesPresenter;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;

/**
 * Builds a presenter that already supports all built-in aggregators.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class DefaultXmlPresenterBuilder implements XmlPresenterBuilder
{
    /**
     * @var XmlPresenterBuilder
     */
    private $builder;
    
    public function __construct()
    {
        $this->builder = (new BareXmlPresenterBuilder())
            ->registerActualPresenter(
                Counter::class,
                new CounterPresenter()
            )
            ->registerActualPresenter(
                ListAggregator::class,
                new ListPresenter()
            )
            ->registerActualPresenter(
                Container::class,
                new ContainerPresenter()
            )
            ->registerActualPresenter(
                Filter::class,
                new FilterPresenter()
            )
            ->registerActualPresenter(
                AggregatorOfSubjectsWithCommonProperties::class,
                new SubjectsWithCommonPropertiesPresenter()
            )
            ->registerActualPresenter(
                GroupingAggregator::class,
                new GroupPresenter()
            )
            ->registerActualPresenter(
                HierarchicalAggregator::class,
                new HierarchyPresenter()
            )
            ->registerActualPresenter(
                NodeAggregator::class,
                new HierarchyNodePresenter()
            )
            ->registerActualPresenter(
                MoreThan::class,
                new MoreThanPresenter()
            )
            ->registerActualPresenter(
                ProjectionAggregator::class,
                new ProjectionPresenter()
            )
        ;
    }

    public function build()
    {
        return $this->builder->build();
    }

    public function registerActualPresenter($classOfSupportedAggregators, XmlPresenter $itsPresenter)
    {
        $this->builder->registerActualPresenter($classOfSupportedAggregators, $itsPresenter);
        return $this;
    }

    public function registerDependency($classOfDependentObjects, $dependency)
    {
        $this->builder->registerDependency($classOfDependentObjects, $dependency);
        return $this;
    }
}

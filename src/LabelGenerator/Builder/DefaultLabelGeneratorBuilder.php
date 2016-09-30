<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator\Builder;

use lukaszmakuch\Aggregator\Impl\Container\Container;
use lukaszmakuch\Aggregator\Impl\Counter\Counter;
use lukaszmakuch\Aggregator\Impl\Counter\MoreThan;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\HierarchicalAggregator;
use lukaszmakuch\Aggregator\Impl\HierarchicalAggregator\NodeAggregator;
use lukaszmakuch\Aggregator\Impl\Limit\Limit;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\Impl\Percentage\Percentage;
use lukaszmakuch\Aggregator\Impl\Projection\ProjectionAggregator;
use lukaszmakuch\Aggregator\Impl\PropertyList\PropertyList;
use lukaszmakuch\Aggregator\LabelGenerator\AggregatorOfSubjectsWithCommonPropertiesLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\Builder\BareLabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\Builder\LabelGeneratorBuilder;
use lukaszmakuch\Aggregator\LabelGenerator\CounterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\FilterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\GroupingAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\HierarchicalAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\HierarchyNodeAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\LimitLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\ListAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\MoreThanLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\PercentageLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\ProjectionLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\PropertyListLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\WithCustomLabel;
use lukaszmakuch\Aggregator\LabelGenerator\WithCustomLabelLabelGenerator;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Supports built-in aggregators.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class DefaultLabelGeneratorBuilder implements LabelGeneratorBuilder
{
    /**
     * @var LabelGeneratorBuilder actual builder
     */
    private $builder;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        $this->builder = (new BareLabelGeneratorBuilder())
            ->registerLabelGeneratorPrototype(
                Counter::class,
                new CounterLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                ListAggregator::class,
                new ListAggregatorLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                Filter::class,
                new FilterLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                GroupingAggregator::class,
                new GroupingAggregatorLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                Container::class,
                NULLTextGenerator::getInstance()
            )
            ->registerLabelGeneratorPrototype(
                AggregatorOfSubjectsWithCommonProperties::class,
                new AggregatorOfSubjectsWithCommonPropertiesLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                NodeAggregator::class,
                new HierarchyNodeAggregatorLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                HierarchicalAggregator::class,
                new HierarchicalAggregatorLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                ProjectionAggregator::class,
                new ProjectionLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                MoreThan::class,
                new MoreThanLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                Percentage::class,
                new PercentageLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                Limit::class,
                new LimitLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                PropertyList::class,
                new PropertyListLabelGenerator()
            )
            ->registerLabelGeneratorPrototype(
                WithCustomLabel::class, 
                new WithCustomLabelLabelGenerator()
            );
    }

    public function registerDependency($classOfDependentLabelGenerator, $dependency)
    {
        return $this->builder->registerDependency($classOfDependentLabelGenerator, $dependency);
    }

    public function registerLabelGeneratorPrototype(
        $classOfSupportedAggregators,
        TextGenerator $labelGeneratorPrototype
    ) {
        return $this->builder->registerLabelGeneratorPrototype(
            $classOfSupportedAggregators,
            $labelGeneratorPrototype
        );
    }

    public function build()
    {
        return $this->builder->build();
    }
}

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
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\AggregatorOfSubjectsWithCommonProperties;
use lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator;
use lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator;
use lukaszmakuch\Aggregator\LabelGenerator\AggregatorOfSubjectsWithCommonPropertiesLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\CounterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\FilterLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\GroupingAggregatorLabelGenerator;
use lukaszmakuch\Aggregator\LabelGenerator\ListAggregatorLabelGenerator;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\TextGenerator;
use lukaszmakuch\Aggregator\Impl\Filter\Filter;

/**
 * Supports built-in aggregators.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class DefaultLabelGeneratorBuilder implements LabelGeneratorBuilder
{
    /**
     * @var LabelGeneratorBuilder actual builder 
     */
    private $builder;

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
        ;
    }

    public function registerDependency($classOfDependentLabelGenerator, $dependency)
    {
        return $this->builder->registerDependency($classOfDependentLabelGenerator, $dependency);
    }

    public function registerLabelGeneratorPrototype($classOfSupportedAggregators, TextGenerator $labelGeneratorPrototype)
    {
        return $this->builder->registerLabelGeneratorPrototype($classOfSupportedAggregators, $labelGeneratorPrototype);
    }

    public function build()
    {
        return $this->builder->build();
    }
}

[![travis](https://travis-ci.org/lukaszmakuch/aggregator.svg)](https://travis-ci.org/lukaszmakuch/aggregator)
# Aggregator
Makes generating statistics database agnostic and flexible.

This document briefly describes the library. For more details, check unit tests.
## Why is it interesting?
A simple aggregator
```php
$aggregator = new GroupingAggregator(
    new AgeReader(),
    new ListAggregator(new NameReader(), ", ")
);
```
given
```php
$aggregator->aggregate(new Cat(['name' => 'Henry', 'age' => 5]));
$aggregator->aggregate(new Cat(['name' => 'Mruczek', 'age' => 2]));
$aggregator->aggregate(new Cat(['name' => 'Meow', 'age' => 2]));
$aggregator->aggregate(new Cat(['name' => 'Bob', 'age' => 2]));
$aggregator->aggregate(new Cat(['name' => 'Tim', 'age' => 5]));
```
gives
```json
{
    "type": "group",
    "label": "grouped by age",
    "data": [{
        "type": "subjects_with_common_properties",
        "label": "age 5",
        "data": {
            "type": "list",
            "label": "list",
            "data": "Henry, Tim"
        }
    }, {
        "type": "subjects_with_common_properties",
        "label": "age 2",
        "data": {
            "type": "list",
            "label": "list",
            "data": "Mruczek, Meow, Bob"
        }
    }]
}
```
## About the library
### Built-in aggregators
There are few basic aggregators built-in

#### Counter
*lukaszmakuch\Aggregator\Impl\Counter\Counter*

Counts all given subjects.

#### ListAggregator
*lukaszmakuch\Aggregator\Impl\ListAggregator\ListAggregator*

Generates a list of text representations of subjects separated with some given delimiter.

#### Filter
*lukaszmakuch\Aggregator\Impl\Filter\Filter*

Takes into account only those subjects that meet the given requirement.

#### GroupingAggregator
*lukaszmakuch\Aggregator\Impl\GroupingAggregator\GroupingAggregator*

Groups subjects by some property.

#### Container
*lukaszmakuch\Aggregator\Impl\Container\Container*

Holds many actual aggregators and passes to them all what's passed to it.

### LabelGenerator
An instance of [*lukaszmakuch\TextGenerator\TextGenerator*](https://github.com/lukaszmakuch/text-generator) is used to generate labels for aggregators.

#### LabelGeneratorBuilder
Because of the complex nature of possible labels, there's a builder that makes it easier to get a generator that fits your needs.

```php
$labelGenerator = (new DefaultLabelGeneratorBuilder())
    ->registerDependency(
        PropertyToTextConverterUser::class,
        (new ClassBasedTextGeneratorProxy())->registerActualGenerator(
            Age::class,
            new AgeToTextConverter()
        )
    )
    ->registerLabelGeneratorPrototype(
        CustomAggregator::class,
        new CustomAggregatorLabelGenerator()
    )
    ->build()
;
```
### ScalarPresenter
Generates a representation of the given aggregator as a scalar value or an array of scalar values.
#### ScalarPresenterBuilder
Because of the complex nature of possible aggregator composites, there's a builder that makes it easier to get a scalar presenter that fits your needs.
```php
$scalarPresenter = (new DefaultScalarPresenterBuilder())
    ->setLabelGenerator($labelGenerator)
    ->registerExtension(new ExtensionImpl(
        CustomAggregator::class,
        new CustomAggregatorPresenter(),
        "some_custom_aggregator"
    ))
    ->build();
```
## How to install
```
$ composer require lukaszmakuch/aggregator
```

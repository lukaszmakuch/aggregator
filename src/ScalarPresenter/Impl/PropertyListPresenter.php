<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\ScalarPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\PropertyList\PropertyList;
use lukaszmakuch\Aggregator\LabelGenerator\PropertyToTextConverterUser;
use lukaszmakuch\Aggregator\ScalarPresenter\Exception\UnableToPresent;
use lukaszmakuch\Aggregator\SubjectProperty\ComparableProperty;
use lukaszmakuch\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Presents lists of properties.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PropertyListPresenter extends ScalarPresenterTpl implements PropertyToTextConverterUser
{
    /**
     * @var TextGenerator
     */
    private $propertyAsText;
    
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct()
    {
        $this->propertyAsText = NULLTextGenerator::getInstance();
    }

    public function setPropertyToTextConverter(TextGenerator $converter)
    {
        $this->propertyAsText = $converter;
    }

    protected function getSupportedAggregatorClass()
    {
        return PropertyList::class;
    }

    protected function convertToScalarImpl(Aggregator $aggregator)
    {
        try {
            /* @var $aggregator PropertyList */
            return array_map(function (ComparableProperty $p) {
                return $this->propertyAsText->getTextBasedOn($p);
            }, $aggregator->getProperties());
        } catch (UnableToGetText $e) {
            throw new UnableToPresent('unable to convert a property to text');
        }
    }
}

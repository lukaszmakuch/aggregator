<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use DOMElement;
use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\Impl\PropertyList\PropertyList;
use lukaszmakuch\Aggregator\LabelGenerator\PropertyToTextConverterUser;
use lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml;
use lukaszmakuch\TextGenerator\Exception\UnableToGetText;
use lukaszmakuch\TextGenerator\NULLTextGenerator;
use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Renders list of properties.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class PropertyListPresenter extends PresenterTpl implements PropertyToTextConverterUser
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
        parent::__construct();
        $this->propertyAsText = NULLTextGenerator::getInstance();
    }
    
    public function setPropertyToTextConverter(TextGenerator $converter)
    {
        $this->propertyAsText = $converter;
    }
    
    protected function getClassOfSupportedAggregators()
    {
        return PropertyList::class;
    }

    protected function getRootTagName()
    {
        return "properties";
    }

    protected function putDataHeldByAggregator(Aggregator $a, DOMElement $destination)
    {
        /* @var $a PropertyList */
        $this->setLabelAttribute($destination, $a);
        try {
            foreach ($a->getProperties() as $property) {
                $destination->appendChild($destination->ownerDocument->createElement(
                    "value",
                    $this->propertyAsText->getTextBasedOn($property)
                ));
            }
        } catch (UnableToGetText $e) {
            throw new UnableToCreateXml("unable to convert a property to text");
        }
    }
}

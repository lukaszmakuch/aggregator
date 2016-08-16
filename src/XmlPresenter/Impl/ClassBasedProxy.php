<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter\Impl;

use lukaszmakuch\Aggregator\Aggregator;
use lukaszmakuch\Aggregator\XmlPresenter\Exception\UnableToCreateXml;
use lukaszmakuch\Aggregator\XmlPresenter\XmlPresenter;
use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;

/**
 * Picks the correct presenter based on the class of the visited aggregator.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassBasedProxy implements XmlPresenter
{
    /**
     * @var ClassBasedRegistry;
     */
    private $presentersByClasses;

    public function __construct()
    {
        $this->presentersByClasses = new ClassBasedRegistry();
    }

    /**
     * @param String $classOfSupportedAggregators
     * @param XmlPresenter $itsPresenter
     * @return null
     */
    public function registerActualPresenter(
        $classOfSupportedAggregators,
        XmlPresenter $itsPresenter
    ) {
        $this->presentersByClasses->associateValueWithClasses(
            $itsPresenter,
            [$classOfSupportedAggregators]
        );
    }

    public function visit(Aggregator $a)
    {
        return $a->accept($this->findPresenterOf($a));
    }

    /**
     * @param Aggregator $a
     * @return XmlPresenter
     * @throws UnableToCreateXml
     */
    private function findPresenterOf(Aggregator $a)
    {
        try {
            return $this->presentersByClasses->fetchValueByObjects([$a]);
        } catch (ValueNotFound $e) {
            throw new UnableToCreateXml("no xml presenter found for " . get_class($a));
        }
    }
}

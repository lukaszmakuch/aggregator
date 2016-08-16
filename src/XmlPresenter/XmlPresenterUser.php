<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\XmlPresenter;

/**
 * User of an instance of XmlPresenterUser,
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface XmlPresenterUser
{
    /**
     * @param XmlPresenter $p
     * @return null
     */
    public function setXmlPresenter(XmlPresenter $p);
}

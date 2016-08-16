<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\Aggregator\LabelGenerator\LabelingVisitor;

/**
 * User of a LabelingVisitor instance.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface LabelingVisitorUser
{
    /**
     * @param LabelingVisitor $v
     *
     * @return null
     */
    public function setLabelingPresenter(LabelingVisitor $v);
}

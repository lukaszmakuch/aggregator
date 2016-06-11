<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\LabelGenerator;

use lukaszmakuch\TextGenerator\TextGenerator;

/**
 * Describes a label generator which uses a text generator converting
 * a property to text.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface PropertyToTextConverterUser
{
    /**
     * @param TextGenerator $converter
     * @return null
     */
    public function setPropertyToTextConverter(TextGenerator $converter);
}

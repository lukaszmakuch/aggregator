<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\SubjectProjector;

use lukaszmakuch\Aggregator\SubjectProjector\Exception\UnableToProject;

/**
 * Gets a different represenation of the given subject.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface SubjectProjector
{
    /**
     * @param mixed $subject any possible subject
     * @return mixed different representation of the given subject
     * @throws UnableToProject
     */
    public function getDifferentRepresentationOf($subject);
}

<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\Cat;

/**
 * Test subject.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Cat
{
    private $itsParams;
    
    public function __construct(array $itsParams = [])
    {
        $defaultParams = [
            'name' => "Anoncat",
            'age' => 2,
            'color' => 'transparent',
        ];
        $this->itsParams = array_merge($defaultParams, $itsParams);
    }
    
    /**
     * @return String
     */
    public function getName()
    {
        return $this->itsParams['name'];
    }
    
    /**
     * @return int years
     */
    public function getAge()
    {
        return $this->itsParams['age'];
    }
    
    /**
     * @return String color name
     */
    public function getColor()
    {
        return $this->itsParams['color'];
    }
}

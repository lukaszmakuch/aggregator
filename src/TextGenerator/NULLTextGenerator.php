<?php

/**
 * This file is part of the Aggregator library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Aggregator\TextGenerator;

/**
 * Returns an empty string for any object.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class NULLTextGenerator implements TextGenerator
{
    private static $instance;
    
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getTextBasedOn($something)
    {
        return "";
    }
}

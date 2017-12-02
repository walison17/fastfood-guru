<?php 

namespace App\Core; 


use Slim\Container as SlimContainer; 

class Container extends SlimContainer {

    /** Container */
    private static $instance; 

    public static function instance($settings = []) 
    {
        if (! isset(self::$instance)) {
            self::$instance = new static($settings);
        }

        return self::$instance;
    }       
}
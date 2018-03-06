<?php
namespace App;

use Carbon\Carbon;

/**
 * Base Controller class
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 2.0
 * @copyright (c) 2018
 */
class Controller
{
    // PROPERTIES =============================================================
    
    // PUBLIC STATIC METHODS ==================================================
    /**
     * Returns the value of given query param field.
     * @param string $name   The name of query param.
     * @param mixed $default Default value if parameter is not present.
     * @return mixed
     */
    public static function get( string $name, $default = null )
    {
        // Get Request
        $request = \Flight::request();
        
        if( isset( $request->query[$name] ) )
            return $request->query[$name];
        else
            return $default;
    }
    
    /**
     * Returns the value of given post data field.
     * @param string $name   The name of data field.
     * @param mixed $default Default value if field is not present.
     * @return mixed
     */
    public static function post( string $name, $default = null )
    {
        // Get Request
        $request = \Flight::request();
        
        if( isset( $request->data[$name] ) )
            return $request->data[$name];
        else
            return $default;
    }
}

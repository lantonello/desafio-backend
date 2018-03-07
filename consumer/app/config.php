<?php

// Default path for Views
Flight::set('flight.views.path', __DIR__ . '/views');

// Real application path
Flight::set('real_path', __DIR__ . '/../');

// Base application URL
Flight::set('base_url', baseUrl());


function baseUrl()
{
    $server   = $_SERVER['SERVER_NAME'];
    $protocol = $_SERVER["SERVER_PROTOCOL"];
    $protocol = ( (strpos($protocol, "https") !== false) ? "https" : "http" );
    $script   = $_SERVER["SCRIPT_NAME"];
    //$req_uri  = $_SERVER["REQUEST_URI"];
    $slash    = strrpos($script, "/");
    $url      = ( ($slash < strlen($script)) ? substr($script, 0, ($slash + 1)) : $script );
    
    return ($protocol . '://' . $server . $url);
}
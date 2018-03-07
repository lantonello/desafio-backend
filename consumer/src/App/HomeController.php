<?php
namespace App;

use Aura\Session\SessionFactory;

/**
 * HomePage controller
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 2.0
 * @copyright (c) 2018
 */
class HomeController
{
    /**
     * Shows the HomePage
     */
    public static function index()
    {
        // The local API URL
        $base_url = \Flight::get('base_url');
        $api_url  = str_replace('/consumer/public', '', $base_url) . 'api/public/auth';
        
        $data = [ 'base_url' => $base_url, 'action' => $api_url ];
        
        \Flight::render('index', $data, 'content');
        \Flight::render('master-layout', $data);
    }
    
    /**
     * Shows the Tickets page
     */
    public static function tickets()
    {
        // The local API URL
        $base_url = \Flight::get('base_url');
        $api_url  = str_replace('/consumer/public', '', $base_url) . 'api/public/tickets';
        
        $data = [ 'base_url' => $base_url, 'api_url' => $api_url ];
        
        \Flight::render('tickets', $data, 'content');
        \Flight::render('master-layout', $data);
    }
}

<?php
// Includes the Composer autoload
require __DIR__ . '/vendor/autoload.php';

use App\TicketClassifier;

// First, check running mode
if( isset($_SERVER['HTTP_HOST']) )
{
    echo '<h1>This script must be run on Command Line Interface</h1>';
    exit();
}

// The JSON ticket filepath
$json_file = str_replace('classifier', '', __DIR__) . 'tickets.json';

// Instantiate the Ticket Classifier
$classifier = new TicketClassifier( $json_file );

// Defines the Force mode
$force = false;

if( isset($argv[1]) && ($argv[1] === 'force') )
    $force = true;

// Running the Classification
$classifier->classify( $force );


<?php

Flight::route('GET /', function(){
    echo 'NeoAssist Tickets API';
});

// User authentication
Flight::route('POST /auth', ['App\UserController', 'getToken']);

// Tickets listing
Flight::route('GET /tickets', ['App\TicketsController', 'index']);


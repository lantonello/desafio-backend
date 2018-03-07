<?php

// ====================================================================================================================
// HomePage
Flight::route('GET /', ['App\HomeController', 'index']);

// Tickets list
Flight::route('GET /tickets', ['App\HomeController', 'tickets']);
<?php

use DI\Container;
use repository\PostRepository;
use repository\MoviesRepository;

// Get the DI container instance
$container = require __DIR__ . '/db.php';

// Include all PHP files in the repository directory
foreach (glob(__DIR__ . '/../src/repository/*.php') as $filename) {
    require_once $filename;
}

// Define the 'repository.post' service
$container->set('repository.post', function (Container $app) {
    // Access the 'database' dependency from the container
    $database = $app->get('database');

    return new PostRepository($database);
});

# Define the 'repository.movies' service
$container->set('repository.movies', function (Container $app) {
    // Access the 'database' dependency from the container
    $database = $app->get('database');

    return new MoviesRepository($database);
});

// Return the DI container
return $container;

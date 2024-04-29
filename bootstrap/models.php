<?php

use DI\Container;
use Model\PostModel;
use Model\MoviesModel;
use repository\PostRepository;

// Create a new DI container instance
//$app = new Container();
$container = require __DIR__ . '/repositories.php';

// Include all PHP files in the model and repository directories
foreach (glob(__DIR__ . '/../src/model/*.php') as $filename) {
    require_once $filename;
}

// Define the 'model.post'
$container->set('model.post', function (Container $app) {
    return new PostModel($app->get('repository.post'));
});

# Define the 'model.movies'
$container->set('model.movies', function (Container $app) {
    return new MoviesModel($app->get('repository.movies'));
});

// Return the DI container
return $container;

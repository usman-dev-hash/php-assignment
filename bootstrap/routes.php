<?php

use FastRoute\RouteCollector;

return function(RouteCollector $router) {
    // Define routes
    $router->addRoute('POST', '/api/post', ['controller\PostController', 'createPostAction']);
    $router->addRoute('GET', '/api/post/{id}', ['controller\PostController', 'getPostAction']);

    # Define movies routes
    $router->addRoute('POST', '/api/v1/movies', ['controller\MoviesController', 'createMoviesAction']);
    $router->addRoute('GET', '/api/v1/movies/{id}', ['controller\MoviesController', 'getAMoviesAction']);
    $router->addRoute('GET', '/api/v1/all-movies', ['controller\MoviesController', 'getMoviesAction']);
    // Add more routes as needed
};
<?php

use DI\Container;

// Create a new DI container instance
$app = new Container();

$app->set('database', function (Container $container) {
    // MySQL connection parameters
    $host = 'db'; // Hostname
    $username = 'root'; // MySQL username
    $password = 'root'; // MySQL password
    $dbname = 'php_assignment'; // Database name

    // Create a new mysqli connection
    $mysqli = new mysqli($host, $username, $password, $dbname);

    // Check for connection errors
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    // Set charset to UTF-8
    $mysqli->set_charset("utf8mb4");

    // Return the mysqli connection
    return $mysqli;
});

/*$app->set('redis', function (Container $c) {
    return new RedisConnection();
});*/

// Return the DI container
return $app;

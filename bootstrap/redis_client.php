<?php

require 'vendor/autoload.php';

return new Predis\Client([
    'host' => 'redis', // Service name if using network, or 'localhost' if not
    'port' => 8082, // Assuming you changed the port as mentioned earlier
]);
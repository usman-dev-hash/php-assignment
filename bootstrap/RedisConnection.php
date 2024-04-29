<?php
namespace bootstrap;

use Predis\Client;

class RedisClient
{
    private static $host = '172.25.0.4';
    private static $port = '6379';
    private static $client;

    public static function getClient()
    {
        if (!isset(self::$client)) {
            self::$client = new Client([
                'host' => self::$host,
                'port' => self::$port,
            ]);
        }

        return self::$client;
    }
}

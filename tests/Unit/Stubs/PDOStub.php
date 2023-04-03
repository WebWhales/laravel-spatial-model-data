<?php

namespace Stubs;

class PDOStub extends \PDO
{
    public function __construct()
    {
        $host     = env('DB_HOST');
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        parent::__construct("mysql:host=$host;dbname=$database", $username, $password);
    }
}

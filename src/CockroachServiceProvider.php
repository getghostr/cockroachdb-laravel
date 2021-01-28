<?php

namespace Ghostr\Cockroach;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\PostgresConnector;

class CockroachServiceProvider extends ServiceProvider
{
    public function register()
    {
        Connection::resolverFor('cockroach', function ($connection, $database, $prefix, $config) {
            $connection = (new PostgresConnector())->connect($config);

            return new CockroachConnection($connection, $database, $prefix, $config);
        });
    }
}

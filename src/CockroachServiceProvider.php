<?php

namespace Ghostr\Cockroach;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\PostgresConnector;

class CockroachServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('db.connector.cockroach', function ($db) {
            return new PostgresConnector;
        });

        Connection::resolverFor('cockroach', function ($connection, $database, $prefix, $config) {
            return new CockroachConnection($connection, $database, $prefix, $config);
        });
    }
}

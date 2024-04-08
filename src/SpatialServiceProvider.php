<?php

namespace Grimzy\LaravelMysqlSpatial;

use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class SpatialServiceProvider.
 */
class SpatialServiceProvider extends DatabaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });

        Blueprint::macro('point', function ($column, $srid = 0) {
            return $this->geography($column, subtype: 'point', srid: $srid);
        });

        Blueprint::macro('lineString', function ($column) {
            return $this->geometry($column, subtype: 'linestring');
        });

        Blueprint::macro('polygon', function ($column) {
            return $this->geometry($column, subtype: 'polygon');
        });

        Blueprint::macro('multiPoint', function ($column) {
            return $this->geography($column, subtype: 'multipoint');
        });

        Blueprint::macro('multiLineString', function ($column) {
            return $this->geometry($column, subtype: 'multilinestring');
        });

        Blueprint::macro('multiPolygon', function ($column) {
            return $this->geometry($column, subtype: 'multipolygon');
        });

        Blueprint::macro('geometryCollection', function ($column) {
            return $this->geometry($column, subtype: 'geometrycollection');
        });
    }
}

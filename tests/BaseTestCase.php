<?php

namespace Grimzy\LaravelMysqlSpatial\Tests;

use Grimzy\LaravelMysqlSpatial\SpatialServiceProvider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Laravel\BrowserKitTesting\TestCase;
use Mockery;
use PDO;
use RuntimeException;
use const SORT_STRING;

abstract class BaseTestCase extends TestCase
{
    protected $after_fix = false;

    /**
     * Deletes the database.
     *
     * @param bool $recreate If true, then creates the database after deletion
     */
    private static function cleanDatabase($recreate = true)
    {
        $database = env('DB_DATABASE');

        try {
            $pdo = new PDO(
                sprintf(
                    'mysql:host=%s;port=%d;',
                    env('DB_HOST'),
                    env('DB_PORT')
                ),
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            );

            $pdo->exec(sprintf('DROP DATABASE IF EXISTS %s', $database));
            if ($recreate) {
                $pdo->exec(sprintf(
                    'CREATE DATABASE %s CHARACTER SET %s COLLATE %s;',
                    $database,
                    env('DB_CHARSET', 'utf8mb4'),
                    env('DB_COLLATION', 'utf8mb4_unicode_ci')
                ));
            }
        } catch (RuntimeException $exception) {
            throw $exception;
        }
    }

    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';
        $app->register(SpatialServiceProvider::class);

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql.host', env('DB_HOST'));
        $app['config']->set('database.connections.mysql.port', env('DB_PORT'));
        $app['config']->set('database.connections.mysql.database', env('DB_DATABASE'));
        $app['config']->set('database.connections.mysql.username', env('DB_USERNAME'));
        $app['config']->set('database.connections.mysql.password', env('DB_PASSWORD'));
        $app['config']->set('database.connections.mysql.modes', [
            'ONLY_FULL_GROUP_BY',
            'STRICT_TRANS_TABLES',
            'NO_ZERO_IN_DATE',
            'NO_ZERO_DATE',
            'ERROR_FOR_DIVISION_BY_ZERO',
            'NO_ENGINE_SUBSTITUTION',
        ]);

        return $app;
    }

    /**
     * Setup DB before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        self::cleanDatabase(true);

        $this->after_fix = $this->isMySQL8AfterFix();

        $this->app->bind('db.schema', function ($app) {
            return $app['db']->connection()->getSchemaBuilder();
        });

        $this->onMigrations(function (Migration $migration) {
            $migration->up();
        });
    }

    protected function tearDown(): void
    {
        $this->onMigrations(function (Migration $migration) {
            $migration->down();
        }, true);

        Mockery::close();

        parent::tearDown();
    }

    // MySQL 8.0.4 fixed bug #26941370 and bug #88031
    private function isMySQL8AfterFix()
    {
        $results = DB::select('select version()');
        $mysql_version = $results[0]->{'version()'};

        return version_compare($mysql_version, '8.0.4', '>=');
    }

    protected function assertDatabaseHas($table, array $data, $connection = null)
    {
        if (method_exists($this, 'seeInDatabase')) {
            $this->seeInDatabase($table, $data, $connection);
        } else {
            parent::assertDatabaseHas($table, $data, $connection);
        }
    }

    private function onMigrations(\Closure $closure, $reverse_sort = false)
    {
        $fileSystem = new Filesystem();

        $migrations = $fileSystem->files(__DIR__.'/Migrations');
        $reverse_sort ? rsort($migrations, SORT_STRING) : sort($migrations, SORT_STRING);

        foreach ($migrations as $file) {
            $closure($fileSystem->getRequire($file));
        }
    }

    protected function assertException($exceptionName, $exceptionMessage = '', $exceptionCode = 0)
    {
        if (method_exists(parent::class, 'expectException')) {
            parent::expectException($exceptionName);
            parent::expectExceptionCode($exceptionCode);

            if ($exceptionMessage) {
                parent::expectExceptionMessage($exceptionMessage);
            }
        } else {
            $this->setExpectedException($exceptionName, $exceptionMessage, $exceptionCode);
        }
    }
}

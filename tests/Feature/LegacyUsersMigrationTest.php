<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function withIsolatedUsersMigrationDatabase(Closure $test): void
{
    $previousConnection = DB::getDefaultConnection();
    config(['database.connections.users_migration_test' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
        'foreign_key_constraints' => true,
    ]]);
    DB::setDefaultConnection('users_migration_test');

    try {
        $test();
    } finally {
        DB::setDefaultConnection($previousConnection);
        DB::purge('users_migration_test');
    }
}

test('fallback migration creates an accounts table on clean installations', function () {
    withIsolatedUsersMigrationDatabase(function () {
        $migration = require database_path('migrations/2026_09_14_000000_create_users_table_when_missing.php');
        $migration->up();

        expect(Schema::hasColumns('users', ['name', 'password', 'autorizado', 'email_verified_at']))->toBeTrue();
        DB::table('users')->insert(['name' => 'Client', 'password' => 'hash']);
        expect(DB::table('users')->value('autorizado'))->toBe(0);
    });
});

test('fallback migration preserves an existing legacy schema and accounts on rollback', function () {
    withIsolatedUsersMigrationDatabase(function () {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        DB::table('users')->insert(['name' => 'Existing client']);
        $columns = Schema::getColumnListing('users');

        $migration = require database_path('migrations/2026_09_14_000000_create_users_table_when_missing.php');
        $migration->up();
        expect(Schema::getColumnListing('users'))->toBe($columns);

        // A real rollback loads a new migration instance in another process.
        $rollback = require database_path('migrations/2026_09_14_000000_create_users_table_when_missing.php');
        $rollback->down();
        expect(DB::table('users')->value('name'))->toBe('Existing client');
    });
});

test('fallback rollback also preserves newly created accounts', function () {
    withIsolatedUsersMigrationDatabase(function () {
        $migration = require database_path('migrations/2026_09_14_000000_create_users_table_when_missing.php');
        $migration->up();
        DB::table('users')->insert(['name' => 'New client', 'password' => 'hash']);

        $migration->down();
        expect(DB::table('users')->value('name'))->toBe('New client');
    });
});

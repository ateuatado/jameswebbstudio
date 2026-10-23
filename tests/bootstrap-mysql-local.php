<?php

// Explicit, separate runner for a disposable server on a fixed loopback port.
define('JWS_DISPOSABLE_MYSQL', true);
require dirname(__DIR__) . '/vendor/codeigniter4/framework/system/Test/bootstrap.php';
$validationDb = config('Database');
$validationDb->tests = array_replace($validationDb->tests, [
    'DSN' => '', 'DBDriver' => 'MySQLi', 'hostname' => '127.0.0.1',
    'port' => 33317, 'username' => 'jws_validation', 'password' => '',
    'database' => 'jws_validation_only', 'DBPrefix' => '',
    'charset' => 'utf8mb4', 'DBCollat' => 'utf8mb4_general_ci',
]);
// Application migrations reference the authentication tables.
service('migrations')->setNamespace('CodeIgniter\Shield')->latest('tests');
service('migrations')->setNamespace('CodeIgniter\Settings')->latest('tests');
if (! db_connect('tests')->tableExists('users')) {
    require_once dirname(__DIR__) . '/vendor/codeigniter4/shield/src/Database/Migrations/2020-12-28-223112_create_auth_tables.php';
    (new \CodeIgniter\Shield\Database\Migrations\CreateAuthTables(\Config\Database::forge('tests')))->up();
}

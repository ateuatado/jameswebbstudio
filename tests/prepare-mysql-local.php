<?php

// Run only against the disposable loopback instance, never application settings.
$db = new mysqli('127.0.0.1', 'root', '', '', 33317);
$actualDirectory = str_replace('\\', '/', $db->query('SELECT @@datadir')->fetch_row()[0]);
$expectedDirectory = str_replace('\\', '/', dirname(__DIR__) . '/build/validation-mysql/');
if (strcasecmp(rtrim($actualDirectory, '/'), rtrim($expectedDirectory, '/')) !== 0) {
    throw new RuntimeException('Refusing to prepare a server outside the disposable data directory.');
}
$db->query('CREATE DATABASE IF NOT EXISTS jws_validation_only');
$db->query("CREATE USER IF NOT EXISTS 'jws_validation'@'127.0.0.1' IDENTIFIED BY ''");
$db->query("GRANT ALL ON jws_validation_only.* TO 'jws_validation'@'127.0.0.1'");
echo "Disposable database prepared.\n";

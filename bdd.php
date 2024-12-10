<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;


if (file_exists(__DIR__ . '/.env.local')) {
    $dotenv = Dotenv::createImmutable(__DIR__, '.env.local');
    $dotenv->load();
} else {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$host_name = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$database = $_ENV['DB_DATABASE'];
$user_name = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$bdd = null;

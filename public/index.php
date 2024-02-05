<?php

use Rignchen\SlimExemple\Router\RoutingLogic;

require_once __DIR__ . "/../vendor/autoload.php";

$host = '../data.sqlite';
$dsn = "sqlite:$host";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, null, null, $options);

RoutingLogic::init($pdo);

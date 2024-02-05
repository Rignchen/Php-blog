<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Rignchen\SlimExemple\Logic\RoutingLogic;
use Rignchen\SlimExemple\Logic\Database;

$host = '../data.sqlite';
$dsn = "sqlite:$host";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$db = new Database(new PDO($dsn, null, null, $options));

RoutingLogic::init($db);

<?php

namespace Rignchen\SlimExemple\Router;

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

class RoutingLogic {
    public static function init($pdo) {
        $app = AppFactory::create();

        $app->addRoutingMiddleware();
        $app->addErrorMiddleware(true, true, true);
        $app->add(TwigMiddleware::create($app, $twig));

        $app->run();
    }
}
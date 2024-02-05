<?php

namespace Rignchen\SlimExemple\Logic;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

class Router {
    public static function init(App $app, Database $db): void {
        $app->get('/post/{username}/{postName}', function (Request $request, Response $response, $args) use ($db) {
            $user = $db->get_user($args['username']);
            $data = $db->get_post($user['id'], $args['postName']);
			return $response->getBody()->write("You're watching a post from " . $user['username'] . " called " . $data['title']);
        });
    }
}
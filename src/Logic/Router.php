<?php

namespace Rignchen\SlimExemple\Logic;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

class Router {
    public static function init(App $app, Database $db): void {
        $currentUser = $db->get_user("Rignchen");

        $app->get('/post/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $view = Twig::fromRequest($request);
            $user = $db->get_user($args['username']);
            $data = $db->get_post($user['id'], $args['postName']);
            return $view->render($response, 'post.twig', [
                'pageType' => 'post',
                'data' => $data,
                'creator' => $user,
                'user' => $currentUser
            ]);
        });
        $app->get('/edit/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $view = Twig::fromRequest($request);
            $user = $db->get_user($args['username']);
            $data = $db->get_post($user['id'], $args['postName']);
            if ($currentUser['id'] !== $user['id']) {
                return $response->withHeader('Location', '/post/' . $user['username'] . '/' . $data['title'])->withStatus(302);
            }
            return $view->render($response, 'post.twig', [
                'pageType' => 'edit',
                'data' => $data,
                'creator' => $user,
                'user' => $currentUser
            ]);
        });
        $app->post('/edit/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $user = $db->get_user($args['username']);
            $data = $db->get_post($user['id'], $args['postName']);
            if ($currentUser['id'] === $user['id']) {
                $db->update_post($data, $_POST['content']);
            }
            return $response->withHeader('Location', '/post/' . $user['username'] . '/' . $data['title'])->withStatus(302);
        });
    }
}
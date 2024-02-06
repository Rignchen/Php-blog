<?php

namespace Rignchen\SlimExemple\Logic;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

class Router {
    public static function init(App $app, Database $db): void {
        $currentUser = $db->get_user("Onyx");
        //get
        $app->get('/post/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $view = Twig::fromRequest($request);
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            return $view->render($response, 'post.twig', [
                'post' => $post,
                'creator' => $user,
                'user' => $currentUser
            ]);
        });
        $app->get('/edit/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $view = Twig::fromRequest($request);
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            if (!$currentUser->compare_user($user)) {
                return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post['title'])->withStatus(302);
            }
            return $view->render($response, 'edit.twig', [
                'post' => $post,
                'creator' => $user,
                'user' => $currentUser
            ]);
        });
        $app->get('/new', function (Request $request, Response $response) use ($currentUser) {
            $view = Twig::fromRequest($request);
            return $view->render($response, 'new.twig', [
                'user' => $currentUser
            ]);
        });
        //post
        $app->post('/edit/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            if ($currentUser->compare_user($user)) {
                $db->update_post($post, $_POST['content']);
            }
            return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
        });
        $app->post('/delete/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            if ($currentUser->compare_user($user)) {
                $db->delete_post($post);
                return $response->withHeader('Location', '/user/' . $user->get_username())->withStatus(302);
            }
            return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
        });
        $app->post('/new', function (Request $request, Response $response) use ($db, $currentUser) {
            $db->create_post($currentUser, $_POST['title'], $_POST['content']);
            return $response->withHeader('Location', '/post/' . $currentUser->get_username() . '/' . $_POST['title'])->withStatus(302);
        });
    }
}
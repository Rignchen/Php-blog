<?php

namespace Rignchen\SlimExemple\Logic;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

class Router {
    public static function init(App $app, Database $db): void {
        $currentUser = $db->get_user("Rignchen");
        //get
        $app->get('/new', function (Request $request, Response $response) use ($currentUser) {
            if (!$currentUser) return $response->withHeader('Location', '/')->withStatus(302);
            $view = Twig::fromRequest($request);

            if (isset($_SESSION['error'])) {
                $error = $_SESSION['error'];
                unset($_SESSION['error']);
                return $view->render($response, 'new.twig', [
                    'user' => $currentUser,
                    'error' => $error['message'],
                    'title' => $error['title'],
                    'content' => $error['content']
                ]);
            }

            return $view->render($response, 'new.twig', [
                'user' => $currentUser
            ]);
        });
        $app->get('/user/{username}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $view = Twig::fromRequest($request);
            $user = $db->get_user($args['username'], true);
            return $view->render($response, 'user.twig', [
                'user' => $currentUser,
                'creator' => $user
            ]);
        });
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
            if (!$currentUser || !$currentUser->compare_user($user))
                return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
            return $view->render($response, 'edit.twig', [
                'post' => $post,
                'creator' => $user,
                'user' => $currentUser
            ]);
        });
        //post
        $app->post('/new', function (Request $request, Response $response) use ($db, $currentUser) {
            if (!$currentUser) return $response->withHeader('Location', '/')->withStatus(302);

            $title_size = Lib::len($_POST['title']);

            if (empty($_POST['title'])) $errorMessages = 'Title cannot be empty.';
            else if ($title_size > 100) $errorMessages = 'Title cannot exceed 100 characters.';
            else if (empty($_POST['content'])) $errorMessages = 'Content cannot be empty.';
            else {
                try {
            $db->create_post($currentUser, $_POST['title'], $_POST['content']);
                }
                catch (\Exception $e) {
                    $errorMessages = $e->getCode() === "23000" ? 'Post already exists.' : 'An error occurred.';
                }
            }
            if (isset($errorMessages)) {
                $_SESSION['error'] = ["title" => $_POST['title'], "content" => $_POST['content'], "message" => $errorMessages];
                return $response->withHeader('Location', '/new')->withStatus(302);
            }
            return $response->withHeader('Location', '/post/' . $currentUser->get_username() . '/' . $_POST['title'])->withStatus(302);
        });
        $app->post('/edit/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            if ($currentUser && $currentUser->compare_user($user)) {
                $db->update_post($post, $_POST['content']);
            }
            return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
        });
        $app->post('/delete/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            if ($currentUser && $currentUser->compare_user($user)) {
                $db->delete_post($post);
                return $response->withHeader('Location', '/user/' . $user->get_username())->withStatus(302);
            }
            return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
        });
    }
}
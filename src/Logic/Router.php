<?php

namespace Rignchen\SlimExemple\Logic;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

class Router {
    public static function init(App $app, Database $db): void {
        $currentUser = $db->get_user("Rignchen");

        $message = $_SESSION['message'];
        unset($_SESSION['message']);

        //get
        $app->get('/', function (Request $request, Response $response) use ($db, $currentUser, $message) {
            $view = Twig::fromRequest($request);
            return $view->render($response, 'home.twig', [
                'user' => $currentUser,
                'posts' => $db->getMostRecentPosts(),
                'message' => $message
            ]);
        });
        $app->get('/new', function (Request $request, Response $response) use ($currentUser, $message) {
            if (!$currentUser) return $response->withHeader('Location', '/')->withStatus(302);
            $view = Twig::fromRequest($request);

                return $view->render($response, 'new.twig', [
                    'user' => $currentUser,
                'message' => $message,
                'title' => $message['content']['title'],
                'content' => $message['content']['text']
            ]);
        });
        $app->get('/user/{username}', function (Request $request, Response $response, $args) use ($db, $currentUser, $message) {
            $view = Twig::fromRequest($request);
            $user = $db->get_user($args['username'], true);
            return $view->render($response, 'user.twig', [
                'user' => $currentUser,
                'creator' => $user,
                'message' => $message
            ]);
        });
        $app->get('/post/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser, $message) {
            $view = Twig::fromRequest($request);

            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            return $view->render($response, 'post.twig', [
                'post' => $post,
                'creator' => $user,
                'user' => $currentUser,
                'message' => $message
            ]);
        });
        $app->get('/edit/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser, $message) {
            $view = Twig::fromRequest($request);
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            if (!$currentUser || !$currentUser->compare_user($user))
                return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
            return $view->render($response, 'edit.twig', [
                'post' => $post,
                'creator' => $user,
                'user' => $currentUser,
                'message' => $message,
                'content' => $message['content']['text'] ?? $post->get_content(),
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
                $_SESSION['message'] = ["content" => ['title' => $_POST['title'], 'text' => $_POST['content']], "error" => $errorMessages];
                return $response->withHeader('Location', '/new')->withStatus(302);
            }
            $_SESSION['message'] = ["success" => "Post created successfully."];
            return $response->withHeader('Location', '/post/' . $currentUser->get_username() . '/' . $_POST['title'])->withStatus(302);
        });
        $app->post('/edit/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            if (Lib::len($_POST['content']) === 0) {
                $_SESSION['message'] = ["content" => ['text' => $_POST['content']], "error" => 'Content cannot be empty.'];
                return $response->withHeader('Location', '/edit/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
            }
            if ($currentUser && $currentUser->compare_user($user)) {
                $db->update_post($post, $_POST['content']);
            }
            $_SESSION['message'] = ["success" => "Post updated successfully."];
            return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
        });
        $app->post('/delete/{username}/{postName}', function (Request $request, Response $response, $args) use ($db, $currentUser) {
            $user = $db->get_user($args['username']);
            $post = $db->get_post($user->get_id(), $args['postName']);
            if ($currentUser && $currentUser->compare_user($user)) {
                $db->delete_post($post);
                $_SESSION['message'] = ["success" => "Post deleted successfully."];
                return $response->withHeader('Location', '/user/' . $user->get_username())->withStatus(302);
            }
            return $response->withHeader('Location', '/post/' . $user->get_username() . '/' . $post->get_title())->withStatus(302);
        });
    }
}
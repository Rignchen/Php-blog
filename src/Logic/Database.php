<?php

namespace Rignchen\SlimExemple\Logic;
use PDO;
use Rignchen\SlimExemple\Logic\DataType\Post;
use Rignchen\SlimExemple\Logic\DataType\User;

class Database {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    // query data
    public function get_user(String $username, bool $includeDatabase = false): User {
        $user_id = $this->pdo->prepare('select * from users where username = :username');
        $user_id->execute(['username' => $username]);
        return new User($user_id->fetch(), $includeDatabase? $this : null);
    }
    public function get_user_by_id(int $id, bool $includeDatabase = false): User {
        $user_id = $this->pdo->prepare('select * from users where id = :id');
        $user_id->execute(['id' => $id]);
        return new User($user_id->fetch(), $includeDatabase? $this : null);
    }
    public function get_post(int $user_id, String $postName): Post {
        $stmt = $this->pdo->prepare('select * from posts where user_id = :username and title = :postName');
        $stmt->execute(['username' => $user_id, 'postName' => $postName]);
        return new Post($stmt->fetch());
    }

    // post modification
    public function create_post(User $user, string $title, string $content): void {
        $stmt = $this->pdo->prepare('insert into posts (user_id, title, content) values (:id, :title, :content)');
        $stmt->execute(['id' => $user->get_id(), 'title' => $title, 'content' => $content]);
    }
    public function update_post(Post $post, String $newContent): void {
        if ($post->get_content() === $newContent) {
            return;
        }
        $stmt = $this->pdo->prepare('update posts set content = :content where user_id = :id and title = :title');
        $stmt->execute(['content' => $newContent, 'id' => $post->get_user_id(), 'title' => $post->get_title()]);
    }
    public function delete_post(Post $post): void {
        $stmt = $this->pdo->prepare('delete from posts where user_id = :id and title = :title');
        $stmt->execute(['id' => $post->get_user_id(), 'title' => $post->get_title()]);
    }

    private function make_post_array($stmt): array {
        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = new Post($row);
        }
        return $posts;
    }
    public function get_all_posts_from_user(User $user): array {
        $stmt = $this->pdo->prepare('select * from posts where user_id = :id');
        $stmt->execute(['id' => $user->get_id()]);
        return $this->make_post_array($stmt);
    }
    public function getMostRecentPosts(): array {
        $stmt = $this->pdo->prepare('select * from posts order by created_at desc limit 10');
        $stmt->execute();
        return $this->make_post_array($stmt);
    }
}

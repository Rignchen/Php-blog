<?php

namespace Rignchen\SlimExemple\Logic;
use PDO;

class Database {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    // query data
    public function get_user(String $username): array {
        $user_id = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
        $user_id->execute(['username' => $username]);
        return $user_id->fetch();
    }
    public function get_post(int $user_id, String $postName): array {
        $stmt = $this->pdo->prepare('SELECT * FROM posts WHERE user_id = :username AND title = :postName');
        $stmt->execute(['username' => $user_id, 'postName' => $postName]);
        return $stmt->fetch();
    }

    // post modification
    public function create_post(int $user_id, string $title, string $content): void {
        $stmt = $this->pdo->prepare('insert into posts (user_id, title, content) values (:id, :title, :content)');
        $stmt->execute(['id' => $user_id, 'title' => $title, 'content' => $content]);
    }
    public function update_post(array $data, String $content): void {
        if ($data['content'] === $content) {
            return;
        }
        $stmt = $this->pdo->prepare('update posts set content = :content where user_id = :id and title = :title');
        $stmt->execute(['content' => $content, 'id' => $data['user_id'], 'title' => $data['title']]);
    }
}
